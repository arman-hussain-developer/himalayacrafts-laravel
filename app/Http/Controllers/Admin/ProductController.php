<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $subCategories = SubCategory::with('category')->orderBy('name')->get();

        $products = Product::with(['category', 'subCategory'])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = $request->string('q')->toString();

                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('sub_category_id'), fn ($query) => $query->where('sub_category_id', $request->integer('sub_category_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->boolean('status')))
            ->when($request->filled('is_featured'), fn ($query) => $query->where('is_featured', $request->boolean('is_featured')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'categories', 'subCategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $subCategories = SubCategory::with('category')->orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $specifications = $validated['specifications'] ?? [];
        $colors = $validated['colors'] ?? [];
        $variants = $validated['variants'] ?? [];

        unset(
            $validated['specifications'],
            $validated['colors'],
            $validated['variants'],
            $validated['gallery_images'],
            $validated['branding_images'],
            $validated['packaging_images'],
            $validated['existing_media'],
            $validated['remove_media']
        );

        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['sub_category_id'] = $validated['sub_category_id'] ?? null;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['status'] = $request->boolean('status');

        if ($image = $this->uploadImage($request)) {
            $validated['featured_image'] = $image;
        }

        $product = Product::create($validated);
        $this->syncSpecifications($product, $specifications);
        $this->syncColors($product, $colors, $request);
        $this->syncVariants($product, $variants);
        $this->syncProductMedia($product, $request);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load(['specifications', 'colors', 'images', 'variants']);
        $categories = Category::orderBy('name')->get();
        $subCategories = SubCategory::with('category')->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'subCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate($this->rules());
        $specifications = $validated['specifications'] ?? [];
        $colors = $validated['colors'] ?? [];
        $variants = $validated['variants'] ?? [];

        unset(
            $validated['specifications'],
            $validated['colors'],
            $validated['variants'],
            $validated['gallery_images'],
            $validated['branding_images'],
            $validated['packaging_images'],
            $validated['existing_media'],
            $validated['remove_media']
        );

        $validated['slug'] = $this->uniqueSlug($validated['name'], $product->id);
        $validated['sub_category_id'] = $validated['sub_category_id'] ?? null;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['status'] = $request->boolean('status');

        if ($image = $this->uploadImage($request)) {
            $this->deleteImage($product->featured_image);
            $validated['featured_image'] = $image;
        }

        $product->update($validated);
        $this->syncSpecifications($product, $specifications);
        $this->syncColors($product, $colors, $request);
        $this->syncVariants($product, $variants);
        $this->syncProductMedia($product, $request);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->deleteImage($product->featured_image);
        $product->images()->get()->each(function ($image): void {
            $this->deleteImage($image->image);
        });
        $product->colors()->whereNotNull('color_image')->get()->each(function ($color): void {
            $this->deleteImage($color->color_image);
        });
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => ['nullable', 'exists:sub_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'branding_details' => ['nullable', 'string'],
            'packaging_details' => ['nullable', 'string'],
            'production_timeline' => ['nullable', 'string', 'max:255'],
            'moq' => ['nullable', 'string', 'max:255'],
            'printing_methods' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'featured_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'branding_images' => ['nullable', 'array'],
            'branding_images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'packaging_images' => ['nullable', 'array'],
            'packaging_images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'existing_media' => ['nullable', 'array'],
            'existing_media.*.image_type' => ['nullable', 'in:gallery,branding_mockup,packaging'],
            'existing_media.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'remove_media' => ['nullable', 'array'],
            'remove_media.*' => ['nullable', 'integer', 'exists:product_images,id'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'boolean'],
            'specifications' => ['nullable', 'array'],
            'specifications.*.label' => ['nullable', 'string', 'max:255'],
            'specifications.*.value' => ['nullable', 'string', 'max:255'],
            'specifications.*.icon' => ['nullable', 'string', 'max:255'],
            'specifications.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'colors' => ['nullable', 'array'],
            'colors.*.color_name' => ['nullable', 'string', 'max:255'],
            'colors.*.color_code' => ['nullable', 'string', 'max:20'],
            'colors.*.color_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'colors.*.existing_color_image' => ['nullable', 'string', 'max:255'],
            'colors.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'variants' => ['nullable', 'array'],
            'variants.*.id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'variants.*.variant_name' => ['nullable', 'string', 'max:255'],
            'variants.*.variant_value' => ['nullable', 'string', 'max:255'],
            'variants.*.price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
        ];
    }

    private function syncSpecifications(Product $product, array $specifications): void
    {
        $product->specifications()->delete();

        foreach ($specifications as $specification) {
            $label = trim((string) ($specification['label'] ?? ''));
            $value = trim((string) ($specification['value'] ?? ''));

            if ($label === '' || $value === '') {
                continue;
            }

            $product->specifications()->create([
                'label' => $label,
                'value' => $value,
                'icon' => trim((string) ($specification['icon'] ?? '')) ?: null,
                'sort_order' => (int) ($specification['sort_order'] ?? 0),
            ]);
        }
    }

    private function syncColors(Product $product, array $colors, Request $request): void
    {
        $previousImages = $product->colors()
            ->whereNotNull('color_image')
            ->pluck('color_image')
            ->filter()
            ->values()
            ->all();
        $colorRows = [];
        $usedImages = [];

        foreach ($colors as $index => $color) {
            $colorName = trim((string) ($color['color_name'] ?? ''));

            if ($colorName === '') {
                continue;
            }

            $existingImage = trim((string) ($color['existing_color_image'] ?? '')) ?: null;

            if ($existingImage && ! in_array($existingImage, $previousImages, true)) {
                $existingImage = null;
            }

            $colorImage = $existingImage;
            $uploadedImage = $request->file("colors.{$index}.color_image");

            if ($uploadedImage instanceof UploadedFile) {
                $colorImage = $this->storeUploadedFile($uploadedImage, 'uploads/products/colors');
            }

            if ($colorImage) {
                $usedImages[] = $colorImage;
            }

            $colorRows[] = [
                'color_name' => $colorName,
                'color_code' => trim((string) ($color['color_code'] ?? '')) ?: null,
                'color_image' => $colorImage,
                'sort_order' => (int) ($color['sort_order'] ?? 0),
            ];
        }

        $product->colors()->delete();

        foreach ($colorRows as $colorRow) {
            $product->colors()->create($colorRow);
        }

        foreach ($previousImages as $previousImage) {
            if (! in_array($previousImage, $usedImages, true)) {
                $this->deleteImage($previousImage);
            }
        }
    }

    private function syncVariants(Product $product, array $variants): void
    {
        $existingVariants = $product->variants()->get()->keyBy('id');
        $submittedVariantIds = [];

        foreach ($variants as $variant) {
            $variantName = trim((string) ($variant['variant_name'] ?? ''));
            $variantValue = trim((string) ($variant['variant_value'] ?? ''));

            if ($variantName === '' && $variantValue === '') {
                continue;
            }

            $payload = [
                'variant_name' => $variantName !== '' ? $variantName : 'Option',
                'variant_value' => $variantValue !== '' ? $variantValue : 'Default',
                'price' => ($variant['price'] ?? '') !== '' ? (float) $variant['price'] : null,
                'stock' => (int) ($variant['stock'] ?? 0),
            ];

            $variantId = (int) ($variant['id'] ?? 0);

            if ($variantId > 0) {
                $existingVariant = $existingVariants->get($variantId);

                if (! $existingVariant) {
                    continue;
                }

                $existingVariant->update($payload);
                $submittedVariantIds[] = $variantId;

                continue;
            }

            $newVariant = $product->variants()->create($payload);
            $submittedVariantIds[] = $newVariant->id;
        }

        $removedVariantsQuery = $product->variants();

        if ($submittedVariantIds !== []) {
            $removedVariantsQuery->whereNotIn('id', $submittedVariantIds);
        }

        $removedVariantsQuery->get()->each(function (ProductVariant $variant): void {
            if ($variant->enquiries()->exists() || $variant->orderItems()->exists()) {
                $variant->update(['stock' => 0]);

                return;
            }

            $variant->delete();
        });
    }

    private function syncProductMedia(Product $product, Request $request): void
    {
        $removeMediaIds = collect($request->input('remove_media', []))
            ->map(fn ($mediaId) => (int) $mediaId)
            ->filter()
            ->values()
            ->all();

        foreach ($removeMediaIds as $mediaId) {
            $media = $product->images()->whereKey($mediaId)->first();

            if (! $media) {
                continue;
            }

            $this->deleteImage($media->image);
            $media->delete();
        }

        foreach ($request->input('existing_media', []) as $mediaId => $mediaData) {
            if (in_array((int) $mediaId, $removeMediaIds, true)) {
                continue;
            }

            $media = $product->images()->whereKey($mediaId)->first();

            if (! $media) {
                continue;
            }

            $media->update([
                'image_type' => $this->normalizeMediaType($mediaData['image_type'] ?? null),
                'sort_order' => (int) ($mediaData['sort_order'] ?? 0),
            ]);
        }

        $this->storeProductMediaFiles($product, (array) $request->file('gallery_images', []), 'gallery');
        $this->storeProductMediaFiles($product, (array) $request->file('branding_images', []), 'branding_mockup');
        $this->storeProductMediaFiles($product, (array) $request->file('packaging_images', []), 'packaging');
    }

    private function storeProductMediaFiles(Product $product, array $files, string $imageType): void
    {
        $sortOrder = (int) ($product->images()->where('image_type', $imageType)->max('sort_order') ?? 0);

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $sortOrder++;

            $product->images()->create([
                'image' => $this->storeUploadedFile($file),
                'image_type' => $imageType,
                'sort_order' => $sortOrder,
            ]);
        }
    }

    private function normalizeMediaType(?string $imageType): string
    {
        return in_array($imageType, ['gallery', 'branding_mockup', 'packaging'], true)
            ? $imageType
            : 'gallery';
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'product';
        $slug = $baseSlug;

        if (! $this->slugExists($slug, $ignoreId)) {
            return $slug;
        }

        $slug = $baseSlug . '-' . time();
        $counter = 1;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $baseSlug . '-' . time() . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return Product::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }

    private function uploadImage(Request $request): ?string
    {
        if (! $request->hasFile('featured_image')) {
            return null;
        }

        return $this->storeUploadedFile($request->file('featured_image'));
    }

private function storeUploadedFile(UploadedFile $file, string $directory = 'uploads/products'): string
{
    $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $directory;

    if (!File::exists($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true, true);
    }

    $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

    $file->move($uploadPath, $fileName);

    return $directory . '/' . $fileName;
}
    private function deleteImage(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
