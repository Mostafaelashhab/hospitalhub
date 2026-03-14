{{-- Favicon --}}
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<meta name="theme-color" content="#6366f1">

{{-- PWA --}}
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ __('app.app_name') }}">
{{-- iOS Apple Touch Icons (all sizes for different devices) --}}
<link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('icons/icon-144x144.png') }}">
<link rel="apple-touch-icon" sizes="128x128" href="{{ asset('icons/icon-128x128.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
<link rel="apple-touch-icon" sizes="384x384" href="{{ asset('icons/icon-384x384.png') }}">
<link rel="apple-touch-icon" sizes="512x512" href="{{ asset('icons/icon-512x512.png') }}">
{{-- iOS Splash Screens --}}
@foreach([
    ['w' => 1170, 'h' => 2532, 'r' => 3, 'name' => 'iPhone 12/13/14'],
    ['w' => 1179, 'h' => 2556, 'r' => 3, 'name' => 'iPhone 14 Pro'],
    ['w' => 1290, 'h' => 2796, 'r' => 3, 'name' => 'iPhone 14 Pro Max'],
    ['w' => 1170, 'h' => 2532, 'r' => 3, 'name' => 'iPhone 15'],
    ['w' => 1206, 'h' => 2622, 'r' => 3, 'name' => 'iPhone 15 Pro'],
    ['w' => 1320, 'h' => 2868, 'r' => 3, 'name' => 'iPhone 15 Pro Max'],
    ['w' => 2048, 'h' => 2732, 'r' => 2, 'name' => 'iPad Pro 12.9'],
    ['w' => 1668, 'h' => 2388, 'r' => 2, 'name' => 'iPad Pro 11'],
] as $screen)
<link rel="apple-touch-startup-image" href="{{ asset('icons/icon-512x512.png') }}" media="(device-width: {{ $screen['w'] / $screen['r'] }}px) and (device-height: {{ $screen['h'] / $screen['r'] }}px) and (-webkit-device-pixel-ratio: {{ $screen['r'] }})">
@endforeach

{{-- VAPID Public Key for Push Notifications --}}
<meta name="vapid-public-key" content="{{ config('webpush.vapid.public_key') }}">

{{-- SEO --}}
<meta name="description" content="{{ $metaDescription ?? __('app.landing_hero_desc') }}">

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $metaTitle ?? __('app.app_name') . ' — ' . __('app.hero_title') }}">
<meta property="og:description" content="{{ $metaDescription ?? __('app.landing_hero_desc') }}">
<meta property="og:image" content="{{ asset('og-image.svg') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="{{ __('app.app_name') }}">
<meta property="og:locale" content="{{ app()->getLocale() === 'ar' ? 'ar_AR' : 'en_US' }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle ?? __('app.app_name') . ' — ' . __('app.hero_title') }}">
<meta name="twitter:description" content="{{ $metaDescription ?? __('app.landing_hero_desc') }}">
<meta name="twitter:image" content="{{ asset('og-image.svg') }}">
