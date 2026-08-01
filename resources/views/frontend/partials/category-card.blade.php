<a href="{{ route('products.category', $category->slug) }}" class="group rounded border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    @if ($category->image)
        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="mb-4 h-32 w-full rounded object-cover">
    @else
        <div class="mb-4 flex h-32 w-full items-center justify-center rounded bg-slate-100 text-sm font-medium text-slate-400">
            {{ $category->name }}
        </div>
    @endif

    <div class="font-semibold text-slate-900 group-hover:text-slate-700">{{ $category->name }}</div>
    @if ($category->description)
        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">{{ $category->description }}</p>
    @endif
</a>
