<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BulkQuoteRequest;
use App\Support\FrontendData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BulkQuoteController extends Controller
{
    public function create()
    {
        $categories = FrontendData::categories();

        return view('frontend.bulk-quote', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'requirement' => ['required', 'string'],
        ]);

        $bulkQuote = BulkQuoteRequest::create([
            ...$validated,
            'status' => 'new',
        ]);

        Log::info('Bulk quote request received', [
            'bulk_quote_id' => $bulkQuote->id,
            'phone' => $bulkQuote->phone,
            'category_id' => $bulkQuote->category_id,
        ]);

        return redirect()
            ->route('bulk-quote.create')
            ->with('success', 'Your bulk quote request has been submitted. Our team will contact you shortly.');
    }
}
