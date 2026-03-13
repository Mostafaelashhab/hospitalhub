<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.app_name') }} — {{ __('app.hero_title') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900|cairo:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: {{ app()->getLocale() === 'ar' ? "'Cairo'" : "'Inter'" }}, sans-serif; }
        .fade-up { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.16,1,0.3,1); }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
        .fade-left { opacity: 0; transform: translateX(-40px); transition: all 0.8s cubic-bezier(0.16,1,0.3,1); }
        .fade-left.visible { opacity: 1; transform: translateX(0); }
        .fade-right { opacity: 0; transform: translateX(40px); transition: all 0.8s cubic-bezier(0.16,1,0.3,1); }
        .fade-right.visible { opacity: 1; transform: translateX(0); }
        .d1{transition-delay:.1s}.d2{transition-delay:.2s}.d3{transition-delay:.3s}.d4{transition-delay:.4s}.d5{transition-delay:.5s}
        .gradient-text { background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .float-1 { animation: f1 6s ease-in-out infinite; }
        .float-2 { animation: f2 8s ease-in-out infinite; }
        .float-3 { animation: f3 7s ease-in-out infinite; }
        @keyframes f1{0%,100%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(-20px) rotate(3deg)}}
        @keyframes f2{0%,100%{transform:translateY(0)}50%{transform:translateY(-15px) rotate(-2deg)}}
        @keyframes f3{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
        .card-lift{transition:all .4s cubic-bezier(.4,0,.2,1)}.card-lift:hover{transform:translateY(-8px);box-shadow:0 25px 60px -12px rgba(0,0,0,.12)}
        .mockup-shadow{box-shadow:0 50px 100px -20px rgba(99,102,241,.25),0 30px 60px -15px rgba(0,0,0,.15)}
        .progress-animate{animation:pg 2s ease-out forwards}@keyframes pg{from{width:0}}
        .marquee-track{animation:mq 35s linear infinite}@keyframes mq{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
        [dir=rtl] .marquee-track{animation:mqr 35s linear infinite}@keyframes mqr{0%{transform:translateX(0)}100%{transform:translateX(50%)}}
        html{scroll-behavior:smooth}

        /* Illustration styles */
        .illust-pill { animation: pillFloat 4s ease-in-out infinite; }
        @keyframes pillFloat { 0%,100%{transform:translateY(0) rotate(-10deg)}50%{transform:translateY(-8px) rotate(-5deg)} }
        .illust-pulse { animation: iPulse 2s ease-in-out infinite; }
        @keyframes iPulse { 0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.05);opacity:.8} }
        .illust-scan { animation: scanLine 3s ease-in-out infinite; }
        @keyframes scanLine { 0%,100%{transform:translateY(0)}50%{transform:translateY(60px)} }
        .illust-drop { animation: dropIn 3s ease-in-out infinite; }
        @keyframes dropIn { 0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)} }
    </style>
