@php
$productModel = $product ?? null;
$existingSpecifications = $productModel?->specifications?->map(fn ($specification) => [
'label' => $specification->label,
'value' => $specification->value,
'icon' => $specification->icon,
'sort_order' => $specification->sort_order,
])->values()->all() ?? [];
$existingColors = $productModel?->colors?->map(fn ($color) => [
'color_name' => $color->color_name,
'color_code' => $color->color_code,
'color_image' => $color->color_image,
'existing_color_image' => $color->color_image,
'sort_order' => $color->sort_order,
])->values()->all() ?? [];
$existingMedia = $productModel?->images ?? collect();
$existingVariants = $productModel?->variants?->map(fn ($variant) => [
'id' => $variant->id,
'variant_name' => $variant->variant_name,
'variant_value' => $variant->variant_value,
'price' => $variant->price,
'stock' => $variant->stock,
])->values()->all() ?? [];
$mediaTypeLabels = [
'gallery' => 'Gallery',
'branding_mockup' => 'Branding Mockup',
'packaging' => 'Packaging',
];

$specificationRows = old('specifications', $existingSpecifications);
$colorRows = old('colors', $existingColors);
$variantRows = old('variants', $existingVariants);

if (empty($specificationRows)) {
$specificationRows = [['label' => '', 'value' => '', 'icon' => '', 'sort_order' => 0]];
}
if (empty($colorRows)) {
$colorRows = [['color_name' => '', 'color_code' => '', 'existing_color_image' => '', 'sort_order' => 0]];
}
if (empty($variantRows)) {
$variantRows = [['id' => '', 'variant_name' => '', 'variant_value' => '', 'price' => '', 'stock' => 0]];
}

$sectionClass = 'rounded-lg border border-gray-200 bg-white shadow-sm';
$sectionKickerClass = 'text-xs font-semibold uppercase tracking-wide text-gray-500';
$sectionTitleClass = 'text-lg font-semibold text-gray-950';
$sectionHelpClass = 'mt-1 text-sm leading-6 text-gray-500';
$inputClass = 'w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900';
$labelClass = 'mb-1 block text-sm font-medium text-gray-800';
$helpClass = 'mt-1 text-xs leading-5 text-gray-500';
@endphp

{{-- ── STEP 1: Basic info ── --}}
<section class="{{ $sectionClass }}">
    <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
        <div class="flex items-center gap-3">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-bold text-white">1</span>
            <div>
                <h3 class="{{ $sectionTitleClass }}">Basic Product Information</h3>
                <p class="{{ $sectionHelpClass }}">Catalogue placement and main product identifiers.</p>
            </div>
        </div>
    </div>
    <div class="p-5 sm:p-6">
        <div class="grid gap-5 lg:grid-cols-2">
            <div>
                <label for="category_id" class="{{ $labelClass }}">Category</label>
                <select id="category_id" name="category_id" class="{{ $inputClass }}">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $productModel?->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="sub_category_id" class="{{ $labelClass }}">Sub Category</label>
                <select id="sub_category_id" name="sub_category_id" class="{{ $inputClass }}">
                    <option value="">No Sub Category</option>
                    @foreach ($subCategories as $subCategory)
                    <option value="{{ $subCategory->id }}" @selected(old('sub_category_id', $productModel?->sub_category_id) == $subCategory->id)>
                        {{ $subCategory->category?->name }} / {{ $subCategory->name }}
                    </option>
                    @endforeach
                </select>
                @error('sub_category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="name" class="{{ $labelClass }}">Product Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $productModel?->name) }}" class="{{ $inputClass }}" placeholder="e.g. Executive Leather Diary">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="sku" class="{{ $labelClass }}">SKU / Product Code</label>
                <input type="text" id="sku" name="sku" value="{{ old('sku', $productModel?->sku) }}" class="{{ $inputClass }}" placeholder="e.g. DRY-EXEC-001">
                <p class="{{ $helpClass }}">Optional internal code used on catalogues and quotations.</p>
                @error('sku')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            @if ($productModel?->slug)
            <div class="lg:col-span-2">
                <label for="slug_display" class="{{ $labelClass }}">Slug (auto-generated)</label>
                <input type="text" id="slug_display" value="{{ $productModel->slug }}" readonly
                    class="{{ $inputClass }} cursor-not-allowed bg-gray-50 text-gray-400">
                <p class="{{ $helpClass }}">Used in the public product URL. Auto-generated from product name.</p>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- ── STEP 2: Catalogue content ── --}}
