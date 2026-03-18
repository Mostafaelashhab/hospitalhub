<x-dashboard-layout>
    <x-slot name="title">{{ __('app.pharmacy') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.pharmacy') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'pharmacy'])
    </x-slot>

    <div class="space-y-6">

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
            <div class="px-4 py-2 bg-emerald-50 rounded-xl text-center">
                <p class="text-lg font-bold text-emerald-700">{{ number_format($totalDrugs) }}</p>
                <p class="text-[10px] text-emerald-600 font-medium">{{ __('app.total_drugs') }}</p>
            </div>
        </div>

        {{-- How it works --}}
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
            <form method="GET" action="{{ route('dashboard.pharmacy.index') }}">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 relative">
                        <svg class="absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" value="{{ $query }}"
                               placeholder="{{ __('app.search_drug_placeholder') }}"
                               class="{{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }} w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        {{ __('app.search') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Drug Results --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('app.results') }}</h3>
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
                                        <a href="{{ route('dashboard.pharmacy.show', $drug) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-medium rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            {{ __('app.view') }}
                                        </a>
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
    </div>
</x-dashboard-layout>
