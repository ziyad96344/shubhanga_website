@extends('layouts.app', ['title' => 'Free resources | '.config('site.full_name'), 'description' => 'Free solutions, mind maps, syllabus files and the full catalogue to download.'])

@section('content')
    @include('partials.page-head', [
        'crumbs' => ['Free resources' => null],
        'eyebrow' => 'Downloads',
        'title' => 'Free <em>resources</em>',
        'intro' => 'Catalogue, sample chapters and teacher resources. Teacher material is shared with partner schools on request.',
    ])

    <section class="wrap resource-page">
        @foreach ($groups as $group => $items)
            <div class="resource-group">
                <h2>{{ $group }}</h2>
                <ul class="res-list">
                    @foreach ($items as $res)
                        <li>
                            <a href="{{ $res['teachers'] ? route('forms.show', 'specimen-copy') : '#' }}">
                                <span><strong>{{ $res['title'] }}</strong><span>{{ $res['meta'] }}</span></span>
                                <span class="res-icon" aria-hidden="true">
                                    @if ($res['teachers'])
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#221518" stroke-width="2" stroke-linecap="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                                    @else
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#221518" stroke-width="2.4" stroke-linecap="round"><path d="M12 4v12M6 11l6 6 6-6M5 20h14"/></svg>
                                    @endif
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </section>
@endsection
