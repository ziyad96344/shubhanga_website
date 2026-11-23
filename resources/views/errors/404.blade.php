@extends('layouts.app', ['title' => 'Page not found | '.config('site.full_name'), 'noindex' => true])

@section('content')
    <section class="wrap error-page">
        <span class="error-code" aria-hidden="true">404</span>
        <h1>This page is not on our shelf</h1>
        <p>The link may be old, or the page has moved. Try searching for a book, or start from the catalogue.</p>
        <form action="{{ route('books.index') }}" method="get" class="newsletter" role="search" style="max-width: 460px; margin-top: 4px">
            <label for="nf-q" class="sr-only">Search books</label>
            <input id="nf-q" type="search" name="q" placeholder="Search books, e.g. class 5 maths" style="color: var(--color-ink); border-bottom-color: rgba(34, 21, 24, .3)">
            <button type="submit" class="btn">Search</button>
        </form>
        <div class="error-actions">
            <a href="{{ route('books.index') }}" class="btn btn--ghost">Browse books</a>
            <a href="{{ route('home') }}" class="link-arrow">Back to home</a>
        </div>
    </section>
@endsection