<section class="{{ $sectionClass }}">
    <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
        <div class="flex items-center gap-3">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-bold text-white">2</span>
            <div>
                <h3 class="{{ $sectionTitleClass }}">Catalogue Content</h3>
                <p class="{{ $sectionHelpClass }}">Short copy for cards; full editor content for the product detail page.</p>
            </div>
        </div>
    </div>
    <div class="p-5 sm:p-6">
        <div>
            <label for="short_description" class="{{ $labelClass }}">Short Description</label>
            <textarea data-wysiwyg="true" id="short_description" name="short_description" rows="3" class="{{ $inputClass }}">{{ old('short_description', $productModel?->short_description) }}</textarea>
            <p class="{{ $helpClass }}">Shown on product cards. Keep to 1–2 catalogue-friendly lines.</p>
            @error('short_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mt-5">
            <label for="description" class="{{ $labelClass }}">Full Description</label>
            <textarea data-wysiwyg="true" id="description" name="description" rows="8" class="{{ $inputClass }}">{{ old('description', $productModel?->description) }}</textarea>
            <p class="{{ $helpClass }}">Shown on the product detail page Description tab.</p>
            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mt-5 grid gap-5 lg:grid-cols-2">
            <div>
                <label for="branding_details" class="{{ $labelClass }}">Branding Details</label>
                <textarea data-wysiwyg="true" id="branding_details" name="branding_details" rows="5" class="{{ $inputClass }}">{{ old('branding_details', $productModel?->branding_details) }}</textarea>
                <p class="{{ $helpClass }}">Logo placement, printing, embossing and customization notes.</p>
                @error('branding_details')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="packaging_details" class="{{ $labelClass }}">Packaging Details</label>
                <textarea data-wysiwyg="true" id="packaging_details" name="packaging_details" rows="5" class="{{ $inputClass }}">{{ old('packaging_details', $productModel?->packaging_details) }}</textarea>
                <p class="{{ $helpClass }}">Box, sleeve, pouch, carton or presentation packaging info.</p>
                @error('packaging_details')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-5 grid gap-5 lg:grid-cols-3">
            <div>
                <label for="production_timeline" class="{{ $labelClass }}">Production Timeline</label>
                <input type="text" id="production_timeline" name="production_timeline"
                    value="{{ old('production_timeline', $productModel?->production_timeline) }}"
                    placeholder="e.g. 7–10 working days" class="{{ $inputClass }}">
                @error('production_timeline')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="moq" class="{{ $labelClass }}">MOQ</label>
                <input type="text" id="moq" name="moq"
                    value="{{ old('moq', $productModel?->moq) }}"
                    placeholder="e.g. 100 pcs" class="{{ $inputClass }}">
                @error('moq')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="printing_methods" class="{{ $labelClass }}">Printing Methods</label>
                <textarea id="printing_methods" name="printing_methods" rows="2"
                    placeholder="Screen print, UV print, laser engraving"
                    class="{{ $inputClass }}">{{ old('printing_methods', $productModel?->printing_methods) }}</textarea>
                @error('printing_methods')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
</section>

{{-- ── STEP 3: Pricing ── --}}
<section class="{{ $sectionClass }}">
    <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
        <div class="flex items-center gap-3">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-bold text-white">3</span>
            <div>
                <h3 class="{{ $sectionTitleClass }}">Pricing / Enquiry</h3>
                <p class="{{ $sectionHelpClass }}">Use 0 if price is enquiry-based and should not be shown publicly.</p>
            </div>
        </div>
    </div>
    <div class="p-5 sm:p-6">
        <div class="grid gap-5 md:grid-cols-3">
            <div>
                <label for="price" class="{{ $labelClass }}">Price</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rs.</span>
                    <input type="number" step="0.01" min="0" id="price" name="price"
                        value="{{ old('price', $productModel?->price ?? 0) }}"
                        class="{{ $inputClass }} pl-9">
                </div>
                @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="sale_price" class="{{ $labelClass }}">Sale Price <span class="text-gray-400 font-normal">(optional)</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rs.</span>
                    <input type="number" step="0.01" min="0" id="sale_price" name="sale_price"
                        value="{{ old('sale_price', $productModel?->sale_price) }}"
                        class="{{ $inputClass }} pl-9">
                </div>
                @error('sale_price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="stock" class="{{ $labelClass }}">Stock</label>
                <input type="number" min="0" id="stock" name="stock"
                    value="{{ old('stock', $productModel?->stock ?? 0) }}"
                    class="{{ $inputClass }}">
                @error('stock')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
</section>

{{-- ── STEP 4: Images ── --}}
<section class="{{ $sectionClass }}">
    <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
        <div class="flex items-center gap-3">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-bold text-white">4</span>
            <div>
                <h3 class="{{ $sectionTitleClass }}">Product Images</h3>
                <p class="{{ $sectionHelpClass }}">Recommended: at least 900×650 px, JPG/PNG/WebP, product centred on a clean background.</p>
            </div>
        </div>
    </div>
    <div class="p-5 sm:p-6">

        {{-- Featured image with live preview --}}
        <div class="grid gap-5 lg:grid-cols-[280px_1fr]">
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <label class="{{ $labelClass }}">Featured Image</label>
                <p class="{{ $helpClass }} mb-3">Primary product image shown on cards and detail page.</p>

                {{-- Preview box --}}
                <div class="relative flex h-44 items-center justify-center overflow-hidden rounded border border-dashed border-gray-300 bg-white">
                    @if ($productModel?->featured_image)
                    <img id="featured-image-preview-img"
                        src="{{ asset($productModel->featured_image) }}"
                        alt="Featured"
                        class="h-full w-full object-contain p-3">
                    {{-- Hover overlay --}}
                    <label for="featured_image"
                        class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/0 text-transparent transition hover:bg-black/30 hover:text-white text-xs font-semibold">
                        Click to change
                    </label>
                    @else
                    <div class="text-center">
                        <svg class="mx-auto mb-2 h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm text-gray-400">No image selected</p>
                    </div>
                    @endif
                </div>

                <input type="file" id="featured_image" name="featured_image"
                    accept="image/*"
                    data-featured-preview
                    class="mt-3 w-full rounded border border-gray-300 px-3 py-2 text-sm
                      file:mr-4 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-gray-800">
                @error('featured_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Gallery / branding / packaging uploads --}}
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-lg border border-gray-200 p-4">
                    <label for="gallery_images" class="{{ $labelClass }}">Gallery Images</label>
                    <p class="{{ $helpClass }} mb-3">Detail images shown in the thumbnail strip.</p>
                    <input type="file" id="gallery_images" name="gallery_images[]" multiple accept="image/*"
                        data-multi-preview="gallery-images-preview"
                        class="w-full rounded border border-gray-300 px-3 py-2 text-sm file:mr-4 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-gray-800">
                    <div id="gallery-images-preview" class="mt-3 grid grid-cols-3 gap-2"></div>
                </div>

                <div class="rounded-lg border border-gray-200 p-4">
                    <label for="branding_images" class="{{ $labelClass }}">Branding Mockups</label>
                    <p class="{{ $helpClass }} mb-3">Logo placement, print or embossing previews.</p>
                    <input type="file" id="branding_images" name="branding_images[]" multiple accept="image/*"
                        data-multi-preview="branding-images-preview"
                        class="w-full rounded border border-gray-300 px-3 py-2 text-sm file:mr-4 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-gray-800">
                    <div id="branding-images-preview" class="mt-3 grid grid-cols-3 gap-2"></div>
                </div>

                <div class="rounded-lg border border-gray-200 p-4 md:col-span-2">
                    <label for="packaging_images" class="{{ $labelClass }}">Packaging Images</label>
                    <p class="{{ $helpClass }} mb-3">Packaging, box, pouch or bulk carton images.</p>
                    <input type="file" id="packaging_images" name="packaging_images[]" multiple accept="image/*"
                        data-multi-preview="packaging-images-preview"
                        class="w-full rounded border border-gray-300 px-3 py-2 text-sm file:mr-4 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-gray-800">
                    <div id="packaging-images-preview" class="mt-3 grid grid-cols-3 gap-2 sm:grid-cols-6"></div>
                </div>
            </div>


        </div>
        {{-- Existing media manager --}}
        @if ($existingMedia->isNotEmpty())
        <div class="mt-6 border-t border-gray-100 pt-5">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">Existing Media</h4>
                    <p class="mt-0.5 text-xs text-gray-500">Change type, sort order, or tick "Remove" to delete on save.</p>
                </div>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">{{ $existingMedia->count() }} file{{ $existingMedia->count() !== 1 ? 's' : '' }}</span>
            </div>
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($existingMedia as $media)
                <div class="group rounded-lg border border-gray-200 bg-gray-50 p-3">
                    <div class="relative overflow-hidden rounded bg-white">
                        <img src="{{ asset($media->image) }}"
                            alt="{{ $productModel?->name }}"
                            class="h-28 w-full object-contain">
                    </div>
                    <div class="mt-3 grid gap-2 sm:grid-cols-[1fr_80px]">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">Type</label>
                            <select name="existing_media[{{ $media->id }}][image_type]"
                                class="w-full rounded border border-gray-300 px-2 py-1.5 text-xs focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                                @foreach ($mediaTypeLabels as $value => $label)
                                <option value="{{ $value }}" @selected(old('existing_media.' . $media->id . '.image_type', $media->image_type ?: 'gallery') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">Sort</label>
                            <input type="number" min="0"
                                name="existing_media[{{ $media->id }}][sort_order]"
                                value="{{ old('existing_media.' . $media->id . '.sort_order', $media->sort_order ?? 0) }}"
                                class="w-full rounded border border-gray-300 px-2 py-1.5 text-xs focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                        </div>
                    </div>
                    <label class="mt-3 flex cursor-pointer items-center gap-2 rounded border border-red-100 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                        <input type="checkbox" name="remove_media[]" value="{{ $media->id }}"
                            class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        Remove on save
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    

</section>

{{-- ── STEP 5: Specifications ── --}}
<section class="{{ $sectionClass }}">
    <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-bold text-white">5</span>
                <div>
                    <h3 class="{{ $sectionTitleClass }}">Specifications</h3>
                    <p class="{{ $sectionHelpClass }}">e.g. Material / Leather Finish &nbsp;·&nbsp; Size / 23×15 cm &nbsp;·&nbsp; Branding / Logo printing available</p>
                </div>
            </div>
            <button type="button" id="add-specification-row"
                class="shrink-0 rounded border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400">
                + Add Row
            </button>
        </div>
    </div>
    <div class="p-5 sm:p-6">

        {{-- Column headers --}}
        <div class="mb-2 hidden grid-cols-[1fr_1fr_1fr_90px_36px] gap-3 px-1 xl:grid">
            <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Label</span>
            <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Value</span>
            <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Icon class <span class="font-normal text-gray-400">(Font Awesome)</span></span>
            <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Sort</span>
            <span></span>
        </div>

        <div id="specification-rows" class="space-y-2">
            @foreach ($specificationRows as $index => $specification)
            <div class="rounded border border-gray-200 bg-gray-50 p-3" data-repeatable-row>
                <div class="grid gap-2 xl:grid-cols-[1fr_1fr_1fr_90px_36px]">
                    <input type="text" name="specifications[{{ $index }}][label]" value="{{ $specification['label'] ?? '' }}" placeholder="e.g. Material" class="{{ $inputClass }}">
                    <input type="text" name="specifications[{{ $index }}][value]" value="{{ $specification['value'] ?? '' }}" placeholder="e.g. Leather Finish" class="{{ $inputClass }}">
                    <div class="flex items-center gap-2">
                        @if (!empty($specification['icon']))
                        <i class="{{ $specification['icon'] }} w-5 text-center text-sm text-gray-600 spec-icon-preview"></i>
                        @else
                        <i class="fa-solid fa-circle-check w-5 text-center text-sm text-gray-300 spec-icon-preview"></i>
                        @endif
                        <input type="text" name="specifications[{{ $index }}][icon]" value="{{ $specification['icon'] ?? '' }}" placeholder="fa-solid fa-ruler-combined" class="{{ $inputClass }} flex-1" data-spec-icon>
                    </div>
                    <input type="number" name="specifications[{{ $index }}][sort_order]" value="{{ $specification['sort_order'] ?? 0 }}" placeholder="0" class="{{ $inputClass }}">
                    <button type="button" class="flex h-9 w-9 items-center justify-center rounded border border-red-200 bg-white text-red-500 hover:bg-red-50" data-remove-row title="Remove row">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── STEP 6: Colors ── --}}
<section class="{{ $sectionClass }}">
    <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-bold text-white">6</span>
                <div>
                    <h3 class="{{ $sectionTitleClass }}">Colors</h3>
                    <p class="{{ $sectionHelpClass }}">e.g. Black / #111827 &nbsp;·&nbsp; Brown / #8B4513 &nbsp;·&nbsp; Add a color image for textured finishes.</p>
                </div>
            </div>
            <button type="button" id="add-color-row"
                class="shrink-0 rounded border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400">
                + Add Color
            </button>
        </div>
    </div>
    <div class="p-5 sm:p-6">

        <div id="color-rows" class="space-y-2">
            @foreach ($colorRows as $index => $color)
            @php
            $colorImage = $color['existing_color_image'] ?? $color['color_image'] ?? '';
            $colorCode = $color['color_code'] ?? '';
            $swatchStyle = preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/', (string) $colorCode)
            ? 'background-color:' . $colorCode . ';'
            : 'background-color:#ffffff;';
            @endphp
            <div class="rounded border border-gray-200 bg-gray-50 p-3" data-repeatable-row>
                <div class="grid gap-3 xl:grid-cols-[1fr_1.2fr_80px_1.2fr_36px]">

                    {{-- Color name --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-500 xl:hidden">Color Name</label>
                        <input type="text" name="colors[{{ $index }}][color_name]"
                            value="{{ $color['color_name'] ?? '' }}"
                            placeholder="Color name e.g. Black"
                            class="{{ $inputClass }}">
                    </div>

                    {{-- Hex input with live swatch + native color picker --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-500 xl:hidden">Hex Code</label>
                        <div class="flex items-center gap-2">
                            <span class="h-9 w-9 shrink-0 rounded-full border-2 border-white shadow-md ring-1 ring-gray-200"
                                data-color-swatch style="{{ $swatchStyle }}"></span>
                            <input type="color" data-color-picker
                                value="{{ preg_match('/^#[A-Fa-f0-9]{6}$/', $colorCode) ? $colorCode : '#ffffff' }}"
                                class="h-9 w-9 shrink-0 cursor-pointer rounded border border-gray-300 p-0.5">
                            <input type="text" name="colors[{{ $index }}][color_code]"
                                value="{{ $colorCode }}"
                                placeholder="#111827"
                                data-color-code
                                class="{{ $inputClass }} font-mono">
                        </div>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-500 xl:hidden">Sort</label>
                        <input type="number" min="0" name="colors[{{ $index }}][sort_order]"
                            value="{{ $color['sort_order'] ?? 0 }}"
                            placeholder="0"
                            class="{{ $inputClass }}">
                    </div>

                    {{-- Color image upload --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-500 xl:hidden">Color Image <span class="font-normal">(optional)</span></label>
                        <div class="flex items-center gap-2">
                            @if ($colorImage)
                            <img src="{{ asset($colorImage) }}" alt="{{ $color['color_name'] ?? '' }}"
                                class="h-9 w-9 shrink-0 rounded-full border border-gray-200 object-cover">
                            @else
                            <span class="hidden h-9 w-9 shrink-0 items-center justify-center rounded-full border border-dashed border-gray-300 bg-white text-gray-300 color-img-thumb">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01" />
                                </svg>
                            </span>
                            @endif
                            <input type="hidden" name="colors[{{ $index }}][existing_color_image]" value="{{ $colorImage }}">
                            <input type="file" name="colors[{{ $index }}][color_image]"
                                accept="image/*"
                                data-color-img-preview
                                class="w-full rounded border border-gray-300 px-2 py-1.5 text-xs
                                              file:mr-2 file:rounded file:border-0 file:bg-gray-900 file:px-2 file:py-1 file:text-xs file:font-medium file:text-white hover:file:bg-gray-800">
                        </div>
                    </div>

                    {{-- Remove --}}
                    <div class="flex items-center justify-end xl:items-start xl:pt-0">
                        <button type="button"
                            class="flex h-9 w-9 items-center justify-center rounded border border-red-200 bg-white text-red-500 hover:bg-red-50"
                            data-remove-row title="Remove color">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── STEP 7: Variants ── --}}
<section class="{{ $sectionClass }}">
    <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-bold text-white">7</span>
                <div>
                    <h3 class="{{ $sectionTitleClass }}">Variants <span class="text-sm font-normal text-gray-500">(optional)</span></h3>
                    <p class="{{ $sectionHelpClass }}">For size, page type, capacity, branding option, model, or pack option.</p>
                </div>
            </div>
            <button type="button" id="add-variant-row"
                class="shrink-0 rounded border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400">
                + Add Variant
            </button>
        </div>
    </div>
    <div class="p-5 sm:p-6">

        <div id="variant-rows" class="space-y-3">
            @foreach ($variantRows as $index => $variant)
            <div class="rounded border border-gray-200 bg-gray-50 p-4" data-repeatable-row>
                <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] ?? '' }}">
                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-[1fr_1fr_130px_100px_auto]">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600">Variant Name <span class="text-red-500">*</span></label>
                        <input type="text" name="variants[{{ $index }}][variant_name]"
                            value="{{ $variant['variant_name'] ?? '' }}"
                            placeholder="e.g. Size" class="{{ $inputClass }}">
                        @error("variants.{$index}.variant_name")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600">Variant Value <span class="text-red-500">*</span></label>
                        <input type="text" name="variants[{{ $index }}][variant_value]"
                            value="{{ $variant['variant_value'] ?? '' }}"
                            placeholder="e.g. Small" class="{{ $inputClass }}">
                        @error("variants.{$index}.variant_value")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600">Price</label>
                        <div class="relative">
                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rs.</span>
                            <input type="number" step="0.01" min="0"
                                name="variants[{{ $index }}][price]"
                                value="{{ $variant['price'] ?? '' }}"
                                placeholder="0.00" class="{{ $inputClass }} pl-8">
                        </div>
                        @error("variants.{$index}.price")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600">Stock</label>
                        <input type="number" min="0"
                            name="variants[{{ $index }}][stock]"
                            value="{{ $variant['stock'] ?? 0 }}"
                            placeholder="0" class="{{ $inputClass }}">
                        @error("variants.{$index}.stock")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-end">
                        <button type="button" data-remove-row
                            class="flex h-9 w-9 items-center justify-center rounded border border-red-200 bg-white text-red-500 hover:bg-red-50" title="Remove">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @error('variants')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</section>

{{-- ── STEP 8: Status / Featured ── --}}
<section class="{{ $sectionClass }}">
    <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
        <div class="flex items-center gap-3">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-900 text-xs font-bold text-white">8</span>
            <div>
                <h3 class="{{ $sectionTitleClass }}">Status &amp; Visibility</h3>
                <p class="{{ $sectionHelpClass }}">Control public visibility and featured catalogue placement.</p>
            </div>
        </div>
    </div>
    <div class="p-5 sm:p-6">
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 bg-gray-50 p-4 hover:bg-white">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" id="status" name="status" value="1"
                    @checked(old('status', $productModel?->status ?? 1))
                class="mt-0.5 h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                <span>
                    <span class="block text-sm font-semibold text-gray-900">Active</span>
                    <span class="mt-1 block text-xs leading-5 text-gray-500">Show this product on the public catalogue.</span>
                </span>
            </label>

            <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 bg-gray-50 p-4 hover:bg-white">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" id="is_featured" name="is_featured" value="1"
                    @checked(old('is_featured', $productModel?->is_featured ?? 0))
                class="mt-0.5 h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                <span>
                    <span class="block text-sm font-semibold text-gray-900">Featured</span>
                    <span class="mt-1 block text-xs leading-5 text-gray-500">Highlight in featured catalogue sections on the home page.</span>
                </span>
            </label>
        </div>
    </div>
</section>

@push('scripts')
<script>
    (() => {
        let specificationIndex = {
            {
                count($specificationRows)
            }
        };
        let colorIndex = {
            {
                count($colorRows)
            }
        };
        let variantIndex = {
            {
                count($variantRows)
            }
        };

        const inputClass = @json($inputClass);
        const xBtn = `<button type="button" class="flex h-9 w-9 items-center justify-center rounded border border-red-200 bg-white text-red-500 hover:bg-red-50" data-remove-row title="Remove"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>`;

        /* ── Templates ── */
        const specTemplate = (i) => `
        <div class="rounded border border-gray-200 bg-gray-50 p-3" data-repeatable-row>
            <div class="grid gap-2 xl:grid-cols-[1fr_1fr_1fr_90px_36px]">
                <input type="text"   name="specifications[${i}][label]"      placeholder="e.g. Material"          class="${inputClass}">
                <input type="text"   name="specifications[${i}][value]"      placeholder="e.g. Leather Finish"    class="${inputClass}">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check w-5 text-center text-sm text-gray-300 spec-icon-preview"></i>
                    <input type="text" name="specifications[${i}][icon]" placeholder="fa-solid fa-ruler-combined" class="${inputClass} flex-1" data-spec-icon>
                </div>
                <input type="number" name="specifications[${i}][sort_order]" value="0" placeholder="0"            class="${inputClass}">
                ${xBtn}
            </div>
        </div>`;

        const colorTemplate = (i) => `
        <div class="rounded border border-gray-200 bg-gray-50 p-3" data-repeatable-row>
            <div class="grid gap-3 xl:grid-cols-[1fr_1.2fr_80px_1.2fr_36px]">
                <input type="text" name="colors[${i}][color_name]" placeholder="Color name e.g. Black" class="${inputClass}">
                <div class="flex items-center gap-2">
                    <span class="h-9 w-9 shrink-0 rounded-full border-2 border-white shadow-md ring-1 ring-gray-200 bg-white" data-color-swatch></span>
                    <input type="color" data-color-picker value="#ffffff" class="h-9 w-9 shrink-0 cursor-pointer rounded border border-gray-300 p-0.5">
                    <input type="text" name="colors[${i}][color_code]" placeholder="#111827" data-color-code class="${inputClass} font-mono">
                </div>
                <input type="number" min="0" name="colors[${i}][sort_order]" value="0" placeholder="0" class="${inputClass}">
                <div class="flex items-center gap-2">
                    <input type="hidden" name="colors[${i}][existing_color_image]" value="">
                    <input type="file" name="colors[${i}][color_image]" accept="image/*" data-color-img-preview class="w-full rounded border border-gray-300 px-2 py-1.5 text-xs file:mr-2 file:rounded file:border-0 file:bg-gray-900 file:px-2 file:py-1 file:text-xs file:font-medium file:text-white hover:file:bg-gray-800">
                </div>
                ${xBtn}
            </div>
        </div>`;

        const variantTemplate = (i) => `
        <div class="rounded border border-gray-200 bg-gray-50 p-4" data-repeatable-row>
            <input type="hidden" name="variants[${i}][id]" value="">
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-[1fr_1fr_130px_100px_auto]">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Variant Name *</label>
                    <input type="text" name="variants[${i}][variant_name]" placeholder="e.g. Size" class="${inputClass}">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Variant Value *</label>
                    <input type="text" name="variants[${i}][variant_value]" placeholder="e.g. Small" class="${inputClass}">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Price</label>
                    <div class="relative"><span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rs.</span>
                    <input type="number" step="0.01" min="0" name="variants[${i}][price]" placeholder="0.00" class="${inputClass} pl-8"></div>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Stock</label>
                    <input type="number" min="0" name="variants[${i}][stock]" value="0" class="${inputClass}">
                </div>
                <div class="flex items-end">${xBtn}</div>
            </div>
        </div>`;

        /* ── Add row buttons ── */
        document.getElementById('add-specification-row')?.addEventListener('click', () => {
            document.getElementById('specification-rows')?.insertAdjacentHTML('beforeend', specTemplate(specificationIndex++));
        });
        document.getElementById('add-color-row')?.addEventListener('click', () => {
            document.getElementById('color-rows')?.insertAdjacentHTML('beforeend', colorTemplate(colorIndex++));
        });
        document.getElementById('add-variant-row')?.addEventListener('click', () => {
            document.getElementById('variant-rows')?.insertAdjacentHTML('beforeend', variantTemplate(variantIndex++));
        });

        /* ── Remove row ── */
        document.addEventListener('click', (e) => {
            if (!e.target.closest('[data-remove-row]')) return;
            const row = e.target.closest('[data-repeatable-row]');
            const container = row?.parentElement;
            if (!row || !container) return;

            if (container.querySelectorAll('[data-repeatable-row]').length === 1) {
                row.querySelectorAll('input:not([type="checkbox"])').forEach((inp) => {
                    inp.value = inp.type === 'number' ? '0' : '';
                });
                row.querySelectorAll('[data-color-swatch]').forEach((sw) => {
                    sw.style.backgroundColor = '#ffffff';
                });
                return;
            }
            row.remove();
        });

        /* ── Color hex ↔ picker ↔ swatch live sync ── */
        function syncSwatch(input) {
            const val = input.value.trim();
            const row = input.closest('[data-repeatable-row]');
            const swatch = row?.querySelector('[data-color-swatch]');
            const picker = row?.querySelector('[data-color-picker]');
            const valid = /^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/.test(val);
            if (swatch) swatch.style.backgroundColor = valid ? val : '#ffffff';
            if (picker && /^#[A-Fa-f0-9]{6}$/.test(val)) picker.value = val;
        }
        document.addEventListener('input', (e) => {
            if (e.target.matches('[data-color-code]')) syncSwatch(e.target);
        });
        document.querySelectorAll('[data-color-code]').forEach(syncSwatch);

        /* Picker → hex text input + swatch */
        document.addEventListener('input', (e) => {
            if (!e.target.matches('[data-color-picker]')) return;
            const row = e.target.closest('[data-repeatable-row]');
            const codeInput = row?.querySelector('[data-color-code]');
            if (codeInput) {
                codeInput.value = e.target.value;
                syncSwatch(codeInput);
            }
        });

        /* ── Spec icon → live preview ── */
        function syncSpecIcon(input) {
            const preview = input.closest('[data-repeatable-row]')?.querySelector('.spec-icon-preview');
            if (!preview) return;
            const val = input.value.trim();
            preview.className = (val || 'fa-solid fa-circle-check') + ' w-5 text-center text-sm spec-icon-preview ' + (val ? 'text-gray-600' : 'text-gray-300');
        }
        document.addEventListener('input', (e) => {
            if (e.target.matches('[data-spec-icon]')) syncSpecIcon(e.target);
        });
        document.querySelectorAll('[data-spec-icon]').forEach(syncSpecIcon);

        /* ── Featured image: instant live preview ── */
        const featuredInput = document.querySelector('[data-featured-preview]');
        const featuredImg = document.getElementById('featured-image-preview-img');

        featuredInput?.addEventListener('change', () => {
            const file = featuredInput.files?.[0];
            if (!file) return;

            const reader = new FileReader();
            reader.addEventListener('load', () => {
                if (featuredImg) {
                    featuredImg.src = reader.result;
                    featuredImg.classList.remove('hidden');
                }
                // Hide placeholder if exists
                const placeholder = featuredImg?.parentElement?.querySelector('div');
                if (placeholder) placeholder.style.display = 'none';
            });
            reader.readAsDataURL(file);
        });

        /* ── Multi-image upload: preview grid with removable items ── */
        function buildMultiPreview(input, previewId) {
            const preview = document.getElementById(previewId);
            if (!preview) return;

            input.addEventListener('change', () => {
                preview.innerHTML = '';
                const dt = new DataTransfer();
                Array.from(input.files || []).forEach((file) => {
                    dt.items.add(file);
                });
                renderPreviews(input, preview, dt);
            });

            function renderPreviews(inp, box, dt) {
                box.innerHTML = '';
                Array.from(dt.files).forEach((file, idx) => {
                    const reader = new FileReader();
                    reader.addEventListener('load', () => {
                        const wrap = document.createElement('div');
                        wrap.className = 'relative rounded border border-gray-200 bg-gray-50 p-1';
                        wrap.innerHTML = `
                        <img src="${reader.result}" alt="${file.name}" class="h-16 w-full rounded object-contain">
                        <button type="button" data-preview-remove="${idx}"
                                class="absolute -right-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-white shadow hover:bg-red-700" title="Remove">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>`;
                        wrap.querySelector('[data-preview-remove]')?.addEventListener('click', () => {
                            const newDt = new DataTransfer();
                            Array.from(dt.files).forEach((f, i) => {
                                if (i !== idx) newDt.items.add(f);
                            });
                            inp.files = newDt.files;
                            renderPreviews(inp, box, newDt);
                        });
                        box.appendChild(wrap);
                    });
                    reader.readAsDataURL(file);
                });
            }
        }

        document.querySelectorAll('[data-multi-preview]').forEach((inp) => {
            buildMultiPreview(inp, inp.dataset.multiPreview);
        });

        /* ── Color image → small thumbnail preview ── */
        document.addEventListener('change', (e) => {
            if (!e.target.matches('[data-color-img-preview]')) return;
            const file = e.target.files?.[0];
            if (!file) return;
            const row = e.target.closest('[data-repeatable-row]');
            if (!row) return;
            const reader = new FileReader();
            reader.addEventListener('load', () => {
                let thumb = row.querySelector('.color-img-thumb-preview');
                if (!thumb) {
                    thumb = document.createElement('img');
                    thumb.className = 'color-img-thumb-preview h-9 w-9 shrink-0 rounded-full border border-gray-200 object-cover';
                    e.target.parentElement.insertBefore(thumb, e.target);
                }
                thumb.src = reader.result;
            });
            reader.readAsDataURL(file);
        });
    })();
</script>
@endpush