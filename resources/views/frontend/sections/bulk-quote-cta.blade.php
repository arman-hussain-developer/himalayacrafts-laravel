<section class="bg-neutral-950 py-14 text-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[1fr_auto] lg:items-center lg:px-8">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.28em] text-red-300">Bulk Corporate Gifts</p>
            <h2 class="mt-3 text-3xl font-black md:text-4xl">Need Bulk Corporate Gifts?</h2>
            <p class="mt-4 max-w-3xl text-sm leading-7 text-white/75">
                Send us your category, quantity, branding preference and delivery timeline. Our team will help you shortlist bags, wallets, bottles, diaries, pens and promotional products.
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
            <a href="{{ route('bulk-quote.create') }}" class="rounded bg-neutral-950 px-7 py-3 text-center text-sm font-black uppercase tracking-wide text-white hover:bg-neutral-800">
                Request Bulk Quote
            </a>
            <a href="{{ \App\Support\WhatsApp::bulkQuoteUrl() }}" target="_blank" rel="noopener" class="rounded border border-white/20 px-7 py-3 text-center text-sm font-black uppercase tracking-wide text-white hover:bg-white hover:text-neutral-950">
                WhatsApp
            </a>
        </div>
    </div>
</section>
