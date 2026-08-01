<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ClientCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = $this->catalog();
        $oldProductIds = Product::pluck('id')->all();
        $oldVariantIds = ProductVariant::pluck('id')->all();

        DB::transaction(function () use ($catalog, $oldProductIds, $oldVariantIds): void {
            $this->detachHistoricalVariantReferences($oldVariantIds);

            Schema::disableForeignKeyConstraints();
            DB::table('product_images')->delete();
            DB::table('product_variants')->delete();
            Schema::enableForeignKeyConstraints();

            $categoryIds = [];
            $subCategoryIds = [];
            $productIds = [];
            $usedImagePaths = [];
            $skuCounter = 1;

            foreach ($catalog as $index => $categoryData) {
                $categorySlug = Str::slug($categoryData['name']);
                $categoryImage = $this->writePlaceholder(
                    'categories',
                    $categorySlug,
                    $categoryData['name'],
                    $this->color($index)
                );
                $usedImagePaths[] = $categoryImage;

                $category = Category::updateOrCreate(
                    ['slug' => $categorySlug],
                    [
                        'name' => $categoryData['name'],
                        'description' => $categoryData['description'],
                        'image' => $categoryImage,
                        'status' => true,
                    ]
                );

                $categoryIds[] = $category->id;
                $subCategoriesByName = [];

                foreach ($categoryData['subcategories'] as $subCategoryName) {
                    $subCategorySlug = Str::slug($subCategoryName);

                    $subCategory = SubCategory::updateOrCreate(
                        ['slug' => $subCategorySlug],
                        [
                            'category_id' => $category->id,
                            'name' => $subCategoryName,
                            'description' => $subCategoryName . ' available for catalogue, corporate and bulk enquiry requirements.',
                            'status' => true,
                        ]
                    );

                    $subCategoryIds[] = $subCategory->id;
                    $subCategoriesByName[$subCategoryName] = $subCategory;
                }

                foreach ($categoryData['products'] as $productIndex => $productData) {
                    $productSlug = Str::slug($productData['name']);
                    $subCategory = $subCategoriesByName[$productData['subcategory']] ?? collect($subCategoriesByName)->first();
                    $productImage = $this->writePlaceholder(
                        'products',
                        $productSlug,
                        $productData['name'],
                        $this->color($index + $productIndex + 2)
                    );
                    $galleryImage = $this->writePlaceholder(
                        'products',
                        $productSlug . '-catalog-view',
                        $productData['name'] . ' Catalog View',
                        $this->color($index + $productIndex + 5)
                    );
                    $usedImagePaths[] = $productImage;
                    $usedImagePaths[] = $galleryImage;

                    $product = Product::updateOrCreate(
                        ['slug' => $productSlug],
                        [
                            'category_id' => $category->id,
                            'sub_category_id' => $subCategory?->id,
                            'name' => $productData['name'],
                            'sku' => $this->sku($categoryData['name'], $skuCounter++),
                            'short_description' => $productData['short'],
                            'description' => $this->description($productData['name'], $categoryData['name']),
                            'price' => 0,
                            'sale_price' => null,
                            'stock' => 100,
                            'featured_image' => $productImage,
                            'is_featured' => $productIndex < 2,
                            'status' => true,
                        ]
                    );

                    $productIds[] = $product->id;

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $galleryImage,
                    ]);

                    foreach ($this->variantsFor($categoryData['name']) as $variant) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'variant_name' => $variant[0],
                            'variant_value' => $variant[1],
                            'price' => null,
                            'stock' => 100,
                        ]);
                    }
                }
            }

            $this->removeStaleCatalogRows($oldProductIds, $productIds, $subCategoryIds, $categoryIds);
            $this->cleanupUnusedCatalogUploads($usedImagePaths);
            $this->refreshHomepageCopy();
        });
    }

    private function detachHistoricalVariantReferences(array $oldVariantIds): void
    {
        if (empty($oldVariantIds)) {
            return;
        }

        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'variant_id')) {
            DB::table('order_items')
                ->whereIn('variant_id', $oldVariantIds)
                ->update(['variant_id' => null]);
        }

        if (Schema::hasTable('product_enquiries') && Schema::hasColumn('product_enquiries', 'variant_id')) {
            DB::table('product_enquiries')
                ->whereIn('variant_id', $oldVariantIds)
                ->update(['variant_id' => null]);
        }
    }

    private function removeStaleCatalogRows(array $oldProductIds, array $productIds, array $subCategoryIds, array $categoryIds): void
    {
        $staleProductIds = array_values(array_diff($oldProductIds, $productIds));

        if (! empty($staleProductIds)) {
            if (Schema::hasTable('product_enquiries') && Schema::hasColumn('product_enquiries', 'product_id')) {
                DB::table('product_enquiries')
                    ->whereIn('product_id', $staleProductIds)
                    ->update([
                        'product_id' => null,
                        'variant_id' => null,
                    ]);
            }

            if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'product_id') && ! empty($productIds)) {
                DB::table('order_items')
                    ->whereIn('product_id', $staleProductIds)
                    ->update([
                        'product_id' => $productIds[0],
                    ]);
            }

            Product::whereIn('id', $staleProductIds)->delete();
        }

        SubCategory::whereNotIn('id', $subCategoryIds)->delete();
        Category::whereNotIn('id', $categoryIds)->delete();
    }

    private function writePlaceholder(string $module, string $slug, string $title, string $color): string
    {
        $relativePath = "uploads/{$module}/{$slug}.svg";
        $path = public_path($relativePath);

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->svg($title, $color));

        return $relativePath;
    }

    private function cleanupUnusedCatalogUploads(array $usedImagePaths): void
    {
        $used = collect($usedImagePaths)
            ->map(fn (string $path) => $this->normalizePath(public_path($path)))
            ->all();

        foreach (['categories', 'products'] as $module) {
            $directory = public_path("uploads/{$module}");

            if (! File::isDirectory($directory)) {
                continue;
            }

            foreach (File::files($directory) as $file) {
                $path = $this->normalizePath($file->getPathname());

                if (! in_array($path, $used, true)) {
                    File::delete($file->getPathname());
                }
            }
        }
    }

    private function normalizePath(string $path): string
    {
        return strtolower(str_replace('\\', '/', $path));
    }

    private function refreshHomepageCopy(): void
    {
        if (Schema::hasTable('testimonials')) {
            DB::table('testimonials')
                ->where('message', 'like', '%art supplies%')
                ->update([
                    'designation' => 'Corporate Buyer',
                    'message' => 'The catalogue range is useful for office gifting, bags, diaries, bottles and branded promotional requirements.',
                ]);
        }

        if (Schema::hasTable('trust_items')) {
            DB::table('trust_items')
                ->where('description', 'like', '%art supplies%')
                ->update([
                    'description' => 'Bags, wallets, belts, bottles, diaries, pens and promotional catalogue products.',
                ]);
        }
    }

    private function svg(string $title, string $color): string
    {
        $lines = explode("\n", wordwrap($title, 24, "\n", true));
        $escapedLines = collect(array_slice($lines, 0, 3))
            ->map(fn (string $line, int $index) => '<tspan x="50%" dy="' . ($index === 0 ? '0' : '30') . '">' . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . '</tspan>')
            ->implode('');

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="900" height="650" viewBox="0 0 900 650">
  <rect width="900" height="650" fill="{$color}"/>
  <rect x="48" y="48" width="804" height="554" rx="32" fill="rgba(255,255,255,0.16)" stroke="rgba(255,255,255,0.42)" stroke-width="4"/>
  <circle cx="735" cy="140" r="72" fill="rgba(255,255,255,0.18)"/>
  <circle cx="160" cy="505" r="94" fill="rgba(0,0,0,0.08)"/>
  <text x="50%" y="300" text-anchor="middle" font-family="Arial, sans-serif" font-size="42" font-weight="700" fill="#ffffff">{$escapedLines}</text>
  <text x="50%" y="395" text-anchor="middle" font-family="Arial, sans-serif" font-size="22" font-weight="600" fill="rgba(255,255,255,0.78)">Catalog Enquiry Product</text>
</svg>
SVG;
    }

    private function color(int $index): string
    {
        $colors = [
            '#b91c1c',
            '#0f766e',
            '#4338ca',
            '#92400e',
            '#047857',
            '#be123c',
            '#0369a1',
            '#7c2d12',
            '#6d28d9',
            '#15803d',
            '#a16207',
            '#334155',
        ];

        return $colors[$index % count($colors)];
    }

    private function sku(string $categoryName, int $counter): string
    {
        $prefix = Str::of($categoryName)
            ->replaceMatches('/[^A-Za-z0-9 ]/', ' ')
            ->explode(' ')
            ->filter()
            ->map(fn (string $word) => Str::upper(Str::substr($word, 0, 1)))
            ->take(3)
            ->implode('');

        return ($prefix ?: 'CAT') . '-' . str_pad((string) $counter, 3, '0', STR_PAD_LEFT);
    }

    private function description(string $productName, string $categoryName): string
    {
        return '<p>' . $productName . ' is selected for professional catalogue enquiries in the ' . $categoryName . ' range. It is suitable for corporate gifting, institutional supply, retail counters and bulk procurement requirements.</p>'
            . '<p>Share your quantity, branding preference and delivery timeline through the enquiry form. Our team can confirm availability, customization options, packing details and best commercial pricing based on your requirement.</p>';
    }

    private function variantsFor(string $categoryName): array
    {
        if (str_contains($categoryName, 'Bottle')) {
            return [['Capacity', '750 ml'], ['Capacity', '1000 ml']];
        }

        if (str_contains($categoryName, 'Torch')) {
            return [['Power Type', 'Rechargeable'], ['Power Type', 'Battery Operated']];
        }

        if (str_contains($categoryName, 'Pen')) {
            return [['Ink Color', 'Blue'], ['Ink Color', 'Black']];
        }

        if (str_contains($categoryName, 'Diaries')) {
            return [['Page Type', 'Ruled'], ['Page Type', 'Planner Format']];
        }

        if (str_contains($categoryName, 'Belt') || str_contains($categoryName, 'Wallet')) {
            return [['Color', 'Black'], ['Color', 'Brown']];
        }

        if (str_contains($categoryName, 'Bag')) {
            return [['Color', 'Black'], ['Branding', 'Logo Printing']];
        }

        return [['Pack Type', 'Single Piece'], ['Pack Type', 'Bulk Pack']];
    }

    private function catalog(): array
    {
        return [
            [
                'name' => 'Bags Executive and Sack',
                'description' => 'Executive bags, laptop bags and sack bags for corporate, office and promotional supply.',
                'subcategories' => ['Executive Bags', 'Laptop Bags', 'Travel Sack Bags'],
                'products' => [
                    ['name' => 'Premium Executive Laptop Bag', 'subcategory' => 'Laptop Bags', 'short' => 'Padded laptop bag with smart office compartments for business users.'],
                    ['name' => 'Corporate Executive Document Bag', 'subcategory' => 'Executive Bags', 'short' => 'Professional document bag for meetings, conferences and corporate gifting.'],
                    ['name' => 'Heavy Duty Travel Sack Bag', 'subcategory' => 'Travel Sack Bags', 'short' => 'Durable travel sack bag suitable for promotional and utility requirements.'],
                    ['name' => 'Compact Office Backpack Bag', 'subcategory' => 'Laptop Bags', 'short' => 'Compact office backpack for daily commute and laptop carrying needs.'],
                    ['name' => 'Promotional Drawstring Sack Bag', 'subcategory' => 'Travel Sack Bags', 'short' => 'Lightweight drawstring sack for events, campaigns and giveaways.'],
                ],
            ],
            [
                'name' => 'Side Bag Shoulder',
                'description' => 'Shoulder bags, crossbody bags and side bags for office and daily utility catalog enquiries.',
                'subcategories' => ['Shoulder Bags', 'Crossbody Bags', 'Office Side Bags'],
                'products' => [
                    ['name' => 'Classic Shoulder Side Bag', 'subcategory' => 'Shoulder Bags', 'short' => 'Everyday shoulder bag with practical storage and clean finish.'],
                    ['name' => 'Compact Crossbody Utility Bag', 'subcategory' => 'Crossbody Bags', 'short' => 'Compact crossbody bag for travel, events and staff utility use.'],
                    ['name' => 'Office Side Messenger Bag', 'subcategory' => 'Office Side Bags', 'short' => 'Messenger style office side bag with organized compartments.'],
                    ['name' => 'Promotional Shoulder Sling Bag', 'subcategory' => 'Shoulder Bags', 'short' => 'Brandable sling bag suitable for corporate promotions and campaigns.'],
                    ['name' => 'Executive Crossbody Office Bag', 'subcategory' => 'Crossbody Bags', 'short' => 'Premium crossbody option for business and office travel use.'],
                ],
            ],
            [
                'name' => 'Gents Wallets',
                'description' => 'Wallets and card wallets for corporate gifting, retail and bulk procurement.',
                'subcategories' => ['Leather Wallets', 'Non-Leather Wallets', 'Card Wallets'],
                'products' => [
                    ['name' => 'Classic Leather Finish Gents Wallet', 'subcategory' => 'Leather Wallets', 'short' => 'Classic wallet with refined finish for business gifting requirements.'],
                    ['name' => 'Slim Non-Leather Daily Wallet', 'subcategory' => 'Non-Leather Wallets', 'short' => 'Slim wallet option for value-focused catalogue and retail supply.'],
                    ['name' => 'Compact Card Wallet Holder', 'subcategory' => 'Card Wallets', 'short' => 'Compact holder for cards and essentials with professional finish.'],
                    ['name' => 'Executive Gift Wallet Set', 'subcategory' => 'Leather Wallets', 'short' => 'Gift-ready wallet range for corporate and festive distribution.'],
                    ['name' => 'Multi Slot Business Card Wallet', 'subcategory' => 'Card Wallets', 'short' => 'Multi-slot wallet for business cards, IDs and daily utility.'],
                ],
            ],
            [
                'name' => 'Belt',
                'description' => 'Formal, casual and leather finish belts for retail, gifting and institutional demand.',
                'subcategories' => ['Formal Belts', 'Casual Belts', 'Leather Finish Belts'],
                'products' => [
                    ['name' => 'Formal Office Wear Belt', 'subcategory' => 'Formal Belts', 'short' => 'Formal belt suited for office wear, uniforms and gifting.'],
                    ['name' => 'Casual Daily Wear Belt', 'subcategory' => 'Casual Belts', 'short' => 'Casual belt with durable finish for regular use.'],
                    ['name' => 'Leather Finish Reversible Belt', 'subcategory' => 'Leather Finish Belts', 'short' => 'Reversible style belt for premium corporate catalogue enquiries.'],
                    ['name' => 'Executive Buckle Belt', 'subcategory' => 'Formal Belts', 'short' => 'Executive buckle belt with clean presentation and gifting appeal.'],
                    ['name' => 'Corporate Gift Belt Pack', 'subcategory' => 'Leather Finish Belts', 'short' => 'Gift pack belt option for employee and client gifting programs.'],
                ],
            ],
            [
                'name' => 'Shopping Bag',
                'description' => 'Reusable, non-woven and printed carry bags for promotional and retail use.',
                'subcategories' => ['Reusable Shopping Bags', 'Non-Woven Bags', 'Printed Carry Bags'],
                'products' => [
                    ['name' => 'Reusable Grocery Shopping Bag', 'subcategory' => 'Reusable Shopping Bags', 'short' => 'Reusable shopping bag for stores, events and customer giveaways.'],
                    ['name' => 'Non-Woven Promotional Carry Bag', 'subcategory' => 'Non-Woven Bags', 'short' => 'Non-woven carry bag suitable for logo printing and promotions.'],
                    ['name' => 'Printed Retail Carry Bag', 'subcategory' => 'Printed Carry Bags', 'short' => 'Retail carry bag with custom print possibilities for brands.'],
                    ['name' => 'Foldable Shopping Tote Bag', 'subcategory' => 'Reusable Shopping Bags', 'short' => 'Foldable tote bag for compact storage and everyday shopping use.'],
                    ['name' => 'Corporate Event Welcome Bag', 'subcategory' => 'Printed Carry Bags', 'short' => 'Event welcome bag for conferences, exhibitions and campaigns.'],
                ],
            ],
            [
                'name' => 'Table Stationary',
                'description' => 'Desk organizers, office stationery and table accessories for workplace supply.',
                'subcategories' => ['Desk Organizers', 'Office Stationery', 'Table Accessories'],
                'products' => [
                    ['name' => 'Multi Section Desk Organizer', 'subcategory' => 'Desk Organizers', 'short' => 'Desk organizer for pens, notes and daily office essentials.'],
                    ['name' => 'Executive Table Stationery Set', 'subcategory' => 'Office Stationery', 'short' => 'Coordinated table stationery set for desks and welcome kits.'],
                    ['name' => 'Desktop Pen Stand Holder', 'subcategory' => 'Table Accessories', 'short' => 'Practical pen stand holder for office and reception counters.'],
                    ['name' => 'Office Memo Pad Holder', 'subcategory' => 'Desk Organizers', 'short' => 'Memo pad holder for organized note access on office tables.'],
                    ['name' => 'Corporate Table Accessory Kit', 'subcategory' => 'Table Accessories', 'short' => 'Accessory kit suitable for workstations and executive desks.'],
                ],
            ],
            [
                'name' => 'Torch',
                'description' => 'LED, rechargeable and utility torches for corporate, household and field use.',
                'subcategories' => ['LED Torch', 'Rechargeable Torch', 'Utility Torch'],
                'products' => [
                    ['name' => 'Compact LED Utility Torch', 'subcategory' => 'LED Torch', 'short' => 'Compact LED torch for daily utility and emergency requirements.'],
                    ['name' => 'Rechargeable Hand Torch', 'subcategory' => 'Rechargeable Torch', 'short' => 'Rechargeable torch with practical use for staff and field teams.'],
                    ['name' => 'Heavy Duty Utility Torch', 'subcategory' => 'Utility Torch', 'short' => 'Heavy duty torch suitable for outdoor, warehouse and security use.'],
                    ['name' => 'Mini Promotional LED Torch', 'subcategory' => 'LED Torch', 'short' => 'Mini LED torch option for promotional and giveaway programs.'],
                    ['name' => 'Multi Purpose Emergency Torch', 'subcategory' => 'Utility Torch', 'short' => 'Emergency torch for offices, homes and travel kits.'],
                ],
            ],
            [
                'name' => 'Diaries / Note Book',
                'description' => 'Diaries, notebooks and planners for office, institutional and gifting requirements.',
                'subcategories' => ['Diaries', 'Notebooks', 'Planners'],
                'products' => [
                    ['name' => 'Corporate Hardbound Diary', 'subcategory' => 'Diaries', 'short' => 'Hardbound diary with professional finish for annual gifting.'],
                    ['name' => 'Premium Ruled Notebook', 'subcategory' => 'Notebooks', 'short' => 'Ruled notebook for office, school and institutional supplies.'],
                    ['name' => 'Executive Daily Planner', 'subcategory' => 'Planners', 'short' => 'Daily planner for organized scheduling and business use.'],
                    ['name' => 'Custom Branding Notebook', 'subcategory' => 'Notebooks', 'short' => 'Notebook suitable for logo branding and bulk promotional orders.'],
                    ['name' => 'Annual Business Diary', 'subcategory' => 'Diaries', 'short' => 'Business diary designed for professional and client gifting needs.'],
                ],
            ],
            [
                'name' => 'Personal Documents & Credit Card Holder',
                'description' => 'Document holders, card holders and passport holders for secure daily carry.',
                'subcategories' => ['Document Holders', 'Credit Card Holders', 'Passport Holders'],
                'products' => [
                    ['name' => 'Personal Document Organizer', 'subcategory' => 'Document Holders', 'short' => 'Organizer for IDs, papers and personal documents.'],
                    ['name' => 'Credit Card Holder Case', 'subcategory' => 'Credit Card Holders', 'short' => 'Compact card holder case for daily and travel use.'],
                    ['name' => 'Passport and Travel Document Holder', 'subcategory' => 'Passport Holders', 'short' => 'Travel holder for passport, tickets and important documents.'],
                    ['name' => 'Executive Document Folder Holder', 'subcategory' => 'Document Holders', 'short' => 'Document folder holder for business and official use.'],
                    ['name' => 'RFID Style Card Wallet Holder', 'subcategory' => 'Credit Card Holders', 'short' => 'Card holder option for secure and organized card carrying.'],
                ],
            ],
            [
                'name' => 'Key Chain & Key Hanger Display',
                'description' => 'Metal key chains, promotional key chains and key hanger displays for gifting and retail counters.',
                'subcategories' => ['Metal Key Chains', 'Promotional Key Chains', 'Key Hanger Displays'],
                'products' => [
                    ['name' => 'Metal Logo Key Chain', 'subcategory' => 'Metal Key Chains', 'short' => 'Metal key chain suitable for branding and corporate giveaways.'],
                    ['name' => 'Promotional Acrylic Key Chain', 'subcategory' => 'Promotional Key Chains', 'short' => 'Promotional key chain for campaigns, events and retail counters.'],
                    ['name' => 'Wall Key Hanger Display', 'subcategory' => 'Key Hanger Displays', 'short' => 'Wall display option for organized key chain merchandising.'],
                    ['name' => 'Executive Gift Key Chain Set', 'subcategory' => 'Metal Key Chains', 'short' => 'Gift-ready key chain set for clients and employees.'],
                    ['name' => 'Counter Key Chain Display Stand', 'subcategory' => 'Key Hanger Displays', 'short' => 'Counter display stand for retail key chain presentation.'],
                ],
            ],
            [
                'name' => 'Pen & Ball Pen',
                'description' => 'Ball pens, gel pens and corporate pen sets for office and promotional requirements.',
                'subcategories' => ['Ball Pens', 'Gel Pens', 'Corporate Pen Sets'],
                'products' => [
                    ['name' => 'Smooth Writing Ball Pen', 'subcategory' => 'Ball Pens', 'short' => 'Ball pen range for daily office, school and promotional use.'],
                    ['name' => 'Premium Gel Ink Pen', 'subcategory' => 'Gel Pens', 'short' => 'Gel ink pen with smooth writing for professional users.'],
                    ['name' => 'Corporate Pen Gift Set', 'subcategory' => 'Corporate Pen Sets', 'short' => 'Gift set pen option for branding, events and client gifting.'],
                    ['name' => 'Logo Branding Ball Pen', 'subcategory' => 'Ball Pens', 'short' => 'Ball pen suitable for logo printing and bulk promotions.'],
                    ['name' => 'Executive Metal Pen Set', 'subcategory' => 'Corporate Pen Sets', 'short' => 'Metal pen set for executive gifting and premium catalogues.'],
                ],
            ],
            [
                'name' => 'Water Bottle',
                'description' => 'Steel, plastic and corporate bottles for gifting, staff use and promotional programs.',
                'subcategories' => ['Steel Bottles', 'Plastic Bottles', 'Corporate Bottles'],
                'products' => [
                    ['name' => 'Stainless Steel Water Bottle', 'subcategory' => 'Steel Bottles', 'short' => 'Steel bottle for daily use, gifting and corporate supply.'],
                    ['name' => 'Lightweight Plastic Water Bottle', 'subcategory' => 'Plastic Bottles', 'short' => 'Lightweight bottle option for events, staff and institutions.'],
                    ['name' => 'Corporate Logo Water Bottle', 'subcategory' => 'Corporate Bottles', 'short' => 'Bottle suitable for logo branding and promotional distribution.'],
                    ['name' => 'Insulated Steel Flask Bottle', 'subcategory' => 'Steel Bottles', 'short' => 'Insulated steel bottle for premium gifting and everyday use.'],
                    ['name' => 'Event Giveaway Sports Bottle', 'subcategory' => 'Corporate Bottles', 'short' => 'Sports bottle suited for campaigns, events and bulk enquiry.'],
                ],
            ],
        ];
    }
}
