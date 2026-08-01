<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BulkQuoteRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BulkQuoteController extends Controller
{
    private array $statuses = [
        'new',
        'contacted',
        'quoted',
        'converted',
        'closed',
    ];

    public function index(Request $request)
    {
        $bulkQuotes = $this->query($request)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.bulk-quotes.index', [
            'bulkQuotes' => $bulkQuotes,
            'statuses' => $this->statuses,
        ]);
    }

    public function show(BulkQuoteRequest $bulkQuote)
    {
        $bulkQuote->load('category');

        return view('admin.bulk-quotes.show', [
            'bulkQuote' => $bulkQuote,
            'statuses' => $this->statuses,
        ]);
    }

    public function update(Request $request, BulkQuoteRequest $bulkQuote)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in($this->statuses)],
        ]);

        $bulkQuote->update($validated);

        return redirect()
            ->route('admin.bulk-quotes.show', $bulkQuote)
            ->with('success', 'Bulk quote status updated successfully.');
    }

    public function destroy(BulkQuoteRequest $bulkQuote)
    {
        $bulkQuote->delete();

        return redirect()
            ->route('admin.bulk-quotes.index')
            ->with('success', 'Bulk quote request deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $fileName = 'bulk-quotes-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($request): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Phone', 'Email', 'Company', 'Category', 'Quantity', 'Requirement', 'Status', 'Created At']);

            $this->query($request)
                ->latest()
                ->chunk(200, function ($bulkQuotes) use ($handle): void {
                    foreach ($bulkQuotes as $bulkQuote) {
                        fputcsv($handle, [
                            $bulkQuote->id,
                            $bulkQuote->name,
                            $bulkQuote->phone,
                            $bulkQuote->email,
                            $bulkQuote->company_name,
                            $bulkQuote->category?->name,
                            $bulkQuote->quantity,
                            $bulkQuote->requirement,
                            $bulkQuote->status,
                            $bulkQuote->created_at?->format('Y-m-d H:i:s'),
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
        return BulkQuoteRequest::with('category')
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = $request->string('q')->toString();

                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('requirement', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('date_from')))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('date_to')));
    }
}
