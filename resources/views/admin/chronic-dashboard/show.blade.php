<x-dashboard-layout>
    <x-slot name="title">{{ __('app.chronic_dashboard') }} - {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.chronic_dashboard') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    {{-- Chart.js CDN --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.patients.show', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    {{-- ===== HEALTH ALERTS ===== --}}
    @if(count($alerts) > 0)
    <div class="mb-6 space-y-3">
        @foreach($alerts as $alert)
        <div class="flex items-center gap-3 px-5 py-4 rounded-xl border {{ $alert['type'] === 'danger' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
            <div class="shrink-0">
                @if($alert['type'] === 'danger')
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                @else
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                @endif
            </div>
            <div class="flex-1">
                <span class="font-semibold text-sm">{{ __('app.' . $alert['key']) }}</span>
                <span class="text-sm font-mono mx-2 px-2 py-0.5 rounded-lg {{ $alert['type'] === 'danger' ? 'bg-red-100' : 'bg-amber-100' }}" dir="ltr">{{ $alert['reading'] }}</span>
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full {{ $alert['type'] === 'danger' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                {{ $alert['type'] === 'danger' ? __('app.high') : __('app.borderline') }}
            </span>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ===== PATIENT HEADER ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex flex-wrap items-start gap-5">
            {{-- Avatar --}}
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $patient->gender === 'male' ? 'from-blue-500 to-cyan-600' : 'from-pink-500 to-rose-600' }} flex items-center justify-center text-2xl font-bold text-white shadow-lg shrink-0">
                {{ mb_substr($patient->name, 0, 1) }}
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="text-xl font-bold text-gray-900">{{ $patient->name }}</h1>
                    @if($patient->date_of_birth)
                    <span class="text-sm text-gray-500 font-medium">{{ $patient->date_of_birth->age }} {{ __('app.years') }}</span>
                    @endif
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ $patient->gender === 'male' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-pink-50 text-pink-700 border border-pink-200' }}">
                        {{ __('app.' . $patient->gender) }}
                    </span>
                </div>

                {{-- Chronic disease badges --}}
                @if($chronicDiseases->count())
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($chronicDiseases as $disease)
                    @php
                        $dc = ['mild' => 'bg-yellow-50 text-yellow-700 border-yellow-200', 'moderate' => 'bg-orange-50 text-orange-700 border-orange-200', 'severe' => 'bg-red-50 text-red-700 border-red-200'];
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg border {{ $dc[$disease->severity] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 10.414V14a1 1 0 102 0v-3.586l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/></svg>
                        {{ $disease->disease_name }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- Meta row --}}
                <div class="flex flex-wrap gap-5 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        <span class="font-semibold text-gray-700">{{ $activeMedications->count() }}</span> {{ __('app.active_medications') }}
                    </span>
                    @if($lastCheckup)
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ __('app.last_checkup') }}: <span class="font-semibold text-gray-700">{{ $lastCheckup->format('Y-m-d') }}</span>
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ===== SUMMARY CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        @php
            $trendIcons = [
                'improving' => ['icon' => 'M5 10l7-7m0 0l7 7m-7-7v18', 'color' => 'text-emerald-500'],
                'worsening' => ['icon' => 'M19 14l-7 7m0 0l-7-7m7 7V3',  'color' => 'text-red-500'],
                'stable'    => ['icon' => 'M5 12h14',                       'color' => 'text-blue-400'],
            ];
            $statusBg = [
                'green'  => 'bg-emerald-50 border-emerald-100',
                'yellow' => 'bg-amber-50 border-amber-100',
                'red'    => 'bg-red-50 border-red-100',
            ];
            $statusDot = [
                'green'  => 'bg-emerald-400',
                'yellow' => 'bg-amber-400',
                'red'    => 'bg-red-500',
            ];
            $statusLabel = [
                'green'  => 'text-emerald-700',
                'yellow' => 'text-amber-700',
                'red'    => 'text-red-700',
            ];
        @endphp

        {{-- Blood Pressure --}}
        <div class="bg-white rounded-2xl border shadow-sm p-5 {{ $statusBg[$bpStatus['color']] }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <div class="flex items-center gap-1 text-xs font-semibold {{ $statusLabel[$bpStatus['color']] }}">
                    <span class="w-2 h-2 rounded-full {{ $statusDot[$bpStatus['color']] }}"></span>
                    {{ __('app.' . $bpStatus['label']) }}
                </div>
            </div>
            <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.blood_pressure_trend') }}</p>
            @if($latestVital && $latestVital->blood_pressure)
            <p class="text-2xl font-bold text-gray-900 font-mono" dir="ltr">{{ $latestVital->blood_pressure }}</p>
            <p class="text-xs text-gray-400 mt-0.5">mmHg</p>
            @else
            <p class="text-sm text-gray-400">{{ __('app.no_readings') }}</p>
            @endif
            <div class="flex items-center gap-1 mt-3 text-xs {{ $trendIcons[$trends['bp']]['color'] }} font-semibold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $trendIcons[$trends['bp']]['icon'] }}"/></svg>
                {{ __('app.' . $trends['bp']) }}
            </div>
        </div>

        {{-- Blood Sugar --}}
        <div class="bg-white rounded-2xl border shadow-sm p-5 {{ $statusBg[$sugarStatus['color']] }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <div class="flex items-center gap-1 text-xs font-semibold {{ $statusLabel[$sugarStatus['color']] }}">
                    <span class="w-2 h-2 rounded-full {{ $statusDot[$sugarStatus['color']] }}"></span>
                    {{ __('app.' . $sugarStatus['label']) }}
                </div>
            </div>
            <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.blood_sugar_trend') }}</p>
            @if($latestVital && $latestVital->blood_sugar)
            <p class="text-2xl font-bold text-gray-900 font-mono" dir="ltr">{{ number_format((float) $latestVital->blood_sugar, 0) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">mg/dL</p>
            @else
            <p class="text-sm text-gray-400">{{ __('app.no_readings') }}</p>
            @endif
            <div class="flex items-center gap-1 mt-3 text-xs {{ $trendIcons[$trends['sugar']]['color'] }} font-semibold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $trendIcons[$trends['sugar']]['icon'] }}"/></svg>
                {{ __('app.' . $trends['sugar']) }}
            </div>
        </div>

        {{-- Weight / BMI --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                </div>
                @if($latestBmi)
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $latestBmi < 18.5 ? 'bg-blue-50 text-blue-700' : ($latestBmi < 25 ? 'bg-emerald-50 text-emerald-700' : ($latestBmi < 30 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700')) }}">
                    BMI {{ $latestBmi }}
                </span>
                @endif
            </div>
            <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.weight_trend') }}</p>
            @if($latestVital && $latestVital->weight)
            <p class="text-2xl font-bold text-gray-900 font-mono" dir="ltr">{{ number_format((float) $latestVital->weight, 1) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">kg</p>
            @else
            <p class="text-sm text-gray-400">{{ __('app.no_readings') }}</p>
            @endif
            <div class="flex items-center gap-1 mt-3 text-xs {{ $trendIcons[$trends['weight']]['color'] }} font-semibold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $trendIcons[$trends['weight']]['icon'] }}"/></svg>
                {{ __('app.' . $trends['weight']) }}
            </div>
        </div>

        {{-- Heart Rate --}}
        <div class="bg-white rounded-2xl border shadow-sm p-5 {{ $statusBg[$hrStatus['color']] }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div class="flex items-center gap-1 text-xs font-semibold {{ $statusLabel[$hrStatus['color']] }}">
                    <span class="w-2 h-2 rounded-full {{ $statusDot[$hrStatus['color']] }}"></span>
                    {{ __('app.' . $hrStatus['label']) }}
                </div>
            </div>
            <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.heart_rate_trend') }}</p>
            @if($latestVital && $latestVital->heart_rate)
            <p class="text-2xl font-bold text-gray-900 font-mono" dir="ltr">{{ number_format((float) $latestVital->heart_rate, 0) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">bpm</p>
            @else
            <p class="text-sm text-gray-400">{{ __('app.no_readings') }}</p>
            @endif
            <div class="flex items-center gap-1 mt-3 text-xs {{ $trendIcons[$trends['hr']]['color'] }} font-semibold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $trendIcons[$trends['hr']]['icon'] }}"/></svg>
                {{ __('app.' . $trends['hr']) }}
            </div>
        </div>

    </div>

    {{-- ===== CHARTS SECTION ===== --}}
    @if(count($chartData['labels']) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- Chart 1: Blood Pressure Over Time --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('app.blood_pressure_trend') }}</h3>
                <span class="text-xs text-gray-400 font-mono">(mmHg)</span>
            </div>
            <div class="relative" style="height: 220px;">
                <canvas id="bpChart"></canvas>
            </div>
        </div>

        {{-- Chart 2: Blood Sugar Over Time --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('app.blood_sugar_trend') }}</h3>
                <span class="text-xs text-gray-400 font-mono">(mg/dL)</span>
            </div>
            <div class="relative" style="height: 220px;">
                <canvas id="sugarChart"></canvas>
            </div>
        </div>

        {{-- Chart 3: Weight Trend --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('app.weight_trend') }}</h3>
                <span class="text-xs text-gray-400 font-mono">(kg)</span>
            </div>
            <div class="relative" style="height: 220px;">
                <canvas id="weightChart"></canvas>
            </div>
        </div>

        {{-- Chart 4: Heart Rate Over Time --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('app.heart_rate_trend') }}</h3>
                <span class="text-xs text-gray-400 font-mono">(bpm)</span>
            </div>
            <div class="relative" style="height: 220px;">
                <canvas id="hrChart"></canvas>
            </div>
        </div>

    </div>
    @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 mb-6 text-center">
        <svg class="w-14 h-14 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        <p class="text-base font-semibold text-gray-400">{{ __('app.no_readings') }}</p>
    </div>
    @endif

    {{-- ===== BOTTOM ROW: MEDICATIONS + CHRONIC DISEASES ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Active Medications --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('app.active_medications') }}</h3>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 {{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }}">{{ $activeMedications->count() }}</span>
            </div>
            @if($activeMedications->count())
            <div class="divide-y divide-gray-50">
                @foreach($activeMedications as $med)
                @php
                    $medColors = [
                        0 => 'bg-blue-50 text-blue-700 border-blue-100',
                        1 => 'bg-violet-50 text-violet-700 border-violet-100',
                        2 => 'bg-teal-50 text-teal-700 border-teal-100',
                        3 => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                        4 => 'bg-cyan-50 text-cyan-700 border-cyan-100',
                    ];
                    $colorKey = $loop->index % 5;
                @endphp
                <div class="px-6 py-4 flex items-start gap-4">
                    <div class="w-9 h-9 rounded-xl {{ ['bg-blue-100', 'bg-violet-100', 'bg-teal-100', 'bg-indigo-100', 'bg-cyan-100'][$colorKey] }} flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 {{ ['text-blue-600', 'text-violet-600', 'text-teal-600', 'text-indigo-600', 'text-cyan-600'][$colorKey] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $med->medication_name }}</p>
                        <div class="flex flex-wrap gap-2 mt-1.5">
                            @if($med->dosage)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-md border font-medium {{ $medColors[$colorKey] }}">{{ $med->dosage }}</span>
                            @endif
                            @if($med->frequency)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-md border border-gray-100 text-gray-600 bg-gray-50 font-medium">{{ $med->frequency }}</span>
                            @endif
                        </div>
                        @if($med->start_date)
                        <p class="text-xs text-gray-400 mt-1">{{ __('app.from') }} {{ $med->start_date->format('Y-m-d') }}{{ $med->end_date ? ' ' . __('app.to') . ' ' . $med->end_date->format('Y-m-d') : '' }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-6 py-10 text-center">
                <svg class="w-10 h-10 mx-auto text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                <p class="text-sm text-gray-400">{{ __('app.no_medications') }}</p>
            </div>
            @endif
        </div>

        {{-- Chronic Diseases --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('app.chronic_diseases') }}</h3>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 {{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }}">{{ $chronicDiseases->count() }}</span>
            </div>
            @if($chronicDiseases->count())
            <div class="divide-y divide-gray-50">
                @foreach($chronicDiseases as $disease)
                @php
                    $sc = ['mild' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-100', 'dot' => 'bg-yellow-400'], 'moderate' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-100', 'dot' => 'bg-orange-400'], 'severe' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-100', 'dot' => 'bg-red-500']];
                    $s = $sc[$disease->severity] ?? $sc['mild'];
                @endphp
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="w-9 h-9 rounded-xl {{ $s['bg'] }} flex items-center justify-center shrink-0">
                        <span class="w-3 h-3 rounded-full {{ $s['dot'] }}"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $disease->disease_name }}</p>
                        @if($disease->diagnosed_date)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $disease->diagnosed_date->format('Y-m-d') }}</p>
                        @endif
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }}">
                        {{ __('app.' . $disease->severity) }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-6 py-10 text-center">
                <svg class="w-10 h-10 mx-auto text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <p class="text-sm text-gray-400">{{ __('app.no_chronic_diseases') }}</p>
            </div>
            @endif
        </div>

    </div>

    {{-- ===== CHART.JS SCRIPTS ===== --}}
    @if(count($chartData['labels']) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const labels      = @json($chartData['labels']);
        const systolic    = @json($chartData['systolic']);
        const diastolic   = @json($chartData['diastolic']);
        const sugar       = @json($chartData['sugar']);
        const weight      = @json($chartData['weight']);
        const heartRate   = @json($chartData['heart_rate']);

        const gridColor   = 'rgba(0,0,0,0.04)';
        const tickColor   = '#9CA3AF';
        const fontFamily  = "'Inter', 'Segoe UI', sans-serif";

        const baseOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        font: { family: fontFamily, size: 11 },
                        color: '#374151',
                        boxWidth: 12,
                        padding: 16,
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17,24,39,0.92)',
                    padding: 10,
                    cornerRadius: 10,
                    titleFont: { family: fontFamily, size: 12, weight: 'bold' },
                    bodyFont:  { family: fontFamily, size: 11 },
                    callbacks: {
                        label: function(ctx) {
                            return ' ' + ctx.dataset.label + ': ' + (ctx.raw ?? '–');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: gridColor },
                    ticks: { color: tickColor, font: { family: fontFamily, size: 10 }, maxRotation: 45 }
                },
                y: {
                    grid: { color: gridColor },
                    ticks: { color: tickColor, font: { family: fontFamily, size: 10 } }
                }
            },
            elements: {
                point: { radius: 4, hoverRadius: 6, borderWidth: 2 },
                line:  { tension: 0.4 }
            }
        };

        // ---- Blood Pressure Chart ----
        const bpCtx = document.getElementById('bpChart');
        if (bpCtx) {
            const bpSysData  = systolic.map(v => v ?? null);
            const bpDiaData  = diastolic.map(v => v ?? null);
            const hasAnyBp   = bpSysData.some(v => v !== null) || bpDiaData.some(v => v !== null);

            if (hasAnyBp) {
                new Chart(bpCtx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            {
                                label: 'Systolic',
                                data: bpSysData,
                                borderColor: '#EF4444',
                                backgroundColor: 'rgba(239,68,68,0.08)',
                                fill: false,
                                spanGaps: true,
                                pointBackgroundColor: '#EF4444',
                            },
                            {
                                label: 'Diastolic',
                                data: bpDiaData,
                                borderColor: '#F97316',
                                backgroundColor: 'rgba(249,115,22,0.08)',
                                fill: false,
                                spanGaps: true,
                                pointBackgroundColor: '#F97316',
                            },
                            {
                                label: 'Systolic Threshold (140)',
                                data: labels.map(() => 140),
                                borderColor: 'rgba(239,68,68,0.4)',
                                borderDash: [6, 4],
                                borderWidth: 1.5,
                                pointRadius: 0,
                                fill: false,
                                spanGaps: true,
                            },
                            {
                                label: 'Diastolic Threshold (90)',
                                data: labels.map(() => 90),
                                borderColor: 'rgba(249,115,22,0.4)',
                                borderDash: [6, 4],
                                borderWidth: 1.5,
                                pointRadius: 0,
                                fill: false,
                                spanGaps: true,
                            },
                        ]
                    },
                    options: { ...baseOptions }
                });
            }
        }

        // ---- Blood Sugar Chart ----
        const sugarCtx = document.getElementById('sugarChart');
        if (sugarCtx) {
            const sugarData = sugar.map(v => v ?? null);
            const hasAnySugar = sugarData.some(v => v !== null);

            if (hasAnySugar) {
                new Chart(sugarCtx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            {
                                label: 'Blood Sugar',
                                data: sugarData,
                                borderColor: '#F59E0B',
                                backgroundColor: 'rgba(245,158,11,0.08)',
                                fill: true,
                                spanGaps: true,
                                pointBackgroundColor: '#F59E0B',
                            },
                            {
                                label: 'Danger (200)',
                                data: labels.map(() => 200),
                                borderColor: 'rgba(239,68,68,0.5)',
                                borderDash: [6, 4],
                                borderWidth: 1.5,
                                pointRadius: 0,
                                fill: false,
                                spanGaps: true,
                            },
                            {
                                label: 'Borderline (140)',
                                data: labels.map(() => 140),
                                borderColor: 'rgba(245,158,11,0.5)',
                                borderDash: [6, 4],
                                borderWidth: 1.5,
                                pointRadius: 0,
                                fill: false,
                                spanGaps: true,
                            },
                        ]
                    },
                    options: { ...baseOptions }
                });
            }
        }

        // ---- Weight Chart ----
        const weightCtx = document.getElementById('weightChart');
        if (weightCtx) {
            const weightData = weight.map(v => v ?? null);
            const hasAnyWeight = weightData.some(v => v !== null);

            if (hasAnyWeight) {
                new Chart(weightCtx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            {
                                label: 'Weight (kg)',
                                data: weightData,
                                borderColor: '#8B5CF6',
                                backgroundColor: 'rgba(139,92,246,0.08)',
                                fill: true,
                                spanGaps: true,
                                pointBackgroundColor: '#8B5CF6',
                            }
                        ]
                    },
                    options: { ...baseOptions }
                });
            }
        }

        // ---- Heart Rate Chart ----
        const hrCtx = document.getElementById('hrChart');
        if (hrCtx) {
            const hrData = heartRate.map(v => v ?? null);
            const hasAnyHr = hrData.some(v => v !== null);

            if (hasAnyHr) {
                new Chart(hrCtx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            {
                                label: 'Heart Rate (bpm)',
                                data: hrData,
                                borderColor: '#F43F5E',
                                backgroundColor: 'rgba(244,63,94,0.08)',
                                fill: true,
                                spanGaps: true,
                                pointBackgroundColor: '#F43F5E',
                            },
                            {
                                label: 'Upper Normal (100)',
                                data: labels.map(() => 100),
                                borderColor: 'rgba(16,185,129,0.4)',
                                borderDash: [6, 4],
                                borderWidth: 1.5,
                                pointRadius: 0,
                                fill: false,
                                spanGaps: true,
                            },
                            {
                                label: 'Lower Normal (60)',
                                data: labels.map(() => 60),
                                borderColor: 'rgba(16,185,129,0.4)',
                                borderDash: [6, 4],
                                borderWidth: 1.5,
                                pointRadius: 0,
                                fill: false,
                                spanGaps: true,
                            },
                        ]
                    },
                    options: { ...baseOptions }
                });
            }
        }

    });
    </script>
    @endif

</x-dashboard-layout>
