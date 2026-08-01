<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    private array $textFields = [
        'site_name',
        'site_email',
        'site_phone',
        'site_address',
        'whatsapp_number',
        'facebook_url',
        'instagram_url',
        'youtube_url',
    ];

    public function edit()
    {
        $settings = Setting::pluck('value', 'setting_key')->toArray();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_email' => ['nullable', 'email', 'max:255'],
            'site_phone' => ['nullable', 'string', 'max:50'],
            'site_address' => ['nullable', 'string'],
            'whatsapp_number' => ['nullable', 'string', 'max:50'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'favicon' => ['nullable', 'file', 'mimes:ico,jpg,jpeg,png,webp,svg', 'max:2048'],
        ]);

        foreach ($this->textFields as $field) {
            Setting::updateOrCreate(
                ['setting_key' => $field],
                [
                    'value' => $validated[$field] ?? null,
                    'type' => 'text',
                ]
            );
        }

        foreach (['logo', 'favicon'] as $field) {
            if ($request->hasFile($field)) {
                $oldPath = Setting::where('setting_key', $field)->value('value');
                $this->deleteFile($oldPath);

                Setting::updateOrCreate(
                    ['setting_key' => $field],
                    [
                        'value' => $this->uploadFile($request, $field),
                        'type' => 'file',
                    ]
                );
            }
        }

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Settings updated successfully.');
    }

    private function uploadFile(Request $request, string $field): string
    {
        $directory = 'uploads/settings';
        $uploadPath = public_path($directory);

        if (! File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $file = $request->file($field);
        $fileName = $field . '-' . time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move($uploadPath, $fileName);

        return $directory . '/' . $fileName;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
