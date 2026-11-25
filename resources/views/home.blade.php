@extends('layouts.app')

@section('body_class', 'has-hero')

@push('head')
    <link rel="preload" as="image" href="{{ config('site.hero_image') }}" fetchpriority="high">
@endpush

@section('content')
    @include('home.hero')
    @include('home.new-releases')
    @include('home.journey')
    @include('home.trending')
    @include('home.look-inside')
    @include('home.early-learning')
    @include('home.stories')
    @include('home.schools')
    @include('home.resources')
@endsection
