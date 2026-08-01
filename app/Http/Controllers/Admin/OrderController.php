<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    private array $orderStatuses = [
        'pending',
        'confirmed',
        'processing',
        'shipped',
        'delivered',
        'cancelled',
    ];

    private array $paymentStatuses = [
        'pending',
        'paid',
        'failed',
        'refunded',
    ];

    public function index(Request $request)
    {
        $orders = Order::withCount('items')
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = $request->string('q')->toString();

                $query->where(function ($query) use ($search): void {
                    $query->where('order_no', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('status', $request->string('status')->toString());
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $orderStatuses = $this->orderStatuses;

        return view('admin.orders.index', compact('orders', 'orderStatuses'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'user']);

        return view('admin.orders.show', [
            'order' => $order,
            'orderStatuses' => $this->orderStatuses,
            'paymentStatuses' => $this->paymentStatuses,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in($this->orderStatuses)],
            'payment_status' => ['nullable', Rule::in($this->paymentStatuses)],
        ]);

        $order->update(array_filter($validated, fn ($value) => $value !== null));

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }
}
