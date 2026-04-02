<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('app.register_clinic') }} - {{ __('app.app_name') }}</title>
    @include('partials.meta')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|cairo:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(app()->getLocale() === 'ar')
    <style>body { font-family: 'Cairo', sans-serif !important; }</style>
    @endif
</head>
<body class="font-sans antialiased bg-gray-950 text-white min-h-screen">

    @php
        $hasStep3Errors = $errors->hasAny(['admin_name', 'admin_email', 'admin_phone', 'password']);
        $hasStep2Errors = $errors->hasAny(['doctors_count', 'expected_patients_monthly', 'has_existing_system']);
        $initialStep = $hasStep3Errors ? 3 : ($hasStep2Errors ? 2 : 1);
    @endphp

    <div class="min-h-screen flex" x-data="{ step: {{ $initialStep }}, loading: false, hasSystem: {{ old('has_existing_system', false) ? 'true' : 'false' }} }">

        {{-- Left Side - Branding --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-indigo-950 via-indigo-900 to-purple-950 overflow-hidden">
            {{-- Animated background --}}
            <div class="absolute inset-0 opacity-[0.06]" style="background-image:linear-gradient(rgba(129,140,248,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(129,140,248,.5) 1px,transparent 1px);background-size:50px 50px"></div>
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[500px] h-[300px] bg-indigo-500/15 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-20 right-10 w-[300px] h-[300px] bg-purple-500/10 rounded-full blur-[80px]"></div>

            {{-- Floating medical icons --}}
            <style>
                @keyframes rfl1{0%,100%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(-15px) rotate(3deg)}}
                @keyframes rfl2{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px) rotate(-2deg)}}
                @keyframes rfl3{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
                @keyframes rfl4{0%,100%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(-18px) rotate(-5deg)}}
                .rfl-1{animation:rfl1 6s ease-in-out infinite}.rfl-2{animation:rfl2 8s ease-in-out infinite}.rfl-3{animation:rfl3 7s ease-in-out infinite}.rfl-4{animation:rfl4 9s ease-in-out infinite}
            </style>

            <div class="absolute top-[12%] right-[10%] rfl-1">
                <div class="w-12 h-12 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-xl flex items-center justify-center rotate-12">
                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21"/></svg>
                </div>
            </div>
            <div class="absolute top-[35%] left-[7%] rfl-2">
                <div class="w-10 h-10 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-lg flex items-center justify-center -rotate-6">
                    <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0"/></svg>
                </div>
            </div>
            <div class="absolute bottom-[25%] right-[12%] rfl-3" style="animation-delay:-2s">
                <div class="w-11 h-11 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-xl flex items-center justify-center rotate-6">
                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                </div>
            </div>
            <div class="absolute bottom-[45%] left-[15%] rfl-4" style="animation-delay:-3s">
                <div class="w-9 h-9 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-lg flex items-center justify-center rotate-3">
                    <svg class="w-4 h-4 text-pink-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
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

                {{-- Dynamic content based on step --}}
                <div class="space-y-8">
                    <div>
                        <h1 class="text-4xl xl:text-5xl font-black leading-tight mb-5">
                            <span x-show="step === 1">
                                @if(app()->getLocale() === 'ar')
                                    <span class="text-white">سجّل عيادة</span><br>
                                    <span class="bg-gradient-to-r from-cyan-400 via-indigo-400 to-purple-400 bg-clip-text text-transparent">أسنانك الآن</span>
                                @else
                                    <span class="text-white">Register Your</span><br>
                                    <span class="bg-gradient-to-r from-cyan-400 via-indigo-400 to-purple-400 bg-clip-text text-transparent">Dental Clinic</span>
                                @endif
                            </span>
                            <span x-show="step === 2" x-cloak>
                                @if(app()->getLocale() === 'ar')
                                    <span class="text-white">خلينا نعرف</span><br>
                                    <span class="bg-gradient-to-r from-purple-400 via-pink-400 to-rose-400 bg-clip-text text-transparent">عيادتك أكتر</span>
                                @else
                                    <span class="text-white">Tell Us More</span><br>
                                    <span class="bg-gradient-to-r from-purple-400 via-pink-400 to-rose-400 bg-clip-text text-transparent">About Your Clinic</span>
                                @endif
                            </span>
                            <span x-show="step === 3" x-cloak>
                                @if(app()->getLocale() === 'ar')
                                    <span class="text-white">خطوة أخيرة</span><br>
                                    <span class="bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 bg-clip-text text-transparent">لتفعيل حسابك</span>
                                @else
                                    <span class="text-white">Last Step</span><br>
                                    <span class="bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 bg-clip-text text-transparent">To Activate</span>
                                @endif
                            </span>
                        </h1>
                        <p class="text-lg text-indigo-200/60 max-w-md leading-relaxed">{{ __('app.hero_subtitle') }}</p>
                    </div>

                    {{-- Step-specific preview cards --}}
                    {{-- Step 1: Clinic setup preview --}}
                    <div x-show="step === 1" class="bg-white/[0.06] backdrop-blur-sm border border-white/[0.08] rounded-2xl p-5 max-w-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 bg-indigo-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ app()->getLocale()==='ar' ? 'إعداد العيادة' : 'Clinic Setup' }}</p>
                                <p class="text-[11px] text-indigo-300/50">{{ app()->getLocale()==='ar' ? 'سجّل بياناتك الأساسية' : 'Register your basic info' }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="h-3 bg-white/[0.06] rounded-full w-full"></div>
                            <div class="h-3 bg-white/[0.06] rounded-full w-3/4"></div>
                            <div class="h-3 bg-indigo-500/20 rounded-full w-1/2"></div>
                        </div>
                    </div>

                    {{-- Step 2: Clinic details preview --}}
                    <div x-show="step === 2" x-cloak class="bg-white/[0.06] backdrop-blur-sm border border-white/[0.08] rounded-2xl p-5 max-w-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ app()->getLocale()==='ar' ? 'تفاصيل العيادة' : 'Clinic Details' }}</p>
                                <p class="text-[11px] text-indigo-300/50">{{ app()->getLocale()==='ar' ? 'حدّد نظام عملك' : 'Configure your workflow' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-white/[0.06] rounded-lg p-3 text-center">
                                <svg class="w-5 h-5 text-purple-400 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <p class="text-[9px] text-indigo-300/50">{{ app()->getLocale()==='ar' ? 'تفاصيل العيادة' : 'Clinic Info' }}</p>
                            </div>
                            <div class="bg-white/[0.06] rounded-lg p-3 text-center">
                                <svg class="w-5 h-5 text-pink-400 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                                <p class="text-[9px] text-indigo-300/50">{{ app()->getLocale()==='ar' ? 'فريق العمل' : 'Team Size' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Almost done preview --}}
                    <div x-show="step === 3" x-cloak class="bg-white/[0.06] backdrop-blur-sm border border-white/[0.08] rounded-2xl p-5 max-w-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.745 3.745 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ app()->getLocale()==='ar' ? 'تقريباً خلصت!' : 'Almost Done!' }}</p>
                                <p class="text-[11px] text-indigo-300/50">{{ app()->getLocale()==='ar' ? 'أنشئ حساب المدير' : 'Create admin account' }}</p>
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

                    {{-- Feature checkmarks --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-indigo-200/70">{{ app()->getLocale()==='ar' ? 'شارت أسنان تفاعلي لكل مريض' : 'Interactive dental chart per patient' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-indigo-200/70">{{ app()->getLocale()==='ar' ? 'إدارة مواعيد وطوابير ذكية' : 'Smart appointments & queue management' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-indigo-200/70">{{ app()->getLocale()==='ar' ? 'فواتير وتقسيط وكوبونات خصم' : 'Invoicing, installments & discount coupons' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-indigo-200/70">{{ app()->getLocale()==='ar' ? 'صور قبل وبعد العلاج' : 'Before & after treatment photos' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Bottom stats --}}
                <div class="flex gap-8">
                    <div><div class="text-2xl font-black text-white">32</div><div class="text-xs text-indigo-300/50 font-medium">{{ app()->getLocale()==='ar' ? 'سنّة بالشارت' : 'Teeth Charted' }}</div></div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div><div class="text-2xl font-black text-white">15+</div><div class="text-xs text-indigo-300/50 font-medium">{{ app()->getLocale()==='ar' ? 'خدمة أسنان' : 'Dental Services' }}</div></div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div><div class="text-2xl font-black text-white">24/7</div><div class="text-xs text-indigo-300/50 font-medium">{{ app()->getLocale()==='ar' ? 'حجز أونلاين' : 'Online Booking' }}</div></div>
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
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition">{{ __('app.login') }}</a>
                </div>
            </div>

            <div class="flex-1 flex items-start justify-center px-6 py-4 lg:px-12 overflow-y-auto">
                <div class="w-full max-w-lg">

                    <div class="mb-6">
                        <h2 class="text-3xl font-bold mb-2">{{ __('app.register_clinic') }}</h2>
                        <p class="text-gray-400">
                            @if(app()->getLocale() === 'ar') سجّل عيادة أسنانك في دقائق وابدأ في إدارة مرضاك @else Register your dental clinic in minutes and start managing your patients @endif
                        </p>
                    </div>

                    {{-- 3-Step Indicator --}}
                    <div class="flex items-center mb-8">
                        {{-- Step 1 --}}
                        <button type="button" @click="step = 1" class="flex items-center gap-1.5 cursor-pointer">
                            <div :class="step >= 1 ? 'bg-indigo-600 border-indigo-600 shadow-lg shadow-indigo-600/30' : 'bg-gray-800 border-gray-700'" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all duration-300">
                                <span x-show="step <= 1">1</span>
                                <svg x-show="step > 1" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-xs font-medium transition hidden sm:inline" :class="step >= 1 ? 'text-white' : 'text-gray-500'">{{ __('app.clinic_info') }}</span>
                        </button>

                        <div class="flex-1 mx-2 h-px relative">
                            <div class="absolute inset-0 bg-gray-800 rounded"></div>
                            <div class="absolute inset-y-0 start-0 bg-indigo-600 rounded transition-all duration-500" :style="step >= 2 ? 'width: 100%' : 'width: 0%'"></div>
                        </div>

                        {{-- Step 2 --}}
                        <button type="button" @click="step = 2" class="flex items-center gap-1.5 cursor-pointer">
                            <div :class="step >= 2 ? 'bg-indigo-600 border-indigo-600 shadow-lg shadow-indigo-600/30' : 'bg-gray-800 border-gray-700'" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all duration-300">
                                <span x-show="step <= 2">2</span>
                                <svg x-show="step > 2" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-xs font-medium transition hidden sm:inline" :class="step >= 2 ? 'text-white' : 'text-gray-500'">{{ __('app.clinic_details') }}</span>
                        </button>

                        <div class="flex-1 mx-2 h-px relative">
                            <div class="absolute inset-0 bg-gray-800 rounded"></div>
                            <div class="absolute inset-y-0 start-0 bg-indigo-600 rounded transition-all duration-500" :style="step >= 3 ? 'width: 100%' : 'width: 0%'"></div>
                        </div>

                        {{-- Step 3 --}}
                        <button type="button" @click="step = 3" class="flex items-center gap-1.5 cursor-pointer">
                            <div :class="step >= 3 ? 'bg-indigo-600 border-indigo-600 shadow-lg shadow-indigo-600/30' : 'bg-gray-800 border-gray-700'" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all duration-300">3</div>
                            <span class="text-xs font-medium transition hidden sm:inline" :class="step >= 3 ? 'text-white' : 'text-gray-500'">{{ __('app.admin_info') }}</span>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('register.clinic.store') }}" @submit="loading = true">
                        @csrf

                        {{-- ===== STEP 1: Basic Clinic Info ===== --}}
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.clinic_name_en') }} <span class="text-red-400">*</span></label>
                                        <input type="text" name="clinic_name_en" value="{{ old('clinic_name_en') }}" placeholder="Smile Dental Clinic"
                                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                        @error('clinic_name_en') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.clinic_name_ar') }} <span class="text-red-400">*</span></label>
                                        <input type="text" name="clinic_name_ar" value="{{ old('clinic_name_ar') }}" dir="rtl" placeholder="عيادة سمايل لطب الأسنان"
                                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                        @error('clinic_name_ar') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.clinic_phone') }} <span class="text-red-400">*</span></label>
                                    <input type="tel" name="clinic_phone" value="{{ old('clinic_phone') }}" placeholder="01xxxxxxxxx"
                                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                    @error('clinic_phone') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.clinic_email') }}</label>
                                        <input type="email" name="clinic_email" value="{{ old('clinic_email') }}" placeholder="clinic@example.com"
                                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.city') }}</label>
                                        <input type="text" name="city" value="{{ old('city') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'القاهرة' : 'Cairo' }}"
                                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.clinic_address_en') }}</label>
                                        <textarea name="address_en" rows="2" placeholder="123 Main St, Floor 2" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition resize-none">{{ old('address_en') }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.clinic_address_ar') }}</label>
                                        <textarea name="address_ar" rows="2" dir="rtl" placeholder="١٢٣ شارع الرئيسي، الدور الثاني" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition resize-none">{{ old('address_ar') }}</textarea>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.tax_number') }}</label>
                                    <input type="text" name="tax_number" value="{{ old('tax_number') }}" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                </div>
                            </div>

                            <button type="button" @click="step = 2" class="w-full mt-6 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-500 hover:to-purple-500 transition-all duration-300 shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 flex items-center justify-center gap-2">
                                {{ __('app.next') }}
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </button>
                        </div>

                        {{-- ===== STEP 2: Clinic Details ===== --}}
                        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                            <div class="space-y-5">

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.doctors_count') }}</label>
                                    <input type="number" name="doctors_count" value="{{ old('doctors_count') }}" min="1" max="100" placeholder="3"
                                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.expected_patients') }}</label>
                                    <input type="number" name="expected_patients_monthly" value="{{ old('expected_patients_monthly') }}" min="1" placeholder="200"
                                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                </div>

                                {{-- Has Existing System --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('app.has_existing_system') }}</label>
                                    <div class="flex gap-3">
                                        <label class="cursor-pointer flex-1">
                                            <input type="radio" name="has_existing_system" value="1" class="peer hidden" @click="hasSystem = true" {{ old('has_existing_system') == '1' ? 'checked' : '' }}>
                                            <div class="peer-checked:border-indigo-500 peer-checked:bg-indigo-600/10 border border-gray-700 rounded-xl p-3 text-center transition hover:border-gray-600">
                                                <span class="text-sm font-medium">{{ __('app.yes') }}</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer flex-1">
                                            <input type="radio" name="has_existing_system" value="0" class="peer hidden" @click="hasSystem = false" {{ old('has_existing_system', '0') == '0' ? 'checked' : '' }}>
                                            <div class="peer-checked:border-indigo-500 peer-checked:bg-indigo-600/10 border border-gray-700 rounded-xl p-3 text-center transition hover:border-gray-600">
                                                <span class="text-sm font-medium">{{ __('app.no') }}</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div x-show="hasSystem" x-transition x-cloak>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.existing_system_name') }}</label>
                                    <input type="text" name="existing_system_name" value="{{ old('existing_system_name') }}" placeholder="e.g. Vezeeta, Clinido..."
                                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                </div>

                                {{-- Referral Source --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.referral_source') }}</label>
                                    <select name="referral_source" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                        <option value="">--</option>
                                        <option value="google" {{ old('referral_source') === 'google' ? 'selected' : '' }}>{{ __('app.ref_google') }}</option>
                                        <option value="social_media" {{ old('referral_source') === 'social_media' ? 'selected' : '' }}>{{ __('app.ref_social') }}</option>
                                        <option value="friend" {{ old('referral_source') === 'friend' ? 'selected' : '' }}>{{ __('app.ref_friend') }}</option>
                                        <option value="ad" {{ old('referral_source') === 'ad' ? 'selected' : '' }}>{{ __('app.ref_ad') }}</option>
                                        <option value="other" {{ old('referral_source') === 'other' ? 'selected' : '' }}>{{ __('app.ref_other') }}</option>
                                    </select>
                                </div>

                                {{-- Notes --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.additional_notes') }}</label>
                                    <textarea name="notes" rows="2" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition resize-none" placeholder="{{ app()->getLocale() === 'ar' ? 'أي متطلبات خاصة...' : 'Any special requirements...' }}">{{ old('notes') }}</textarea>
                                </div>
                            </div>

                            <div class="flex gap-3 mt-6">
                                <button type="button" @click="step = 1" class="px-5 py-3.5 bg-gray-800 text-gray-300 font-semibold rounded-xl hover:bg-gray-700 transition-all border border-gray-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                                    {{ __('app.back') }}
                                </button>
                                <button type="button" @click="step = 3" class="flex-1 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-500 hover:to-purple-500 transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 flex items-center justify-center gap-2">
                                    {{ __('app.next') }}
                                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- ===== STEP 3: Admin Info ===== --}}
                        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.admin_name') }} <span class="text-red-400">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </div>
                                        <input type="text" name="admin_name" value="{{ old('admin_name') }}" class="w-full bg-gray-900 border border-gray-700 rounded-xl ps-12 pe-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                    </div>
                                    @error('admin_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.admin_email') }} <span class="text-red-400">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </div>
                                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" placeholder="admin@clinic.com" class="w-full bg-gray-900 border border-gray-700 rounded-xl ps-12 pe-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                    </div>
                                    @error('admin_email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.admin_phone') }} <span class="text-red-400">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        </div>
                                        <input type="tel" name="admin_phone" value="{{ old('admin_phone') }}" placeholder="01xxxxxxxxx" class="w-full bg-gray-900 border border-gray-700 rounded-xl ps-12 pe-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-green-500 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2.546 21.2a1 1 0 001.254 1.254l4.032-.892A9.96 9.96 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg>
                                        {{ app()->getLocale() === 'ar' ? 'هيوصلك رمز تأكيد على واتساب بعد التسجيل' : 'A verification code will be sent via WhatsApp after registration' }}
                                    </p>
                                    @error('admin_phone') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.password') }} <span class="text-red-400">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            </div>
                                            <input type="password" name="password" class="w-full bg-gray-900 border border-gray-700 rounded-xl ps-12 pe-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                        </div>
                                        @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.confirm_password') }} <span class="text-red-400">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            </div>
                                            <input type="password" name="password_confirmation" class="w-full bg-gray-900 border border-gray-700 rounded-xl ps-12 pe-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3 mt-6">
                                <button type="button" @click="step = 2" class="px-5 py-3.5 bg-gray-800 text-gray-300 font-semibold rounded-xl hover:bg-gray-700 transition-all border border-gray-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                                    {{ __('app.back') }}
                                </button>
                                <button type="submit" :disabled="loading" class="flex-1 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-500 hover:to-purple-500 transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                    <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    <span x-show="!loading">{{ __('app.submit') }}</span>
                                    <span x-show="loading">@if(app()->getLocale() === 'ar') جاري التسجيل... @else Registering... @endif</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-500">
                        @if(app()->getLocale() === 'ar') عندك حساب بالفعل؟ @else Already have an account? @endif
                        <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition ms-1">{{ __('app.login') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
