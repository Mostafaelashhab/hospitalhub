<x-dashboard-layout>
    <x-slot name="title">{{ __('app.clinic_website') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.clinic_website') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'website'])
    </x-slot>

    {{-- Success --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('app.clinic_website') }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ __('app.clinic_website_desc') }}</p>
            </div>
        </div>
        @if($clinic->website_enabled)
        <a href="{{ route('clinic.page', $clinic->slug) }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:border-indigo-300 text-sm font-semibold text-gray-700 hover:text-indigo-600 rounded-xl transition-all hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            {{ __('app.view_website') }}
        </a>
        @endif
    </div>

    <form method="POST" action="{{ route('dashboard.website.update') }}" enctype="multipart/form-data"
          x-data="{
              enabled: {{ $clinic->website_enabled ? 'true' : 'false' }},
              primaryColor: '{{ old('website_primary_color', $clinic->website_primary_color ?? '#0d9488') }}',
              secondaryColor: '{{ old('website_secondary_color', $clinic->website_secondary_color ?? '#6366f1') }}',
              services: {{ json_encode(old('website_services', $clinic->website_services ?? [])) }},
              addService() { this.services.push({ name_en: '', name_ar: '', icon: 'stethoscope' }); },
              removeService(idx) { this.services.splice(idx, 1); }
          }">
        @csrf
        @method('PUT')

        <div class="space-y-6">

            {{-- 1. Enable / URL --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        {{ __('app.website_status') }}
                    </h3>
                </div>
                <div class="p-6 space-y-5">
                    <label class="flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition-all"
                           :class="enabled ? 'border-indigo-500 bg-indigo-50/50' : 'border-gray-200 bg-gray-50 hover:border-gray-300'">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors"
                                 :class="enabled ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-500'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold" :class="enabled ? 'text-indigo-900' : 'text-gray-700'">{{ __('app.enable_website') }}</p>
                                <p class="text-xs mt-0.5" :class="enabled ? 'text-indigo-600' : 'text-gray-500'">{{ __('app.enable_website_desc') }}</p>
                            </div>
                        </div>
                        <input type="hidden" name="website_enabled" value="0">
                        <input type="checkbox" name="website_enabled" value="1" x-model="enabled"
                               class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    </label>

                    <div x-show="enabled" x-transition class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <p class="text-xs font-medium text-emerald-700">
                            {{ __('app.your_website_url') }}:
                            <a href="{{ route('clinic.page', $clinic->slug) }}" target="_blank" class="underline font-bold hover:text-emerald-900" dir="ltr">
                                {{ url('/clinic/' . $clinic->slug) }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div x-show="enabled" x-transition class="space-y-6">

                {{-- 2. Branding & Colors --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            {{ __('app.branding_colors') }}
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.primary_color') }}</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" name="website_primary_color" x-model="primaryColor"
                                           class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-0.5">
                                    <input type="text" x-model="primaryColor" dir="ltr"
                                           class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.secondary_color') }}</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" name="website_secondary_color" x-model="secondaryColor"
                                           class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-0.5">
                                    <input type="text" x-model="secondaryColor" dir="ltr"
                                           class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Color preview --}}
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 border border-gray-200">
                            <span class="text-xs font-medium text-gray-500">{{ __('app.preview') }}:</span>
                            <div class="h-8 flex-1 rounded-lg" :style="'background: linear-gradient(135deg, ' + primaryColor + ', ' + secondaryColor + ')'"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.hero_image') }}</label>
                            @if($clinic->website_hero_image)
                            <div class="mb-3">
                                <img src="{{ Storage::url($clinic->website_hero_image) }}" class="h-32 rounded-xl object-cover border border-gray-200">
                            </div>
                            @endif
                            <input type="file" name="website_hero_image" accept="image/*"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.meta_description') }}</label>
                            <input type="text" name="website_meta_description" value="{{ old('website_meta_description', $clinic->website_meta_description) }}" maxlength="300"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                                   placeholder="{{ __('app.meta_description_placeholder') }}">
                        </div>
                    </div>
                </div>

                {{-- 3. About Section --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('app.about_clinic') }}
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.about_english') }}</label>
                            <textarea name="website_about_en" rows="4"
                                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none"
                                      placeholder="{{ __('app.about_placeholder') }}">{{ old('website_about_en', $clinic->website_about_en) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.about_arabic') }}</label>
                            <textarea name="website_about_ar" rows="4" dir="rtl"
                                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none"
                                      placeholder="{{ __('app.about_placeholder_ar') }}">{{ old('website_about_ar', $clinic->website_about_ar) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- 4. Services --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            {{ __('app.services') }}
                        </h3>
                        <button type="button" @click="addService()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            {{ __('app.add_service') }}
                        </button>
                    </div>
                    <div class="p-6">
                        <div x-show="services.length === 0" class="text-center py-8 text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            <p class="text-sm">{{ __('app.no_services_yet') }}</p>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(service, idx) in services" :key="idx">
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-200 group">
                                    <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold flex-shrink-0" x-text="idx + 1"></span>
                                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <input type="text" :name="'website_services[' + idx + '][name_en]'" x-model="service.name_en"
                                               class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"
                                               placeholder="Service name (EN)">
                                        <input type="text" :name="'website_services[' + idx + '][name_ar]'" x-model="service.name_ar" dir="rtl"
                                               class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"
                                               placeholder="اسم الخدمة (عربي)">
                                    </div>
                                    <input type="hidden" :name="'website_services[' + idx + '][icon]'" x-model="service.icon">
                                    <button type="button" @click="removeService(idx)"
                                            class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all opacity-0 group-hover:opacity-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- 5. Social Links --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            {{ __('app.social_links') }}
                        </h3>
                    </div>
                    <div class="p-6">
                        @php $socials = $clinic->website_social_links ?? []; @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Facebook</label>
                                <input type="url" name="website_social_links[facebook]" value="{{ old('website_social_links.facebook', $socials['facebook'] ?? '') }}" dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                                       placeholder="https://facebook.com/...">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Instagram</label>
                                <input type="url" name="website_social_links[instagram]" value="{{ old('website_social_links.instagram', $socials['instagram'] ?? '') }}" dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                                       placeholder="https://instagram.com/...">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">WhatsApp</label>
                                <input type="text" name="website_social_links[whatsapp]" value="{{ old('website_social_links.whatsapp', $socials['whatsapp'] ?? '') }}" dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                                       placeholder="+20xxxxxxxxxx">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">TikTok</label>
                                <input type="url" name="website_social_links[tiktok]" value="{{ old('website_social_links.tiktok', $socials['tiktok'] ?? '') }}" dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                                       placeholder="https://tiktok.com/@...">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 6. Display Options --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            {{ __('app.display_options') }}
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-gray-300 cursor-pointer transition-all">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">{{ __('app.show_doctors_section') }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('app.show_doctors_section_desc') }}</p>
                            </div>
                            <input type="hidden" name="website_show_doctors" value="0">
                            <input type="checkbox" name="website_show_doctors" value="1" {{ old('website_show_doctors', $clinic->website_show_doctors ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </label>
                        <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-gray-300 cursor-pointer transition-all">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">{{ __('app.show_booking_section') }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('app.show_booking_section_desc') }}</p>
                            </div>
                            <input type="hidden" name="website_show_booking" value="0">
                            <input type="checkbox" name="website_show_booking" value="1" {{ old('website_show_booking', $clinic->website_show_booking ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </label>
                    </div>
                </div>

            </div>

            {{-- Save Button --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/20 transition-all hover:shadow-indigo-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('app.save_settings') }}
                </button>
            </div>

        </div>
    </form>
</x-dashboard-layout>
