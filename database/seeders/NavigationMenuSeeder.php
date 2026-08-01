<?php

namespace Database\Seeders;

use App\Models\NavigationMenu;
use App\Models\Page;
use Illuminate\Database\Seeder;

class NavigationMenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CmsPageSeeder::class);

        $pages = Page::whereIn('slug', [
            'about-us',
            'shipping-policy',
            'returns-policy',
            'privacy-policy',
            'terms-conditions',
        ])->get()->keyBy('slug');

        foreach ($this->headerMenus($pages) as $menu) {
            $this->upsertMenu($menu);
        }

        foreach ($this->footerMenus($pages) as $menu) {
            $this->upsertMenu($menu);
        }
    }

    private function upsertMenu(array $menu): void
    {
        NavigationMenu::updateOrCreate(
            [
                'title' => $menu['title'],
                'location' => $menu['location'],
            ],
            $menu
        );
    }

    private function headerMenus($pages): array
    {
        return [
            [
                'title' => 'Header Home',
                'label' => 'Home',
                'type' => 'route',
                'route_name' => 'home',
                'url' => null,
                'page_id' => null,
                'category_id' => null,
                'parent_id' => null,
                'location' => 'header',
                'sort_order' => 10,
                'open_in_new_tab' => false,
                'is_mega' => false,
                'status' => true,
            ],
            [
                'title' => 'Header Catalog',
                'label' => 'Catalog',
                'type' => 'route',
                'route_name' => 'products.index',
                'url' => null,
                'page_id' => null,
                'category_id' => null,
                'parent_id' => null,
                'location' => 'header',
                'sort_order' => 20,
                'open_in_new_tab' => false,
                'is_mega' => false,
                'status' => true,
            ],
            [
                'title' => 'Header About',
                'label' => 'About',
                'type' => 'page',
                'route_name' => null,
                'url' => null,
                'page_id' => $pages->get('about-us')?->id,
                'category_id' => null,
                'parent_id' => null,
                'location' => 'header',
                'sort_order' => 30,
                'open_in_new_tab' => false,
                'is_mega' => false,
                'status' => true,
            ],
            [
                'title' => 'Header Categories',
                'label' => 'Categories',
                'type' => 'route',
                'route_name' => 'products.index',
                'url' => null,
                'page_id' => null,
                'category_id' => null,
                'parent_id' => null,
                'location' => 'header',
                'sort_order' => 40,
                'open_in_new_tab' => false,
                'is_mega' => true,
                'status' => true,
            ],
            [
                'title' => 'Header Bulk Quote',
                'label' => 'Bulk Quote',
                'type' => 'route',
                'route_name' => 'bulk-quote.create',
                'url' => null,
                'page_id' => null,
                'category_id' => null,
                'parent_id' => null,
                'location' => 'header',
                'sort_order' => 50,
                'open_in_new_tab' => false,
                'is_mega' => false,
                'status' => true,
            ],
            [
                'title' => 'Header Contact',
                'label' => 'Contact',
                'type' => 'route',
                'route_name' => 'contact',
                'url' => null,
                'page_id' => null,
                'category_id' => null,
                'parent_id' => null,
                'location' => 'header',
                'sort_order' => 60,
                'open_in_new_tab' => false,
                'is_mega' => false,
                'status' => true,
            ],
        ];
    }

    private function footerMenus($pages): array
    {
        $menus = [
            ['Products', 'route', 'products.index', null, 10],
            ['Bulk / Wholesale', 'route', 'bulk-quote.create', null, 20],
            ['About Us', 'page', null, 'about-us', 30],
            ['Contact Us', 'route', 'contact', null, 40],
            ['Shipping Policy', 'page', null, 'shipping-policy', 50],
            ['Returns Policy', 'page', null, 'returns-policy', 60],
            ['Privacy Policy', 'page', null, 'privacy-policy', 70],
            ['Terms & Conditions', 'page', null, 'terms-conditions', 80],
        ];

        return collect($menus)->map(function (array $menu) use ($pages): array {
            [$label, $type, $routeName, $pageSlug, $sortOrder] = $menu;

            return [
                'title' => 'Footer ' . $label,
                'label' => $label,
                'type' => $type,
                'route_name' => $routeName,
                'url' => null,
                'page_id' => $pageSlug ? $pages->get($pageSlug)?->id : null,
                'category_id' => null,
                'parent_id' => null,
                'location' => 'footer',
                'sort_order' => $sortOrder,
                'open_in_new_tab' => false,
                'is_mega' => false,
                'status' => true,
            ];
        })->all();
    }
}
