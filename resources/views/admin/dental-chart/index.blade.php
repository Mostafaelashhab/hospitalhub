<x-dashboard-layout>
    <x-slot name="title">{{ __('app.dental_chart') }} — {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.dental_chart') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    {{-- Success --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Back --}}
    <div class="mb-5">
        <a href="{{ route('dashboard.patients.show', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ $patient->name }}
        </a>
    </div>

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.dental_chart') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $patient->name }}</p>
        </div>
        <a href="{{ route('dashboard.patients.dental-chart.history', $patient) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ __('app.chart_history') }}
        </a>
    </div>

    {{-- Alpine.js Dental Chart Component --}}
    <div
        x-data="dentalChart(@js($toothData))"
        class="space-y-6"
    >
        {{-- Legend --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-700 mb-3">{{ __('app.tooth_status') }}</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($statuses as $status)
                <button
                    type="button"
                    @click="filterStatus = (filterStatus === '{{ $status }}' ? null : '{{ $status }}')"
                    :class="filterStatus === '{{ $status }}' ? 'ring-2 ring-offset-1 ring-gray-400' : ''"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all"
                    :style="legendStyle('{{ $status }}')"
                >
                    <span class="w-3 h-3 rounded-full border border-black/10" :style="dotStyle('{{ $status }}')"></span>
                    {{ __('app.' . $status) }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Dental Arch SVG --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            {{-- Upper jaw label --}}
            <p class="text-xs font-semibold text-center text-gray-400 uppercase tracking-widest mb-1">{{ __('app.upper_jaw') }}</p>

            <div class="overflow-x-auto">
                <svg
                    viewBox="0 0 760 520"
                    class="w-full max-w-3xl mx-auto select-none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    {{-- ===== UPPER ARCH ===== --}}
                    {{--
                        FDI Upper:  right side 18→11, left side 21→28
                        We draw them along a U-arch curve.
                        Centre X = 380. Y baseline for upper = 190 (opening downward arch).
                        Tooth X positions are mirrored about centre.
                        Tooth widths (px): molar=46, premolar=36, canine=30, incisor=28
                    --}}

                    {{-- Upper right: 18 17 16 15 14 13 12 11 --}}
                    @php
                    // [tooth, cx, cy, width, height, rx, type]
                    // Upper right (patient's right = viewer's left)
                    $upperRight = [
                        ['18', 68,  90,  46, 62, 8, 'molar'],
                        ['17', 118, 72,  46, 66, 8, 'molar'],
                        ['16', 168, 57,  46, 70, 8, 'molar'],
                        ['15', 214, 46,  36, 64, 7, 'premolar'],
                        ['14', 252, 38,  36, 62, 7, 'premolar'],
                        ['13', 287, 33,  30, 58, 8, 'canine'],
                        ['12', 320, 30,  28, 52, 6, 'incisor'],
                        ['11', 350, 29,  28, 52, 5, 'incisor'],
                    ];
                    // Upper left (patient's left = viewer's right)
                    $upperLeft = [
                        ['21', 382, 29,  28, 52, 5, 'incisor'],
                        ['22', 412, 30,  28, 52, 6, 'incisor'],
                        ['23', 445, 33,  30, 58, 8, 'canine'],
                        ['24', 480, 38,  36, 62, 7, 'premolar'],
                        ['25', 518, 46,  36, 64, 7, 'premolar'],
                        ['26', 554, 57,  46, 70, 8, 'molar'],
                        ['27', 604, 72,  46, 66, 8, 'molar'],
                        ['28', 654, 90,  46, 62, 8, 'molar'],
                    ];
                    // Lower right (patient's right = viewer's left)
                    $lowerRight = [
                        ['48', 68,  430, 46, 62, 8, 'molar'],
                        ['47', 118, 448, 46, 66, 8, 'molar'],
                        ['46', 168, 463, 46, 70, 8, 'molar'],
                        ['45', 214, 472, 36, 64, 7, 'premolar'],
                        ['44', 252, 480, 36, 62, 7, 'premolar'],
                        ['43', 287, 485, 30, 58, 8, 'canine'],
                        ['42', 320, 488, 28, 52, 6, 'incisor'],
                        ['41', 350, 489, 28, 52, 5, 'incisor'],
                    ];
                    // Lower left (patient's left = viewer's right)
                    $lowerLeft = [
                        ['31', 382, 489, 28, 52, 5, 'incisor'],
                        ['32', 412, 488, 28, 52, 6, 'incisor'],
                        ['33', 445, 485, 30, 58, 8, 'canine'],
                        ['34', 480, 480, 36, 62, 7, 'premolar'],
                        ['35', 518, 472, 36, 64, 7, 'premolar'],
                        ['36', 554, 463, 46, 70, 8, 'molar'],
                        ['37', 604, 448, 46, 66, 8, 'molar'],
                        ['38', 654, 430, 46, 62, 8, 'molar'],
                    ];
                    @endphp

                    {{-- Centre divider line --}}
                    <line x1="380" y1="8" x2="380" y2="512" stroke="#e5e7eb" stroke-width="1" stroke-dasharray="4,4"/>
                    {{-- Upper/lower divider --}}
                    <line x1="30" y1="260" x2="730" y2="260" stroke="#e5e7eb" stroke-width="1" stroke-dasharray="4,4"/>

                    {{-- Quadrant labels --}}
                    <text x="30" y="24" font-size="10" fill="#9ca3af" font-family="sans-serif">UR</text>
                    <text x="700" y="24" font-size="10" fill="#9ca3af" font-family="sans-serif" text-anchor="end">UL</text>
                    <text x="30" y="512" font-size="10" fill="#9ca3af" font-family="sans-serif">LR</text>
                    <text x="700" y="512" font-size="10" fill="#9ca3af" font-family="sans-serif" text-anchor="end">LL</text>

                    {{-- Render upper right teeth --}}
                    @foreach($upperRight as [$num, $cx, $cy, $tw, $th, $rx, $type])
                    <g
                        @click="selectTooth('{{ $num }}')"
                        class="cursor-pointer"
                        :opacity="filterStatus && teeth['{{ $num }}'].status !== filterStatus ? '0.25' : '1'"
                    >
                        {{-- Shadow rect --}}
                        <rect
                            x="{{ $cx - $tw/2 + 2 }}"
                            y="{{ $cy + 2 }}"
                            width="{{ $tw }}"
                            height="{{ $th }}"
                            rx="{{ $rx }}"
                            fill="rgba(0,0,0,0.08)"
                        />
                        {{-- Main tooth body --}}
                        <rect
                            x="{{ $cx - $tw/2 }}"
                            y="{{ $cy }}"
                            width="{{ $tw }}"
                            height="{{ $th }}"
                            rx="{{ $rx }}"
                            :fill="toothFill('{{ $num }}')"
                            :stroke="selectedTooth === '{{ $num }}' ? '#6366f1' : toothStroke('{{ $num }}')"
                            :stroke-width="selectedTooth === '{{ $num }}' ? '2.5' : '1.5'"
                            :stroke-dasharray="teeth['{{ $num }}'].status === 'missing' ? '4,3' : 'none'"
                        />
                        {{-- Root indication (top of upper teeth) --}}
                        @if($type === 'molar')
                        <rect x="{{ $cx - $tw/2 + 4 }}" y="{{ $cy }}" width="{{ ($tw-8)/3 }}" height="16" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 4 + ($tw-8)/3 + 3 }}" y="{{ $cy }}" width="{{ ($tw-8)/3 }}" height="18" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 4 + 2*($tw-8)/3 + 6 }}" y="{{ $cy }}" width="{{ ($tw-8)/3 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @elseif($type === 'premolar')
                        <rect x="{{ $cx - $tw/2 + 5 }}" y="{{ $cy }}" width="{{ ($tw-10)/2 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 5 + ($tw-10)/2 + 3 }}" y="{{ $cy }}" width="{{ ($tw-10)/2 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @elseif($type === 'canine')
                        <rect x="{{ $cx - 5 }}" y="{{ $cy }}" width="10" height="18" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @else
                        <rect x="{{ $cx - 5 }}" y="{{ $cy }}" width="10" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @endif
                        {{-- Cusp detail for molars/premolars (lower part of crown) --}}
                        @if($type === 'molar')
                        <line x1="{{ $cx }}" y1="{{ $cy + $th*0.55 }}" x2="{{ $cx }}" y2="{{ $cy + $th - 6 }}" stroke="rgba(0,0,0,0.12)" stroke-width="1"/>
                        <line x1="{{ $cx - $tw/2 + 6 }}" y1="{{ $cy + $th*0.6 }}" x2="{{ $cx + $tw/2 - 6 }}" y2="{{ $cy + $th*0.6 }}" stroke="rgba(0,0,0,0.12)" stroke-width="1"/>
                        @elseif($type === 'premolar')
                        <line x1="{{ $cx }}" y1="{{ $cy + $th*0.55 }}" x2="{{ $cx }}" y2="{{ $cy + $th - 5 }}" stroke="rgba(0,0,0,0.10)" stroke-width="1"/>
                        @elseif($type === 'canine')
                        <polygon points="{{ $cx }},{{ $cy + $th - 10 }} {{ $cx - 7 }},{{ $cy + $th - 3 }} {{ $cx + 7 }},{{ $cy + $th - 3 }}" fill="rgba(0,0,0,0.08)"/>
                        @endif
                        {{-- Tooth number --}}
                        <text
                            x="{{ $cx }}"
                            y="{{ $cy + $th + 14 }}"
                            text-anchor="middle"
                            font-size="10"
                            font-family="sans-serif"
                            font-weight="600"
                            :fill="selectedTooth === '{{ $num }}' ? '#6366f1' : '#6b7280'"
                        >{{ $num }}</text>
                        {{-- Status dot --}}
                        <circle
                            cx="{{ $cx + $tw/2 - 5 }}"
                            cy="{{ $cy + 6 }}"
                            r="4"
                            :fill="statusDotColor('{{ $num }}')"
                            :opacity="teeth['{{ $num }}'].status !== 'healthy' ? '1' : '0'"
                        />
                    </g>
                    @endforeach

                    {{-- Render upper left teeth --}}
                    @foreach($upperLeft as [$num, $cx, $cy, $tw, $th, $rx, $type])
                    <g
                        @click="selectTooth('{{ $num }}')"
                        class="cursor-pointer"
                        :opacity="filterStatus && teeth['{{ $num }}'].status !== filterStatus ? '0.25' : '1'"
                    >
                        <rect x="{{ $cx - $tw/2 + 2 }}" y="{{ $cy + 2 }}" width="{{ $tw }}" height="{{ $th }}" rx="{{ $rx }}" fill="rgba(0,0,0,0.08)"/>
                        <rect
                            x="{{ $cx - $tw/2 }}"
                            y="{{ $cy }}"
                            width="{{ $tw }}"
                            height="{{ $th }}"
                            rx="{{ $rx }}"
                            :fill="toothFill('{{ $num }}')"
                            :stroke="selectedTooth === '{{ $num }}' ? '#6366f1' : toothStroke('{{ $num }}')"
                            :stroke-width="selectedTooth === '{{ $num }}' ? '2.5' : '1.5'"
                            :stroke-dasharray="teeth['{{ $num }}'].status === 'missing' ? '4,3' : 'none'"
                        />
                        @if($type === 'molar')
                        <rect x="{{ $cx - $tw/2 + 4 }}" y="{{ $cy }}" width="{{ ($tw-8)/3 }}" height="16" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 4 + ($tw-8)/3 + 3 }}" y="{{ $cy }}" width="{{ ($tw-8)/3 }}" height="18" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 4 + 2*($tw-8)/3 + 6 }}" y="{{ $cy }}" width="{{ ($tw-8)/3 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @elseif($type === 'premolar')
                        <rect x="{{ $cx - $tw/2 + 5 }}" y="{{ $cy }}" width="{{ ($tw-10)/2 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 5 + ($tw-10)/2 + 3 }}" y="{{ $cy }}" width="{{ ($tw-10)/2 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @elseif($type === 'canine')
                        <rect x="{{ $cx - 5 }}" y="{{ $cy }}" width="10" height="18" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @else
                        <rect x="{{ $cx - 5 }}" y="{{ $cy }}" width="10" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @endif
                        @if($type === 'molar')
                        <line x1="{{ $cx }}" y1="{{ $cy + $th*0.55 }}" x2="{{ $cx }}" y2="{{ $cy + $th - 6 }}" stroke="rgba(0,0,0,0.12)" stroke-width="1"/>
                        <line x1="{{ $cx - $tw/2 + 6 }}" y1="{{ $cy + $th*0.6 }}" x2="{{ $cx + $tw/2 - 6 }}" y2="{{ $cy + $th*0.6 }}" stroke="rgba(0,0,0,0.12)" stroke-width="1"/>
                        @elseif($type === 'premolar')
                        <line x1="{{ $cx }}" y1="{{ $cy + $th*0.55 }}" x2="{{ $cx }}" y2="{{ $cy + $th - 5 }}" stroke="rgba(0,0,0,0.10)" stroke-width="1"/>
                        @elseif($type === 'canine')
                        <polygon points="{{ $cx }},{{ $cy + $th - 10 }} {{ $cx - 7 }},{{ $cy + $th - 3 }} {{ $cx + 7 }},{{ $cy + $th - 3 }}" fill="rgba(0,0,0,0.08)"/>
                        @endif
                        <text x="{{ $cx }}" y="{{ $cy + $th + 14 }}" text-anchor="middle" font-size="10" font-family="sans-serif" font-weight="600" :fill="selectedTooth === '{{ $num }}' ? '#6366f1' : '#6b7280'">{{ $num }}</text>
                        <circle cx="{{ $cx + $tw/2 - 5 }}" cy="{{ $cy + 6 }}" r="4" :fill="statusDotColor('{{ $num }}')" :opacity="teeth['{{ $num }}'].status !== 'healthy' ? '1' : '0'"/>
                    </g>
                    @endforeach

                    {{-- Render lower right teeth --}}
                    @foreach($lowerRight as [$num, $cx, $cy, $tw, $th, $rx, $type])
                    <g
                        @click="selectTooth('{{ $num }}')"
                        class="cursor-pointer"
                        :opacity="filterStatus && teeth['{{ $num }}'].status !== filterStatus ? '0.25' : '1'"
                    >
                        <rect x="{{ $cx - $tw/2 + 2 }}" y="{{ $cy + 2 }}" width="{{ $tw }}" height="{{ $th }}" rx="{{ $rx }}" fill="rgba(0,0,0,0.08)"/>
                        <rect
                            x="{{ $cx - $tw/2 }}"
                            y="{{ $cy }}"
                            width="{{ $tw }}"
                            height="{{ $th }}"
                            rx="{{ $rx }}"
                            :fill="toothFill('{{ $num }}')"
                            :stroke="selectedTooth === '{{ $num }}' ? '#6366f1' : toothStroke('{{ $num }}')"
                            :stroke-width="selectedTooth === '{{ $num }}' ? '2.5' : '1.5'"
                            :stroke-dasharray="teeth['{{ $num }}'].status === 'missing' ? '4,3' : 'none'"
                        />
                        {{-- Root indication (bottom of lower teeth) --}}
                        @if($type === 'molar')
                        <rect x="{{ $cx - $tw/2 + 4 }}" y="{{ $cy + $th - 16 }}" width="{{ ($tw-8)/3 }}" height="16" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 4 + ($tw-8)/3 + 3 }}" y="{{ $cy + $th - 18 }}" width="{{ ($tw-8)/3 }}" height="18" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 4 + 2*($tw-8)/3 + 6 }}" y="{{ $cy + $th - 14 }}" width="{{ ($tw-8)/3 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @elseif($type === 'premolar')
                        <rect x="{{ $cx - $tw/2 + 5 }}" y="{{ $cy + $th - 14 }}" width="{{ ($tw-10)/2 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 5 + ($tw-10)/2 + 3 }}" y="{{ $cy + $th - 14 }}" width="{{ ($tw-10)/2 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @elseif($type === 'canine')
                        <rect x="{{ $cx - 5 }}" y="{{ $cy + $th - 18 }}" width="10" height="18" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @else
                        <rect x="{{ $cx - 5 }}" y="{{ $cy + $th - 14 }}" width="10" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @endif
                        @if($type === 'molar')
                        <line x1="{{ $cx }}" y1="{{ $cy + 6 }}" x2="{{ $cx }}" y2="{{ $cy + $th*0.45 }}" stroke="rgba(0,0,0,0.12)" stroke-width="1"/>
                        <line x1="{{ $cx - $tw/2 + 6 }}" y1="{{ $cy + $th*0.4 }}" x2="{{ $cx + $tw/2 - 6 }}" y2="{{ $cy + $th*0.4 }}" stroke="rgba(0,0,0,0.12)" stroke-width="1"/>
                        @elseif($type === 'premolar')
                        <line x1="{{ $cx }}" y1="{{ $cy + 5 }}" x2="{{ $cx }}" y2="{{ $cy + $th*0.45 }}" stroke="rgba(0,0,0,0.10)" stroke-width="1"/>
                        @elseif($type === 'canine')
                        <polygon points="{{ $cx }},{{ $cy + 10 }} {{ $cx - 7 }},{{ $cy + 3 }} {{ $cx + 7 }},{{ $cy + 3 }}" fill="rgba(0,0,0,0.08)"/>
                        @endif
                        <text x="{{ $cx }}" y="{{ $cy - 4 }}" text-anchor="middle" font-size="10" font-family="sans-serif" font-weight="600" :fill="selectedTooth === '{{ $num }}' ? '#6366f1' : '#6b7280'">{{ $num }}</text>
                        <circle cx="{{ $cx + $tw/2 - 5 }}" cy="{{ $cy + $th - 7 }}" r="4" :fill="statusDotColor('{{ $num }}')" :opacity="teeth['{{ $num }}'].status !== 'healthy' ? '1' : '0'"/>
                    </g>
                    @endforeach

                    {{-- Render lower left teeth --}}
                    @foreach($lowerLeft as [$num, $cx, $cy, $tw, $th, $rx, $type])
                    <g
                        @click="selectTooth('{{ $num }}')"
                        class="cursor-pointer"
                        :opacity="filterStatus && teeth['{{ $num }}'].status !== filterStatus ? '0.25' : '1'"
                    >
                        <rect x="{{ $cx - $tw/2 + 2 }}" y="{{ $cy + 2 }}" width="{{ $tw }}" height="{{ $th }}" rx="{{ $rx }}" fill="rgba(0,0,0,0.08)"/>
                        <rect
                            x="{{ $cx - $tw/2 }}"
                            y="{{ $cy }}"
                            width="{{ $tw }}"
                            height="{{ $th }}"
                            rx="{{ $rx }}"
                            :fill="toothFill('{{ $num }}')"
                            :stroke="selectedTooth === '{{ $num }}' ? '#6366f1' : toothStroke('{{ $num }}')"
                            :stroke-width="selectedTooth === '{{ $num }}' ? '2.5' : '1.5'"
                            :stroke-dasharray="teeth['{{ $num }}'].status === 'missing' ? '4,3' : 'none'"
                        />
                        @if($type === 'molar')
                        <rect x="{{ $cx - $tw/2 + 4 }}" y="{{ $cy + $th - 16 }}" width="{{ ($tw-8)/3 }}" height="16" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 4 + ($tw-8)/3 + 3 }}" y="{{ $cy + $th - 18 }}" width="{{ ($tw-8)/3 }}" height="18" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 4 + 2*($tw-8)/3 + 6 }}" y="{{ $cy + $th - 14 }}" width="{{ ($tw-8)/3 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @elseif($type === 'premolar')
                        <rect x="{{ $cx - $tw/2 + 5 }}" y="{{ $cy + $th - 14 }}" width="{{ ($tw-10)/2 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        <rect x="{{ $cx - $tw/2 + 5 + ($tw-10)/2 + 3 }}" y="{{ $cy + $th - 14 }}" width="{{ ($tw-10)/2 }}" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @elseif($type === 'canine')
                        <rect x="{{ $cx - 5 }}" y="{{ $cy + $th - 18 }}" width="10" height="18" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @else
                        <rect x="{{ $cx - 5 }}" y="{{ $cy + $th - 14 }}" width="10" height="14" rx="2" fill="rgba(0,0,0,0.07)"/>
                        @endif
                        @if($type === 'molar')
                        <line x1="{{ $cx }}" y1="{{ $cy + 6 }}" x2="{{ $cx }}" y2="{{ $cy + $th*0.45 }}" stroke="rgba(0,0,0,0.12)" stroke-width="1"/>
                        <line x1="{{ $cx - $tw/2 + 6 }}" y1="{{ $cy + $th*0.4 }}" x2="{{ $cx + $tw/2 - 6 }}" y2="{{ $cy + $th*0.4 }}" stroke="rgba(0,0,0,0.12)" stroke-width="1"/>
                        @elseif($type === 'premolar')
                        <line x1="{{ $cx }}" y1="{{ $cy + 5 }}" x2="{{ $cx }}" y2="{{ $cy + $th*0.45 }}" stroke="rgba(0,0,0,0.10)" stroke-width="1"/>
                        @elseif($type === 'canine')
                        <polygon points="{{ $cx }},{{ $cy + 10 }} {{ $cx - 7 }},{{ $cy + 3 }} {{ $cx + 7 }},{{ $cy + 3 }}" fill="rgba(0,0,0,0.08)"/>
                        @endif
                        <text x="{{ $cx }}" y="{{ $cy - 4 }}" text-anchor="middle" font-size="10" font-family="sans-serif" font-weight="600" :fill="selectedTooth === '{{ $num }}' ? '#6366f1' : '#6b7280'">{{ $num }}</text>
                        <circle cx="{{ $cx + $tw/2 - 5 }}" cy="{{ $cy + $th - 7 }}" r="4" :fill="statusDotColor('{{ $num }}')" :opacity="teeth['{{ $num }}'].status !== 'healthy' ? '1' : '0'"/>
                    </g>
                    @endforeach

                </svg>
            </div>

            {{-- Lower jaw label --}}
            <p class="text-xs font-semibold text-center text-gray-400 uppercase tracking-widest mt-1">{{ __('app.lower_jaw') }}</p>
        </div>

        {{-- Tooth Detail Panel --}}
        <div
            x-show="selectedTooth !== null"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="bg-white rounded-2xl border border-indigo-200 shadow-sm p-6"
        >
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-900">
                    {{ __('app.tooth_number') }} <span x-text="selectedTooth" class="text-indigo-600"></span>
                    &nbsp;—&nbsp;
                    <span x-text="selectedTooth ? toothTypeName(selectedTooth) : ''" class="text-gray-500 font-normal"></span>
                </h3>
                <button type="button" @click="selectedTooth = null" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Status selector --}}
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 mb-2">{{ __('app.tooth_status') }}</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($statuses as $status)
                    <button
                        type="button"
                        @click="setStatus('{{ $status }}')"
                        :class="selectedTooth && teeth[selectedTooth].status === '{{ $status }}' ? 'ring-2 ring-offset-1 ring-indigo-500 scale-105' : ''"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all"
                        :style="legendStyle('{{ $status }}')"
                    >
                        <span class="w-3 h-3 rounded-full border border-black/10" :style="dotStyle('{{ $status }}')"></span>
                        {{ __('app.' . $status) }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Per-tooth notes --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">{{ __('app.notes') }}</label>
                <textarea
                    x-model="selectedTooth ? teeth[selectedTooth].notes : ''"
                    rows="2"
                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent resize-none"
                    placeholder="{{ __('app.notes') }}..."
                ></textarea>
            </div>
        </div>

        {{-- Save Form --}}
        <form
            method="POST"
            action="{{ route('dashboard.patients.dental-chart.store', $patient) }}"
            @submit.prevent="submitChart($el)"
        >
            @csrf

            <input type="hidden" name="tooth_data" x-ref="toothDataInput">

            {{-- Global notes --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.notes') }}</label>
                <textarea
                    name="notes"
                    rows="3"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent resize-none"
                    placeholder="{{ __('app.notes') }}..."
                >{{ old('notes', $chart?->notes) }}</textarea>
            </div>

            {{-- Summary bar --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
                <div class="flex flex-wrap gap-4 text-sm">
                    <template x-for="s in allStatuses" :key="s">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full border border-black/10" :style="dotStyle(s)"></span>
                            <span class="text-gray-500" x-text="statusLabel(s)"></span>
                            <span class="font-bold text-gray-900" x-text="countStatus(s)"></span>
                        </div>
                    </template>
                </div>
            </div>

            <button
                type="submit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ __('app.save_chart') }}
            </button>
        </form>
    </div>

    @push('scripts')
    <script>
    function dentalChart(initialTeeth) {
        return {
            teeth: initialTeeth,
            selectedTooth: null,
            filterStatus: null,

            allStatuses: [
                'healthy','cavity','filling','crown','extraction',
                'implant','root_canal','bridge','veneer','missing'
            ],

            statusLabels: {
                healthy:    '{{ __('app.healthy') }}',
                cavity:     '{{ __('app.cavity') }}',
                filling:    '{{ __('app.filling') }}',
                crown:      '{{ __('app.crown') }}',
                extraction: '{{ __('app.extraction') }}',
                implant:    '{{ __('app.implant') }}',
                root_canal: '{{ __('app.root_canal') }}',
                bridge:     '{{ __('app.bridge') }}',
                veneer:     '{{ __('app.veneer') }}',
                missing:    '{{ __('app.missing') }}',
            },

            // Fill colours for each status (used in SVG :fill binding)
            statusColors: {
                healthy:    '#f8fafc',
                cavity:     '#fee2e2',
                filling:    '#e5e7eb',
                crown:      '#fef9c3',
                extraction: '#1f2937',
                implant:    '#dbeafe',
                root_canal: '#ffedd5',
                bridge:     '#f3e8ff',
                veneer:     '#cffafe',
                missing:    'transparent',
            },

            statusStrokeColors: {
                healthy:    '#cbd5e1',
                cavity:     '#ef4444',
                filling:    '#9ca3af',
                crown:      '#ca8a04',
                extraction: '#374151',
                implant:    '#3b82f6',
                root_canal: '#f97316',
                bridge:     '#a855f7',
                veneer:     '#06b6d4',
                missing:    '#94a3b8',
            },

            statusDotColors: {
                healthy:    '#22c55e',
                cavity:     '#ef4444',
                filling:    '#9ca3af',
                crown:      '#eab308',
                extraction: '#374151',
                implant:    '#3b82f6',
                root_canal: '#f97316',
                bridge:     '#a855f7',
                veneer:     '#06b6d4',
                missing:    '#94a3b8',
            },

            toothTypeMap: {
                '18':'molar','17':'molar','16':'molar',
                '15':'premolar','14':'premolar',
                '13':'canine',
                '12':'incisor','11':'incisor',
                '21':'incisor','22':'incisor',
                '23':'canine',
                '24':'premolar','25':'premolar',
                '26':'molar','27':'molar','28':'molar',
                '31':'incisor','32':'incisor',
                '33':'canine',
                '34':'premolar','35':'premolar',
                '36':'molar','37':'molar','38':'molar',
                '41':'incisor','42':'incisor',
                '43':'canine',
                '44':'premolar','45':'premolar',
                '46':'molar','47':'molar','48':'molar',
            },

            selectTooth(num) {
                this.selectedTooth = (this.selectedTooth === num) ? null : num;
            },

            setStatus(status) {
                if (!this.selectedTooth) return;
                this.teeth[this.selectedTooth].status = status;
            },

            toothFill(num) {
                const status = this.teeth[num]?.status ?? 'healthy';
                return this.statusColors[status] ?? '#f8fafc';
            },

            toothStroke(num) {
                const status = this.teeth[num]?.status ?? 'healthy';
                return this.statusStrokeColors[status] ?? '#cbd5e1';
            },

            statusDotColor(num) {
                const status = this.teeth[num]?.status ?? 'healthy';
                return this.statusDotColors[status] ?? '#22c55e';
            },

            dotStyle(status) {
                return `background:${this.statusDotColors[status] ?? '#22c55e'}`;
            },

            legendStyle(status) {
                const stroke = this.statusStrokeColors[status] ?? '#cbd5e1';
                const fill   = this.statusColors[status] === 'transparent'
                    ? 'transparent'
                    : (this.statusColors[status] ?? '#f8fafc');
                return `background:${fill};border-color:${stroke};color:#374151`;
            },

            statusLabel(s) {
                return this.statusLabels[s] ?? s;
            },

            toothTypeName(num) {
                return this.toothTypeMap[num] ?? '';
            },

            countStatus(status) {
                return Object.values(this.teeth).filter(t => t.status === status).length;
            },

            submitChart(form) {
                this.$refs.toothDataInput.value = JSON.stringify(this.teeth);
                form.submit();
            },
        };
    }
    </script>
    @endpush

</x-dashboard-layout>
