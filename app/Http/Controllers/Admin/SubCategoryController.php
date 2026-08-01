<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->latest()->paginate(10);

        return view('admin.sub-categories.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.sub-categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['status'] = $request->boolean('status');

        SubCategory::create($validated);

        return redirect()
            ->route('admin.sub-categories.index')
            ->with('success', 'Sub category created successfully.');
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.sub-categories.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name'], $subCategory->id);
        $validated['status'] = $request->boolean('status');

        $subCategory->update($validated);

        return redirect()
            ->route('admin.sub-categories.index')
            ->with('success', 'Sub category updated successfully.');
    }

    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();

        return redirect()
            ->route('admin.sub-categories.index')
            ->with('success', 'Sub category deleted successfully.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'sub-category';
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
        return SubCategory::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }
}
