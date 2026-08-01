@extends('frontend.layouts.app')

@section('title', 'Contact')
@section('meta_description', 'Contact us for corporate gifts, catalogue products, bulk quotes and branding enquiries.')

@section('content')
<section class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-semibold">Contact</h1>
        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Reach out for product questions, corporate gifting, catalogue shortlisting and bulk quote requirements.</p>
        <div class="mt-5 flex flex-wrap gap-3">
            <a href="{{ route('bulk-quote.create') }}" class="rounded bg-neutral-950 px-5 py-3 text-xs font-black uppercase tracking-wide text-white hover:bg-neutral-800">Request Bulk Quote</a>
            <a href="{{ \App\Support\WhatsApp::bulkQuoteUrl() }}" target="_blank" rel="noopener" class="rounded border border-green-200 px-5 py-3 text-xs font-black uppercase tracking-wide text-green-700 hover:bg-green-50">WhatsApp</a>
        </div>
    </div>
</section>

<section class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[1fr_1.4fr] lg:px-8">
    <div class="rounded border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Store Details</h2>
        <div class="mt-5 space-y-4 text-sm text-slate-700">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</div>
                <div class="mt-1">{{ $settings->get('site_phone') ?: 'Not added yet' }}</div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</div>
                <div class="mt-1">{{ $settings->get('site_email') ?: 'Not added yet' }} <br>himalayacraft@yahoo.com</div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Address</div>
                <div class="mt-1">{{ $settings->get('site_address') ?: 'Not added yet' }}</div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">WhatsApp</div>
                <div class="mt-1">{{ $settings->get('whatsapp_number') ?: 'Not added yet' }}</div>
            </div>
        </div>
    </div>

    <div class="rounded border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold">Send A Message</h2>
        <form action="#" method="GET" class="mt-5 space-y-5">
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" id="name" name="name" class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
                </div>
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
                </div>
            </div>

            <div>
                <label for="subject" class="mb-1 block text-sm font-medium text-slate-700">Subject</label>
                <input type="text" id="subject" name="subject" class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
            </div>

            <div>
                <label for="message" class="mb-1 block text-sm font-medium text-slate-700">Message</label>
                <textarea id="message" name="message" rows="6" class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900"></textarea>
            </div>

            <button type="button" class="rounded bg-slate-900 px-5 py-2 text-sm font-medium text-white hover:bg-slate-800">
                Send Message
            </button>
        </form>
    </div>
</section>

<section class="mt-16 bg-neutral-950 text-white">
    <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3501.4984441401853!2d77.20951457550123!3d28.644790575658085!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMjjCsDM4JzQxLjMiTiA3N8KwMTInNDMuNSJF!5e0!3m2!1sen!2sin!4v1780123733296!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
@endsection
    
</section>

