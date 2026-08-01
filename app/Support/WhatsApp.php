<?php

namespace App\Support;

class WhatsApp
{
    public static function url(?string $phone, string $message): string
    {
        $number = self::normalize($phone);

        return 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
    }

    public static function productUrl(string $productName, ?string $phone = null): string
    {
        return self::url($phone ?: self::defaultPhone(), "Hello, I am interested in {$productName}. Please share details.");
    }

    public static function bulkQuoteUrl(?string $phone = null): string
    {
        return self::url($phone ?: self::defaultPhone(), 'Hello, I need a bulk quote for corporate gifts/catalog products. Please share details.');
    }

    public static function defaultPhone(): ?string
    {
        $settings = FrontendData::settings();

        return $settings->get('whatsapp_number') ?: $settings->get('site_phone');
    }

    public static function normalize(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if (strlen($digits) === 10) {
            return '91' . $digits;
        }

        if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            return $digits;
        }

        return $digits ?: '910000000000';
    }
}
