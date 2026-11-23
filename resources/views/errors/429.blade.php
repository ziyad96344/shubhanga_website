@extends('layouts.app', ['title' => 'Too many requests | '.config('site.full_name'), 'noindex' => true])

@section('content')
    <section class="wrap error-page">
        <span class="error-code" aria-hidden="true">429</span>
        <h1>Please wait a minute</h1>
        <p>We received several requests from you in a short time. Wait a minute and try again.</p>
        <div class="error-actions">
            <a href="{{ url()->previous() }}" class="btn">Go back</a>
        </div>
    </section>
@endsection
