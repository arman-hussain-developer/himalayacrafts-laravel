# Himalaya Crafts

Production e-commerce and product-catalogue platform for Himalayan handicrafts, developed with Laravel 12 and a custom administrative workflow.

Live website: [himalayacrafts.in](https://himalayacrafts.in/)

## Highlights

- Product, category and variant management
- Cart, order and bulk-quotation workflows
- Dynamic landing pages, banners, testimonials and navigation
- Enquiry and transactional-email flows
- Responsive, SEO-focused storefront

## Local setup

Requirements: PHP 8.2+, Composer, MySQL and Node.js.

```bash
git clone https://github.com/arman-hussain-developer/himalayacrafts-laravel.git
cd himalayacrafts-laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Production uploads, database records and credentials are intentionally excluded. Restore media and data from a private backup when rebuilding production.

## Security

This repository was reconstructed from a hosting backup after removal of an unauthorized web shell. Secrets and runtime artifacts are not committed.
