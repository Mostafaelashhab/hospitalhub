<x-dashboard-layout>
    <x-slot name="title">{{ __('app.pharmacy') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.pharmacy') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'pharmacy'])
    </x-slot>

    <div x-data="pharmacyApp()" class="space-y-6">

        {{-- Header --}}
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
                               class="{{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }} w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3"
                               x-ref="searchInput">
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

        {{-- API Result --}}
        @if($source === 'api' && $apiResult)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-purple-50">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <h3 class="text-sm font-bold text-gray-900">{{ __('app.api_drug_result') }}</h3>
                        <span class="text-[10px] font-bold text-violet-600 bg-violet-100 px-2 py-0.5 rounded-full">RapidAPI</span>
                    </div>
                </div>

                @if($apiResult['success'] && $apiResult['data'])
                    <div class="p-6">
                        @php $drug = $apiResult['data']; @endphp

                        {{-- Drug Header --}}
                        <div class="flex flex-col md:flex-row gap-6 mb-6">
                            @if(!empty($drug['image']))
                                <div class="shrink-0">
                                    <img src="{{ $drug['image'] }}" alt="{{ $drug['name'] ?? $query }}" class="w-32 h-32 object-contain rounded-xl border border-gray-200 bg-gray-50 p-2">
                                </div>
                            @endif
                            <div class="flex-1 space-y-2">
                                <h3 class="text-xl font-bold text-gray-900">{{ $drug['name'] ?? $query }}</h3>
                                @if(!empty($drug['generic_name']))
                                    <p class="text-sm text-gray-500">
                                        <span class="font-medium text-gray-700">{{ __('app.generic_name') }}:</span>
                                        {{ $drug['generic_name'] }}
                                    </p>
                                @endif
                                @if(!empty($drug['manufacturer']))
                                    <p class="text-sm text-gray-500">
                                        <span class="font-medium text-gray-700">{{ __('app.manufacturer') }}:</span>
                                        {{ $drug['manufacturer'] }}
                                    </p>
                                @endif
                                @if(!empty($drug['drug_class']) || !empty($drug['type']))
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @if(!empty($drug['drug_class']))
                                            <span class="inline-flex items-center px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg">{{ $drug['drug_class'] }}</span>
                                        @endif
                                        @if(!empty($drug['type']))
                                            <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-lg">{{ $drug['type'] }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Drug Details Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach(['description', 'indications', 'dosage', 'side_effects', 'contraindications', 'interactions', 'warnings', 'pregnancy_category', 'storage'] as $field)
                                @if(!empty($drug[$field]))
                                    <div class="p-4 bg-gray-50 rounded-xl {{ in_array($field, ['description', 'side_effects', 'warnings']) ? 'md:col-span-2' : '' }}">
                                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                            {{ __('app.drug_' . $field) }}
                                        </h4>
                                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ is_array($drug[$field]) ? implode("\n", $drug[$field]) : $drug[$field] }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- Price History --}}
                        @if(!empty($drug['price']) || !empty($drug['prices']))
                            <div class="mt-4 p-4 bg-emerald-50 rounded-xl">
                                <h4 class="text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">{{ __('app.drug_price') }}</h4>
                                @if(!empty($drug['price']))
                                    <p class="text-2xl font-bold text-emerald-800">{{ $drug['price'] }}</p>
                                @endif
                                @if(!empty($drug['prices']) && is_array($drug['prices']))
                                    <div class="space-y-1 mt-2">
                                        @foreach($drug['prices'] as $priceEntry)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-emerald-700">{{ $priceEntry['pharmacy'] ?? $priceEntry['source'] ?? '-' }}</span>
                                                <span class="font-semibold text-emerald-800">{{ $priceEntry['price'] ?? '-' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Raw data (collapsible for debugging) --}}
                        <div class="mt-4" x-data="{ showRaw: false }">
                            <button @click="showRaw = !showRaw" class="text-xs text-gray-400 hover:text-gray-600 flex items-center gap-1">
                                <svg class="w-3 h-3 transition-transform" :class="showRaw && 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                {{ __('app.raw_data') }}
                            </button>
                            <pre x-show="showRaw" x-transition class="mt-2 p-4 bg-gray-900 text-green-400 text-xs rounded-xl overflow-x-auto max-h-64">{{ json_encode($drug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                @elseif($apiResult['error'] === 'rate_limit')
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-amber-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('app.rate_limit_reached') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('app.rate_limit_desc') }}</p>
                    </div>
                @elseif($apiResult['error'] === 'no_results')
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('app.no_results_found') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('app.try_different_drug_name') }}</p>
                    </div>
                @else
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-red-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('app.api_error') }}</h3>
                        <p class="text-xs text-gray-500">{{ $apiResult['error'] }}</p>
                    </div>
                @endif
            </div>
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
                                <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.category') }}</th>
                                <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.price') }}</th>
                                <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.type') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.api_lookup') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($localDrugs as $drug)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-6 py-3.5">
                                        <div class="flex items-center gap-3">
                                            @if($drug->image)
                                                <img src="{{ asset('storage/' . $drug->image) }}" alt="{{ $drug->name }}" class="w-10 h-10 rounded-lg object-contain border border-gray-200 bg-white p-0.5">
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
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        @if($drug->category_name)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-md">
                                                {{ app()->getLocale() === 'ar' && $drug->category_name_ar ? $drug->category_name_ar : $drug->category_name }}
                                            </span>
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
                                    <td class="px-6 py-3.5">
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-md {{ $drug->is_drug ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }}">
                                            {{ $drug->is_drug ? __('app.drug') : __('app.product') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5 text-center">
                                        @if($isConfigured)
                                            <a href="{{ route('dashboard.pharmacy.index', ['q' => $drug->name, 'source' => 'api']) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-violet-50 hover:bg-violet-100 text-violet-700 text-xs font-medium rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                {{ __('app.lookup') }}
                                            </a>
                                        @else
                                            <span class="text-gray-300 text-xs">—</span>
                                        @endif
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

        {{-- Quick API Lookup (AJAX sidebar) --}}
        @if($isConfigured)
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-purple-50">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('app.quick_drug_lookup') }}</h3>
                    <span class="text-[10px] font-bold text-violet-600 bg-violet-100 px-2 py-0.5 rounded-full">AI</span>
                </div>
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
                            <div class="flex items-start gap-3">
                                <template x-if="quickResult.data && quickResult.data.image">
                                    <img :src="quickResult.data.image" class="w-16 h-16 object-contain rounded-lg border bg-white p-1">
                                </template>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900" x-text="quickResult.data?.name || quickQuery"></h4>
                                    <template x-if="quickResult.data?.generic_name">
                                        <p class="text-xs text-gray-500 mt-0.5" x-text="'Generic: ' + quickResult.data.generic_name"></p>
                                    </template>
                                    <template x-if="quickResult.data?.drug_class">
                                        <span class="inline-flex mt-1 items-center px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-md" x-text="quickResult.data.drug_class"></span>
                                    </template>
                                    <template x-if="quickResult.data?.description">
                                        <p class="text-sm text-gray-600 mt-2 line-clamp-3" x-text="quickResult.data.description"></p>
                                    </template>
                                    <template x-if="quickResult.data?.price">
                                        <p class="text-lg font-bold text-emerald-700 mt-2" x-text="quickResult.data.price"></p>
                                    </template>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-emerald-200">
                                <button @click="showFullRaw = !showFullRaw" class="text-xs text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                                    <svg class="w-3 h-3 transition-transform" :class="showFullRaw && 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    {{ __('app.view_full_data') }}
                                </button>
                                <pre x-show="showFullRaw" x-transition class="mt-2 p-3 bg-gray-900 text-green-400 text-xs rounded-lg overflow-x-auto max-h-48" x-text="JSON.stringify(quickResult.data, null, 2)"></pre>
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
                showFullRaw: false,

                async quickLookup() {
                    if (this.quickQuery.length < 2 || this.loading) return;
                    this.loading = true;
                    this.quickResult = null;
                    this.showFullRaw = false;

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
