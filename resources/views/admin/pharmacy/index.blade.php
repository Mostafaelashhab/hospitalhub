<x-dashboard-layout>
    <x-slot name="title">{{ __('app.pharmacy') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.pharmacy') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'pharmacy'])
    </x-slot>

    <div x-data="pharmacyApp()" class="space-y-6">

        {{-- Header + Stats --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('app.pharmacy') }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ __('app.pharmacy_desc') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 bg-emerald-50 rounded-xl text-center">
                    <p class="text-lg font-bold text-emerald-700">{{ number_format($totalDrugs) }}</p>
                    <p class="text-[10px] text-emerald-600 font-medium">{{ __('app.total_drugs') }}</p>
                </div>
            </div>
        </div>

        {{-- How it works info --}}
        <div class="bg-gradient-to-r from-emerald-50 via-teal-50 to-cyan-50 rounded-2xl border border-emerald-200/60 p-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('app.how_pharmacy_works') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-gray-600">
                        <div class="flex items-start gap-2">
                            <span class="w-5 h-5 rounded-full bg-emerald-200 text-emerald-800 flex items-center justify-center text-[10px] font-bold shrink-0">1</span>
                            <p>{{ __('app.pharmacy_step_1') }}</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="w-5 h-5 rounded-full bg-violet-200 text-violet-800 flex items-center justify-center text-[10px] font-bold shrink-0">2</span>
                            <p>{{ __('app.pharmacy_step_2') }}</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="w-5 h-5 rounded-full bg-blue-200 text-blue-800 flex items-center justify-center text-[10px] font-bold shrink-0">3</span>
                            <p>{{ __('app.pharmacy_step_3') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Box --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6">
            <form method="GET" action="{{ route('dashboard.pharmacy.index') }}" class="space-y-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 relative">
                        <svg class="absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" value="{{ $query }}"
                               placeholder="{{ __('app.search_drug_placeholder') }}"
                               class="{{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }} w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3">
                    </div>

                    {{-- Source Toggle --}}
                    <div class="flex items-center bg-gray-100 rounded-xl p-1 gap-1">
                        <label class="flex-1">
                            <input type="radio" name="source" value="local" {{ $source === 'local' ? 'checked' : '' }} class="sr-only peer">
                            <div class="px-4 py-2.5 text-xs font-semibold text-center rounded-lg cursor-pointer transition-all peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-sm text-gray-500 hover:text-gray-700">
                                {{ __('app.local_db') }}
                            </div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="source" value="api" {{ $source === 'api' ? 'checked' : '' }} class="sr-only peer">
                            <div class="px-4 py-2.5 text-xs font-semibold text-center rounded-lg cursor-pointer transition-all peer-checked:bg-white peer-checked:text-violet-700 peer-checked:shadow-sm text-gray-500 hover:text-gray-700">
                                {{ __('app.api_search') }} <span class="text-[10px] text-violet-500">AI</span>
                            </div>
                        </label>
                    </div>

                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        {{ __('app.search') }}
                    </button>
                </div>

                @if(!$isConfigured)
                    <div class="flex items-center gap-2 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        <span class="text-xs text-amber-700">{{ __('app.drug_api_not_configured') }}</span>
                    </div>
                @endif
            </form>
        </div>

        {{-- API Result + Saved Notice --}}
        @if($source === 'api' && $apiResult)
            @if($apiResult['success'] && $savedDrug)
                {{-- Success: saved to DB --}}
                <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-emerald-100 bg-gradient-to-r from-emerald-50 to-teal-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <h3 class="text-sm font-bold text-gray-900">{{ __('app.drug_saved_to_db') }}</h3>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full">{{ __('app.saved') }}</span>
                        </div>
                        <a href="{{ route('dashboard.pharmacy.show', $savedDrug) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors">
                            {{ __('app.view_details') }}
                            <svg class="w-3 h-3 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start gap-5">
                            @if($savedDrug->image)
                                <img src="{{ filter_var($savedDrug->image, FILTER_VALIDATE_URL) ? $savedDrug->image : asset('storage/' . $savedDrug->image) }}"
                                     alt="{{ $savedDrug->name }}"
                                     class="w-20 h-20 object-contain rounded-xl border border-gray-200 bg-gray-50 p-2">
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900">{{ $savedDrug->name }}</h3>
                                @if($savedDrug->generic_name)
                                    <p class="text-sm text-gray-500 mt-0.5">{{ __('app.generic_name') }}: {{ $savedDrug->generic_name }}</p>
                                @endif
                                @if($savedDrug->description)
                                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $savedDrug->description }}</p>
                                @endif
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @if($savedDrug->drug_class)
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg">{{ $savedDrug->drug_class }}</span>
                                    @endif
                                    @if($savedDrug->manufacturer)
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-lg">{{ $savedDrug->manufacturer }}</span>
                                    @endif
                                    @if($savedDrug->price > 0)
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg">{{ number_format($savedDrug->price, 2) }} {{ __('app.egp') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($apiResult['error'] === 'rate_limit')
                <div class="bg-white rounded-2xl border border-amber-200 shadow-sm p-8 text-center">
                    <svg class="w-12 h-12 text-amber-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('app.rate_limit_reached') }}</h3>
                    <p class="text-xs text-gray-500">{{ __('app.rate_limit_desc') }}</p>
                </div>
            @elseif($apiResult['error'] === 'no_results')
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('app.no_results_found') }}</h3>
                    <p class="text-xs text-gray-500">{{ __('app.try_different_drug_name') }}</p>
                </div>
            @elseif(!$apiResult['success'])
                <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-8 text-center">
                    <svg class="w-12 h-12 text-red-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('app.api_error') }}</h3>
                    <p class="text-xs text-gray-500">{{ $apiResult['error'] }}</p>
                </div>
            @endif
        @endif

        {{-- Local Drug Database --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('app.local_drug_db') }}</h3>
                </div>
                <span class="text-xs text-gray-400">{{ $localDrugs->count() }} {{ __('app.results') }}</span>
            </div>

            @if($localDrugs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.drug_name') }}</th>
                                <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.generic_name') }}</th>
                                <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.category') }}</th>
                                <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.price') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($localDrugs as $drug)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-6 py-3.5">
                                        <div class="flex items-center gap-3">
                                            @if($drug->image)
                                                <img src="{{ filter_var($drug->image, FILTER_VALIDATE_URL) ? $drug->image : asset('storage/' . $drug->image) }}"
                                                     alt="{{ $drug->name }}"
                                                     class="w-10 h-10 rounded-lg object-contain border border-gray-200 bg-white p-0.5">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $drug->name }}</p>
                                                @if($drug->name_ar)
                                                    <p class="text-xs text-gray-400">{{ $drug->name_ar }}</p>
                                                @endif
                                                @if($drug->description)
                                                    <p class="text-xs text-gray-400 line-clamp-1 max-w-xs mt-0.5">{{ Str::limit($drug->description, 60) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        @if($drug->generic_name)
                                            <span class="text-sm text-gray-600">{{ Str::limit($drug->generic_name, 30) }}</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5">
                                        @if($drug->category_name)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-md">
                                                {{ app()->getLocale() === 'ar' && $drug->category_name_ar ? $drug->category_name_ar : $drug->category_name }}
                                            </span>
                                        @elseif($drug->drug_class)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-600 text-xs rounded-md">{{ Str::limit($drug->drug_class, 20) }}</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5">
                                        @if($drug->price > 0)
                                            <span class="font-semibold text-emerald-700">{{ number_format($drug->price, 2) }} {{ __('app.egp') }}</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('dashboard.pharmacy.show', $drug) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-medium rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                {{ __('app.view') }}
                                            </a>
                                            @if($isConfigured)
                                                <a href="{{ route('dashboard.pharmacy.index', ['q' => $drug->name, 'source' => 'api']) }}"
                                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-violet-50 hover:bg-violet-100 text-violet-700 text-xs font-medium rounded-lg transition-colors"
                                                   title="{{ __('app.refresh_from_api') }}">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                    API
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('app.no_drugs_found') }}</h3>
                    <p class="text-xs text-gray-500">{{ __('app.try_different_search') }}</p>
                </div>
            @endif
        </div>

        {{-- Quick API Lookup --}}
        @if($isConfigured)
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-purple-50">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('app.quick_drug_lookup') }}</h3>
                    <span class="text-[10px] font-bold text-violet-600 bg-violet-100 px-2 py-0.5 rounded-full">API</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ __('app.quick_lookup_desc') }}</p>
            </div>
            <div class="p-6">
                <div class="flex gap-3">
                    <input type="text" x-model="quickQuery" @keydown.enter="quickLookup()"
                           placeholder="{{ __('app.type_drug_name') }}"
                           class="flex-1 rounded-xl border-gray-200 focus:border-violet-500 focus:ring-violet-500 text-sm py-2.5">
                    <button @click="quickLookup()" :disabled="loading"
                            class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 disabled:opacity-50 text-white text-sm font-semibold rounded-xl transition-all flex items-center gap-2">
                        <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        {{ __('app.lookup') }}
                    </button>
                </div>

                {{-- Quick result --}}
                <div x-show="quickResult" x-transition class="mt-4">
                    <template x-if="quickResult && quickResult.success">
                        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-xs font-bold text-emerald-700">{{ __('app.drug_saved_to_db') }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <template x-if="quickResult.data && quickResult.data.image">
                                    <img :src="quickResult.data.image" class="w-16 h-16 object-contain rounded-lg border bg-white p-1">
                                </template>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900" x-text="quickResult.data?.name || quickQuery"></h4>
                                    <template x-if="quickResult.data?.generic_name">
                                        <p class="text-xs text-gray-500 mt-0.5"><span class="font-medium">{{ __('app.generic_name') }}:</span> <span x-text="quickResult.data.generic_name"></span></p>
                                    </template>
                                    <template x-if="quickResult.data?.description">
                                        <p class="text-sm text-gray-600 mt-2 line-clamp-2" x-text="quickResult.data.description"></p>
                                    </template>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <template x-if="quickResult.data?.drug_class">
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-md" x-text="quickResult.data.drug_class"></span>
                                        </template>
                                        <template x-if="quickResult.data?.price">
                                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-md" x-text="quickResult.data.price"></span>
                                        </template>
                                    </div>
                                    <template x-if="quickResult.saved_drug_id">
                                        <a :href="'{{ route('dashboard.pharmacy.index') }}/' + quickResult.saved_drug_id"
                                           class="inline-flex items-center gap-1 mt-3 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                            {{ __('app.view_details') }}
                                            <svg class="w-3 h-3 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="quickResult && !quickResult.success">
                        <div class="p-4 bg-red-50 rounded-xl border border-red-200 text-center">
                            <p class="text-sm text-red-700" x-text="quickResult.error === 'rate_limit' ? '{{ __('app.rate_limit_reached') }}' : (quickResult.error === 'no_results' ? '{{ __('app.no_results_found') }}' : quickResult.error)"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function pharmacyApp() {
            return {
                quickQuery: '',
                quickResult: null,
                loading: false,

                async quickLookup() {
                    if (this.quickQuery.length < 2 || this.loading) return;
                    this.loading = true;
                    this.quickResult = null;

                    try {
                        const res = await fetch(`{{ route('dashboard.pharmacy.api-lookup') }}?drug=${encodeURIComponent(this.quickQuery)}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        this.quickResult = await res.json();
                    } catch (e) {
                        this.quickResult = { success: false, error: e.message };
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
    @endpush
</x-dashboard-layout>
