{{-- Shubhanga social profiles from config/site.php, in each platform's own colours. Optional: $class --}}
@php
    $gid = 'ig-'.substr(md5(uniqid('', true)), 0, 8); // unique per include: the page may show this list more than once
    $icons = [
        'Instagram' => '<defs><radialGradient id="'.$gid.'" cx="30%" cy="107%" r="150%"><stop offset="0" stop-color="#fdf497"/><stop offset=".05" stop-color="#fdf497"/><stop offset=".45" stop-color="#fd5949"/><stop offset=".6" stop-color="#d6249f"/><stop offset=".9" stop-color="#285AEB"/></radialGradient></defs>'
            .'<rect x="1" y="1" width="22" height="22" rx="6.5" fill="url(#'.$gid.')"/>'
            .'<rect x="5.5" y="5.5" width="13" height="13" rx="4" fill="none" stroke="#fff" stroke-width="1.8"/>'
            .'<circle cx="12" cy="12" r="3.1" fill="none" stroke="#fff" stroke-width="1.8"/>'
            .'<circle cx="16.4" cy="7.6" r="1" fill="#fff"/>',
        'YouTube' => '<rect x="1" y="4.5" width="22" height="15" rx="4.5" fill="#FF0000"/>'
            .'<path d="M10 8.8v6.4l5.6-3.2z" fill="#fff"/>',
        'Facebook' => '<circle cx="12" cy="12" r="11" fill="#1877F2"/>'
            .'<path d="M13.4 23v-8.2h2.7l.4-3.2h-3.1V9.6c0-.9.3-1.6 1.6-1.6h1.7V5.2c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.5-4 4.1v2.4H7.6v3.2h2.7V23z" fill="#fff"/>',
        'LinkedIn' => '<rect x="1" y="1" width="22" height="22" rx="4" fill="#0A66C2"/>'
            .'<path d="M5.6 9.5h2.6v8.3H5.6zM6.9 5.4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM9.8 9.5h2.5v1.1c.4-.7 1.2-1.3 2.5-1.3 2.6 0 3.1 1.7 3.1 4v4.5h-2.6v-4c0-1 0-2.2-1.4-2.2-1.3 0-1.5 1-1.5 2.1v4.1H9.8z" fill="#fff"/>',
    ];
@endphp
<ul class="social {{ $class ?? '' }}" aria-label="{{ config('site.full_name') }} on social media">
    @foreach (config('site.social') as $name => $url)
        <li>
            <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ config('site.name') }} on {{ $name }}">
                <svg width="22" height="22" viewBox="0 0 24 24" aria-hidden="true">{!! $icons[$name] ?? '<circle cx="12" cy="12" r="10" fill="#888"/>' !!}</svg>
            </a>
        </li>
    @endforeach
</ul>
