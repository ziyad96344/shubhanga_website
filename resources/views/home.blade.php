@extends('layouts.app')

@section('body_class', 'has-hero')

@push('head')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => config('site.full_name'),
        'url' => route('home'),
        'email' => config('site.contact.email'),
        'logo' => url(config('site.logo')),
        'sameAs' => array_values(config('site.social')),
        'address' => ['@type' => 'PostalAddress', 'streetAddress' => config('site.contact.address'), 'addressCountry' => 'IN'],
        'contactPoint' => collect(config('site.contact.phones'))->map(fn ($p) => ['@type' => 'ContactPoint', 'telephone' => $p['number'], 'contactType' => $p['label']])->values()->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    @include('home.hero')
    @include('home.new-releases')
    @include('home.journey')
    @include('home.trending')
    @include('home.look-inside')
    @include('home.early-learning')
    @include('home.stories')
    @include('home.awards')
    @include('home.schools')
    @include('home.resources')
@endsection
