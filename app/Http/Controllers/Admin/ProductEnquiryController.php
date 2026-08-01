<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductEnquiry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductEnquiryController extends Controller
{
    private array $statuses = [
        'new',
        'contacted',
        'converted',
        'closed',
    ];

    public function index(Request $request)
    {
        $enquiries = $this->query($request)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.enquiries.index', [
            'enquiries' => $enquiries,
            'statuses' => $this->statuses,
        ]);
    }

    public function show(ProductEnquiry $enquiry)
    {
        $enquiry->load(['product.category', 'product.subCategory', 'variant']);

        return view('admin.enquiries.show', [
            'enquiry' => $enquiry,
            'statuses' => $this->statuses,
        ]);
    }

    public function update(Request $request, ProductEnquiry $enquiry)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in($this->statuses)],
        ]);

        $enquiry->update($validated);

        return redirect()
            ->route('admin.enquiries.show', $enquiry)
            ->with('success', 'Enquiry status updated successfully.');
    }

    public function destroy(ProductEnquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()
            ->route('admin.enquiries.index')
            ->with('success', 'Enquiry deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $fileName = 'product-enquiries-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($request): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Product', 'Customer Name', 'Phone', 'Email', 'Message', 'Status', 'Created At']);

            $this->query($request)
                ->latest()
                ->chunk(200, function ($enquiries) use ($handle): void {
                    foreach ($enquiries as $enquiry) {
                        fputcsv($handle, [
                            $enquiry->id,
                            $enquiry->product_name ?: $enquiry->product?->name,
                            $enquiry->customer_name,
                            $enquiry->customer_phone,
                            $enquiry->customer_email,
                            $enquiry->message,
                            $enquiry->status,
                            $enquiry->created_at?->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function query(Request $request)
    {
        return ProductEnquiry::with(['product', 'variant'])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = $request->string('q')->toString();

                $query->where(function ($query) use ($search): void {
                    $query->where('product_name', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('date_from')))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('date_to')));
    }
}
