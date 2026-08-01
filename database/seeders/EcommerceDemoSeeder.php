<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\BrandLogo;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\SubCategory;
use App\Models\Testimonial;
use App\Models\TrustItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class EcommerceDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->clearStorefrontData();
        $this->ensureUploadDirectories();

        $categories = $this->seedCategoriesAndProducts();
        $this->seedBanners();
        $this->seedHomeSections();
        $this->seedPages();
        $this->seedTrustItems();
        $this->seedTestimonials();
        $this->seedBrandLogos();
    }

    private function clearStorefrontData(): void
    {
        Schema::disableForeignKeyConstraints();

        ProductImage::query()->delete();
        ProductVariant::query()->delete();
        Product::query()->delete();
        SubCategory::query()->delete();
        Category::query()->delete();
        Banner::query()->delete();
        Page::query()->delete();
        Testimonial::query()->delete();
        TrustItem::query()->delete();
        BrandLogo::query()->delete();

        Schema::enableForeignKeyConstraints();
    }

    private function ensureUploadDirectories(): void
    {
        foreach ([
            'uploads/categories',
            'uploads/products',
            'uploads/banners',
            'uploads/testimonials',
            'uploads/trust-items',
            'uploads/brand-logos',
            'uploads/home-sections',
            'uploads/settings',
        ] as $directory) {
            File::ensureDirectoryExists(public_path($directory));
        }
    }

    private function seedCategoriesAndProducts(): array
    {
        $catalog = [
            'Notebooks' => ['Spiral Notebooks', 'Composition Books', 'Subject Notebooks'],
            'Journals & Diaries' => ['Daily Planners', 'Bullet Journals', 'Dated Diaries'],
            'Files & Folders' => ['Ring Binders', 'Report Files', 'Document Folders'],
            'Art Supplies' => ['Drawing Sheets', 'Sketch Books', 'Colour Pencils'],
            'Tapes & Adhesives' => ['Clear Tapes', 'Glue Sticks', 'Double Sided Tapes'],
        ];

        $categories = [];
        $productCount = 1;

        foreach ($catalog as $categoryName => $subCategories) {
            $categorySlug = Str::slug($categoryName);
            $categoryImage = $this->writeSvg(
                "uploads/categories/{$categorySlug}.svg",
                $categoryName,
                '#fff1f2',
                '#dc2626'
            );

            $category = Category::create([
                'name' => $categoryName,
                'slug' => $categorySlug,
                'description' => "Curated {$categoryName} for school, office, planning and creative work.",
                'image' => $categoryImage,
                'status' => true,
            ]);

            $categories[$categoryName] = $category;

            foreach ($subCategories as $subCategoryName) {
                $subCategory = SubCategory::create([
                    'category_id' => $category->id,
                    'name' => $subCategoryName,
                    'slug' => Str::slug($subCategoryName),
                    'description' => "Shop reliable {$subCategoryName} in useful sizes and finishes.",
                    'status' => true,
                ]);

                foreach ($this->productNames($subCategoryName) as $index => $productName) {
                    $slug = Str::slug($productName . '-' . $productCount);
                    $basePrice = 90 + ($productCount * 7);
                    $salePrice = $index % 2 === 0 ? $basePrice - 12 : null;
                    $productImage = $this->writeSvg(
                        "uploads/products/{$slug}.svg",
                        $productName,
                        $this->backgroundFor($categoryName),
                        '#111827'
                    );

                    $product = Product::create([
                        'category_id' => $category->id,
                        'sub_category_id' => $subCategory->id,
                        'name' => $productName,
                        'slug' => $slug,
                        'sku' => 'ST-' . str_pad((string) $productCount, 4, '0', STR_PAD_LEFT),
                        'short_description' => "A dependable {$productName} for everyday stationery needs.",
                        'description' => "<p>{$productName} is designed for smooth daily use, easy storage and a neat desk setup.</p><p>Ideal for students, office teams, artists and home organization.</p>",
                        'price' => $basePrice,
                        'sale_price' => $salePrice,
                        'stock' => 25 + $index + $productCount,
                        'featured_image' => $productImage,
                        'is_featured' => $productCount % 3 === 0,
                        'status' => true,
                    ]);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $this->writeSvg("uploads/products/{$slug}-detail-1.svg", $productName . ' Detail', '#f8fafc', '#dc2626'),
                    ]);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $this->writeSvg("uploads/products/{$slug}-detail-2.svg", $productName . ' Angle', '#fff7ed', '#111827'),
                    ]);

                    foreach ($this->variantsFor($categoryName, $productCount) as $variant) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'variant_name' => $variant['name'],
                            'variant_value' => $variant['value'],
                            'price' => $variant['price'],
                            'stock' => $variant['stock'],
                        ]);
                    }

                    $productCount++;
                }
            }
        }

        return $categories;
    }

    private function productNames(string $subCategoryName): array
    {
        return [
            'Premium ' . $subCategoryName,
            'Classic ' . $subCategoryName,
            'Student ' . $subCategoryName,
            'Office ' . $subCategoryName,
        ];
    }

    private function variantsFor(string $categoryName, int $productCount): array
    {
        return match ($categoryName) {
            'Files & Folders' => [
                ['name' => 'Size', 'value' => 'A4', 'price' => 120 + $productCount, 'stock' => 18],
                ['name' => 'Size', 'value' => 'Legal', 'price' => 145 + $productCount, 'stock' => 14],
            ],
            'Tapes & Adhesives' => [
                ['name' => 'Pack', 'value' => 'Single', 'price' => 55 + $productCount, 'stock' => 30],
                ['name' => 'Pack', 'value' => 'Set of 3', 'price' => 145 + $productCount, 'stock' => 20],
            ],
            'Art Supplies' => [
                ['name' => 'Size', 'value' => 'A4', 'price' => 80 + $productCount, 'stock' => 22],
                ['name' => 'Size', 'value' => 'A3', 'price' => 130 + $productCount, 'stock' => 16],
            ],
            default => [
                ['name' => 'Pages', 'value' => '120 Pages', 'price' => 95 + $productCount, 'stock' => 25],
                ['name' => 'Pages', 'value' => '240 Pages', 'price' => 155 + $productCount, 'stock' => 18],
            ],
        };
    }

    private function seedBanners(): void
    {
        $banners = [
            ['Back To School Essentials', 'Fresh notebooks, files and planners', 'Shop Now'],
            ['Creative Desk Upgrade', 'Sketch books, colours and drawing sheets', 'Explore Art'],
            ['Office Organization Sale', 'Files, folders and tapes for tidy work', 'Organize Now'],
        ];

        foreach ($banners as $index => [$title, $subtitle, $button]) {
            Banner::create([
                'title' => $title,
                'subtitle' => $subtitle,
                'image' => $this->writeSvg('uploads/banners/banner-' . ($index + 1) . '.svg', $title, $index === 1 ? '#fef3c7' : '#fee2e2', '#991b1b'),
                'button_text' => $button,
                'button_link' => route('products.index', [], false),
                'position' => 'home',
                'status' => true,
            ]);
        }
    }

    private function seedHomeSections(): void
    {
        $settings = [
            'site_name' => ['Stationery Hub', 'text'],
            'site_email' => ['hello@stationeryhub.test', 'text'],
            'site_phone' => ['+91 98765 43210', 'text'],
            'site_address' => ['Stationery Hub, MG Road, Bengaluru, India', 'text'],
            'whatsapp_number' => ['+91 98765 43210', 'text'],
            'facebook_url' => ['https://facebook.com', 'text'],
            'instagram_url' => ['https://instagram.com', 'text'],
            'youtube_url' => ['https://youtube.com', 'text'],
            'logo' => [$this->writeSvg('uploads/settings/logo.svg', 'Stationery Hub', '#ffffff', '#dc2626'), 'file'],
            'favicon' => [$this->writeSvg('uploads/settings/favicon.svg', 'SH', '#dc2626', '#ffffff'), 'file'],
            'promo_status' => ['1', 'boolean'],
            'promo_title' => ['Flat 10% Off On Desk Essentials', 'text'],
            'promo_subtitle' => ['Stock up on notebooks, files and tapes for school or office.', 'text'],
            'promo_button_text' => ['Shop Offers', 'text'],
            'promo_button_link' => [route('products.index', [], false), 'text'],
            'promo_background_color' => ['#dc2626', 'text'],
            'promo_background_image' => [$this->writeSvg('uploads/home-sections/promo.svg', 'Stationery Offers', '#dc2626', '#ffffff'), 'file'],
            'about_status' => ['1', 'boolean'],
            'about_title' => ['Built For Better Everyday Work', 'text'],
            'about_description' => ['We curate stationery that feels practical, polished and ready for classrooms, studios and modern offices.', 'text'],
            'about_button_text' => ['Contact Us', 'text'],
            'about_button_link' => [route('contact', [], false), 'text'],
            'about_background_image' => [$this->writeSvg('uploads/home-sections/about.svg', 'About Stationery Hub', '#111827', '#ffffff'), 'file'],
        ];

        foreach ($settings as $settingKey => [$value, $type]) {
            Setting::updateOrCreate(
                ['setting_key' => $settingKey],
                ['value' => $value, 'type' => $type]
            );
        }
    }

    private function seedPages(): void
    {
        foreach ([
            'About Us' => 'We supply useful stationery for school, office and creative routines.',
            'Shipping Policy' => 'Orders are packed carefully and shipped through trusted delivery partners.',
            'Returns Policy' => 'Unused products can be returned as per store policy.',
        ] as $title => $content) {
            Page::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => '<p>' . $content . '</p>',
                'meta_title' => $title,
                'meta_description' => $content,
                'status' => true,
            ]);
        }
    }

    private function seedTrustItems(): void
    {
        foreach ([
            ['Fast Delivery', 'Quick shipping on stationery essentials.', 'fa-solid fa-truck-fast'],
            ['Quality Checked', 'Products selected for daily reliable use.', 'fa-solid fa-circle-check'],
            ['COD Available', 'Flexible payment option for local orders.', 'fa-solid fa-hand-holding-dollar'],
            ['Secure Checkout', 'Simple and safe checkout experience.', 'fa-solid fa-shield-halved'],
            ['Wide Collection', 'Everything from notebooks to art supplies.', 'fa-solid fa-briefcase'],
        ] as $index => [$title, $description, $icon]) {
            TrustItem::create([
                'title' => $title,
                'description' => $description,
                'icon' => $icon,
                'image' => $this->writeSvg('uploads/trust-items/' . Str::slug($title) . '.svg', $title, '#fff1f2', '#dc2626'),
                'status' => true,
                'sort_order' => $index,
            ]);
        }
    }

    private function seedTestimonials(): void
    {
        foreach ([
            ['Nisha Rao', 'School Coordinator', 'The notebooks and files are neatly packed and very dependable for our school supply lists.'],
            ['Amit Shah', 'Office Manager', 'Great range of folders, tapes and planners. Reordering is simple and quick.'],
            ['Priya Menon', 'Design Student', 'The art supplies and sketch books are perfect for studio work and assignments.'],
        ] as $index => [$name, $designation, $message]) {
            Testimonial::create([
                'name' => $name,
                'designation' => $designation,
                'message' => $message,
                'rating' => 5,
                'image' => $this->writeSvg('uploads/testimonials/' . Str::slug($name) . '.svg', $name, '#fef2f2', '#991b1b'),
                'status' => true,
                'sort_order' => $index,
            ]);
        }
    }

    private function seedBrandLogos(): void
    {
        foreach (['Classmate', 'Camlin', 'Pilot', 'Faber-Castell', 'Kokuyo'] as $index => $name) {
            BrandLogo::create([
                'name' => $name,
                'logo' => $this->writeSvg('uploads/brand-logos/' . Str::slug($name) . '.svg', $name, '#ffffff', '#111827'),
                'link' => null,
                'status' => true,
                'sort_order' => $index,
            ]);
        }
    }

    private function backgroundFor(string $categoryName): string
    {
        return match ($categoryName) {
            'Notebooks' => '#dbeafe',
            'Journals & Diaries' => '#fae8ff',
            'Files & Folders' => '#dcfce7',
            'Art Supplies' => '#fef3c7',
            'Tapes & Adhesives' => '#fee2e2',
            default => '#f8fafc',
        };
    }

    private function writeSvg(string $relativePath, string $title, string $background, string $foreground): string
    {
        $path = public_path($relativePath);
        File::ensureDirectoryExists(dirname($path));

        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="900" height="600" viewBox="0 0 900 600">
  <rect width="900" height="600" fill="{$background}"/>
  <rect x="110" y="110" width="680" height="380" rx="36" fill="#ffffff" opacity="0.82"/>
  <path d="M180 205h540M180 285h420M180 365h500" stroke="{$foreground}" stroke-width="18" stroke-linecap="round" opacity="0.22"/>
  <circle cx="705" cy="405" r="58" fill="{$foreground}" opacity="0.16"/>
  <text x="450" y="310" text-anchor="middle" font-family="Arial, sans-serif" font-size="46" font-weight="700" fill="{$foreground}">{$safeTitle}</text>
</svg>
SVG;

        File::put($path, $svg);

        return $relativePath;
    }
}
