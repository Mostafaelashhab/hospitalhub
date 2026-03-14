<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('app.app_name') }}</title>
        @include('partials.meta')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|cairo:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @if(app()->getLocale() === 'ar')
        <style>
            body { font-family: 'Cairo', sans-serif !important; }
        </style>
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/" class="text-2xl font-bold text-indigo-600">
                    {{ __('app.app_name') }}
                </a>
            </div>

            <!-- Language Switcher -->
            <div class="mt-2 flex gap-2 text-sm">
                <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'text-indigo-600 font-semibold' : 'text-gray-500 hover:text-gray-700' }}">English</a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('lang.switch', 'ar') }}" class="{{ app()->getLocale() === 'ar' ? 'text-indigo-600 font-semibold' : 'text-gray-500 hover:text-gray-700' }}">العربية</a>
            </div>

            <div class="w-full {{ isset($wide) && $wide ? 'sm:max-w-2xl' : 'sm:max-w-md' }} mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
