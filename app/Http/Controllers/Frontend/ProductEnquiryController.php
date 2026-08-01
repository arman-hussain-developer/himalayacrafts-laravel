<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ProductEnquiryMail;
use App\Models\Product;
use App\Models\ProductEnquiry;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProductEnquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['nullable', 'exists:products,id'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string'],
        ]);

        $product = null;
        $variant = null;

        if (! empty($validated['product_id'])) {
            $product = Product::find($validated['product_id']);
        }

        if (! empty($validated['variant_id'])) {
            $variant = ProductVariant::where('id', $validated['variant_id'])
                ->when($product, fn ($query) => $query->where('product_id', $product->id))
                ->first();

            if (! $variant) {
                return back()
                    ->withInput()
                    ->withErrors(['variant_id' => 'Selected variant is not available for this product.']);
            }
        }

        $enquiry = ProductEnquiry::create([
            'product_id' => $product?->id,
            'variant_id' => $variant?->id,
            'product_name' => $product?->name,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => 'new',
        ]);

        $this->sendNotification($enquiry);

        return back()->with('success', 'Thank you. Your enquiry has been submitted successfully.');
    }

    private function sendNotification(ProductEnquiry $enquiry): void
    {
        Log::info('Product enquiry received', [
            'enquiry_id' => $enquiry->id,
            'product_id' => $enquiry->product_id,
            'product_name' => $enquiry->product_name,
            'customer_phone' => $enquiry->customer_phone,
        ]);

        try {
            $adminEmail = Setting::where('setting_key', 'site_email')->value('value');

            if ($adminEmail) {
                Mail::to($adminEmail)->send(new ProductEnquiryMail($enquiry));
            }
        } catch (\Throwable $exception) {
            Log::error('Product enquiry email failed', [
                'enquiry_id' => $enquiry->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
