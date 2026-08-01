<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductVisualDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            Product::with('category')->chunkById(100, function ($products): void {
                foreach ($products as $product) {
                    $profile = $this->profileFor((string) $product->category?->name);

                    $product->specifications()->delete();
                    $product->colors()->delete();

                    foreach ($profile['specifications'] as $index => $specification) {
                        $product->specifications()->create([
                            'label' => $specification['label'],
                            'value' => $specification['value'],
                            'icon' => $specification['icon'],
                            'sort_order' => $index,
                        ]);
                    }

                    foreach ($profile['colors'] as $index => $color) {
                        $product->colors()->create([
                            'color_name' => $color['name'],
                            'color_code' => $color['code'],
                            'sort_order' => $index,
                        ]);
                    }
                }
            });
        });
    }

    private function profileFor(string $categoryName): array
    {
        $category = Str::of($categoryName)->lower();

        if ($category->contains('wallet')) {
            return [
                'specifications' => [
                    ['label' => 'Material', 'value' => 'Leatherette Finish', 'icon' => 'fa-solid fa-layer-group'],
                    ['label' => 'Card Slots', 'value' => 'Multiple Compartments', 'icon' => 'fa-solid fa-address-card'],
                    ['label' => 'Finish', 'value' => 'Corporate Gift Ready', 'icon' => 'fa-solid fa-gift'],
                ],
                'colors' => [
                    ['name' => 'Black', 'code' => '#111827'],
                    ['name' => 'Brown', 'code' => '#92400E'],
                    ['name' => 'Tan', 'code' => '#B45309'],
                ],
            ];
        }

        if ($category->contains('belt')) {
            return [
                'specifications' => [
                    ['label' => 'Material', 'value' => 'Leather Finish', 'icon' => 'fa-solid fa-layer-group'],
                    ['label' => 'Buckle Type', 'value' => 'Formal Metal Buckle', 'icon' => 'fa-solid fa-link'],
                    ['label' => 'Size Range', 'value' => 'Standard Adjustable', 'icon' => 'fa-solid fa-ruler-combined'],
                ],
                'colors' => [
                    ['name' => 'Black', 'code' => '#111827'],
                    ['name' => 'Brown', 'code' => '#92400E'],
                ],
            ];
        }

        if ($category->contains('bottle')) {
            return [
                'specifications' => [
                    ['label' => 'Material', 'value' => 'Steel / BPA-Free Options', 'icon' => 'fa-solid fa-bottle-water'],
                    ['label' => 'Capacity', 'value' => '750 ml to 1000 ml', 'icon' => 'fa-solid fa-gauge-high'],
                    ['label' => 'Usage', 'value' => 'Office and Gifting', 'icon' => 'fa-solid fa-briefcase'],
                ],
                'colors' => [
                    ['name' => 'Blue', 'code' => '#2563EB'],
                    ['name' => 'Black', 'code' => '#111827'],
                    ['name' => 'Silver', 'code' => '#CBD5E1'],
                ],
            ];
        }

        if ($category->contains('pen')) {
            return [
                'specifications' => [
                    ['label' => 'Ink Type', 'value' => 'Smooth Writing', 'icon' => 'fa-solid fa-pen'],
                    ['label' => 'Body Material', 'value' => 'Plastic / Metal Options', 'icon' => 'fa-solid fa-layer-group'],
                    ['label' => 'Branding', 'value' => 'Logo Printing Available', 'icon' => 'fa-solid fa-bullhorn'],
                ],
                'colors' => [
                    ['name' => 'Blue', 'code' => '#2563EB'],
                    ['name' => 'Black', 'code' => '#111827'],
                    ['name' => 'Red', 'code' => '#DC2626'],
                ],
            ];
        }

        if ($category->contains('diaries') || $category->contains('note')) {
            return [
                'specifications' => [
                    ['label' => 'Size', 'value' => 'A5 / A4 Options', 'icon' => 'fa-solid fa-ruler-combined'],
                    ['label' => 'Paper Type', 'value' => 'Ruled and Planner Layouts', 'icon' => 'fa-regular fa-file-lines'],
                    ['label' => 'Binding', 'value' => 'Hardbound / Wiro Options', 'icon' => 'fa-solid fa-book'],
                ],
                'colors' => [
                    ['name' => 'Black', 'code' => '#111827'],
                    ['name' => 'Brown', 'code' => '#92400E'],
                    ['name' => 'Blue', 'code' => '#2563EB'],
                    ['name' => 'Red', 'code' => '#DC2626'],
                ],
            ];
        }

        if ($category->contains('torch')) {
            return [
                'specifications' => [
                    ['label' => 'Light Type', 'value' => 'LED Beam', 'icon' => 'fa-solid fa-lightbulb'],
                    ['label' => 'Power Source', 'value' => 'Rechargeable / Battery', 'icon' => 'fa-solid fa-battery-full'],
                    ['label' => 'Usage', 'value' => 'Utility and Emergency', 'icon' => 'fa-solid fa-toolbox'],
                ],
                'colors' => [
                    ['name' => 'Black', 'code' => '#111827'],
                    ['name' => 'Red', 'code' => '#DC2626'],
                    ['name' => 'Blue', 'code' => '#2563EB'],
                ],
            ];
        }

        if ($category->contains('bag')) {
            return [
                'specifications' => [
                    ['label' => 'Material', 'value' => 'Durable Fabric Finish', 'icon' => 'fa-solid fa-layer-group'],
                    ['label' => 'Compartment', 'value' => 'Organized Storage', 'icon' => 'fa-solid fa-box-open'],
                    ['label' => 'Usage', 'value' => 'Corporate Gifting', 'icon' => 'fa-solid fa-briefcase'],
                    ['label' => 'Branding', 'value' => 'Logo Printing Available', 'icon' => 'fa-solid fa-bullhorn'],
                ],
                'colors' => [
                    ['name' => 'Black', 'code' => '#111827'],
                    ['name' => 'Brown', 'code' => '#92400E'],
                    ['name' => 'Navy', 'code' => '#1E3A8A'],
                ],
            ];
        }

        if ($category->contains('stationary') || $category->contains('stationery')) {
            return [
                'specifications' => [
                    ['label' => 'Material', 'value' => 'Office Grade Finish', 'icon' => 'fa-solid fa-layer-group'],
                    ['label' => 'Desk Use', 'value' => 'Daily Workstation Utility', 'icon' => 'fa-solid fa-desktop'],
                    ['label' => 'Pack Type', 'value' => 'Single or Bulk Pack', 'icon' => 'fa-solid fa-boxes-stacked'],
                ],
                'colors' => [
                    ['name' => 'Black', 'code' => '#111827'],
                    ['name' => 'White', 'code' => '#FFFFFF'],
                    ['name' => 'Grey', 'code' => '#9CA3AF'],
                ],
            ];
        }

        if ($category->contains('document') || $category->contains('credit card') || $category->contains('holder')) {
            return [
                'specifications' => [
                    ['label' => 'Material', 'value' => 'Premium Utility Finish', 'icon' => 'fa-solid fa-layer-group'],
                    ['label' => 'Storage', 'value' => 'Cards and Documents', 'icon' => 'fa-solid fa-folder-open'],
                    ['label' => 'Usage', 'value' => 'Travel and Business', 'icon' => 'fa-solid fa-passport'],
                ],
                'colors' => [
                    ['name' => 'Black', 'code' => '#111827'],
                    ['name' => 'Brown', 'code' => '#92400E'],
                    ['name' => 'Navy', 'code' => '#1E3A8A'],
                ],
            ];
        }

        if ($category->contains('key')) {
            return [
                'specifications' => [
                    ['label' => 'Material', 'value' => 'Metal / Acrylic Options', 'icon' => 'fa-solid fa-key'],
                    ['label' => 'Branding', 'value' => 'Logo Customization', 'icon' => 'fa-solid fa-bullhorn'],
                    ['label' => 'Usage', 'value' => 'Promotional Gifting', 'icon' => 'fa-solid fa-gift'],
                ],
                'colors' => [
                    ['name' => 'Silver', 'code' => '#CBD5E1'],
                    ['name' => 'Black', 'code' => '#111827'],
                    ['name' => 'Red', 'code' => '#DC2626'],
                ],
            ];
        }

        return [
            'specifications' => [
                ['label' => 'Material', 'value' => 'Catalogue Grade', 'icon' => 'fa-solid fa-layer-group'],
                ['label' => 'Usage', 'value' => 'Corporate Enquiry', 'icon' => 'fa-solid fa-briefcase'],
                ['label' => 'Branding', 'value' => 'Customization Available', 'icon' => 'fa-solid fa-bullhorn'],
            ],
            'colors' => [
                ['name' => 'Black', 'code' => '#111827'],
                ['name' => 'Blue', 'code' => '#2563EB'],
            ],
        ];
    }
}
