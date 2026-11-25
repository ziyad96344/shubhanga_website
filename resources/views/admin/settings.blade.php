@extends('admin.layouts.app')

@section('title', 'Settings')
@section('crumb', 'Store')
@section('heading')Store <em>settings</em>@endsection

@section('content')
    <form method="post" action="{{ route('admin.settings.update') }}" class="grid" style="max-width: 900px;">
        @csrf @method('put')

        @foreach ($groups as $group => $title)
            <section class="card card-pad form">
                <div class="card-head" style="margin: -26px -26px 4px; padding-inline: 26px;"><h2>{{ $title }}</h2></div>
                <div class="form-row">
                    @foreach ($settings[$group] ?? [] as $setting)
                        <div class="field" @if ($setting->type === 'textarea') style="grid-column: 1 / -1;" @endif>
                            <label for="s-{{ $setting->key }}">{{ $setting->label }}</label>
                            @if ($setting->type === 'textarea')
                                <textarea id="s-{{ $setting->key }}" name="{{ $setting->key }}" class="textarea" style="min-height: 80px;">{{ old($setting->key, $setting->value) }}</textarea>
                            @else
                                <input id="s-{{ $setting->key }}" type="{{ $setting->type }}" name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}" class="input">
                            @endif
                            @error($setting->key)<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="form-actions">
            <button type="submit" class="btn"><x-admin.icon name="check" size="15" /> Save settings</button>
            <span class="muted">Changes show on the website straight away.</span>
        </div>
    </form>
@endsection
