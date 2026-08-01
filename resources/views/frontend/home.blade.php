@extends('frontend.layouts.app')

@section('title', 'Home')
@section('meta_description', 'Corporate gifts, accessories and stationery catalogue for bulk enquiries, branding and business gifting.')

@section('content')
    @include('frontend.sections.hero')
    @include('frontend.sections.category-strip')
   
    @include('frontend.sections.best-seller')
     @include('frontend.sections.bulk-quote-cta')
     @include('frontend.sections.trending')
     @include('frontend.sections.promo')
    @include('frontend.sections.exclusive-category')
    @include('frontend.sections.about')
    @include('frontend.sections.trust')
    @include('frontend.sections.brand-logos')
    @include('frontend.sections.testimonials')
@endsection

@push('scripts')
    @include('frontend.sections.hero-script')
@endpush
