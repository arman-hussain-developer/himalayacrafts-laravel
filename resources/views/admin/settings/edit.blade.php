@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-semibold">Settings</h2>
    <p class="mt-1 text-sm text-gray-500">Manage site contact, social, and brand settings.</p>
</div>

<div class="rounded border border-gray-200 bg-white p-6 shadow-sm">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="site_name" class="mb-1 block text-sm font-medium text-gray-700">Site Name</label>
                <input type="text" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('site_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="site_email" class="mb-1 block text-sm font-medium text-gray-700">Site Email</label>
                <input type="email" id="site_email" name="site_email" value="{{ old('site_email', $settings['site_email'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('site_email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="site_phone" class="mb-1 block text-sm font-medium text-gray-700">Site Phone</label>
                <input type="text" id="site_phone" name="site_phone" value="{{ old('site_phone', $settings['site_phone'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('site_phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="whatsapp_number" class="mb-1 block text-sm font-medium text-gray-700">WhatsApp Number</label>
                <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('whatsapp_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="site_address" class="mb-1 block text-sm font-medium text-gray-700">Site Address</label>
                    <textarea id="site_address" name="site_address" rows="4" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">{{ old('site_address', $settings['site_address'] ?? '') }}</textarea>
            @error('site_address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            <div>
                <label for="facebook_url" class="mb-1 block text-sm font-medium text-gray-700">Facebook URL</label>
                <input type="text" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('facebook_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="instagram_url" class="mb-1 block text-sm font-medium text-gray-700">Instagram URL</label>
                <input type="text" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('instagram_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="youtube_url" class="mb-1 block text-sm font-medium text-gray-700">YouTube URL</label>
                <input type="text" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                @error('youtube_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="logo" class="mb-1 block text-sm font-medium text-gray-700">Logo</label>
                @if (! empty($settings['logo']))
                    <img src="{{ asset($settings['logo']) }}" alt="Logo" class="mb-3 h-16 w-32 rounded object-contain">
                @endif
                <input type="file" id="logo" name="logo" class="w-full rounded border border-gray-300 px-3 py-2 text-sm file:mr-4 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
                @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="favicon" class="mb-1 block text-sm font-medium text-gray-700">Favicon</label>
                @if (! empty($settings['favicon']))
                    <img src="{{ asset($settings['favicon']) }}" alt="Favicon" class="mb-3 h-12 w-12 rounded object-contain">
                @endif
                <input type="file" id="favicon" name="favicon" class="w-full rounded border border-gray-300 px-3 py-2 text-sm file:mr-4 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
                @error('favicon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end border-t border-gray-100 pt-5">
            <button type="submit" class="rounded bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
