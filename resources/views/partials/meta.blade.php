{{-- Favicon --}}
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<meta name="theme-color" content="#6366f1">

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
