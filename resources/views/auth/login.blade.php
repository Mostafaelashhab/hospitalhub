<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('app.login') }} - {{ __('app.app_name') }}</title>
    @include('partials.meta')
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
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-indigo-950 via-indigo-900 to-purple-950 overflow-hidden">
            {{-- Animated background --}}
            <div class="absolute inset-0 opacity-[0.06]" style="background-image:linear-gradient(rgba(129,140,248,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(129,140,248,.5) 1px,transparent 1px);background-size:50px 50px"></div>
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[500px] h-[300px] bg-indigo-500/15 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-20 right-10 w-[300px] h-[300px] bg-purple-500/10 rounded-full blur-[80px]"></div>

            {{-- Floating medical icons --}}
            <style>
                @keyframes fl1{0%,100%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(-15px) rotate(3deg)}}
                @keyframes fl2{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px) rotate(-2deg)}}
                @keyframes fl3{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
                .fl-1{animation:fl1 6s ease-in-out infinite}.fl-2{animation:fl2 8s ease-in-out infinite}.fl-3{animation:fl3 7s ease-in-out infinite}
            </style>

            <div class="absolute top-[15%] right-[10%] fl-1">
                <div class="w-12 h-12 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-xl flex items-center justify-center rotate-12">
                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                </div>
            </div>
            <div class="absolute top-[40%] left-[8%] fl-2">
                <div class="w-10 h-10 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-lg flex items-center justify-center -rotate-6">
                    <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0"/></svg>
                </div>
            </div>
            <div class="absolute bottom-[20%] right-[15%] fl-3" style="animation-delay:-2s">
                <div class="w-11 h-11 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-xl flex items-center justify-center rotate-6">
                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>

            <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                {{-- Logo --}}
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                        <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/25 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <span class="text-2xl font-extrabold text-white">{{ __('app.app_name') }}</span>
                    </a>
                </div>

                {{-- Center Content --}}
                <div class="space-y-10">
                    <div>
                        <h1 class="text-4xl xl:text-5xl font-black leading-tight mb-5">
                            @if(app()->getLocale() === 'ar')
                                <span class="text-white">مرحباً</span><br>
                                <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">بعودتك</span>
                            @else
                                <span class="text-white">Welcome</span><br>
                                <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Back</span>
                            @endif
                        </h1>
                        <p class="text-lg text-indigo-200/60 max-w-md leading-relaxed">
                            @if(app()->getLocale() === 'ar')
                                سجّل دخولك وأدر عيادتك بكل سهولة
                            @else
                                Sign in and manage your clinic with ease
                            @endif
                        </p>
                    </div>

                    {{-- Mini dashboard preview --}}
                    <div class="bg-white/[0.06] backdrop-blur-sm border border-white/[0.08] rounded-2xl p-5 max-w-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 bg-indigo-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ app()->getLocale()==='ar' ? 'لوحة التحكم' : 'Dashboard' }}</p>
                                <p class="text-[11px] text-indigo-300/50">{{ app()->getLocale()==='ar' ? 'نظرة سريعة على عيادتك' : 'Quick overview of your clinic' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-white/[0.06] rounded-lg p-3 text-center">
                                <p class="text-lg font-black text-white">18</p>
                                <p class="text-[9px] text-indigo-300/50">{{ __('app.appointments') }}</p>
                            </div>
                            <div class="bg-white/[0.06] rounded-lg p-3 text-center">
                                <p class="text-lg font-black text-white">248</p>
                                <p class="text-[9px] text-indigo-300/50">{{ __('app.patients') }}</p>
                            </div>
                            <div class="bg-white/[0.06] rounded-lg p-3 text-center">
                                <p class="text-lg font-black text-emerald-400">+12%</p>
                                <p class="text-[9px] text-indigo-300/50">{{ app()->getLocale()==='ar' ? 'نمو' : 'Growth' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-indigo-200/70">{{ __('app.feature_appointments') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-indigo-200/70">{{ __('app.feature_patients') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-indigo-200/70">{{ __('app.feature_billing') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Bottom stats --}}
                <div class="flex gap-8">
                    <div><div class="text-2xl font-black text-white">5,000+</div><div class="text-xs text-indigo-300/50 font-medium">{{ app()->getLocale()==='ar' ? 'دواء' : 'Medications' }}</div></div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div><div class="text-2xl font-black text-white">500+</div><div class="text-xs text-indigo-300/50 font-medium">{{ app()->getLocale()==='ar' ? 'تحليل' : 'Lab Tests' }}</div></div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div><div class="text-2xl font-black text-white">15+</div><div class="text-xs text-indigo-300/50 font-medium">{{ app()->getLocale()==='ar' ? 'تخصص' : 'Specialties' }}</div></div>
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
