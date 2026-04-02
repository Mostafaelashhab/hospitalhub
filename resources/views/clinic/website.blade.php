@php
    $locale = app()->getLocale();
    $isAr = $locale === 'ar';
    $name = $isAr ? ($clinic->name_ar ?: $clinic->name_en) : ($clinic->name_en ?: $clinic->name_ar);
    $about = $isAr ? ($clinic->website_about_ar ?: $clinic->website_about_en) : ($clinic->website_about_en ?: $clinic->website_about_ar);
    $address = $isAr ? ($clinic->address_ar ?: $clinic->address_en) : ($clinic->address_en ?: $clinic->address_ar);
    $specialtyName = $isAr ? ($clinic->specialty->name_ar ?? '') : ($clinic->specialty->name_en ?? '');
    $primary = $clinic->website_primary_color ?? '#0d9488';
    $secondary = $clinic->website_secondary_color ?? '#6366f1';
    $services = $clinic->website_services ?? [];
    $socials = $clinic->website_social_links ?? [];
    $doctors = $clinic->doctors ?? collect();
    $branches = $clinic->branches ?? collect();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $name }} — {{ $specialtyName }}</title>
    @if($clinic->website_meta_description)
    <meta name="description" content="{{ $clinic->website_meta_description }}">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900|cairo:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: {{ $isAr ? "'Cairo'" : "'Inter'" }}, sans-serif; }
        .fade-up { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.16,1,0.3,1); }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
        .fade-left { opacity: 0; transform: translateX({{ $isAr ? '40px' : '-40px' }}); transition: all 0.8s cubic-bezier(0.16,1,0.3,1); }
        .fade-left.visible { opacity: 1; transform: translateX(0); }
        .fade-right { opacity: 0; transform: translateX({{ $isAr ? '-40px' : '40px' }}); transition: all 0.8s cubic-bezier(0.16,1,0.3,1); }
        .fade-right.visible { opacity: 1; transform: translateX(0); }
        .d1{transition-delay:.1s}.d2{transition-delay:.2s}.d3{transition-delay:.3s}.d4{transition-delay:.4s}.d5{transition-delay:.5s}
        .brand-gradient { background: linear-gradient(135deg, {{ $primary }}, {{ $secondary }}); }
        .brand-text { background: linear-gradient(135deg, {{ $primary }}, {{ $secondary }}); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .float-1 { animation: f1 6s ease-in-out infinite; }
        .float-2 { animation: f2 8s ease-in-out infinite; }
        .float-3 { animation: f3 7s ease-in-out infinite; }
        @keyframes f1{0%,100%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(-20px) rotate(3deg)}}
        @keyframes f2{0%,100%{transform:translateY(0)}50%{transform:translateY(-15px) rotate(-2deg)}}
        @keyframes f3{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
        .card-lift{transition:all .4s cubic-bezier(.4,0,.2,1)}.card-lift:hover{transform:translateY(-8px);box-shadow:0 25px 60px -12px rgba(0,0,0,.12)}
        .mockup-shadow{box-shadow:0 50px 100px -20px color-mix(in srgb, {{ $primary }} 25%, transparent),0 30px 60px -15px rgba(0,0,0,.15)}
        html{scroll-behavior:smooth}
    </style>
</head>
<body class="antialiased bg-white text-gray-900 overflow-x-hidden"
      x-data="{}" x-init="
        const obs = new IntersectionObserver(e => e.forEach(el => { if(el.isIntersecting) el.target.classList.add('visible') }), {threshold:.1,rootMargin:'0px 0px -50px 0px'});
        document.querySelectorAll('.fade-up,.fade-left,.fade-right').forEach(el => obs.observe(el));
      ">

    {{-- ===== NAVBAR ===== --}}
    <nav class="fixed top-0 inset-x-0 z-50 transition-all duration-500"
         x-data="{s:false, mobileOpen:false}" x-init="window.addEventListener('scroll',()=>s=window.scrollY>30)"
         :class="s ? 'bg-white/95 backdrop-blur-2xl shadow-lg shadow-gray-200/30 border-b border-gray-100' : ''">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="#" class="flex items-center gap-2.5 group">
                    @if($clinic->logo)
                    <img src="{{ Storage::url($clinic->logo) }}" alt="{{ $name }}" class="w-10 h-10 rounded-xl object-cover shadow-lg group-hover:scale-110 transition-transform">
                    @else
                    <div class="w-10 h-10 brand-gradient rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform" style="box-shadow: 0 4px 14px {{ $primary }}40;">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    @endif
                    <div>
                        <span class="text-lg font-extrabold transition-colors" :class="s?'text-gray-900':'text-white'">{{ $name }}</span>
                        <p class="text-[10px] font-medium transition-colors leading-tight" :class="s?'text-gray-400':'text-white/50'">{{ $specialtyName }}</p>
                    </div>
                </a>
                <div class="hidden md:flex items-center gap-8">
                    @if($about)<a href="#about" class="text-sm font-semibold transition-colors" :class="s?'text-gray-600 hover:text-gray-900':'text-white/60 hover:text-white'">{{ __('app.about_clinic') }}</a>@endif
                    @if(count($services) > 0)<a href="#services" class="text-sm font-semibold transition-colors" :class="s?'text-gray-600 hover:text-gray-900':'text-white/60 hover:text-white'">{{ __('app.our_services') }}</a>@endif
                    @if($clinic->website_show_doctors && $doctors->count() > 0)<a href="#doctors" class="text-sm font-semibold transition-colors" :class="s?'text-gray-600 hover:text-gray-900':'text-white/60 hover:text-white'">{{ __('app.our_doctors') }}</a>@endif
                    @if($clinic->website_show_booking)<a href="#booking" class="text-sm font-semibold transition-colors" :class="s?'text-gray-600 hover:text-gray-900':'text-white/60 hover:text-white'">{{ __('app.online_booking') }}</a>@endif
                    <a href="#contact" class="text-sm font-semibold transition-colors" :class="s?'text-gray-600 hover:text-gray-900':'text-white/60 hover:text-white'">{{ __('app.contact_info') }}</a>
                </div>
                <div class="flex items-center gap-3">
                    @if($clinic->website_show_booking)
                    <a href="#booking" class="hidden sm:inline-flex px-5 py-2.5 text-white text-sm font-bold rounded-xl brand-gradient hover:opacity-90 transition-all shadow-lg" style="box-shadow: 0 4px 14px {{ $primary }}40;">{{ __('app.book_now') }}</a>
                    @endif
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg transition-colors" :class="s ? 'text-gray-600 hover:bg-gray-100' : 'text-white/80 hover:bg-white/10'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
            {{-- Mobile Menu --}}
            <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="md:hidden pb-4 space-y-1" @click.away="mobileOpen=false">
                @if($about)<a href="#about" @click="mobileOpen=false" class="block px-3 py-2 text-sm font-semibold rounded-lg" :class="s ? 'text-gray-600 hover:bg-gray-50' : 'text-white/70 hover:bg-white/10'">{{ __('app.about_clinic') }}</a>@endif
                @if(count($services) > 0)<a href="#services" @click="mobileOpen=false" class="block px-3 py-2 text-sm font-semibold rounded-lg" :class="s ? 'text-gray-600 hover:bg-gray-50' : 'text-white/70 hover:bg-white/10'">{{ __('app.our_services') }}</a>@endif
                @if($clinic->website_show_doctors && $doctors->count() > 0)<a href="#doctors" @click="mobileOpen=false" class="block px-3 py-2 text-sm font-semibold rounded-lg" :class="s ? 'text-gray-600 hover:bg-gray-50' : 'text-white/70 hover:bg-white/10'">{{ __('app.our_doctors') }}</a>@endif
                @if($clinic->website_show_booking)<a href="#booking" @click="mobileOpen=false" class="block px-3 py-2 text-sm font-semibold rounded-lg" :class="s ? 'text-gray-600 hover:bg-gray-50' : 'text-white/70 hover:bg-white/10'">{{ __('app.online_booking') }}</a>@endif
                <a href="#contact" @click="mobileOpen=false" class="block px-3 py-2 text-sm font-semibold rounded-lg" :class="s ? 'text-gray-600 hover:bg-gray-50' : 'text-white/70 hover:bg-white/10'">{{ __('app.contact_info') }}</a>
            </div>
        </div>
    </nav>

    {{-- ===== HERO ===== --}}
    <section class="relative min-h-screen overflow-hidden" style="background: linear-gradient(to bottom, color-mix(in srgb, {{ $primary }} 85%, #000) 0%, color-mix(in srgb, {{ $primary }} 70%, #0a0a1a) 50%, color-mix(in srgb, {{ $primary }} 85%, #000) 100%);">
        {{-- Animated grid --}}
        <div class="absolute inset-0 opacity-[0.07]" style="background-image:linear-gradient(color-mix(in srgb, {{ $primary }} 50%, white) 1px,transparent 1px),linear-gradient(90deg,color-mix(in srgb, {{ $primary }} 50%, white) 1px,transparent 1px);background-size:60px 60px"></div>
        {{-- Glow orbs --}}
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[400px] rounded-full blur-[120px]" style="background: {{ $primary }}30;"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] rounded-full blur-[100px]" style="background: {{ $secondary }}20;"></div>
        <div class="absolute top-20 right-0 w-[300px] h-[300px] bg-cyan-500/10 rounded-full blur-[80px]"></div>

        {{-- Floating medical icons --}}
        <div class="absolute top-32 {{ $isAr ? 'right' : 'left' }}-[8%] float-1 hidden lg:block">
            <div class="w-14 h-14 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-2xl flex items-center justify-center rotate-12">
                <svg class="w-7 h-7" style="color: color-mix(in srgb, {{ $primary }} 60%, white);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
            </div>
        </div>
        <div class="absolute top-48 {{ $isAr ? 'left' : 'right' }}-[12%] float-2 hidden lg:block">
            <div class="w-12 h-12 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-xl flex items-center justify-center -rotate-6">
                <svg class="w-6 h-6" style="color: color-mix(in srgb, {{ $secondary }} 60%, white);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3"/></svg>
            </div>
        </div>
        <div class="absolute bottom-[30%] {{ $isAr ? 'right' : 'left' }}-[5%] float-3 hidden lg:block">
            <div class="w-11 h-11 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-xl flex items-center justify-center rotate-6">
                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="absolute bottom-[25%] {{ $isAr ? 'left' : 'right' }}-[7%] float-1 hidden lg:block" style="animation-delay:-2s">
            <div class="w-10 h-10 bg-white/[0.07] backdrop-blur-sm border border-white/[0.1] rounded-lg flex items-center justify-center -rotate-12">
                <svg class="w-5 h-5 text-pink-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-10">
            <div class="text-center max-w-4xl mx-auto mb-16">
                {{-- Badge pill --}}
                <div class="fade-up inline-flex items-center gap-2.5 px-5 py-2.5 bg-white/[0.08] border border-white/[0.12] rounded-full text-sm font-bold mb-8 backdrop-blur-sm" style="color: color-mix(in srgb, {{ $primary }} 50%, white);">
                    <span class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute h-full w-full rounded-full opacity-75" style="background: {{ $primary }};"></span><span class="relative rounded-full h-2.5 w-2.5" style="background: {{ $primary }};"></span></span>
                    {{ $specialtyName }}
                </div>
                {{-- Main heading --}}
                <h1 class="fade-up d1 text-4xl md:text-6xl lg:text-7xl font-black leading-[1.08] mb-6 tracking-tight text-white">
                    {{ __('app.welcome_to') }}
                    <span class="brand-text">{{ $name }}</span>
                </h1>
                @if($about)
                <p class="fade-up d2 text-lg md:text-xl mb-10 max-w-2xl mx-auto leading-relaxed" style="color: color-mix(in srgb, {{ $primary }} 40%, white); opacity: 0.7;">{{ Str::limit($about, 200) }}</p>
                @else
                <p class="fade-up d2 text-lg md:text-xl mb-10 max-w-2xl mx-auto leading-relaxed" style="color: color-mix(in srgb, {{ $primary }} 40%, white); opacity: 0.7;">{{ $isAr ? 'نوفر لكم أفضل رعاية صحية مع أطبائنا المتخصصين' : 'We provide the best healthcare with our specialized doctors' }}</p>
                @endif
                {{-- CTA buttons --}}
                <div class="fade-up d3 flex flex-col sm:flex-row items-center justify-center gap-4 mb-10">
                    @if($clinic->website_show_booking)
                    <a href="#booking" class="group w-full sm:w-auto flex items-center justify-center gap-2.5 px-8 py-4 bg-white font-bold rounded-2xl hover:bg-gray-50 transition-all shadow-xl shadow-black/20 text-lg hover:scale-[1.02]" style="color: {{ $primary }};">
                        {{ __('app.book_now') }}
                        <svg class="w-5 h-5 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    @elseif($clinic->phone)
                    <a href="tel:{{ $clinic->phone }}" class="group w-full sm:w-auto flex items-center justify-center gap-2.5 px-8 py-4 bg-white font-bold rounded-2xl hover:bg-gray-50 transition-all shadow-xl shadow-black/20 text-lg hover:scale-[1.02]" style="color: {{ $primary }};">
                        {{ __('app.call_us') }}
                        <svg class="w-5 h-5 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    @endif
                    @if(!empty($socials['whatsapp']))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socials['whatsapp']) }}" target="_blank" class="w-full sm:w-auto flex items-center justify-center gap-2.5 px-8 py-4 text-white font-bold rounded-2xl border-2 border-white/40 hover:bg-white/10 hover:border-white/60 transition-all text-lg backdrop-blur-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                    @elseif($clinic->phone)
                    <a href="tel:{{ $clinic->phone }}" class="w-full sm:w-auto flex items-center justify-center gap-2.5 px-8 py-4 text-white font-bold rounded-2xl border-2 border-white/40 hover:bg-white/10 hover:border-white/60 transition-all text-lg backdrop-blur-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ __('app.call_us') }}
                    </a>
                    @endif
                </div>
                {{-- Trust row --}}
                <div class="fade-up d4 flex items-center justify-center gap-8 flex-wrap">
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2 rtl:space-x-reverse">
                            <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-[10px] font-bold text-white" style="background: linear-gradient(135deg, {{ $primary }}, color-mix(in srgb, {{ $primary }} 70%, #000)); border-color: color-mix(in srgb, {{ $primary }} 85%, #000);">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-[10px] font-bold text-white" style="background: linear-gradient(135deg, {{ $secondary }}, color-mix(in srgb, {{ $secondary }} 70%, #000)); border-color: color-mix(in srgb, {{ $primary }} 85%, #000);">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        </div>
                        <span class="text-sm font-medium" style="color: color-mix(in srgb, {{ $primary }} 50%, white);">{{ $doctors->count() }}+ {{ __('app.doctors') }}</span>
                    </div>
                    <div class="w-px h-5 bg-white/10 hidden sm:block"></div>
                    <div class="flex items-center gap-1.5">
                        @for($i=0;$i<5;$i++)<svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
                        <span class="text-sm font-medium ms-1" style="color: color-mix(in srgb, {{ $primary }} 50%, white);">5.0</span>
                    </div>
                    <div class="w-px h-5 bg-white/10 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span class="text-sm font-medium" style="color: color-mix(in srgb, {{ $primary }} 50%, white);">{{ $isAr ? 'ابتسامة مثالية' : 'Perfect Smile' }}</span>
                    </div>
                </div>
            </div>

            {{-- Dashboard Mockup --}}
            <div class="fade-up d5 relative max-w-5xl mx-auto">
                <div class="absolute -inset-4 rounded-[2rem] blur-2xl" style="background: linear-gradient(90deg, {{ $primary }}30, {{ $secondary }}30, {{ $primary }}30);"></div>

                {{-- Floating card left --}}
                @if($isAr)
                <div class="absolute -top-6 right-4 md:right-8 z-30 float-1">
                @else
                <div class="absolute -top-6 left-4 md:left-8 z-30 float-1">
                @endif
                    <div class="bg-white rounded-2xl shadow-2xl shadow-black/20 p-4 border border-gray-100 flex items-center gap-3">
                        <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 font-medium">{{ $isAr ? 'حجوزات اليوم' : 'Today' }}</p>
                            <p class="text-xl font-black text-gray-900">+18</p>
                        </div>
                        <div class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">+12%</div>
                    </div>
                </div>

                {{-- Floating card right --}}
                @if($isAr)
                <div class="absolute -top-4 left-4 md:left-12 z-30 float-2">
                @else
                <div class="absolute -top-4 right-4 md:right-12 z-30 float-2">
                @endif
                    <div class="bg-white rounded-2xl shadow-2xl shadow-black/20 p-4 border border-gray-100 flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: {{ $primary }}15;">
                            <svg class="w-6 h-6" style="color: {{ $primary }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 font-medium">{{ __('app.patients') }}</p>
                            <p class="text-xl font-black text-gray-900">{{ $doctors->count() > 0 ? '1,248' : '520' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Notification card --}}
                @if($isAr)
                <div class="absolute -bottom-3 left-4 md:left-16 z-30 float-3">
                @else
                <div class="absolute -bottom-3 right-4 md:right-16 z-30 float-3">
                @endif
                    <div class="bg-white rounded-xl shadow-2xl shadow-black/20 p-3 border border-gray-100 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background: {{ $primary }}15;">
                            <svg class="w-5 h-5" style="color: {{ $primary }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">{{ $isAr ? 'موعد جديد' : 'New Appointment' }}</p>
                            <p class="text-[10px] text-gray-400">{{ $isAr ? 'محمد علي' : 'Mohamed Ali' }} — 09:00 AM</p>
                        </div>
                        <span class="text-[10px] font-bold text-white px-2 py-1 rounded-lg" style="background: {{ $primary }};">{{ $isAr ? 'الآن' : 'Now' }}</span>
                    </div>
                </div>

                {{-- Browser mockup --}}
                <div class="relative bg-white rounded-2xl md:rounded-3xl overflow-hidden border border-white/20 shadow-2xl shadow-black/30">
                    <div class="bg-gray-100 border-b border-gray-200 px-5 py-3.5 flex items-center gap-4">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        </div>
                        <div class="flex-1 bg-white rounded-lg px-4 py-1.5 text-xs text-gray-400 font-medium text-center border border-gray-200 flex items-center justify-center gap-2">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                            {{ strtolower($clinic->slug) }}.healthyhub.care
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 md:p-6 min-h-[280px] md:min-h-[360px]">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h2 class="text-sm md:text-base font-bold text-gray-900">{{ $isAr ? 'لوحة التحكم' : 'Dashboard' }}</h2>
                                <p class="text-[10px] md:text-xs text-gray-400">{{ $name }}</p>
                            </div>
                            <div class="w-8 h-8 brand-gradient rounded-lg flex items-center justify-center text-[10px] font-bold text-white">{{ mb_substr($name, 0, 1) }}</div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                            <div class="bg-white rounded-xl p-3 md:p-4 border border-gray-100">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-2" style="background: {{ $primary }}10;"><svg class="w-4 h-4" style="color: {{ $primary }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                                <p class="text-lg md:text-xl font-black text-gray-900">248</p>
                                <p class="text-[10px] text-gray-400">{{ __('app.patients') }}</p>
                            </div>
                            <div class="bg-white rounded-xl p-3 md:p-4 border border-gray-100">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-2" style="background: {{ $secondary }}10;"><svg class="w-4 h-4" style="color: {{ $secondary }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                <p class="text-lg md:text-xl font-black text-gray-900">{{ $doctors->count() ?: 12 }}</p>
                                <p class="text-[10px] text-gray-400">{{ __('app.doctors') }}</p>
                            </div>
                            <div class="brand-gradient rounded-xl p-3 md:p-4 text-white">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mb-2"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                <p class="text-lg md:text-xl font-black">18</p>
                                <p class="text-[10px] text-white/70">{{ __('app.today_appointments') }}</p>
                            </div>
                            <div class="bg-white rounded-xl p-3 md:p-4 border border-gray-100">
                                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center mb-2"><svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                <p class="text-lg md:text-xl font-black text-gray-900">96%</p>
                                <p class="text-[10px] text-gray-400">{{ $isAr ? 'رضا المرضى' : 'Satisfaction' }}</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl p-3 md:p-4 border border-gray-100">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[10px] md:text-xs font-bold text-gray-700">{{ $isAr ? 'المواعيد هذا الأسبوع' : 'Appointments This Week' }}</span>
                                <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">+12.5%</span>
                            </div>
                            <svg viewBox="0 0 400 80" class="w-full h-auto">
                                <defs><linearGradient id="cg" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" stop-color="{{ $primary }}" stop-opacity="0.25"/><stop offset="100%" stop-color="{{ $primary }}" stop-opacity="0.01"/></linearGradient></defs>
                                <path d="M0,60 C40,50 60,35 100,40 C140,45 170,20 200,25 C240,30 270,12 310,10 C340,7 380,18 400,13 L400,80 L0,80Z" fill="url(#cg)"/>
                                <path d="M0,60 C40,50 60,35 100,40 C140,45 170,20 200,25 C240,30 270,12 310,10 C340,7 380,18 400,13" fill="none" stroke="{{ $primary }}" stroke-width="2.5" stroke-linecap="round"/>
                                <circle cx="310" cy="10" r="5" fill="{{ $primary }}" stroke="white" stroke-width="2.5"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t to-transparent pointer-events-none rounded-b-3xl" style="background: linear-gradient(to top, color-mix(in srgb, {{ $primary }} 85%, #000), transparent);"></div>
            </div>
        </div>

        {{-- Wave transition --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" class="w-full"><path d="M0 80V40C240 10 480 0 720 15C960 30 1200 60 1440 40V80H0Z" fill="white"/></svg>
        </div>
    </section>

    {{-- ===== SERVICES ===== --}}
    @if(count($services) > 0)
    <section class="py-20 sm:py-28 bg-white relative" id="services">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full text-sm font-bold mb-6 border" style="color: {{ $primary }}; background: {{ $primary }}08; border-color: {{ $primary }}15;">
                    {{ __('app.services') }}
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-4">
                    {{ $isAr ? 'خدماتنا' : 'Our' }} <span class="brand-text">{{ $isAr ? 'المتميزة' : 'Services' }}</span>
                </h2>
                <p class="text-gray-400 max-w-lg mx-auto">{{ __('app.services_subtitle') }}</p>
            </div>
            @php $serviceIcons = ['M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z','M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z','M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z']; @endphp
            @php $serviceColors = [$primary, $secondary, '#f59e0b', '#10b981', '#ec4899', '#8b5cf6']; @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($services as $idx => $service)
                @php $c = $serviceColors[$idx % count($serviceColors)]; $icon = $serviceIcons[$idx % count($serviceIcons)]; @endphp
                <div class="fade-up d{{ ($idx % 4) + 1 }} card-lift bg-white rounded-2xl p-7 border border-gray-100 group cursor-default relative overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-t-2xl" style="background: linear-gradient(90deg, {{ $c }}, {{ $c }}80);"></div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg" style="background: {{ $c }}10;">
                        <svg class="w-7 h-7" style="color: {{ $c }};" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    </div>
                    <h4 class="text-base font-bold text-gray-900 mb-2">{{ $isAr ? ($service['name_ar'] ?? $service['name_en']) : $service['name_en'] }}</h4>
                    <div class="w-10 h-0.5 rounded-full transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:w-16" style="background: {{ $c }};"></div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== DOCTORS ===== --}}
    @if($clinic->website_show_doctors && $doctors->count() > 0)
    <section class="py-20 sm:py-28 relative" id="doctors" style="background: {{ $primary }}04;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full text-sm font-bold mb-6 border" style="color: {{ $primary }}; background: {{ $primary }}08; border-color: {{ $primary }}15;">
                    {{ __('app.doctors') }}
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-4">
                    {{ $isAr ? 'أطباؤنا' : 'Meet Our' }} <span class="brand-text">{{ $isAr ? 'المتميزون' : 'Doctors' }}</span>
                </h2>
                <p class="text-gray-400 max-w-lg mx-auto">{{ __('app.doctors_subtitle') }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($doctors as $idx => $doctor)
                @php $cardColors = [$primary, $secondary, '#f59e0b', '#10b981', '#ec4899', '#8b5cf6']; $cc = $cardColors[$idx % count($cardColors)]; @endphp
                <div class="fade-up d{{ ($idx % 4) + 1 }} card-lift bg-white rounded-2xl border border-gray-100 overflow-hidden group shadow-sm">
                    <div class="h-2 w-full" style="background: linear-gradient(90deg, {{ $primary }}, {{ $secondary }});"></div>
                    <div class="h-20 relative overflow-hidden" style="background: linear-gradient(135deg, {{ $primary }}08, {{ $secondary }}08);">
                        <div class="absolute inset-0 opacity-[0.07]" style="background-image:radial-gradient(circle, {{ $primary }} 1px, transparent 1px);background-size:20px 20px;"></div>
                    </div>
                    <div class="px-6 pb-6 -mt-10 text-center relative z-10">
                        <div class="relative w-20 h-20 mx-auto mb-4">
                            <div class="absolute -inset-1 rounded-full opacity-0 group-hover:opacity-30 blur-sm transition-opacity duration-500" style="background: {{ $cc }};"></div>
                            <div class="relative w-20 h-20 rounded-full flex items-center justify-center border-[3px] border-white shadow-lg overflow-hidden bg-white" style="background: {{ $primary }}05;">
                                @if($doctor->user && $doctor->user->avatar)
                                <img src="{{ Storage::url($doctor->user->avatar) }}" alt="{{ $doctor->name }}" class="w-full h-full rounded-full object-cover">
                                @else
                                <svg class="w-10 h-10" style="color: {{ $primary }}40;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                            </div>
                        </div>
                        <h4 class="text-base font-bold text-gray-900">{{ $doctor->name }}</h4>
                        @if($doctor->specialty)
                        <p class="text-xs font-semibold mt-1" style="color: {{ $primary }};">{{ $isAr ? ($doctor->specialty->name_ar ?? '') : ($doctor->specialty->name_en ?? '') }}</p>
                        @endif
                        <div class="inline-flex items-center gap-1.5 mt-2.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100">
                            <span class="relative flex h-2 w-2"><span class="animate-ping absolute h-full w-full rounded-full bg-emerald-400 opacity-60"></span><span class="relative rounded-full h-2 w-2 bg-emerald-500"></span></span>
                            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">{{ $isAr ? 'متاح' : 'Available' }}</span>
                        </div>
                        @if($doctor->bio)
                        <p class="text-xs text-gray-400 mt-3 line-clamp-2 leading-relaxed">{{ $doctor->bio }}</p>
                        @endif
                        @if($doctor->consultation_fee)
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">{{ __('app.consultation_fee') }}</span>
                                <p class="text-xl font-extrabold brand-text">{{ number_format($doctor->consultation_fee) }} <span class="text-xs">{{ __('app.egp') }}</span></p>
                            </div>
                            @if($clinic->website_show_booking)
                            <a href="#booking" class="px-4 py-2 text-xs font-bold text-white rounded-xl brand-gradient shadow-md hover:opacity-90 transition-all" style="box-shadow: 0 4px 12px {{ $primary }}25;">
                                {{ __('app.book_now') }}
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== ABOUT ===== --}}
    @if($about)
    <section class="py-20 sm:py-28 bg-white relative" id="about">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                {{-- Visual side --}}
                <div class="fade-left hidden lg:block">
                    <div class="relative">
                        @if($clinic->website_hero_image)
                        <div class="absolute -inset-4 rounded-3xl blur-2xl opacity-10" style="background: {{ $primary }};"></div>
                        <img src="{{ Storage::url($clinic->website_hero_image) }}" alt="{{ $name }}" class="relative w-full h-[380px] object-cover rounded-3xl border-2 shadow-xl" style="border-color: {{ $primary }}10;">
                        @else
                        <div class="absolute -inset-4 rounded-3xl blur-2xl opacity-10" style="background: {{ $primary }};"></div>
                        <div class="relative w-full h-[380px] rounded-3xl overflow-hidden" style="background: linear-gradient(135deg, color-mix(in srgb, {{ $primary }} 85%, #000), color-mix(in srgb, {{ $primary }} 70%, #0a0a1a));">
                            <div class="absolute inset-0 opacity-[0.07]" style="background-image:linear-gradient(color-mix(in srgb, {{ $primary }} 50%, white) 1px,transparent 1px),linear-gradient(90deg,color-mix(in srgb, {{ $primary }} 50%, white) 1px,transparent 1px);background-size:40px 40px"></div>
                            <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-[300px] h-[200px] rounded-full blur-[80px]" style="background: {{ $primary }}30;"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="w-20 h-20 brand-gradient rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl" style="box-shadow: 0 8px 24px {{ $primary }}40;">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <p class="text-white/60 text-sm font-bold">{{ $name }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                {{-- Text side --}}
                <div class="fade-right {{ $isAr ? 'text-right' : 'text-left' }}">
                    <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full text-sm font-bold mb-6 border" style="color: {{ $primary }}; background: {{ $primary }}08; border-color: {{ $primary }}15;">
                        {{ __('app.about_clinic') }}
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-6 tracking-tight leading-tight">
                        {{ $isAr ? 'تعرف على' : 'Learn About' }} <span class="brand-text">{{ $name }}</span>
                    </h2>
                    <p class="text-base text-gray-500 leading-[1.9] whitespace-pre-line mb-8">{{ $about }}</p>
                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0" style="background: {{ $primary }}10;">
                                <svg class="w-3.5 h-3.5" style="color: {{ $primary }};" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">{{ $isAr ? 'فريق أسنان متخصص وذو خبرة' : 'Experienced & specialized dental team' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0" style="background: {{ $primary }}10;">
                                <svg class="w-3.5 h-3.5" style="color: {{ $primary }};" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">{{ $isAr ? 'أحدث أجهزة وتقنيات طب الأسنان' : 'Latest dental equipment & technologies' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0" style="background: {{ $primary }}10;">
                                <svg class="w-3.5 h-3.5" style="color: {{ $primary }};" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">{{ $isAr ? 'حجز سهل ومريح أونلاين' : 'Easy & convenient online booking' }}</span>
                        </div>
                    </div>
                    @if($clinic->phone)
                    <a href="tel:{{ $clinic->phone }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white rounded-xl brand-gradient shadow-lg hover:opacity-90 transition-all" style="box-shadow: 0 4px 14px {{ $primary }}30;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ __('app.call_us') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ===== BOOKING ===== --}}
    @if($clinic->website_show_booking)
    <section class="py-20 sm:py-28 relative" id="booking" style="background: {{ $primary }}04;"
             x-data="{
                 submitted: {{ session('booking_success') ? 'true' : 'false' }},
                 selectedDoctor: '{{ old('doctor_id', '') }}',
                 selectedDate: '{{ old('appointment_date', '') }}',
                 selectedTime: '{{ old('appointment_time', '') }}',
                 slots: [],
                 slotsLoaded: false,
                 slotsLoading: false,
                 onLeave: false,
                 minDate: new Date().toISOString().split('T')[0],
                 async fetchSlots() {
                     this.slots = [];
                     this.slotsLoaded = false;
                     this.onLeave = false;
                     this.selectedTime = '';
                     if (!this.selectedDoctor || !this.selectedDate) return;
                     this.slotsLoading = true;
                     try {
                         const res = await fetch(`/clinic/{{ $clinic->slug }}/slots?doctor_id=${this.selectedDoctor}&date=${this.selectedDate}`);
                         if (res.ok) {
                             const data = await res.json();
                             this.slots = data.slots || [];
                             this.onLeave = data.on_leave || false;
                         }
                     } catch(e) {}
                     this.slotsLoading = false;
                     this.slotsLoaded = true;
                 }
             }">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 fade-up">
                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full text-sm font-bold mb-6 border" style="color: {{ $primary }}; background: {{ $primary }}08; border-color: {{ $primary }}15;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('app.online_booking') }}
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-3">{{ __('app.book_your_appointment') }}</h2>
                <p class="text-gray-400 text-sm max-w-md mx-auto">{{ __('app.booking_desc') }}</p>
            </div>

            {{-- Success --}}
            <div x-show="submitted" x-transition class="fade-up mb-8">
                <div class="rounded-2xl p-8 text-center border" style="background: {{ $primary }}06; border-color: {{ $primary }}20;">
                    <div class="w-14 h-14 rounded-full mx-auto mb-4 flex items-center justify-center" style="background: {{ $primary }}12;">
                        <svg class="w-7 h-7" style="color: {{ $primary }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('app.booking_success_title') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('app.booking_success_desc') }}</p>
                </div>
            </div>

            {{-- Form --}}
            <div x-show="!submitted" class="fade-up d2">
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xl shadow-gray-200/30 overflow-hidden">
                    <div class="brand-gradient px-6 sm:px-8 py-5 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-sm">{{ __('app.book_your_appointment') }}</h4>
                            <p class="text-white/60 text-xs">{{ $isAr ? 'املأ البيانات لحجز موعدك' : 'Fill in details to book' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('clinic.book', $clinic->slug) }}" class="p-6 sm:p-8 space-y-5">
                        @csrf

                        @if(session('booking_error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('booking_error') }}</div>
                        @endif

                        @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.choose_doctor') }} <span class="text-red-500">*</span></label>
                            <select name="doctor_id" x-model="selectedDoctor" @change="fetchSlots()" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:ring-2 transition-all outline-none" style="--tw-ring-color: {{ $primary }}30;">
                                <option value="">{{ __('app.select_doctor') }}</option>
                                @foreach($clinic->doctors->where('is_active', true) as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }} — {{ $doctor->specialty->{'name_' . $locale} ?? '' }}
                                    @if($doctor->consultation_fee) ({{ number_format($doctor->consultation_fee, 0) }} {{ __('app.currency') }}) @endif
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.patient_name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="patient_name" value="{{ old('patient_name') }}" required
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:ring-2 transition-all outline-none" style="--tw-ring-color: {{ $primary }}30;"
                                       placeholder="{{ __('app.full_name') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.phone') }} <span class="text-red-500">*</span></label>
                                <input type="tel" name="patient_phone" value="{{ old('patient_phone') }}" required dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:ring-2 transition-all outline-none" style="--tw-ring-color: {{ $primary }}30;"
                                       placeholder="+20xxxxxxxxxx">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.preferred_date') }} <span class="text-red-500">*</span></label>
                            <input type="date" name="appointment_date" x-model="selectedDate" @change="fetchSlots()" @input="fetchSlots()" required :min="minDate"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:ring-2 transition-all outline-none" style="--tw-ring-color: {{ $primary }}30;">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.preferred_time') }} <span class="text-red-500">*</span></label>
                            <input type="hidden" name="appointment_time" :value="selectedTime">

                            <template x-if="slotsLoading">
                                <div class="flex justify-center py-6">
                                    <svg class="animate-spin w-6 h-6" style="color: {{ $primary }};" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                </div>
                            </template>

                            <template x-if="onLeave && !slotsLoading">
                                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700 flex items-center gap-2">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $isAr ? 'الدكتور في إجازة في التاريخ ده' : 'Doctor is on leave on this date' }}
                                </div>
                            </template>

                            <template x-if="!onLeave && slotsLoaded && !slotsLoading && slots.length === 0">
                                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-sm text-amber-700 flex items-center gap-2">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $isAr ? 'مفيش مواعيد متاحة في اليوم ده' : 'No available slots on this day' }}
                                </div>
                            </template>

                            <template x-if="!onLeave && !slotsLoading && slots.length > 0">
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 max-h-52 overflow-y-auto p-1">
                                    <template x-for="slot in slots" :key="slot.time">
                                        <button type="button" @click="slot.available && (selectedTime = slot.time)"
                                                :disabled="!slot.available"
                                                :class="{
                                                    'text-white shadow-md': selectedTime === slot.time,
                                                    'bg-gray-50 text-gray-700 border-gray-200 hover:border-gray-400': selectedTime !== slot.time && slot.available,
                                                    'bg-gray-100 text-gray-300 border-gray-100 cursor-not-allowed line-through': !slot.available
                                                }"
                                                :style="selectedTime === slot.time ? 'background: {{ $primary }}; border-color: {{ $primary }};' : ''"
                                                class="px-2 py-2.5 text-xs font-semibold rounded-xl border-2 transition-all text-center"
                                                x-text="slot.label"></button>
                                    </template>
                                </div>
                            </template>

                            <template x-if="!slotsLoaded && !slotsLoading && !onLeave">
                                <p class="text-xs text-gray-400 py-3 text-center">{{ $isAr ? 'اختر الدكتور والتاريخ لعرض المواعيد المتاحة' : 'Select doctor & date to see available slots' }}</p>
                            </template>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.notes') }}</label>
                            <textarea name="notes" rows="3"
                                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:ring-2 transition-all resize-none outline-none" style="--tw-ring-color: {{ $primary }}30;"
                                      placeholder="{{ __('app.booking_notes_placeholder') }}">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-full py-3.5 text-white text-sm font-bold rounded-xl brand-gradient shadow-lg hover:opacity-90 hover:shadow-xl transition-all flex items-center justify-center gap-2"
                                style="box-shadow: 0 4px 14px {{ $primary }}30;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ __('app.confirm_booking') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ===== REVIEWS ===== --}}
    @if($totalReviews > 0)
    <section class="py-20 sm:py-28 bg-white relative" id="reviews">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full text-sm font-bold mb-6 border" style="color: {{ $primary }}; background: {{ $primary }}08; border-color: {{ $primary }}15;">
                    {{ __('app.reviews') }}
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-4">{{ __('app.patient_reviews') }}</h2>
                <div class="flex items-center justify-center gap-3 mt-4">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        @endfor
                    </div>
                    <span class="text-2xl font-black text-gray-900">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-sm text-gray-400">({{ $totalReviews }} {{ __('app.reviews') }})</span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($reviews as $review)
                <div class="fade-up card-lift bg-white rounded-2xl border border-gray-100 p-6 relative shadow-sm">
                    <div class="absolute top-4 {{ $isAr ? 'left-4' : 'right-4' }} text-5xl font-serif leading-none opacity-[0.06]" style="color: {{ $primary }};">"</div>
                    <div class="flex items-center gap-0.5 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        @endfor
                    </div>
                    @if($review->comment)
                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">{{ Str::limit($review->comment, 150) }}</p>
                    @endif
                    <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0 brand-gradient">
                            {{ mb_substr($review->patient->name ?? '?', 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $review->patient->name ?? __('app.patient') }}</p>
                            <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== BRANCHES MAP ===== --}}
    @php $mappableBranches = $branches->filter(fn($b) => $b->latitude && $b->longitude); @endphp
    @if($mappableBranches->count() > 0)
    <section class="py-20 sm:py-28" id="map" style="background: {{ $primary }}04;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 fade-up">
                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full text-sm font-bold mb-6 border" style="color: {{ $primary }}; background: {{ $primary }}08; border-color: {{ $primary }}15;">
                    {{ __('app.location_on_map') }}
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mb-4">{{ __('app.our_branches') }}</h2>
            </div>
            <div class="fade-up rounded-2xl overflow-hidden border border-gray-200 shadow-lg" style="height: 400px;">
                <div id="clinic-branches-map" class="w-full h-full"></div>
            </div>
        </div>
    </section>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const branches = @php echo json_encode($mappableBranches->map(function($b) { return ['name' => $b->name, 'lat' => $b->latitude, 'lng' => $b->longitude, 'address' => $b->address, 'phone' => $b->phone, 'is_main' => $b->is_main]; })->values()); @endphp;
        if (!branches.length) return;
        const map = L.map('clinic-branches-map').setView([branches[0].lat, branches[0].lng], branches.length > 1 ? 10 : 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
        const bounds = [];
        branches.forEach(b => {
            const marker = L.marker([b.lat, b.lng]).addTo(map);
            marker.bindPopup(`<b>${b.name}</b>${b.is_main ? ' <span style="color:{{ $primary }};font-size:11px;">({{ __("app.main") }})</span>' : ''}<br>${b.address || ''}${b.phone ? '<br><a href="tel:'+b.phone+'">'+b.phone+'</a>' : ''}`);
            bounds.push([b.lat, b.lng]);
        });
        if (bounds.length > 1) map.fitBounds(bounds, { padding: [50, 50] });
    });
    </script>
    @endif

    {{-- ===== CONTACT ===== --}}
    <section class="py-20 sm:py-28 bg-white" id="contact">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full text-sm font-bold mb-6 border" style="color: {{ $primary }}; background: {{ $primary }}08; border-color: {{ $primary }}15;">
                    {{ __('app.contact_info') }}
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-4">{{ __('app.contact_info') }}</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10 fade-up">
                @if($clinic->phone)
                <a href="tel:{{ $clinic->phone }}" class="card-lift bg-white rounded-2xl border border-gray-100 p-6 text-center shadow-sm group">
                    <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center transition-all group-hover:scale-110" style="background: {{ $primary }}10;">
                        <svg class="w-6 h-6" style="color: {{ $primary }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $isAr ? 'اتصل بنا' : 'Call Us' }}</h4>
                    <p class="text-sm text-gray-500 font-medium" dir="ltr">{{ $clinic->phone }}</p>
                </a>
                @endif
                @if($clinic->email)
                <a href="mailto:{{ $clinic->email }}" class="card-lift bg-white rounded-2xl border border-gray-100 p-6 text-center shadow-sm group">
                    <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center transition-all group-hover:scale-110" style="background: {{ $secondary }}10;">
                        <svg class="w-6 h-6" style="color: {{ $secondary }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $isAr ? 'البريد الإلكتروني' : 'Email Us' }}</h4>
                    <p class="text-sm text-gray-500 font-medium" dir="ltr">{{ $clinic->email }}</p>
                </a>
                @endif
                @if($address)
                <div class="card-lift bg-white rounded-2xl border border-gray-100 p-6 text-center shadow-sm group">
                    <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center transition-all group-hover:scale-110 bg-emerald-50">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $isAr ? 'العنوان' : 'Address' }}</h4>
                    <p class="text-sm text-gray-500 font-medium">{{ $address }}{{ $clinic->city ? ', ' . $clinic->city : '' }}</p>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Working Hours --}}
                <div class="fade-up card-lift bg-white rounded-2xl border border-gray-100 p-7 shadow-sm">
                    <div class="flex items-center gap-3.5 mb-7">
                        <div class="w-11 h-11 rounded-xl brand-gradient flex items-center justify-center shadow-md" style="box-shadow: 0 4px 14px {{ $primary }}25;">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">{{ __('app.working_hours') }}</h4>
                            <p class="text-xs text-gray-400">{{ __('app.daily_hours') }}</p>
                        </div>
                    </div>
                    @if($clinic->working_hours_from && $clinic->working_hours_to)
                    <div class="flex items-center justify-between py-3.5 px-4 bg-gray-50 rounded-xl mb-5">
                        <span class="text-sm text-gray-500 font-medium">{{ __('app.daily_hours') }}</span>
                        <span class="text-sm font-extrabold text-gray-900" dir="ltr">{{ $clinic->working_hours_from }} — {{ $clinic->working_hours_to }}</span>
                    </div>
                    @endif
                    @if($clinic->working_days)
                    <div class="flex flex-wrap gap-2 mb-5">
                        @php $dayNames = ['sat'=>$isAr?'السبت':'Sat','sun'=>$isAr?'الأحد':'Sun','mon'=>$isAr?'الاثنين':'Mon','tue'=>$isAr?'الثلاثاء':'Tue','wed'=>$isAr?'الأربعاء':'Wed','thu'=>$isAr?'الخميس':'Thu','fri'=>$isAr?'الجمعة':'Fri']; @endphp
                        @foreach($dayNames as $key => $dayName)
                        <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold {{ in_array($key, $clinic->working_days) ? 'text-white shadow-sm' : 'bg-gray-100 text-gray-300' }}"
                              @if(in_array($key, $clinic->working_days)) style="background: {{ $primary }}; box-shadow: 0 2px 8px {{ $primary }}25;" @endif>{{ $dayName }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Branches --}}
                @if($branches->count() > 0)
                <div class="fade-up d2 card-lift bg-white rounded-2xl border border-gray-100 p-7 shadow-sm">
                    <div class="flex items-center gap-3.5 mb-7">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-md" style="background: {{ $secondary }}; box-shadow: 0 4px 14px {{ $secondary }}25;">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">{{ __('app.our_branches') }}</h4>
                            <p class="text-xs text-gray-400">{{ $branches->count() }} {{ __('app.branches') }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        @foreach($branches as $branch)
                        <div class="p-4 rounded-xl border border-gray-100 hover:border-gray-200 hover:shadow-sm transition-all duration-300">
                            <div class="flex items-center gap-2.5 mb-1.5">
                                <span class="w-2 h-2 rounded-full flex-shrink-0" style="background: {{ $primary }};"></span>
                                <h5 class="text-sm font-bold text-gray-900">{{ $branch->name }}</h5>
                                @if($branch->is_main)<span class="px-2 py-0.5 rounded-md text-[10px] font-bold text-white" style="background: {{ $primary }};">{{ __('app.main') }}</span>@endif
                            </div>
                            @if($branch->address)<p class="text-xs text-gray-400 mb-1 {{ $isAr ? 'mr-4.5' : 'ml-4.5' }}">{{ $branch->address }}{{ $branch->city ? ', ' . $branch->city : '' }}</p>@endif
                            @if($branch->phone)<a href="tel:{{ $branch->phone }}" class="text-xs font-semibold {{ $isAr ? 'mr-4.5' : 'ml-4.5' }} hover:underline" style="color: {{ $primary }};" dir="ltr">{{ $branch->phone }}</a>@endif
                            @if($branch->latitude && $branch->longitude)
                            <a href="https://www.google.com/maps?q={{ $branch->latitude }},{{ $branch->longitude }}" target="_blank" class="inline-flex items-center gap-1 {{ $isAr ? 'mr-4.5' : 'ml-4.5' }} mt-1 text-xs font-semibold hover:underline" style="color: {{ $secondary }};">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ __('app.view_on_map') }}
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ===== HEALTHYHUB PROMO ===== --}}
    <section class="py-16 sm:py-20" style="background: {{ $primary }}04;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="fade-up relative overflow-hidden rounded-3xl" style="background: linear-gradient(135deg, color-mix(in srgb, {{ $primary }} 85%, #000), color-mix(in srgb, {{ $primary }} 70%, #0a0a1a), color-mix(in srgb, {{ $primary }} 85%, #000));">
                <div class="absolute inset-0 opacity-[0.07]" style="background-image:linear-gradient(color-mix(in srgb, {{ $primary }} 50%, white) 1px,transparent 1px),linear-gradient(90deg,color-mix(in srgb, {{ $primary }} 50%, white) 1px,transparent 1px);background-size:40px 40px"></div>
                <div class="absolute top-0 {{ $isAr ? 'left' : 'right' }}-0 w-72 h-72 rounded-full blur-[100px]" style="background: {{ $primary }}20;"></div>
                <div class="absolute bottom-0 {{ $isAr ? 'right' : 'left' }}-0 w-56 h-56 rounded-full blur-[80px]" style="background: {{ $secondary }}15;"></div>
                <div class="relative px-8 py-14 sm:px-14 sm:py-16 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl brand-gradient mb-7 shadow-xl" style="box-shadow: 0 8px 24px {{ $primary }}40;">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white mb-3 tracking-tight">{{ __('app.healthyhub_promo_title') }}</h3>
                    <p class="text-sm max-w-lg mx-auto mb-8 leading-relaxed" style="color: color-mix(in srgb, {{ $primary }} 40%, white); opacity: 0.5;">{{ __('app.healthyhub_promo_desc') }}</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ route('register.clinic') }}" class="group w-full sm:w-auto flex items-center justify-center gap-2 px-7 py-3.5 text-white font-bold rounded-xl shadow-xl hover:shadow-2xl hover:scale-[1.02] transition-all text-sm brand-gradient" style="box-shadow: 0 6px 24px {{ $primary }}35;">
                            {{ __('app.join_healthyhub') }}
                            <svg class="w-4 h-4 rtl:rotate-180 group-hover:translate-x-0.5 rtl:group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('home') }}" class="w-full sm:w-auto flex items-center justify-center gap-2 px-7 py-3.5 font-bold rounded-xl border hover:text-white transition-all text-sm" style="color: color-mix(in srgb, {{ $primary }} 40%, white); border-color: color-mix(in srgb, {{ $primary }} 50%, transparent);">
                            {{ __('app.learn_more') }}
                        </a>
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-5 mt-8 pt-8" style="border-top: 1px solid color-mix(in srgb, {{ $primary }} 30%, transparent);">
                        <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-xs font-medium" style="color: color-mix(in srgb, {{ $primary }} 40%, white); opacity: 0.5;">{{ __('app.promo_free_setup') }}</span></div>
                        <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-xs font-medium" style="color: color-mix(in srgb, {{ $primary }} 40%, white); opacity: 0.5;">{{ __('app.promo_own_website') }}</span></div>
                        <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-xs font-medium" style="color: color-mix(in srgb, {{ $primary }} 40%, white); opacity: 0.5;">{{ __('app.promo_full_system') }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="py-14 text-white" style="background: #1a1a2e;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        @if($clinic->logo)
                        <img src="{{ Storage::url($clinic->logo) }}" alt="{{ $name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-white/10">
                        @else
                        <div class="w-10 h-10 rounded-xl brand-gradient flex items-center justify-center"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                        @endif
                        <div>
                            <span class="text-sm font-bold">{{ $name }}</span>
                            <p class="text-[10px] text-gray-500">{{ $specialtyName }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed max-w-xs">{{ $about ? Str::limit($about, 120) : '' }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-bold mb-4">{{ $isAr ? 'روابط سريعة' : 'Quick Links' }}</h5>
                    <div class="space-y-2">
                        @if($about)<a href="#about" class="block text-xs text-gray-500 hover:text-white transition-colors">{{ __('app.about_clinic') }}</a>@endif
                        @if(count($services) > 0)<a href="#services" class="block text-xs text-gray-500 hover:text-white transition-colors">{{ __('app.our_services') }}</a>@endif
                        @if($clinic->website_show_doctors && $doctors->count() > 0)<a href="#doctors" class="block text-xs text-gray-500 hover:text-white transition-colors">{{ __('app.our_doctors') }}</a>@endif
                        @if($clinic->website_show_booking)<a href="#booking" class="block text-xs text-gray-500 hover:text-white transition-colors">{{ __('app.online_booking') }}</a>@endif
                        <a href="#contact" class="block text-xs text-gray-500 hover:text-white transition-colors">{{ __('app.contact_info') }}</a>
                    </div>
                </div>
                <div>
                    <h5 class="text-sm font-bold mb-4">{{ $isAr ? 'تواصل معنا' : 'Connect With Us' }}</h5>
                    @if(count($socials) > 0)
                    <div class="flex items-center gap-2.5 mb-4">
                        @if(!empty($socials['facebook']))<a href="{{ $socials['facebook'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center transition-all hover:scale-110"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>@endif
                        @if(!empty($socials['instagram']))<a href="{{ $socials['instagram'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center transition-all hover:scale-110"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>@endif
                        @if(!empty($socials['whatsapp']))<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socials['whatsapp']) }}" target="_blank" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center transition-all hover:scale-110"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></a>@endif
                        @if(!empty($socials['tiktok']))<a href="{{ $socials['tiktok'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center transition-all hover:scale-110"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg></a>@endif
                    </div>
                    @endif
                    @if($clinic->phone)
                    <a href="tel:{{ $clinic->phone }}" class="inline-flex items-center gap-2 text-xs text-gray-500 hover:text-white transition-colors" dir="ltr">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $clinic->phone }}
                    </a>
                    @endif
                </div>
            </div>
            <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-700 to-transparent mb-8"></div>
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} {{ $name }}. {{ __('app.all_rights_reserved') }}</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 transition-all group">
                    <span class="text-[11px] text-gray-500 group-hover:text-gray-300">{{ __('app.powered_by') }}</span>
                    <span class="text-xs font-bold brand-text">Healthy Hub</span>
                </a>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => { e.preventDefault(); const t = document.querySelector(a.getAttribute('href')); if (t) t.scrollIntoView({ behavior: 'smooth' }); });
        });
    </script>
</body>
</html>