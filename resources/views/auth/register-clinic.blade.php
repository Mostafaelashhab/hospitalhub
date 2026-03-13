<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('app.register_clinic') }} - {{ __('app.app_name') }}</title>
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
        $hasStep2Errors = $errors->hasAny(['doctors_count', 'clinic_size', 'working_hours_from']);
        $initialStep = $hasStep3Errors ? 3 : ($hasStep2Errors ? 2 : 1);
    @endphp

    <div class="min-h-screen flex" x-data="{ step: {{ $initialStep }}, loading: false, hasSystem: {{ old('has_existing_system', false) ? 'true' : 'false' }}, isSolo: {{ old('is_solo_doctor', '0') == '1' ? 'true' : 'false' }} }">

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

                {{-- Dynamic content based on step --}}
                <div class="space-y-8">
                    <div>
                        <h1 class="text-4xl xl:text-5xl font-extrabold leading-tight mb-4">
                            <span x-show="step === 1">
                                @if(app()->getLocale() === 'ar') ابدأ رحلتك<br>في إدارة عيادتك @else Start Your<br>Clinic Journey @endif
                            </span>
                            <span x-show="step === 2" x-cloak>
                                @if(app()->getLocale() === 'ar') خلينا نعرف<br>عيادتك أكتر @else Tell Us More<br>About Your Clinic @endif
                            </span>
                            <span x-show="step === 3" x-cloak>
                                @if(app()->getLocale() === 'ar') خطوة أخيرة<br>لتفعيل حسابك @else Last Step<br>To Activate @endif
                            </span>
                        </h1>
                        <p class="text-lg text-white/70 max-w-md">{{ __('app.hero_subtitle') }}</p>
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
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-white/80">{{ __('app.feature_multi_lang') }}</span>
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
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition">{{ __('app.login') }}</a>
                </div>
            </div>

            <div class="flex-1 flex items-start justify-center px-6 py-4 lg:px-12 overflow-y-auto">
                <div class="w-full max-w-lg">

                    <div class="mb-6">
                        <h2 class="text-3xl font-bold mb-2">{{ __('app.register_clinic') }}</h2>
                        <p class="text-gray-400">
                            @if(app()->getLocale() === 'ar') سجّل عيادتك في دقائق وابدأ في إدارة مرضاك @else Register your clinic in minutes and start managing your patients @endif
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

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.specialty') }} <span class="text-red-400">*</span></label>
                                        <select name="specialty_id" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                            <option value="">{{ __('app.select_specialty') }}</option>
                                            @foreach($specialties as $specialty)
                                                <option value="{{ $specialty->id }}" {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                                    {{ app()->getLocale() === 'ar' ? $specialty->name_ar : $specialty->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('specialty_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.clinic_phone') }} <span class="text-red-400">*</span></label>
                                        <input type="tel" name="clinic_phone" value="{{ old('clinic_phone') }}" placeholder="01xxxxxxxxx"
                                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                        @error('clinic_phone') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
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

                                {{-- Solo Doctor Toggle --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('app.is_solo_doctor') }}</label>
                                    <p class="text-xs text-gray-500 mb-3">{{ __('app.is_solo_doctor_desc') }}</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="is_solo_doctor" value="1" class="peer hidden" @click="isSolo = true" {{ old('is_solo_doctor') == '1' ? 'checked' : '' }}>
                                            <div class="peer-checked:border-indigo-500 peer-checked:bg-indigo-600/10 border border-gray-700 rounded-xl p-4 text-center transition hover:border-gray-600">
                                                <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-gray-800 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                </div>
                                                <div class="text-sm font-medium text-white">{{ __('app.solo_practice') }}</div>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="is_solo_doctor" value="0" class="peer hidden" @click="isSolo = false" {{ old('is_solo_doctor', '0') == '0' ? 'checked' : '' }}>
                                            <div class="peer-checked:border-indigo-500 peer-checked:bg-indigo-600/10 border border-gray-700 rounded-xl p-4 text-center transition hover:border-gray-600">
                                                <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-gray-800 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </div>
                                                <div class="text-sm font-medium text-white">{{ __('app.multi_doctor') }}</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Clinic Size (multi-doctor only) --}}
                                <div x-show="!isSolo" x-transition x-cloak>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('app.clinic_size') }}</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach(['small' => 'size_small', 'medium' => 'size_medium', 'large' => 'size_large'] as $value => $label)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="clinic_size" value="{{ $value }}" class="peer hidden" {{ old('clinic_size') === $value ? 'checked' : '' }}>
                                            <div class="peer-checked:border-indigo-500 peer-checked:bg-indigo-600/10 border border-gray-700 rounded-xl p-3 text-center transition hover:border-gray-600">
                                                <div class="text-sm font-medium text-white">{{ __('app.' . $label) }}</div>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Doctors count (multi-doctor only) --}}
                                <div x-show="!isSolo" x-transition x-cloak>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.doctors_count') }}</label>
                                    <input type="number" name="doctors_count" value="{{ old('doctors_count') }}" min="1" max="100" placeholder="3"
                                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('app.expected_patients') }}</label>
                                    <input type="number" name="expected_patients_monthly" value="{{ old('expected_patients_monthly') }}" min="1" placeholder="200"
                                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                </div>

                                {{-- Working Schedule --}}
                                <div x-data="{
                                    days: [
                                        { key: 'sat', label: '{{ __("app.day_sat") }}', active: true, from: '09:00', to: '17:00' },
                                        { key: 'sun', label: '{{ __("app.day_sun") }}', active: true, from: '09:00', to: '17:00' },
                                        { key: 'mon', label: '{{ __("app.day_mon") }}', active: true, from: '09:00', to: '17:00' },
                                        { key: 'tue', label: '{{ __("app.day_tue") }}', active: true, from: '09:00', to: '17:00' },
                                        { key: 'wed', label: '{{ __("app.day_wed") }}', active: true, from: '09:00', to: '17:00' },
                                        { key: 'thu', label: '{{ __("app.day_thu") }}', active: true, from: '09:00', to: '17:00' },
                                        { key: 'fri', label: '{{ __("app.day_fri") }}', active: false, from: '09:00', to: '17:00' }
                                    ]
                                }">
                                    <label class="block text-sm font-medium text-gray-300 mb-3">{{ __('app.working_hours') }}</label>
                                    <div class="space-y-2">
                                        <template x-for="(day, index) in days" :key="day.key">
                                            <div class="flex items-center gap-3 p-3 rounded-xl border transition-all"
                                                 :class="day.active ? 'border-gray-700 bg-gray-900/50' : 'border-gray-800 bg-gray-900/20 opacity-50'">
                                                {{-- Toggle --}}
                                                <button type="button" @click="day.active = !day.active"
                                                        class="w-10 h-6 rounded-full transition-all duration-200 relative shrink-0"
                                                        :class="day.active ? 'bg-indigo-600' : 'bg-gray-700'">
                                                    <span class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all duration-200 shadow-sm"
                                                          :class="day.active ? '{{ app()->getLocale() === "ar" ? "left-1" : "left-5" }}' : '{{ app()->getLocale() === "ar" ? "left-5" : "left-1" }}'"></span>
                                                </button>
                                                {{-- Day name --}}
                                                <span class="text-sm font-medium w-12 shrink-0" :class="day.active ? 'text-white' : 'text-gray-500'" x-text="day.label"></span>
                                                {{-- Time inputs --}}
                                                <div class="flex items-center gap-2 flex-1" x-show="day.active" x-transition>
                                                    <input type="time" x-model="day.from"
                                                           class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-2 py-1.5 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                                    <span class="text-gray-500 text-xs">→</span>
                                                    <input type="time" x-model="day.to"
                                                           class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-2 py-1.5 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                                </div>
                                                <span x-show="!day.active" class="text-xs text-gray-600 flex-1">{{ app()->getLocale() === 'ar' ? 'إجازة' : 'Off' }}</span>
                                                {{-- Hidden inputs --}}
                                                <template x-if="day.active">
                                                    <div>
                                                        <input type="hidden" :name="'schedule[' + day.key + '][from]'" :value="day.from">
                                                        <input type="hidden" :name="'schedule[' + day.key + '][to]'" :value="day.to">
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
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

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                        @error('admin_phone') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
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
