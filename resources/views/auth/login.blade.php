<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('app.login') }} - {{ __('app.app_name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|cairo:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(app()->getLocale() === 'ar')
    <style>body { font-family: 'Cairo', sans-serif !important; }</style>
    @endif
</head>
<body class="font-sans antialiased bg-gray-950 text-white min-h-screen">

    <div class="min-h-screen flex" x-data="{ loading: false }">

        {{-- Left Side - Branding --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-300/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-pink-300/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
            </div>
            <div class="absolute inset-0 opacity-[0.03]">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/></pattern></defs>
                    <rect width="100%" height="100%" fill="url(#grid)"/>
                </svg>
            </div>

            <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <span class="text-2xl font-bold text-white">{{ __('app.app_name') }}</span>
                    </a>
                </div>

                <div class="space-y-8">
                    <div>
                        <h1 class="text-4xl xl:text-5xl font-extrabold leading-tight mb-4">
                            @if(app()->getLocale() === 'ar')
                                مرحباً بعودتك
                            @else
                                Welcome<br>Back
                            @endif
                        </h1>
                        <p class="text-lg text-white/70 max-w-md">
                            @if(app()->getLocale() === 'ar')
                                سجّل دخولك وأدر عيادتك بكل سهولة
                            @else
                                Sign in and manage your clinic with ease
                            @endif
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-white/80">{{ __('app.feature_appointments') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-white/80">{{ __('app.feature_patients') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-white/80">{{ __('app.feature_billing') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-8">
                    <div><div class="text-3xl font-bold">500+</div><div class="text-sm text-white/60">{{ __('app.clinics') }}</div></div>
                    <div><div class="text-3xl font-bold">50K+</div><div class="text-sm text-white/60">{{ __('app.patients') }}</div></div>
                    <div><div class="text-3xl font-bold">99.9%</div><div class="text-sm text-white/60">Uptime</div></div>
                </div>
            </div>
        </div>

        {{-- Right Side - Form --}}
        <div class="w-full lg:w-1/2 flex flex-col">
            <div class="flex items-center justify-between p-6">
                <a href="{{ route('home') }}" class="lg:hidden inline-flex items-center gap-2 text-white font-bold text-xl">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    {{ __('app.app_name') }}
                </a>
                <div class="flex items-center gap-4">
                    <div class="flex items-center bg-gray-800/50 rounded-full p-1">
                        <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 rounded-full text-xs font-medium transition {{ app()->getLocale() === 'en' ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:text-white' }}">EN</a>
                        <a href="{{ route('lang.switch', 'ar') }}" class="px-3 py-1 rounded-full text-xs font-medium transition {{ app()->getLocale() === 'ar' ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:text-white' }}">ع</a>
                    </div>
                    <a href="{{ route('register.clinic') }}" class="text-sm text-gray-400 hover:text-white transition">{{ __('app.register') }}</a>
                </div>
            </div>

            <div class="flex-1 flex items-center justify-center px-6 py-8 lg:px-12">
                <div class="w-full max-w-md">

                    <div class="mb-8">
                        <h2 class="text-3xl font-bold mb-2">{{ __('app.login') }}</h2>
                        <p class="text-gray-400">
                            @if(app()->getLocale() === 'ar')
                                أدخل بياناتك للوصول للوحة التحكم
                            @else
                                Enter your credentials to access the dashboard
                            @endif
                        </p>
                    </div>

                    {{-- Session Status --}}
                    @if (session('status'))
                        <div class="mb-4 p-3 bg-emerald-600/10 border border-emerald-600/30 rounded-xl text-emerald-400 text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" @submit="loading = true">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.email') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                    placeholder="admin@clinic.com"
                                    class="w-full bg-gray-900 border border-gray-700 rounded-xl ps-12 pe-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                            </div>
                            @error('email') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.password') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <input type="password" name="password" required autocomplete="current-password"
                                    class="w-full bg-gray-900 border border-gray-700 rounded-xl ps-12 pe-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                            </div>
                            @error('password') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- Remember + Forgot --}}
                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-gray-900 border-gray-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                                <span class="ms-2 text-sm text-gray-400">
                                    @if(app()->getLocale() === 'ar') تذكرني @else Remember me @endif
                                </span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-indigo-400 hover:text-indigo-300 transition">
                                    @if(app()->getLocale() === 'ar') نسيت كلمة المرور؟ @else Forgot password? @endif
                                </a>
                            @endif
                        </div>

                        {{-- Submit --}}
                        <button type="submit" :disabled="loading"
                            class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-500 hover:to-purple-500 transition-all duration-300 shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            <span x-show="!loading">{{ __('app.login') }}</span>
                            <span x-show="loading">@if(app()->getLocale() === 'ar') جاري الدخول... @else Signing in... @endif</span>
                        </button>
                    </form>

                    {{-- Divider --}}
                    <div class="my-8 flex items-center">
                        <div class="flex-1 h-px bg-gray-800"></div>
                        <span class="px-4 text-xs text-gray-500 uppercase">
                            @if(app()->getLocale() === 'ar') أو @else or @endif
                        </span>
                        <div class="flex-1 h-px bg-gray-800"></div>
                    </div>

                    {{-- Register Link --}}
                    <a href="{{ route('register.clinic') }}" class="w-full py-3.5 bg-gray-800 text-gray-300 font-semibold rounded-xl hover:bg-gray-700 transition-all border border-gray-700 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                        {{ __('app.register_clinic') }}
                    </a>

                </div>
            </div>
        </div>
    </div>

</body>
</html>