</head>
<body class="antialiased bg-white text-gray-900 overflow-x-hidden"
      x-data="{}" x-init="
        const obs = new IntersectionObserver(e => e.forEach(el => { if(el.isIntersecting) el.target.classList.add('visible') }), {threshold:.1,rootMargin:'0px 0px -50px 0px'});
        document.querySelectorAll('.fade-up,.fade-left,.fade-right').forEach(el => obs.observe(el));
      ">

    {{-- NAVBAR --}}
    <nav class="fixed top-0 inset-x-0 z-50 transition-all duration-500"
         x-data="{s:false}" x-init="window.addEventListener('scroll',()=>s=window.scrollY>30)"
         :class="s?'bg-white/95 backdrop-blur-2xl shadow-lg shadow-gray-200/30 border-b border-gray-100':''"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <span class="text-xl font-extrabold transition-colors" :class="s?'text-gray-900':'text-white'">{{ __('app.app_name') }}</span>
                </a>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-semibold transition-colors" :class="s?'text-gray-600 hover:text-indigo-600':'text-indigo-200 hover:text-white'">{{ __('app.features') }}</a>
                    <a href="#database" class="text-sm font-semibold transition-colors" :class="s?'text-gray-600 hover:text-indigo-600':'text-indigo-200 hover:text-white'">{{ app()->getLocale()==='ar' ? 'قاعدة البيانات' : 'Database' }}</a>
                    <a href="#how-it-works" class="text-sm font-semibold transition-colors" :class="s?'text-gray-600 hover:text-indigo-600':'text-indigo-200 hover:text-white'">{{ __('app.landing_how_it_works') }}</a>
                    <a href="#specialties" class="text-sm font-semibold transition-colors" :class="s?'text-gray-600 hover:text-indigo-600':'text-indigo-200 hover:text-white'">{{ __('app.landing_specialties') }}</a>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex rounded-xl p-1 text-xs transition-colors" :class="s?'bg-gray-100':'bg-white/10'">
                        @if(app()->getLocale()==='en')
                            <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1.5 rounded-lg font-bold transition-all bg-white text-gray-900 shadow-sm">EN</a>
                            <a href="{{ route('lang.switch', 'ar') }}" class="px-3 py-1.5 rounded-lg font-bold transition-all" :class="s?'text-gray-500':'text-indigo-200'">عربي</a>
                        @else
                            <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1.5 rounded-lg font-bold transition-all" :class="s?'text-gray-500':'text-indigo-200'">EN</a>
                            <a href="{{ route('lang.switch', 'ar') }}" class="px-3 py-1.5 rounded-lg font-bold transition-all bg-white text-gray-900 shadow-sm">عربي</a>
                        @endif
                    </div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25">{{ __('app.dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold transition-colors hidden sm:block" :class="s?'text-gray-700 hover:text-indigo-600':'text-indigo-200 hover:text-white'">{{ __('app.login') }}</a>
                        <a href="{{ route('register.clinic') }}" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25">{{ __('app.get_started') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ===== HERO ===== --}}
    <section class="relative min-h-screen overflow-hidden bg-gradient-to-b from-indigo-950 via-indigo-900 to-indigo-950">
        {{-- Animated grid --}}
        <div class="absolute inset-0 opacity-[0.07]" style="background-image:linear-gradient(rgba(129,140,248,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(129,140,248,.5) 1px,transparent 1px);background-size:60px 60px"></div>
        {{-- Glow orbs --}}
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-indigo-500/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-purple-500/15 rounded-full blur-[100px]"></div>
        <div class="absolute top-20 right-0 w-[300px] h-[300px] bg-cyan-500/10 rounded-full blur-[80px]"></div>

        {{-- Floating medical icons --}}
        <div class="absolute top-32 left-[8%] float-1 hidden lg:block">
            <div class="w-14 h-14 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-2xl flex items-center justify-center rotate-12">
                <svg class="w-7 h-7 text-indigo-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
            </div>
        </div>
        <div class="absolute top-48 right-[12%] float-2 hidden lg:block">
            <div class="w-12 h-12 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-xl flex items-center justify-center -rotate-6">
                <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3"/></svg>
            </div>
        </div>
        <div class="absolute bottom-[30%] left-[5%] float-3 hidden lg:block">
            <div class="w-11 h-11 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-xl flex items-center justify-center rotate-6">
                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="absolute bottom-[25%] right-[7%] float-1 hidden lg:block" style="animation-delay:-2s">
            <div class="w-10 h-10 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-lg flex items-center justify-center -rotate-12">
                <svg class="w-5 h-5 text-pink-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-10">
            {{-- Text - Centered --}}
            <div class="text-center max-w-4xl mx-auto mb-16">
                <div class="fade-up inline-flex items-center gap-2.5 px-5 py-2.5 bg-white/[0.08] border border-white/[0.12] rounded-full text-sm font-bold text-indigo-300 mb-8 backdrop-blur-sm">
                    <span class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute h-full w-full rounded-full bg-indigo-400 opacity-75"></span><span class="relative rounded-full h-2.5 w-2.5 bg-indigo-400"></span></span>
                    {{ __('app.landing_badge') }}
                </div>
                <h1 class="fade-up d1 text-4xl md:text-6xl lg:text-7xl font-black leading-[1.08] mb-6 tracking-tight text-white">
                    {{ __('app.landing_hero_line1') }}
                    <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">{{ __('app.landing_hero_highlight') }}</span>
                </h1>
                <p class="fade-up d2 text-lg md:text-xl text-indigo-200/70 mb-10 max-w-2xl mx-auto leading-relaxed">{{ __('app.landing_hero_desc') }}</p>
                <div class="fade-up d3 flex flex-col sm:flex-row items-center justify-center gap-4 mb-10">
                    <a href="{{ route('register.clinic') }}" class="group w-full sm:w-auto flex items-center justify-center gap-2.5 px-8 py-4 bg-white text-indigo-700 font-bold rounded-2xl hover:bg-indigo-50 transition-all shadow-xl shadow-black/20 text-lg hover:scale-[1.02]">
                        {{ __('app.landing_start_free') }}
                        <svg class="w-5 h-5 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="#features" class="w-full sm:w-auto flex items-center justify-center gap-2.5 px-8 py-4 text-white font-bold rounded-2xl border-2 border-white/20 hover:bg-white/10 transition-all text-lg backdrop-blur-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z"/></svg>
                        {{ __('app.landing_learn_more') }}
                    </a>
                </div>
                {{-- Trust row --}}
                <div class="fade-up d4 flex items-center justify-center gap-8 flex-wrap">
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2 rtl:space-x-reverse">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 border-2 border-indigo-900 flex items-center justify-center text-[10px] font-bold text-white">A</div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 border-2 border-indigo-900 flex items-center justify-center text-[10px] font-bold text-white">M</div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 border-2 border-indigo-900 flex items-center justify-center text-[10px] font-bold text-white">S</div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 border-2 border-indigo-900 flex items-center justify-center text-[10px] font-bold text-white">K</div>
                        </div>
                        <span class="text-sm text-indigo-300 font-medium">500+ {{ app()->getLocale()==='ar' ? 'عيادة' : 'Clinics' }}</span>
                    </div>
                    <div class="w-px h-5 bg-white/10 hidden sm:block"></div>
                    <div class="flex items-center gap-1.5">
                        @for($i=0;$i<5;$i++)<svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
                        <span class="text-sm text-indigo-300 font-medium ms-1">4.9/5</span>
                    </div>
                    <div class="w-px h-5 bg-white/10 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span class="text-sm text-indigo-300 font-medium">{{ app()->getLocale()==='ar' ? 'آمن ومشفّر' : 'Secure & Encrypted' }}</span>
                    </div>
                </div>
            </div>

            {{-- Dashboard Mockup --}}
            <div class="fade-up d5 relative max-w-5xl mx-auto">
                {{-- Glow behind mockup --}}
                <div class="absolute -inset-4 bg-gradient-to-r from-indigo-500/20 via-purple-500/20 to-pink-500/20 rounded-[2rem] blur-2xl"></div>

                {{-- Floating cards --}}
                <div class="absolute -top-6 {{ app()->getLocale()==='ar' ? 'right-4 md:right-8' : 'left-4 md:left-8' }} z-30 float-1">
                    <div class="bg-white rounded-2xl shadow-2xl shadow-black/20 p-4 border border-gray-100 flex items-center gap-3">
                        <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 font-medium">{{ __('app.completed') }}</p>
                            <p class="text-xl font-black text-gray-900">+24</p>
                        </div>
                        <div class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">+12%</div>
                    </div>
                </div>

                <div class="absolute -top-4 {{ app()->getLocale()==='ar' ? 'left-4 md:left-12' : 'right-4 md:right-12' }} z-30 float-2">
                    <div class="bg-white rounded-2xl shadow-2xl shadow-black/20 p-4 border border-gray-100 flex items-center gap-3">
                        <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 font-medium">{{ __('app.patients') }}</p>
                            <p class="text-xl font-black text-gray-900">1,248</p>
                        </div>
                    </div>
                </div>

                {{-- Notification --}}
                <div class="absolute -bottom-3 {{ app()->getLocale()==='ar' ? 'left-4 md:left-16' : 'right-4 md:right-16' }} z-30 float-3">
                    <div class="bg-white rounded-xl shadow-2xl shadow-black/20 p-3 border border-gray-100 flex items-center gap-3">
                        <div class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">{{ app()->getLocale()==='ar' ? 'موعد جديد' : 'New Appointment' }}</p>
                            <p class="text-[10px] text-gray-400">Mohamed Ali — 09:00 AM</p>
                        </div>
                        <span class="text-[10px] font-bold text-white bg-indigo-600 px-2 py-1 rounded-lg">{{ app()->getLocale()==='ar' ? 'الآن' : 'Now' }}</span>
                    </div>
                </div>

                {{-- Main Browser Mockup --}}
                <div class="relative bg-white rounded-2xl md:rounded-3xl overflow-hidden border border-white/20 shadow-2xl shadow-black/30">
                    {{-- Browser chrome --}}
                    <div class="bg-gray-100 border-b border-gray-200 px-5 py-3.5 flex items-center gap-4">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        </div>
                        <div class="flex-1 bg-white rounded-lg px-4 py-1.5 text-xs text-gray-400 font-medium text-center border border-gray-200 flex items-center justify-center gap-2">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                            {{ strtolower(__('app.app_name')) }}.com/dashboard
                        </div>
                    </div>

                    {{-- Dashboard UI --}}
                    <div class="flex">
                        {{-- Sidebar --}}
                        @if(app()->getLocale()==='ar')
                        <div class="w-14 md:w-52 bg-white border-l border-gray-100 py-4 shrink-0 hidden sm:block">
                        @else
                        <div class="w-14 md:w-52 bg-white border-r border-gray-100 py-4 shrink-0 hidden sm:block">
                        @endif
                            <div class="flex items-center gap-2.5 px-4 mb-6">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-900 hidden md:block">{{ __('app.app_name') }}</span>
                            </div>
                            <div class="space-y-1 px-2">
                                <div class="flex items-center gap-2.5 px-3 py-2 bg-indigo-50 rounded-lg">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span class="text-xs font-bold text-indigo-700 hidden md:block">{{ __('app.dashboard') }}</span>
                                </div>
                                <div class="flex items-center gap-2.5 px-3 py-2 text-gray-400 hover:bg-gray-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs font-medium hidden md:block">{{ __('app.appointments') }}</span>
                                </div>
                                <div class="flex items-center gap-2.5 px-3 py-2 text-gray-400 hover:bg-gray-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="text-xs font-medium hidden md:block">{{ __('app.patients') }}</span>
                                </div>
                                <div class="flex items-center gap-2.5 px-3 py-2 text-gray-400 hover:bg-gray-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span class="text-xs font-medium hidden md:block">{{ __('app.diagnoses') }}</span>
                                </div>
                                <div class="flex items-center gap-2.5 px-3 py-2 text-gray-400 hover:bg-gray-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span class="text-xs font-medium hidden md:block">{{ __('app.invoices') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Main content --}}
                        <div class="flex-1 bg-gray-50 p-4 md:p-6 min-h-[350px] md:min-h-[420px]">
                            {{-- Header --}}
                            <div class="flex items-center justify-between mb-5">
                                <div>
                                    <h2 class="text-sm md:text-base font-bold text-gray-900">{{ app()->getLocale()==='ar' ? 'مرحباً، د. أحمد' : 'Welcome, Dr. Ahmed' }} 👋</h2>
                                    <p class="text-[10px] md:text-xs text-gray-400">{{ app()->getLocale()==='ar' ? 'عيادة النور — الفرع الرئيسي' : 'Al-Nour Clinic — Main Branch' }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    </div>
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-[10px] font-bold text-white">A</div>
                                </div>
                            </div>

                            {{-- Stats cards --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                                <div class="bg-white rounded-xl p-3 md:p-4 border border-gray-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded hidden md:block">+8%</span>
                                    </div>
                                    <p class="text-lg md:text-xl font-black text-gray-900">248</p>
                                    <p class="text-[10px] text-gray-400">{{ __('app.patients') }}</p>
                                </div>
                                <div class="bg-white rounded-xl p-3 md:p-4 border border-gray-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center"><svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                    </div>
                                    <p class="text-lg md:text-xl font-black text-gray-900">12</p>
                                    <p class="text-[10px] text-gray-400">{{ __('app.doctors') }}</p>
                                </div>
                                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-3 md:p-4 text-white">
                                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mb-2"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                    <p class="text-lg md:text-xl font-black">18</p>
                                    <p class="text-[10px] text-indigo-200">{{ __('app.today_appointments') }}</p>
                                </div>
                                <div class="bg-white rounded-xl p-3 md:p-4 border border-gray-100">
                                    <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center mb-2"><svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                    <p class="text-lg md:text-xl font-black text-gray-900">3</p>
                                    <p class="text-[10px] text-gray-400">{{ __('app.unpaid_invoices') }}</p>
                                </div>
                            </div>

                            {{-- Chart + Appointments --}}
                            <div class="grid md:grid-cols-5 gap-3">
                                {{-- Chart --}}
                                <div class="md:col-span-3 bg-white rounded-xl p-3 md:p-4 border border-gray-100">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-[10px] md:text-xs font-bold text-gray-700">{{ app()->getLocale()==='ar' ? 'المواعيد هذا الأسبوع' : 'Appointments This Week' }}</span>
                                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">+12.5%</span>
                                    </div>
                                    <svg viewBox="0 0 400 100" class="w-full h-auto">
                                        <defs><linearGradient id="cg" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" stop-color="#6366f1" stop-opacity="0.25"/><stop offset="100%" stop-color="#6366f1" stop-opacity="0.01"/></linearGradient></defs>
                                        <line x1="0" y1="25" x2="400" y2="25" stroke="#f1f5f9" stroke-width="0.5"/>
                                        <line x1="0" y1="50" x2="400" y2="50" stroke="#f1f5f9" stroke-width="0.5"/>
                                        <line x1="0" y1="75" x2="400" y2="75" stroke="#f1f5f9" stroke-width="0.5"/>
                                        <path d="M0,80 C40,70 60,45 100,50 C140,55 170,25 200,30 C240,35 270,15 310,12 C340,8 380,20 400,15 L400,100 L0,100Z" fill="url(#cg)"/>
                                        <path d="M0,80 C40,70 60,45 100,50 C140,55 170,25 200,30 C240,35 270,15 310,12 C340,8 380,20 400,15" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round"/>
                                        <circle cx="310" cy="12" r="5" fill="#6366f1" stroke="white" stroke-width="2.5"/>
                                        <rect x="285" y="-5" width="50" height="18" rx="6" fill="#6366f1"/>
                                        <text x="310" y="7" text-anchor="middle" fill="white" font-size="8" font-weight="bold" font-family="Inter">24 {{ app()->getLocale()==='ar' ? 'موعد' : 'appts' }}</text>
                                    </svg>
                                    <div class="flex justify-between mt-2">
                                        <span class="text-[9px] text-gray-400 font-medium">Sat</span><span class="text-[9px] text-gray-400 font-medium">Sun</span><span class="text-[9px] text-gray-400 font-medium">Mon</span><span class="text-[9px] text-gray-400 font-medium">Tue</span><span class="text-[9px] text-gray-400 font-medium">Wed</span><span class="text-[9px] text-indigo-600 font-bold">Thu</span><span class="text-[9px] text-gray-400 font-medium">Fri</span>
                                    </div>
                                </div>

                                {{-- Today list --}}
                                <div class="md:col-span-2 bg-white rounded-xl border border-gray-100 overflow-hidden">
                                    <div class="px-3 md:px-4 py-2.5 border-b border-gray-50 flex items-center justify-between">
                                        <span class="text-[10px] md:text-xs font-bold text-gray-700">{{ __('app.today_appointments') }}</span>
                                        <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded">18</span>
                                    </div>
                                    <div class="divide-y divide-gray-50">
                                        <div class="px-3 md:px-4 py-2.5 flex items-center justify-between">
                                            <div class="flex items-center gap-2"><div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center"><span class="text-[9px] font-bold text-blue-600">M</span></div><div><p class="text-[11px] font-semibold text-gray-700">Mohamed Ali</p><p class="text-[9px] text-gray-400" dir="ltr">09:00 AM</p></div></div>
                                            <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">{{ __('app.confirmed') }}</span>
                                        </div>
                                        <div class="px-3 md:px-4 py-2.5 flex items-center justify-between">
                                            <div class="flex items-center gap-2"><div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center"><span class="text-[9px] font-bold text-purple-600">S</span></div><div><p class="text-[11px] font-semibold text-gray-700">Sara Hassan</p><p class="text-[9px] text-gray-400" dir="ltr">09:30 AM</p></div></div>
                                            <span class="text-[8px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ __('app.scheduled') }}</span>
                                        </div>
                                        <div class="px-3 md:px-4 py-2.5 flex items-center justify-between">
                                            <div class="flex items-center gap-2"><div class="w-7 h-7 rounded-lg bg-rose-100 flex items-center justify-center"><span class="text-[9px] font-bold text-rose-600">A</span></div><div><p class="text-[11px] font-semibold text-gray-700">Ahmed Khaled</p><p class="text-[9px] text-gray-400" dir="ltr">10:00 AM</p></div></div>
                                            <span class="text-[8px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">{{ __('app.in_progress') }}</span>
                                        </div>
                                        <div class="px-3 md:px-4 py-2.5 flex items-center justify-between">
                                            <div class="flex items-center gap-2"><div class="w-7 h-7 rounded-lg bg-cyan-100 flex items-center justify-center"><span class="text-[9px] font-bold text-cyan-600">F</span></div><div><p class="text-[11px] font-semibold text-gray-700">Fatma Omar</p><p class="text-[9px] text-gray-400" dir="ltr">10:30 AM</p></div></div>
                                            <span class="text-[8px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ __('app.scheduled') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bottom gradient fade --}}
                <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-indigo-950 to-transparent pointer-events-none rounded-b-3xl"></div>
            </div>
        </div>

        {{-- Wave transition --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" class="w-full"><path d="M0 80V40C240 10 480 0 720 15C960 30 1200 60 1440 40V80H0Z" fill="white"/></svg>
        </div>
    </section>

    {{-- ===== STATS ===== --}}
    <section class="relative bg-white border-y border-gray-100" x-data="{shown:false}" x-intersect.once="shown=true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center fade-up">
                    <p class="text-4xl md:text-5xl font-black mb-1" x-data="{c:0}" x-effect="if(shown){let s=performance.now();const step=n=>{const p=Math.min((n-s)/2000,1);c=Math.floor(p*100);if(p<1)requestAnimationFrame(step)};requestAnimationFrame(step)}"><span class="text-indigo-600" x-text="c+'%'">0%</span></p>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.landing_stat_free') }}</p>
                </div>
                <div class="text-center fade-up">
                    <p class="text-4xl md:text-5xl font-black mb-1" x-data="{c:0}" x-effect="if(shown){let s=performance.now();const step=n=>{const p=Math.min((n-s)/2000,1);c=Math.floor(p*24);if(p<1)requestAnimationFrame(step)};requestAnimationFrame(step)}"><span class="text-purple-600" x-text="c+'/7'">0/7</span></p>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.landing_stat_available') }}</p>
                </div>
                <div class="text-center fade-up">
                    <p class="text-4xl md:text-5xl font-black mb-1" x-data="{c:0}" x-effect="if(shown){let s=performance.now();const step=n=>{const p=Math.min((n-s)/2000,1);c=Math.floor(p*15);if(p<1)requestAnimationFrame(step)};requestAnimationFrame(step)}"><span class="text-pink-600" x-text="c+'+'">0+</span></p>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.landing_stat_specialties') }}</p>
                </div>
                <div class="text-center fade-up">
                    <p class="text-4xl md:text-5xl font-black mb-1 text-indigo-600">AR/EN</p>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.landing_stat_langs') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== MEDICAL DATABASE HIGHLIGHT ===== --}}
    <section id="database" class="py-24 md:py-32 bg-gradient-to-b from-indigo-50/50 to-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-purple-100/30 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-100 rounded-full text-sm font-bold text-indigo-700 uppercase tracking-wider mb-5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                    {{ app()->getLocale()==='ar' ? 'قاعدة بيانات طبية شاملة' : 'Complete Medical Database' }}
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-5">{{ app()->getLocale()==='ar' ? 'كل البيانات الطبية في مكان واحد' : 'All Medical Data In One Place' }}</h2>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto">{{ app()->getLocale()==='ar' ? 'قاعدة بيانات كاملة للأدوية والتحاليل والأشعة مدمجة مباشرة في النظام' : 'A complete database of medications, lab tests, and radiology integrated directly into the system' }}</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Medications Database --}}
                <div class="fade-up card-lift bg-white rounded-3xl p-8 border border-gray-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-50/50 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:bg-blue-100/50 transition-colors"></div>
                    <div class="relative">
                        {{-- Medication Illustration --}}
                        <div class="w-full h-48 mb-6 flex items-center justify-center">
                            <svg viewBox="0 0 200 160" class="w-48 h-auto" fill="none">
                                {{-- Pill bottle --}}
                                <rect x="60" y="40" width="80" height="100" rx="12" fill="#dbeafe" stroke="#93c5fd" stroke-width="1.5"/>
                                <rect x="60" y="40" width="80" height="30" rx="12" fill="#3b82f6"/>
                                <rect x="60" y="58" width="80" height="12" fill="#3b82f6"/>
                                <rect x="68" y="25" width="64" height="22" rx="6" fill="#2563eb"/>
                                <text x="100" y="40" text-anchor="middle" fill="white" font-size="9" font-weight="bold" font-family="Inter">Rx</text>
                                {{-- Label --}}
                                <rect x="72" y="78" width="56" height="45" rx="6" fill="white" stroke="#bfdbfe" stroke-width="0.5"/>
                                <line x1="80" y1="88" x2="120" y2="88" stroke="#93c5fd" stroke-width="2" stroke-linecap="round"/>
                                <line x1="80" y1="96" x2="110" y2="96" stroke="#bfdbfe" stroke-width="1.5" stroke-linecap="round"/>
                                <line x1="80" y1="104" x2="115" y2="104" stroke="#bfdbfe" stroke-width="1.5" stroke-linecap="round"/>
                                <line x1="80" y1="112" x2="105" y2="112" stroke="#bfdbfe" stroke-width="1.5" stroke-linecap="round"/>

                                {{-- Floating pills --}}
                                <g class="illust-pill">
                                    <rect x="150" y="30" width="36" height="16" rx="8" fill="#6366f1" transform="rotate(-15 168 38)"/>
                                    <rect x="150" y="30" width="18" height="16" rx="8" fill="#818cf8" transform="rotate(-15 168 38)"/>
                                </g>
                                <g class="illust-pill" style="animation-delay:-1.5s">
                                    <rect x="10" y="60" width="32" height="14" rx="7" fill="#f472b6" transform="rotate(10 26 67)"/>
                                    <rect x="10" y="60" width="16" height="14" rx="7" fill="#f9a8d4" transform="rotate(10 26 67)"/>
                                </g>
                                <g class="illust-pill" style="animation-delay:-2.5s">
                                    <circle cx="160" cy="100" r="10" fill="#fbbf24"/>
                                    <circle cx="160" cy="100" r="5" fill="#fcd34d"/>
                                </g>
                                {{-- Search icon --}}
                                <g class="illust-drop">
                                    <circle cx="28" cy="120" r="18" fill="#eff6ff" stroke="#93c5fd" stroke-width="1"/>
                                    <circle cx="25" cy="117" r="8" fill="none" stroke="#3b82f6" stroke-width="2"/>
                                    <line x1="31" y1="123" x2="37" y2="129" stroke="#3b82f6" stroke-width="2" stroke-linecap="round"/>
                                </g>
                            </svg>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 rounded-lg text-xs font-bold text-blue-700 mb-3">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7"/></svg>
                            5,000+
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-3">{{ app()->getLocale()==='ar' ? 'قاعدة بيانات الأدوية' : 'Medications Database' }}</h3>
                        <p class="text-gray-500 leading-relaxed">{{ app()->getLocale()==='ar' ? 'أكثر من 5,000 دواء بالأسماء التجارية والعلمية والجرعات والتفاعلات الدوائية. ابحث واختر الدواء المناسب فوراً.' : 'Over 5,000 medications with brand & generic names, dosages, and drug interactions. Search and select instantly.' }}</p>
                    </div>
                </div>

                {{-- Lab Tests Database --}}
                <div class="fade-up d1 card-lift bg-white rounded-3xl p-8 border border-gray-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-50/50 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:bg-emerald-100/50 transition-colors"></div>
                    <div class="relative">
                        {{-- Lab Illustration --}}
                        <div class="w-full h-48 mb-6 flex items-center justify-center">
                            <svg viewBox="0 0 200 160" class="w-48 h-auto" fill="none">
                                {{-- Test tube rack --}}
                                <rect x="40" y="120" width="120" height="8" rx="4" fill="#d1d5db"/>
                                <rect x="45" y="115" width="4" height="10" fill="#9ca3af"/>
                                <rect x="151" y="115" width="4" height="10" fill="#9ca3af"/>
                                {{-- Test tubes --}}
                                <g class="illust-drop">
                                    <rect x="55" y="45" width="18" height="75" rx="9" fill="#dcfce7" stroke="#86efac" stroke-width="1.5"/>
                                    <rect x="55" y="80" width="18" height="40" rx="9" fill="#4ade80"/>
                                    <rect x="55" y="35" width="24" height="12" rx="3" fill="#22c55e" transform="translate(-3,0)"/>
                                </g>
                                <g class="illust-drop" style="animation-delay:-1s">
                                    <rect x="85" y="35" width="18" height="85" rx="9" fill="#fef3c7" stroke="#fcd34d" stroke-width="1.5"/>
                                    <rect x="85" y="75" width="18" height="45" rx="9" fill="#fbbf24"/>
                                    <rect x="82" y="25" width="24" height="12" rx="3" fill="#f59e0b"/>
                                </g>
                                <g class="illust-drop" style="animation-delay:-2s">
                                    <rect x="115" y="50" width="18" height="70" rx="9" fill="#fce7f3" stroke="#f9a8d4" stroke-width="1.5"/>
                                    <rect x="115" y="85" width="18" height="35" rx="9" fill="#f472b6"/>
                                    <rect x="112" y="40" width="24" height="12" rx="3" fill="#ec4899"/>
                                </g>
                                {{-- Clipboard --}}
                                <g transform="translate(145,20)">
                                    <rect x="0" y="8" width="42" height="55" rx="6" fill="white" stroke="#d1d5db" stroke-width="1"/>
                                    <rect x="10" y="2" width="22" height="12" rx="4" fill="#e2e8f0" stroke="#cbd5e1" stroke-width="0.5"/>
                                    <line x1="8" y1="28" x2="34" y2="28" stroke="#10b981" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="8" y1="36" x2="28" y2="36" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round"/>
                                    <line x1="8" y1="43" x2="30" y2="43" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round"/>
                                    <line x1="8" y1="50" x2="24" y2="50" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round"/>
                                    {{-- Checkmark --}}
                                    <path d="M30 43 L33 46 L38 40" stroke="#10b981" stroke-width="2" fill="none" stroke-linecap="round"/>
                                </g>
                                {{-- Microscope simple --}}
                                <g transform="translate(5,55)">
                                    <circle cx="20" cy="20" r="16" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
                                    <circle cx="20" cy="20" r="8" fill="#dcfce7" stroke="#4ade80" stroke-width="1.5"/>
                                    <circle cx="20" cy="20" r="3" fill="#22c55e"/>
                                </g>
                            </svg>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 rounded-lg text-xs font-bold text-emerald-700 mb-3">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7"/></svg>
                            500+
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-3">{{ app()->getLocale()==='ar' ? 'قاعدة بيانات التحاليل' : 'Lab Tests Database' }}</h3>
                        <p class="text-gray-500 leading-relaxed">{{ app()->getLocale()==='ar' ? 'أكثر من 500 تحليل معملي مع المعدلات الطبيعية. اطلب التحاليل مباشرة من شاشة التشخيص.' : 'Over 500 lab tests with normal ranges. Order tests directly from the diagnosis screen.' }}</p>
                    </div>
                </div>

                {{-- Radiology Database --}}
                <div class="fade-up d2 card-lift bg-white rounded-3xl p-8 border border-gray-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-violet-50/50 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:bg-violet-100/50 transition-colors"></div>
                    <div class="relative">
                        {{-- Radiology Illustration --}}
                        <div class="w-full h-48 mb-6 flex items-center justify-center">
                            <svg viewBox="0 0 200 160" class="w-48 h-auto" fill="none">
                                {{-- X-ray display --}}
                                <rect x="30" y="10" width="140" height="120" rx="12" fill="#1e1b4b" stroke="#4338ca" stroke-width="1.5"/>
                                <rect x="38" y="18" width="124" height="104" rx="8" fill="#312e81"/>
                                {{-- Ribcage x-ray --}}
                                <g opacity="0.6">
                                    {{-- Spine --}}
                                    <line x1="100" y1="35" x2="100" y2="110" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                                    {{-- Ribs --}}
                                    <path d="M100 45 Q120 40 135 50" stroke="#818cf8" stroke-width="1.5" fill="none"/>
                                    <path d="M100 45 Q80 40 65 50" stroke="#818cf8" stroke-width="1.5" fill="none"/>
                                    <path d="M100 55 Q125 50 140 58" stroke="#818cf8" stroke-width="1.5" fill="none"/>
                                    <path d="M100 55 Q75 50 60 58" stroke="#818cf8" stroke-width="1.5" fill="none"/>
                                    <path d="M100 65 Q125 60 142 66" stroke="#818cf8" stroke-width="1.5" fill="none"/>
                                    <path d="M100 65 Q75 60 58 66" stroke="#818cf8" stroke-width="1.5" fill="none"/>
                                    <path d="M100 75 Q123 72 138 76" stroke="#818cf8" stroke-width="1.5" fill="none"/>
                                    <path d="M100 75 Q77 72 62 76" stroke="#818cf8" stroke-width="1.5" fill="none"/>
                                    {{-- Pelvis hint --}}
                                    <path d="M80 100 Q100 90 120 100" stroke="#818cf8" stroke-width="1.5" fill="none"/>
                                </g>
                                {{-- Scan line --}}
                                <g class="illust-scan">
                                    <line x1="40" y1="25" x2="160" y2="25" stroke="#a78bfa" stroke-width="1" opacity="0.5"/>
                                    <line x1="40" y1="25" x2="160" y2="25" stroke="#c4b5fd" stroke-width="3" opacity="0.3" filter="url(#glow)"/>
                                </g>
                                <defs><filter id="glow"><feGaussianBlur stdDeviation="2"/></filter></defs>
                                {{-- Light indicator --}}
                                <circle cx="155" cy="18" r="4" fill="#4ade80" class="illust-pulse"/>
                                {{-- Stand --}}
                                <rect x="90" y="130" width="20" height="15" rx="3" fill="#e2e8f0"/>
                                <rect x="70" y="142" width="60" height="8" rx="4" fill="#cbd5e1"/>
                                {{-- Result tag --}}
                                <g transform="translate(145,60)" class="illust-drop">
                                    <rect x="0" y="0" width="48" height="30" rx="8" fill="white" stroke="#c4b5fd" stroke-width="1"/>
                                    <text x="24" y="13" text-anchor="middle" fill="#7c3aed" font-size="7" font-weight="bold" font-family="Inter">X-Ray</text>
                                    <path d="M14 22 L18 26 L34 18" stroke="#22c55e" stroke-width="2" fill="none" stroke-linecap="round"/>
                                </g>
                            </svg>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-violet-50 rounded-lg text-xs font-bold text-violet-700 mb-3">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7"/></svg>
                            200+
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-3">{{ app()->getLocale()==='ar' ? 'قاعدة بيانات الأشعة' : 'Radiology Database' }}</h3>
                        <p class="text-gray-500 leading-relaxed">{{ app()->getLocale()==='ar' ? 'أكثر من 200 نوع أشعة مع الأوصاف والتصنيفات. اطلب الأشعة مباشرة من شاشة التشخيص.' : 'Over 200 radiology types with descriptions. Order imaging directly from the diagnosis screen.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FEATURES ===== --}}
    <section id="features" class="py-24 md:py-32 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 rounded-full text-sm font-bold text-indigo-600 uppercase tracking-wider mb-5">{{ __('app.features') }}</div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-5">{{ __('app.landing_features_title') }}</h2>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto">{{ __('app.landing_features_desc') }}</p>
            </div>

            {{-- Feature 1: Appointments - Image Left --}}
            <div class="grid md:grid-cols-2 gap-16 items-center mb-24">
                <div class="fade-left {{ app()->getLocale()==='ar' ? 'md:order-2' : '' }}">
                    <div class="bg-indigo-50 rounded-3xl p-8 flex items-center justify-center">
                        <svg viewBox="0 0 300 220" class="w-full max-w-[300px]" fill="none">
                            {{-- Calendar --}}
                            <rect x="30" y="20" width="240" height="180" rx="16" fill="white" stroke="#e0e7ff" stroke-width="1.5"/>
                            <rect x="30" y="20" width="240" height="50" rx="16" fill="#6366f1"/>
                            <rect x="30" y="55" width="240" height="15" fill="#6366f1"/>
                            <text x="150" y="50" text-anchor="middle" fill="white" font-size="16" font-weight="bold" font-family="Inter">{{ app()->getLocale()==='ar' ? 'مارس 2026' : 'March 2026' }}</text>
                            {{-- Calendar grid --}}
                            @for($row=0;$row<3;$row++)
                                @for($col=0;$col<7;$col++)
                                    @php $day = $row*7+$col+1; $cx=65+$col*32; $cy=95+$row*35; @endphp
                                    @if($day <= 21)
                                        @if($day == 13)
                                            <circle cx="{{ $cx }}" cy="{{ $cy }}" r="14" fill="#6366f1"/>
                                            <text x="{{ $cx }}" y="{{ $cy+4 }}" text-anchor="middle" fill="white" font-size="11" font-weight="bold" font-family="Inter">{{ $day }}</text>
                                        @elseif(in_array($day,[5,8,15,19]))
                                            <circle cx="{{ $cx }}" cy="{{ $cy }}" r="14" fill="#eef2ff"/>
                                            <text x="{{ $cx }}" y="{{ $cy+4 }}" text-anchor="middle" fill="#6366f1" font-size="11" font-weight="bold" font-family="Inter">{{ $day }}</text>
                                            <circle cx="{{ $cx+8 }}" cy="{{ $cy-8 }}" r="3" fill="#10b981"/>
                                        @else
                                            <text x="{{ $cx }}" y="{{ $cy+4 }}" text-anchor="middle" fill="#64748b" font-size="11" font-family="Inter">{{ $day }}</text>
                                        @endif
                                    @endif
                                @endfor
                            @endfor
                            {{-- Appointment popup --}}
                            <g transform="translate(170,75)" class="float-3">
                                <rect x="0" y="0" width="110" height="55" rx="10" fill="white" stroke="#c7d2fe" stroke-width="1" filter="url(#popShadow)"/>
                                <circle cx="18" cy="20" r="10" fill="#dbeafe"/>
                                <text x="15" y="24" fill="#3b82f6" font-size="10" font-weight="bold" font-family="Inter">M</text>
                                <text x="34" y="18" fill="#1e293b" font-size="9" font-weight="bold" font-family="Inter">Mohamed Ali</text>
                                <text x="34" y="30" fill="#94a3b8" font-size="7" font-family="Inter">09:00 AM - Checkup</text>
                                <rect x="12" y="38" width="45" height="12" rx="4" fill="#dcfce7"/>
                                <text x="22" y="47" fill="#16a34a" font-size="7" font-weight="bold" font-family="Inter">Confirmed</text>
                            </g>
                            <defs><filter id="popShadow"><feDropShadow dx="0" dy="4" stdDeviation="8" flood-color="#6366f1" flood-opacity="0.12"/></filter></defs>
                        </svg>
                    </div>
                </div>
                <div class="fade-right {{ app()->getLocale()==='ar' ? 'md:order-1' : '' }}">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 rounded-lg text-xs font-bold text-blue-700 mb-4">{{ __('app.feature_appointments') }}</div>
                    <h3 class="text-3xl font-black text-gray-900 mb-4">{{ app()->getLocale()==='ar' ? 'جدولة ذكية للمواعيد' : 'Smart Appointment Scheduling' }}</h3>
                    <p class="text-gray-500 leading-relaxed mb-6">{{ __('app.landing_feat_appointments_desc') }}</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3"><div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center shrink-0"><svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div><span class="text-sm text-gray-600">{{ app()->getLocale()==='ar' ? 'حجز وتأكيد وإلغاء المواعيد' : 'Book, confirm, and cancel appointments' }}</span></li>
                        <li class="flex items-center gap-3"><div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center shrink-0"><svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div><span class="text-sm text-gray-600">{{ app()->getLocale()==='ar' ? 'عرض يومي وشهري للجدول' : 'Daily and monthly schedule view' }}</span></li>
                        <li class="flex items-center gap-3"><div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center shrink-0"><svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div><span class="text-sm text-gray-600">{{ app()->getLocale()==='ar' ? 'مواعيد المتابعة التلقائية' : 'Automatic follow-up scheduling' }}</span></li>
                    </ul>
                </div>
            </div>

            {{-- Feature 2: Diagnoses - Image Right --}}
            <div class="grid md:grid-cols-2 gap-16 items-center mb-24">
                <div class="fade-right {{ app()->getLocale()==='ar' ? 'md:order-1' : 'md:order-2' }}">
                    <div class="bg-violet-50 rounded-3xl p-8 flex items-center justify-center">
                        <svg viewBox="0 0 300 220" class="w-full max-w-[300px]" fill="none">
                            {{-- Body outline (simple torso) --}}
                            <g transform="translate(30,10)">
                                {{-- Body outline --}}
                                <path d="M120 0 Q145 0 145 25 L145 40 Q170 50 175 80 L175 160 Q175 175 160 175 L80 175 Q65 175 65 160 L65 80 Q70 50 95 40 L95 25 Q95 0 120 0Z" fill="#ede9fe" stroke="#c4b5fd" stroke-width="1.5"/>
                                {{-- Interactive points --}}
                                <circle cx="120" cy="15" r="20" fill="#ddd6fe" stroke="#a78bfa" stroke-width="1" class="illust-pulse"/>
                                <circle cx="120" cy="70" r="5" fill="#7c3aed" class="illust-pulse" style="animation-delay:-.5s"/>
                                <circle cx="100" cy="100" r="5" fill="#7c3aed" class="illust-pulse" style="animation-delay:-1s"/>
                                <circle cx="140" cy="100" r="5" fill="#7c3aed" class="illust-pulse" style="animation-delay:-1.5s"/>
                                <circle cx="120" cy="130" r="5" fill="#7c3aed" class="illust-pulse" style="animation-delay:-2s"/>
                                {{-- Lines from points to labels --}}
                                <line x1="125" y1="70" x2="190" y2="55" stroke="#a78bfa" stroke-width="1" stroke-dasharray="3"/>
                                <line x1="145" y1="100" x2="195" y2="95" stroke="#a78bfa" stroke-width="1" stroke-dasharray="3"/>
                            </g>
                            {{-- Diagnosis notes --}}
                            <g transform="translate(200,40)">
                                <rect x="0" y="0" width="85" height="35" rx="8" fill="white" stroke="#c4b5fd" stroke-width="1"/>
                                <text x="10" y="15" fill="#7c3aed" font-size="8" font-weight="bold" font-family="Inter">{{ app()->getLocale()==='ar' ? 'القلب' : 'Heart' }}</text>
                                <text x="10" y="27" fill="#94a3b8" font-size="7" font-family="Inter">Normal rhythm</text>
                            </g>
                            <g transform="translate(200,85)">
                                <rect x="0" y="0" width="85" height="35" rx="8" fill="white" stroke="#c4b5fd" stroke-width="1"/>
                                <text x="10" y="15" fill="#7c3aed" font-size="8" font-weight="bold" font-family="Inter">{{ app()->getLocale()==='ar' ? 'الرئتين' : 'Lungs' }}</text>
                                <text x="10" y="27" fill="#94a3b8" font-size="7" font-family="Inter">Clear breath</text>
                            </g>
                            {{-- Pen icon --}}
                            <g transform="translate(220,140)" class="illust-drop">
                                <rect x="0" y="0" width="50" height="50" rx="12" fill="#f5f3ff" stroke="#c4b5fd" stroke-width="1"/>
                                <path d="M15 35 L20 15 L35 12 L30 32Z" fill="#8b5cf6"/>
                                <path d="M20 15 L35 12" stroke="#7c3aed" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="14" cy="37" r="2" fill="#7c3aed"/>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="fade-left {{ app()->getLocale()==='ar' ? 'md:order-2' : 'md:order-1' }}">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-violet-50 rounded-lg text-xs font-bold text-violet-700 mb-4">{{ __('app.landing_feat_diagnoses') }}</div>
                    <h3 class="text-3xl font-black text-gray-900 mb-4">{{ app()->getLocale()==='ar' ? 'تشخيصات تفاعلية ورسومات' : 'Interactive Diagnoses & Body Maps' }}</h3>
                    <p class="text-gray-500 leading-relaxed mb-6">{{ __('app.landing_feat_diagnoses_desc') }}</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3"><div class="w-6 h-6 bg-violet-100 rounded-lg flex items-center justify-center shrink-0"><svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div><span class="text-sm text-gray-600">{{ app()->getLocale()==='ar' ? 'رسومات تفاعلية للجسم لكل تخصص' : 'Specialty-specific body diagrams' }}</span></li>
                        <li class="flex items-center gap-3"><div class="w-6 h-6 bg-violet-100 rounded-lg flex items-center justify-center shrink-0"><svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div><span class="text-sm text-gray-600">{{ app()->getLocale()==='ar' ? 'طلب تحاليل وأشعة مباشرة' : 'Direct lab & imaging orders' }}</span></li>
                        <li class="flex items-center gap-3"><div class="w-6 h-6 bg-violet-100 rounded-lg flex items-center justify-center shrink-0"><svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div><span class="text-sm text-gray-600">{{ app()->getLocale()==='ar' ? 'وصفات طبية مع بحث الأدوية' : 'Prescriptions with drug search' }}</span></li>
                    </ul>
                </div>
            </div>

            {{-- Feature 3: Multi-branch + Billing - Cards --}}
            <div class="grid md:grid-cols-2 gap-8">
                <div class="fade-up card-lift bg-gradient-to-br from-cyan-50 to-blue-50 rounded-3xl p-8 border border-cyan-100">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                        <svg class="w-7 h-7 text-cyan-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('app.landing_feat_branches') }}</h3>
                    <p class="text-gray-500 leading-relaxed">{{ __('app.landing_feat_branches_desc') }}</p>
                </div>
                <div class="fade-up d1 card-lift bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl p-8 border border-amber-100">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('app.feature_billing') }}</h3>
                    <p class="text-gray-500 leading-relaxed">{{ __('app.landing_feat_billing_desc') }}</p>
                </div>
                <div class="fade-up card-lift bg-gradient-to-br from-emerald-50 to-green-50 rounded-3xl p-8 border border-emerald-100">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('app.feature_patients') }}</h3>
                    <p class="text-gray-500 leading-relaxed">{{ __('app.landing_feat_patients_desc') }}</p>
                </div>
                <div class="fade-up d1 card-lift bg-gradient-to-br from-rose-50 to-pink-50 rounded-3xl p-8 border border-rose-100">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                        <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('app.landing_feat_staff') }}</h3>
                    <p class="text-gray-500 leading-relaxed">{{ __('app.landing_feat_staff_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== HOW IT WORKS ===== --}}
    <section id="how-it-works" class="py-24 md:py-32 bg-gray-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 rounded-full text-sm font-bold text-indigo-600 uppercase tracking-wider mb-5">{{ __('app.landing_how_it_works') }}</div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-5">{{ __('app.landing_how_title') }}</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto relative">
                <div class="hidden md:block absolute top-16 left-[25%] right-[25%] h-0.5 bg-gradient-to-r from-indigo-200 via-purple-300 to-pink-200"></div>
                <div class="fade-up d1 text-center group">
                    <div class="w-28 h-28 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-xl shadow-indigo-500/15 group-hover:scale-110 transition-transform relative z-10">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('app.landing_step1_title') }}</h3>
                    <p class="text-gray-500">{{ __('app.landing_step1_desc') }}</p>
                </div>
                <div class="fade-up d2 text-center group">
                    <div class="w-28 h-28 bg-gradient-to-br from-purple-500 to-purple-600 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-xl shadow-purple-500/15 group-hover:scale-110 transition-transform relative z-10">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('app.landing_step2_title') }}</h3>
                    <p class="text-gray-500">{{ __('app.landing_step2_desc') }}</p>
                </div>
                <div class="fade-up d3 text-center group">
                    <div class="w-28 h-28 bg-gradient-to-br from-pink-500 to-pink-600 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-xl shadow-pink-500/15 group-hover:scale-110 transition-transform relative z-10">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('app.landing_step3_title') }}</h3>
                    <p class="text-gray-500">{{ __('app.landing_step3_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SPECIALTIES MARQUEE ===== --}}
    <section id="specialties" class="py-24 md:py-32 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 rounded-full text-sm font-bold text-indigo-600 uppercase tracking-wider mb-5">{{ __('app.landing_specialties') }}</div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-5">{{ __('app.landing_specialties_title') }}</h2>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto">{{ __('app.landing_specialties_desc') }}</p>
            </div>
        </div>
        <div class="relative overflow-hidden">
            <div class="flex gap-4 marquee-track" style="width:max-content">
                @for($r=0;$r<2;$r++)
                <div class="flex items-center gap-3 bg-red-50 border border-red-100 rounded-2xl px-6 py-4 shrink-0 hover:shadow-lg hover:scale-105 transition-all cursor-default"><div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm"><svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div><span class="text-sm font-bold text-gray-700 whitespace-nowrap">{{ __('app.landing_spec_cardiology') }}</span></div>
                <div class="flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-2xl px-6 py-4 shrink-0 hover:shadow-lg hover:scale-105 transition-all cursor-default"><div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm"><svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><span class="text-sm font-bold text-gray-700 whitespace-nowrap">{{ __('app.landing_spec_ophthalmology') }}</span></div>
                <div class="flex items-center gap-3 bg-cyan-50 border border-cyan-100 rounded-2xl px-6 py-4 shrink-0 hover:shadow-lg hover:scale-105 transition-all cursor-default"><div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm"><svg class="w-6 h-6 text-cyan-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><span class="text-sm font-bold text-gray-700 whitespace-nowrap">{{ __('app.landing_spec_dentistry') }}</span></div>
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 rounded-2xl px-6 py-4 shrink-0 hover:shadow-lg hover:scale-105 transition-all cursor-default"><div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm"><svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div><span class="text-sm font-bold text-gray-700 whitespace-nowrap">{{ __('app.landing_spec_orthopedics') }}</span></div>
                <div class="flex items-center gap-3 bg-pink-50 border border-pink-100 rounded-2xl px-6 py-4 shrink-0 hover:shadow-lg hover:scale-105 transition-all cursor-default"><div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm"><svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg></div><span class="text-sm font-bold text-gray-700 whitespace-nowrap">{{ __('app.landing_spec_dermatology') }}</span></div>
                <div class="flex items-center gap-3 bg-green-50 border border-green-100 rounded-2xl px-6 py-4 shrink-0 hover:shadow-lg hover:scale-105 transition-all cursor-default"><div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm"><svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><span class="text-sm font-bold text-gray-700 whitespace-nowrap">{{ __('app.landing_spec_pediatrics') }}</span></div>
                <div class="flex items-center gap-3 bg-purple-50 border border-purple-100 rounded-2xl px-6 py-4 shrink-0 hover:shadow-lg hover:scale-105 transition-all cursor-default"><div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm"><svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg></div><span class="text-sm font-bold text-gray-700 whitespace-nowrap">{{ __('app.landing_spec_neurology') }}</span></div>
                <div class="flex items-center gap-3 bg-indigo-50 border border-indigo-100 rounded-2xl px-6 py-4 shrink-0 hover:shadow-lg hover:scale-105 transition-all cursor-default"><div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm"><svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div><span class="text-sm font-bold text-gray-700 whitespace-nowrap">{{ __('app.landing_spec_internal') }}</span></div>
                <div class="flex items-center gap-3 bg-teal-50 border border-teal-100 rounded-2xl px-6 py-4 shrink-0 hover:shadow-lg hover:scale-105 transition-all cursor-default"><div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm"><svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div><span class="text-sm font-bold text-gray-700 whitespace-nowrap">{{ __('app.landing_spec_physiotherapy') }}</span></div>
                <div class="flex items-center gap-3 bg-orange-50 border border-orange-100 rounded-2xl px-6 py-4 shrink-0 hover:shadow-lg hover:scale-105 transition-all cursor-default"><div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm"><svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10"/></svg></div><span class="text-sm font-bold text-gray-700 whitespace-nowrap">{{ __('app.landing_spec_ent') }}</span></div>
                @endfor
            </div>
        </div>
    </section>

    {{-- ===== CTA ===== --}}
    <section class="py-24 md:py-32 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.05]" style="background-image:radial-gradient(circle,white 1px,transparent 1px);background-size:20px 20px"></div>
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-purple-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-400/20 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-4 text-center fade-up">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight">{{ __('app.landing_cta_title') }}</h2>
            <p class="text-xl text-indigo-200 mb-10 max-w-xl mx-auto">{{ __('app.landing_cta_desc') }}</p>
            <a href="{{ route('register.clinic') }}" class="group inline-flex items-center gap-2.5 px-10 py-5 bg-white text-indigo-700 text-lg font-black rounded-2xl hover:bg-indigo-50 transition-all shadow-xl hover:scale-[1.02]">
                {{ __('app.landing_start_free') }}
                <svg class="w-5 h-5 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <p class="text-sm text-indigo-300 mt-6 font-medium flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                {{ __('app.landing_no_credit') }}
            </p>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5 mb-5">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
                        <span class="text-xl font-extrabold text-white">{{ __('app.app_name') }}</span>
                    </div>
                    <p class="text-gray-400 leading-relaxed max-w-sm">{{ __('app.landing_footer_about') }}</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-5 text-sm uppercase tracking-wider">{{ __('app.landing_quick_links') }}</h4>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('app.features') }}</a></li>
                        <li><a href="#database" class="text-gray-400 hover:text-white text-sm transition-colors">{{ app()->getLocale()==='ar' ? 'قاعدة البيانات' : 'Database' }}</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('app.landing_how_it_works') }}</a></li>
                        <li><a href="#specialties" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('app.landing_specialties') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-5 text-sm uppercase tracking-wider">{{ __('app.landing_contact') }}</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li class="flex items-center gap-3"><div class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>support@healthyhub.com</li>
                        <li class="flex items-center gap-3"><div class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div><span dir="ltr">+20 100 000 0000</span></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800/50 mt-14 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} {{ __('app.app_name') }}. {{ __('app.landing_rights') }}</p>
                <p class="text-gray-600 text-xs flex items-center gap-1.5">{{ app()->getLocale()==='ar' ? 'صُنع بـ' : 'Made with' }}<svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>{{ app()->getLocale()==='ar' ? 'في مصر' : 'in Egypt' }}</p>
            </div>
        </div>
    </footer>

</body>
</html>
