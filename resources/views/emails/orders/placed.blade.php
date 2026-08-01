<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Placed</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h1>Order {{ $order->order_no ?: 'ORD-' . $order->id }}</h1>
    <p>Hello {{ $order->customer_name }},</p>
    <p>Your order has been placed successfully.</p>

    <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; border-color: #e5e7eb;">
        <thead>
            <tr>
                <th align="left">Product</th>
                <th align="left">Variant</th>
                <th align="right">Qty</th>
                <th align="right">Price</th>
                <th align="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->variant_label ?: 'Default' }}</td>
                    <td align="right">{{ $item->quantity }}</td>
                    <td align="right">{{ number_format((float) $item->price, 2) }}</td>
                    <td align="right">{{ number_format((float) $item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total:</strong> {{ number_format((float) $order->total, 2) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
</body>
</html>
