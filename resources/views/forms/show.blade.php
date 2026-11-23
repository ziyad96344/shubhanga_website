@extends('layouts.app', ['title' => strip_tags($form['title']).' | '.config('site.full_name'), 'description' => $form['intro']])

@php
    $site = config('site');
    $hasFile = collect($form['fields'])->contains('type', 'file');
@endphp

@section('content')
    @include('partials.page-head', [
        'crumbs' => [strip_tags($form['title']) => null],
        'eyebrow' => $form['eyebrow'],
        'title' => $form['title'],
        'intro' => $form['intro'],
    ])

    <section class="wrap form-layout">
        <div class="form-card">
            @if (session('sent'))
                <div class="form-success" role="status">
                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m7.5 12.5 3 3 6-7"/></svg>
                    <h2>Sent</h2>
                    <p>{{ $form['success'] }}</p>
                    <div class="form-success-actions">
                        <a href="{{ route('books.index') }}" class="btn">Browse books</a>
                        <a href="{{ route('forms.show', $key) }}" class="link-arrow">Send another</a>
                    </div>
                </div>
            @else
                @if ($errors->any())
                    <div class="form-alert" role="alert">
                        Please check the highlighted fields and try again.
                    </div>
                @endif

                <form method="post" action="{{ route('forms.store', $key) }}" class="form-grid" @if ($hasFile) enctype="multipart/form-data" @endif novalidate>
                    @csrf
                    {{-- honeypot: hidden from people, tempting to bots --}}
                    <div class="hp" aria-hidden="true">
                        <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    @foreach ($form['fields'] as $name => $field)
                        @php
                            $required = str_contains($field['rules'], 'required');
                            $id = 'f-'.$name;
                            $error = $errors->first($name);
                        @endphp
                        <div class="field {{ ! empty($field['wide']) || $field['type'] === 'textarea' ? 'field--wide' : '' }} {{ $error ? 'has-error' : '' }}">
                            <label for="{{ $id }}">{{ $field['label'] }}@if ($required)<span aria-hidden="true"> *</span>@endif</label>

                            @if ($field['type'] === 'select')
                                <select id="{{ $id }}" name="{{ $name }}" class="select" @if ($required) required @endif @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>
                                    <option value="" disabled @selected(! old($name))>Choose</option>
                                    @foreach ($field['options'] as $option)
                                        <option @selected(old($name) === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            @elseif ($field['type'] === 'textarea')
                                <textarea id="{{ $id }}" name="{{ $name }}" rows="4" class="field-input" @if ($required) required @endif @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>{{ old($name) }}</textarea>
                            @elseif ($field['type'] === 'file')
                                <input id="{{ $id }}" type="file" name="{{ $name }}" class="field-file" accept=".pdf,.doc,.docx">
                            @else
                                <input id="{{ $id }}" type="{{ $field['type'] }}" name="{{ $name }}" value="{{ old($name) }}" class="field-input"
                                       @if ($required) required @endif
                                       @if ($field['type'] === 'email') autocomplete="email" @elseif ($field['type'] === 'tel') autocomplete="tel" inputmode="tel" @elseif ($name === 'name') autocomplete="name" @endif
                                       @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>
                            @endif

                            @if ($error)
                                <span class="field-error" id="{{ $id }}-error">{{ $error }}</span>
                            @elseif (! empty($field['hint']))
                                <span class="field-hint">{{ $field['hint'] }}</span>
                            @endif
                        </div>
                    @endforeach

                    <div class="field field--wide form-actions">
                        <button type="submit" class="btn">{{ $form['submit'] }}
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </button>
                        <p class="form-note">We use these details only to reply to you.</p>
                    </div>
                </form>
            @endif
        </div>

        <aside class="form-aside">
            @if (! empty($form['contact_card']))
                <div class="aside-card aside-card--brand">
                    <h2>Talk to us</h2>
                    @foreach ($site['contact']['phones'] as $phone)
                        <p class="aside-phone"><span>{{ $phone['label'] }}</span><a href="tel:{{ preg_replace('/\s+/', '', $phone['number']) }}">{{ $phone['number'] }}</a></p>
                    @endforeach
                    <p class="aside-phone"><span>Email</span><a href="mailto:{{ $site['contact']['email'] }}">{{ $site['contact']['email'] }}</a></p>
                </div>
                <div class="aside-card">
                    <h2>Visit</h2>
                    <p>{{ $site['contact']['address'] }}</p>
                    <p class="aside-muted">{{ $site['contact']['hours'] }}</p>
                </div>
                <div class="aside-card">
                    <h2>Follow us</h2>
                    <p class="aside-muted">News, events and learning tips from Shubhanga.</p>
                    @include('partials.social')
                </div>
            @else
                <div class="aside-card aside-card--brand">
                    <h2>{{ $form['aside']['heading'] }}</h2>
                    <ul>
                        @foreach ($form['aside']['points'] as $point)
                            <li>{{ $point }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="aside-card">
                    <h2>Prefer to call?</h2>
                    <p class="aside-phone"><span>{{ $site['contact']['phones'][1]['label'] }}</span><a href="tel:{{ preg_replace('/\s+/', '', $site['contact']['phones'][1]['number']) }}">{{ $site['contact']['phones'][1]['number'] }}</a></p>
                    <p class="aside-muted">{{ $site['contact']['hours'] }}</p>
                </div>
            @endif
        </aside>
    </section>
@endsection
