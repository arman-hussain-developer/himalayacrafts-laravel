<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrandLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandLogoController extends Controller
{
    public function index()
    {
        $brandLogos = BrandLogo::orderBy('sort_order')->latest()->paginate(10);

        return view('admin.brand-logos.index', compact('brandLogos'));
    }

    public function create()
    {
        return view('admin.brand-logos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(true));
        $validated['status'] = $request->boolean('status');
        $validated['logo'] = $this->uploadLogo($request);

        BrandLogo::create($validated);

        return redirect()
            ->route('admin.brand-logos.index')
            ->with('success', 'Brand logo created successfully.');
    }

    public function edit(BrandLogo $brandLogo)
    {
        return view('admin.brand-logos.edit', compact('brandLogo'));
    }

    public function update(Request $request, BrandLogo $brandLogo)
    {
        $validated = $request->validate($this->rules(false));
        $validated['status'] = $request->boolean('status');

        if ($logo = $this->uploadLogo($request)) {
            $this->deleteLogo($brandLogo->logo);
            $validated['logo'] = $logo;
        }

        $brandLogo->update($validated);

        return redirect()
            ->route('admin.brand-logos.index')
            ->with('success', 'Brand logo updated successfully.');
    }

    public function destroy(BrandLogo $brandLogo)
    {
        $this->deleteLogo($brandLogo->logo);
        $brandLogo->delete();

        return redirect()
            ->route('admin.brand-logos.index')
            ->with('success', 'Brand logo deleted successfully.');
    }

    private function rules(bool $logoRequired): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'logo' => [$logoRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'link' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    private function uploadLogo(Request $request): ?string
    {
        if (! $request->hasFile('logo')) {
            return null;
        }

        $directory = 'uploads/brand-logos';
        $uploadPath = public_path($directory);

        if (! File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $file = $request->file('logo');
        $fileName = time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move($uploadPath, $fileName);

        return $directory . '/' . $fileName;
    }

    private function deleteLogo(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
