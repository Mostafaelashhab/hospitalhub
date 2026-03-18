<x-dashboard-layout>
    <x-slot name="title">{{ $drug->name }} — {{ __('app.pharmacy') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.pharmacy') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'pharmacy'])
    </x-slot>

    <div class="space-y-6">

        {{-- Back --}}
        <a href="{{ route('dashboard.pharmacy.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>

        {{-- Drug Header Card --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-5">
                <div class="flex flex-col sm:flex-row items-start gap-5">
                    @if($drug->image)
                        <img src="{{ filter_var($drug->image, FILTER_VALIDATE_URL) ? $drug->image : asset('storage/' . $drug->image) }}"
                             alt="{{ $drug->name }}"
                             class="w-24 h-24 object-contain rounded-xl bg-white/90 p-2 shadow-lg">
                    @else
                        <div class="w-24 h-24 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-white">{{ $drug->name }}</h1>
                        @if($drug->name_ar)
                            <p class="text-emerald-100 text-sm mt-0.5">{{ $drug->name_ar }}</p>
                        @endif
                        @if($drug->generic_name)
                            <p class="text-emerald-100 text-sm mt-1">{{ __('app.generic_name') }}: <span class="font-semibold text-white">{{ $drug->generic_name }}</span></p>
                        @endif
                        <div class="flex flex-wrap gap-2 mt-3">
                            @if($drug->drug_class)
                                <span class="px-3 py-1 bg-white/20 text-white text-xs font-medium rounded-lg backdrop-blur-sm">{{ $drug->drug_class }}</span>
                            @endif
                            @if($drug->manufacturer)
                                <span class="px-3 py-1 bg-white/20 text-white text-xs font-medium rounded-lg backdrop-blur-sm">{{ $drug->manufacturer }}</span>
                            @endif
                            @if($drug->category_name)
                                <span class="px-3 py-1 bg-white/20 text-white text-xs font-medium rounded-lg backdrop-blur-sm">{{ $drug->category_name }}</span>
                            @endif
                            <span class="px-3 py-1 text-xs font-bold rounded-lg {{ $drug->is_drug ? 'bg-red-400/30 text-white' : 'bg-blue-400/30 text-white' }}">
                                {{ $drug->is_drug ? __('app.drug') : __('app.product') }}
                            </span>
                        </div>
                    </div>
                    @if($drug->price > 0)
                        <div class="text-end">
                            <p class="text-emerald-100 text-xs">{{ __('app.price') }}</p>
                            <p class="text-3xl font-bold text-white">{{ number_format($drug->price, 2) }}</p>
                            <p class="text-emerald-100 text-xs">{{ __('app.egp') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Meta info --}}
            <div class="px-6 py-3 bg-gray-50 border-b border-gray-100 flex flex-wrap gap-4 text-xs text-gray-500">
                @if($drug->api_fetched_at)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('app.api_fetched') }}: {{ $drug->api_fetched_at->diffForHumans() }}
                    </span>
                @endif
                @if($drug->pregnancy_category)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                        {{ __('app.drug_pregnancy_category') }}: <span class="font-semibold text-gray-700">{{ $drug->pregnancy_category }}</span>
                    </span>
                @endif
            </div>
        </div>

        {{-- Info Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
                $sections = [
                    ['key' => 'description', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'blue', 'full' => true],
                    ['key' => 'indications', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'emerald', 'full' => false],
                    ['key' => 'dosage', 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z', 'color' => 'violet', 'full' => false],
                    ['key' => 'side_effects', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z', 'color' => 'amber', 'full' => true],
                    ['key' => 'contraindications', 'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636', 'color' => 'red', 'full' => false],
                    ['key' => 'interactions', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'color' => 'orange', 'full' => false],
                    ['key' => 'warnings', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z', 'color' => 'red', 'full' => true],
                    ['key' => 'storage_info', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'color' => 'gray', 'full' => false],
                ];
            @endphp

            @foreach($sections as $section)
                @php $value = $drug->{$section['key']}; @endphp
                @if($value)
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden {{ $section['full'] ? 'md:col-span-2' : '' }}">
                        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-{{ $section['color'] }}-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-{{ $section['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $section['icon'] }}"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900">{{ __('app.drug_' . $section['key']) }}</h3>
                        </div>
                        <div class="px-5 py-4">
                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $value }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Raw API Data --}}
        @if($drug->api_raw_data)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        <h3 class="text-sm font-bold text-gray-900">{{ __('app.raw_data') }} (JSON)</h3>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition class="px-6 pb-6">
                    <pre class="p-4 bg-gray-900 text-green-400 text-xs rounded-xl overflow-x-auto max-h-96">{{ json_encode($drug->api_raw_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif
    </div>
</x-dashboard-layout>
