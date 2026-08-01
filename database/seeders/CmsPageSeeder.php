<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $slug => $data) {
            $page = Page::firstOrNew(['slug' => $slug]);

            if (! $page->exists) {
                $page->title = $data['title'];
                $page->content = $data['content'];
                $page->meta_title = $data['meta_title'];
                $page->meta_description = $data['meta_description'];
                $page->status = true;
            } else {
                if (! filled($page->content)) {
                    $page->content = $data['content'];
                }

                if (! filled($page->meta_title)) {
                    $page->meta_title = $data['meta_title'];
                }

                if (! filled($page->meta_description)) {
                    $page->meta_description = $data['meta_description'];
                }
            }

            $page->save();
        }
    }

    private function pages(): array
    {
        return [
            'about-us' => [
                'title' => 'About Us',
                'content' => '<p>Himalaya Crafts supports corporate catalogue enquiries for practical gifting, promotional products and bulk procurement needs.</p>',
                'meta_title' => 'About Us',
                'meta_description' => 'Learn about Himalaya Crafts and our corporate catalogue, gifting and bulk enquiry support.',
            ],
            'shipping-policy' => [
                'title' => 'Shipping Policy',
                'content' => '<p>Shipping timelines and delivery options depend on product availability, order quantity, branding requirements and destination.</p>',
                'meta_title' => 'Shipping Policy',
                'meta_description' => 'Shipping information for Himalaya Crafts catalogue and bulk enquiry orders.',
            ],
            'returns-policy' => [
                'title' => 'Returns Policy',
                'content' => '<p>Return eligibility depends on product type, customization, order condition and the terms confirmed during quotation.</p>',
                'meta_title' => 'Returns Policy',
                'meta_description' => 'Returns information for Himalaya Crafts catalogue and corporate enquiry orders.',
            ],
            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'content' => '<p>We use enquiry information to respond to catalogue, pricing, branding and delivery requirements shared with our team.</p>',
                'meta_title' => 'Privacy Policy',
                'meta_description' => 'Privacy information for customers submitting catalogue enquiries to Himalaya Crafts.',
            ],
            'terms-conditions' => [
                'title' => 'Terms & Conditions',
                'content' => '<p>Product details, pricing, branding, availability and delivery commitments are confirmed during quotation and order discussion.</p>',
                'meta_title' => 'Terms & Conditions',
                'meta_description' => 'Terms and conditions for Himalaya Crafts catalogue enquiries and corporate orders.',
            ],
        ];
    }
}
