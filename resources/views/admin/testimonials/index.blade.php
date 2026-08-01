@extends('admin.layouts.app')

@section('title', 'Testimonials')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-semibold">Testimonials</h2>
        <p class="mt-1 text-sm text-gray-500">Manage homepage customer reviews.</p>
    </div>

    <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center justify-center rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
        Add Testimonial
    </a>
</div>

<div class="overflow-hidden rounded border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Message</th>
                    <th class="px-4 py-3">Rating</th>
                    <th class="px-4 py-3">Sort</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($testimonials as $testimonial)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($testimonial->image)
                                    <img src="{{ asset($testimonial->image) }}" alt="{{ $testimonial->name }}" class="h-12 w-12 rounded-full object-cover">
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-xs text-gray-400">N/A</div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $testimonial->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $testimonial->designation ?: 'No designation' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="max-w-md px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($testimonial->message, 90) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $testimonial->rating }}/5</td>
                        <td class="px-4 py-3 text-gray-600">{{ $testimonial->sort_order }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-medium {{ $testimonial->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $testimonial->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="rounded border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                    Edit
                                </a>
                                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Delete this testimonial?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No testimonials found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">
    {{ $testimonials->links() }}
</div>
@endsection
