<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in | {{ config('site.name') }} Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand/favicon-192.png') }}">
    @fonts
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body>
<div class="login-page">
    <div class="login-art">
        <div class="a-brand">
            <img src="{{ asset('images/brand/shubhanga-icon.png') }}" alt="">
            <span><strong>{{ config('site.name') }}</strong><span>Admin panel</span></span>
        </div>
        <div>
            <h1>Run the store <em>from one place</em></h1>
            <p>Orders, books, stock, coupons and every enquiry from the website, in the same red and white as the store.</p>
        </div>
        <p style="font-size: .8rem; opacity: .6;">{{ config('site.full_name') }}</p>
    </div>

    <div class="login-panel">
        <form method="post" action="{{ route('admin.login.post') }}" class="login-card form">
            @csrf
            <div>
                <h2>Welcome <em>back</em></h2>
                <p style="margin-top: 8px; color: var(--ink-soft); font-size: .92rem;">Sign in with your admin account.</p>
            </div>

            @if (session('error'))
                <div class="flash flash--err">{{ session('error') }}</div>
            @endif

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="input" required autofocus autocomplete="username">
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" class="input" required autocomplete="current-password">
            </div>
            <label class="login-remember"><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
            <button type="submit" class="btn">Sign in <x-admin.icon name="arrow-right" size="15" /></button>
            <a href="{{ route('home') }}" class="muted" style="text-align: center;">← Back to the website</a>
        </form>
    </div>
</div>
</body>
</html>
