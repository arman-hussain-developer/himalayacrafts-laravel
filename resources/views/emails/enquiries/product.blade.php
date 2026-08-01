<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Enquiry</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h1>New Product Enquiry</h1>

    <p><strong>Product:</strong> {{ $enquiry->product_name ?: $enquiry->product?->name ?: 'General enquiry' }}</p>
    @if ($enquiry->variant)
        <p><strong>Variant:</strong> {{ $enquiry->variant->variant_name }}: {{ $enquiry->variant->variant_value }}</p>
    @endif

    <p><strong>Name:</strong> {{ $enquiry->customer_name }}</p>
    <p><strong>Phone:</strong> {{ $enquiry->customer_phone }}</p>
    @if ($enquiry->customer_email)
        <p><strong>Email:</strong> {{ $enquiry->customer_email }}</p>
    @endif

    @if ($enquiry->message)
        <p><strong>Message:</strong></p>
        <p>{{ $enquiry->message }}</p>
    @endif

    <p><strong>Status:</strong> {{ ucfirst($enquiry->status) }}</p>
</body>
</html>
