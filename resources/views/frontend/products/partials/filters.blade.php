<form action="{{ route('products.index') }}" method="GET" class="space-y-4">
    <div>
        <label for="q" class="mb-1 block text-sm font-semibold text-neutral-700">Search</label>
        <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Name, SKU, description" class="w-full border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
    </div>

    <div>
        <label for="category" class="mb-1 block text-sm font-semibold text-neutral-700">Category</label>
        <select id="category" name="category" class="w-full border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="sub_category" class="mb-1 block text-sm font-semibold text-neutral-700">Sub Category</label>
        <select id="sub_category" name="sub_category" class="w-full border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
            <option value="">All Sub Categories</option>
            @foreach ($categories as $category)
                @foreach ($category->subCategories as $subCategory)
                    <option value="{{ $subCategory->slug }}" @selected(request('sub_category') === $subCategory->slug)>{{ $category->name }} / {{ $subCategory->name }}</option>
                @endforeach
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="min_price" class="mb-1 block text-sm font-semibold text-neutral-700">Min Budget</label>
            <input type="number" min="0" step="0.01" id="min_price" name="min_price" value="{{ request('min_price') }}" class="w-full border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
        </div>
        <div>
            <label for="max_price" class="mb-1 block text-sm font-semibold text-neutral-700">Max Budget</label>
            <input type="number" min="0" step="0.01" id="max_price" name="max_price" value="{{ request('max_price') }}" class="w-full border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
        </div>
    </div>

    <div>
        <label for="sort" class="mb-1 block text-sm font-semibold text-neutral-700">Sort</label>
        <select id="sort" name="sort" class="w-full border border-neutral-300 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
            <option value="latest" @selected(request('sort', 'latest') === 'latest')>Latest</option>
            <option value="price_low_high" @selected(request('sort') === 'price_low_high')>Budget: Low to High</option>
            <option value="price_high_low" @selected(request('sort') === 'price_high_low')>Budget: High to Low</option>
            <option value="featured" @selected(request('sort') === 'featured')>Featured</option>
        </select>
    </div>

    <button type="submit" class="w-full bg-neutral-950 px-5 py-2.5 text-sm font-bold uppercase tracking-wide text-white hover:bg-neutral-800">
        Apply Filters
    </button>
</form>
