<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrustItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TrustItemController extends Controller
{
    public function index()
    {
        $trustItems = TrustItem::orderBy('sort_order')->latest()->paginate(10);

        return view('admin.trust-items.index', compact('trustItems'));
    }

    public function create()
    {
        return view('admin.trust-items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $validated['status'] = $request->boolean('status');

        if ($image = $this->uploadImage($request)) {
            $validated['image'] = $image;
        }

        TrustItem::create($validated);

        return redirect()
            ->route('admin.trust-items.index')
            ->with('success', 'Trust item created successfully.');
    }

    public function edit(TrustItem $trustItem)
    {
        return view('admin.trust-items.edit', compact('trustItem'));
    }

    public function update(Request $request, TrustItem $trustItem)
    {
        $validated = $request->validate($this->rules());
        $validated['status'] = $request->boolean('status');

        if ($image = $this->uploadImage($request)) {
            $this->deleteImage($trustItem->image);
            $validated['image'] = $image;
        }

        $trustItem->update($validated);

        return redirect()
            ->route('admin.trust-items.index')
            ->with('success', 'Trust item updated successfully.');
    }

    public function destroy(TrustItem $trustItem)
    {
        $this->deleteImage($trustItem->image);
        $trustItem->delete();

        return redirect()
            ->route('admin.trust-items.index')
            ->with('success', 'Trust item deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'status' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    private function uploadImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $directory = 'uploads/trust-items';
        $uploadPath = public_path($directory);

        if (! File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $file = $request->file('image');
        $fileName = time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
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
