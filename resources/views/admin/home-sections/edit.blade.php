@extends('admin.layouts.app')

@section('title', 'Home Sections')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-semibold">Home Sections</h2>
    <p class="mt-1 text-sm text-gray-500">Manage homepage promo and about sections.</p>
</div>

<div class="rounded border border-gray-200 bg-white p-6 shadow-sm">
    <form action="{{ route('admin.home-sections.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <section>
            <div class="mb-5 border-b border-gray-100 pb-4">
                <h3 class="text-lg font-semibold">Promo Section</h3>
                <p class="mt-1 text-sm text-gray-500">Homepage discount or campaign band.</p>
            </div>

            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="promo_status" value="0">
                    <input type="checkbox" id="promo_status" name="promo_status" value="1" @checked(old('promo_status', $settings['promo_status'] ?? 0)) class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                    <label for="promo_status" class="text-sm font-medium text-gray-700">Show Promo Section</label>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="promo_title" class="mb-1 block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="promo_title" name="promo_title" value="{{ old('promo_title', $settings['promo_title'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                        @error('promo_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="promo_subtitle" class="mb-1 block text-sm font-medium text-gray-700">Subtitle</label>
                        <input type="text" id="promo_subtitle" name="promo_subtitle" value="{{ old('promo_subtitle', $settings['promo_subtitle'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                        @error('promo_subtitle')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label for="promo_button_text" class="mb-1 block text-sm font-medium text-gray-700">Button Text</label>
                        <input type="text" id="promo_button_text" name="promo_button_text" value="{{ old('promo_button_text', $settings['promo_button_text'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                        @error('promo_button_text')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="promo_button_link" class="mb-1 block text-sm font-medium text-gray-700">Button Link</label>
                        <input type="text" id="promo_button_link" name="promo_button_link" value="{{ old('promo_button_link', $settings['promo_button_link'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                        @error('promo_button_link')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="promo_background_color" class="mb-1 block text-sm font-medium text-gray-700">Background Color</label>
                        <input type="text" id="promo_background_color" name="promo_background_color" value="{{ old('promo_background_color', $settings['promo_background_color'] ?? 'red') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                        @error('promo_background_color')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="promo_background_image" class="mb-1 block text-sm font-medium text-gray-700">Background Image</label>
                    @if (! empty($settings['promo_background_image']))
                        <img src="{{ asset($settings['promo_background_image']) }}" alt="Promo background" class="mb-3 h-24 w-40 rounded object-cover">
                    @endif
                    <input type="file" id="promo_background_image" name="promo_background_image" class="w-full rounded border border-gray-300 px-3 py-2 text-sm file:mr-4 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
                    @error('promo_background_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <section class="border-t border-gray-100 pt-8">
            <div class="mb-5 border-b border-gray-100 pb-4">
                <h3 class="text-lg font-semibold">About Section</h3>
                <p class="mt-1 text-sm text-gray-500">Homepage brand story section.</p>
            </div>

            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="about_status" value="0">
                    <input type="checkbox" id="about_status" name="about_status" value="1" @checked(old('about_status', $settings['about_status'] ?? 0)) class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                    <label for="about_status" class="text-sm font-medium text-gray-700">Show About Section</label>
                </div>

                <div>
                    <label for="about_title" class="mb-1 block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="about_title" name="about_title" value="{{ old('about_title', $settings['about_title'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                    @error('about_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="about_description" class="mb-1 block text-sm font-medium text-gray-700">Description</label>
                    <textarea data-wysiwyg="true" id="about_description" name="about_description" rows="5" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">{{ old('about_description', $settings['about_description'] ?? '') }}</textarea>
                    @error('about_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="about_button_text" class="mb-1 block text-sm font-medium text-gray-700">Button Text</label>
                        <input type="text" id="about_button_text" name="about_button_text" value="{{ old('about_button_text', $settings['about_button_text'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                        @error('about_button_text')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="about_button_link" class="mb-1 block text-sm font-medium text-gray-700">Button Link</label>
                        <input type="text" id="about_button_link" name="about_button_link" value="{{ old('about_button_link', $settings['about_button_link'] ?? '') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                        @error('about_button_link')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="about_background_image" class="mb-1 block text-sm font-medium text-gray-700">Background Image</label>
                    @if (! empty($settings['about_background_image']))
                        <img src="{{ asset($settings['about_background_image']) }}" alt="About background" class="mb-3 h-24 w-40 rounded object-cover">
                    @endif
                    <input type="file" id="about_background_image" name="about_background_image" class="w-full rounded border border-gray-300 px-3 py-2 text-sm file:mr-4 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
                    @error('about_background_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <div class="flex justify-end border-t border-gray-100 pt-6">
            <button type="submit" class="rounded bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Save Home Sections
            </button>
        </div>
    </form>
</div>
@endsection
