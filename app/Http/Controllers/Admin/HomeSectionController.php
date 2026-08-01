<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomeSectionController extends Controller
{
    private array $textFields = [
        'promo_title',
        'promo_subtitle',
        'promo_button_text',
        'promo_button_link',
        'promo_background_color',
        'about_title',
        'about_description',
        'about_button_text',
        'about_button_link',
    ];

    private array $booleanFields = [
        'promo_status',
        'about_status',
    ];

    public function edit()
    {
        $settings = Setting::pluck('value', 'setting_key')->toArray();

        return view('admin.home-sections.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'promo_status' => ['required', 'boolean'],
            'promo_title' => ['nullable', 'string', 'max:255'],
            'promo_subtitle' => ['nullable', 'string', 'max:500'],
            'promo_button_text' => ['nullable', 'string', 'max:255'],
            'promo_button_link' => ['nullable', 'string', 'max:255'],
            'promo_background_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'promo_background_color' => ['nullable', 'string', 'max:50'],
            'about_status' => ['required', 'boolean'],
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_description' => ['nullable', 'string'],
            'about_button_text' => ['nullable', 'string', 'max:255'],
            'about_button_link' => ['nullable', 'string', 'max:255'],
            'about_background_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $validated['promo_background_color'] = ($validated['promo_background_color'] ?? null) ?: 'red';

        foreach ($this->booleanFields as $field) {
            Setting::updateOrCreate(
                ['setting_key' => $field],
                [
                    'value' => $request->boolean($field) ? '1' : '0',
                    'type' => 'boolean',
                ]
            );
        }

        foreach ($this->textFields as $field) {
            Setting::updateOrCreate(
                ['setting_key' => $field],
                [
                    'value' => $validated[$field] ?? null,
                    'type' => 'text',
                ]
            );
        }

        foreach (['promo_background_image', 'about_background_image'] as $field) {
            if ($request->hasFile($field)) {
                $oldPath = Setting::where('setting_key', $field)->value('value');
                $this->deleteImage($oldPath);

                Setting::updateOrCreate(
                    ['setting_key' => $field],
                    [
                        'value' => $this->uploadImage($request, $field),
                        'type' => 'file',
                    ]
                );
            }
        }

        return redirect()
            ->route('admin.home-sections.edit')
            ->with('success', 'Home sections updated successfully.');
    }

    private function uploadImage(Request $request, string $field): string
    {
        $directory = 'uploads/home-sections';
        $uploadPath = public_path($directory);

        if (! File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $file = $request->file($field);
        $fileName = $field . '-' . time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
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
