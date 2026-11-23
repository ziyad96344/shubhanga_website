@extends('layouts.app', ['title' => 'Page expired | '.config('site.full_name'), 'noindex' => true])

@section('content')
    <section class="wrap error-page">
        <span class="error-code" aria-hidden="true">419</span>
        <h1>This page expired</h1>
        <p>The form was open for too long, so we could not accept it safely. Go back, refresh the page and send it again.</p>
        <div class="error-actions">
            <a href="{{ url()->previous() }}" class="btn">Go back</a>
            <a href="{{ route('home') }}" class="link-arrow">Back to home</a>
        </div>
    </section>
@endsection
