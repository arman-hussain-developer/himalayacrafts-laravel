<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->cart();
        $cartTotal = $this->cartTotal($cart);

        return view('frontend.cart.index', compact('cart', 'cartTotal'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::where('status', true)->findOrFail($validated['product_id']);
        $variant = null;
        $price = (float) ($product->sale_price ?: $product->price);
        $stock = $product->stock;
        $variantLabel = null;

        if (! empty($validated['variant_id'])) {
            $variant = ProductVariant::where('product_id', $product->id)->findOrFail($validated['variant_id']);
            $price = (float) ($variant->price ?: $price);
            $stock = min($product->stock, $variant->stock);
            $variantLabel = $variant->variant_name . ': ' . $variant->variant_value;
        }

        if ($stock < 1) {
            return back()->withErrors(['quantity' => 'This item is out of stock.']);
        }

        $quantity = min((int) $validated['quantity'], $stock);
        $itemKey = $this->itemKey($product->id, $variant?->id);
        $cart = $this->cart();

        if (isset($cart[$itemKey])) {
            $quantity = min($cart[$itemKey]['quantity'] + $quantity, $stock);
        }

        $cart[$itemKey] = [
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'image' => $product->featured_image,
            'variant_label' => $variantLabel,
            'price' => $price,
            'quantity' => $quantity,
            'stock' => $stock,
        ];

        session(['cart' => $cart]);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product added to cart.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'quantities' => ['required', 'array'],
            'quantities.*' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->cart();

        foreach ($validated['quantities'] as $itemKey => $quantity) {
            if (! isset($cart[$itemKey])) {
                continue;
            }

            $cart[$itemKey]['quantity'] = min((int) $quantity, (int) $cart[$itemKey]['stock']);
        }

        session(['cart' => $cart]);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart updated successfully.');
    }

    public function remove(string $itemKey)
    {
        $cart = $this->cart();
        unset($cart[$itemKey]);
        session(['cart' => $cart]);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Item removed from cart.');
    }

    public function checkout(Request $request)
    {
        $cart = $this->cart();

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->withErrors(['cart' => 'Your cart is empty.']);
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_address' => ['required', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

        $order = DB::transaction(function () use ($cart, $validated): Order {
            $items = [];
            $subtotal = 0;

            foreach ($cart as $item) {
                $product = Product::where('status', true)
                    ->lockForUpdate()
                    ->find($item['product_id']);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'cart' => 'One of the products in your cart is no longer available.',
                    ]);
                }

                $variant = null;
                $variantLabel = null;
                $unitPrice = (float) ($product->sale_price ?: $product->price);
                $availableStock = $product->stock;

                if (! empty($item['variant_id'])) {
                    $variant = ProductVariant::where('product_id', $product->id)
                        ->lockForUpdate()
                        ->find($item['variant_id']);

                    if (! $variant) {
                        throw ValidationException::withMessages([
                            'cart' => "{$product->name} variant is no longer available.",
                        ]);
                    }

                    $variantLabel = $variant->variant_name . ': ' . $variant->variant_value;
                    $unitPrice = (float) ($variant->price ?: $unitPrice);
                    $availableStock = min($product->stock, $variant->stock);
                }

                $quantity = (int) $item['quantity'];

                if ($availableStock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart' => "{$product->name} has only {$availableStock} item(s) available.",
                    ]);
                }

                $lineTotal = $unitPrice * $quantity;
                $subtotal += $lineTotal;

                $items[] = [
                    'product' => $product,
                    'variant' => $variant,
                    'variant_label' => $variantLabel,
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                    'total_price' => $lineTotal,
                ];
            }

            $shippingAmount = 0;
            $discountAmount = 0;

            $order = Order::create([
                'order_no' => $this->orderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'pincode' => $validated['pincode'] ?? null,
                'subtotal' => $subtotal,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => $discountAmount,
                'total' => $subtotal + $shippingAmount - $discountAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'cod',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'variant_id' => $item['variant']?->id,
                    'product_name' => $item['product']->name,
                    'variant_label' => $item['variant_label'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total_price' => $item['total_price'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);

                if ($item['variant']) {
                    $item['variant']->decrement('stock', $item['quantity']);
                }
            }

            return $order;
        });

        session()->forget('cart');
        $this->sendOrderEmails($order);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Order ' . $order->order_no . ' placed successfully.');
    }

    private function cart(): array
    {
        return session('cart', []);
    }

    private function cartTotal(array $cart): float
    {
        return collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    private function itemKey(int $productId, ?int $variantId = null): string
    {
        return $productId . ':' . ($variantId ?: 'base');
    }

    private function orderNumber(): string
    {
        do {
            $orderNo = 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
        } while (Order::where('order_no', $orderNo)->exists());

        return $orderNo;
    }

    private function sendOrderEmails(Order $order): void
    {
        try {
            if ($order->customer_email) {
                Mail::to($order->customer_email)->send(new OrderPlacedMail($order));
            }

            $adminEmail = Setting::where('setting_key', 'site_email')->value('value');

            if ($adminEmail) {
                Mail::to($adminEmail)->send(new OrderPlacedMail($order));
            }
        } catch (\Throwable $exception) {
            Log::error('Order email failed', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
