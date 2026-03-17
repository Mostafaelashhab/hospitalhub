<x-dashboard-layout>
    <x-slot name="title">{{ __('app.growth_chart') }} – {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.growth_tracking') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    {{-- Back button --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.patients.show', $patient) }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('app.back') }}
        </a>
    </div>

    {{-- Patient header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            {{-- Avatar --}}
            <div class="w-14 h-14 rounded-2xl flex-shrink-0 bg-gradient-to-br {{ $patient->gender === 'male' ? 'from-blue-500 to-cyan-600 shadow-blue-500/20' : 'from-pink-500 to-rose-600 shadow-pink-500/20' }} flex items-center justify-center text-xl font-bold text-white shadow-lg">
                {{ mb_substr($patient->name, 0, 1) }}
            </div>

            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $patient->name }}</h2>
                <div class="flex flex-wrap items-center gap-2 mt-1">
                    @if($ageInMonths !== null)
                    <span class="text-xs text-gray-500">
                        {{ __('app.age_months') }}: <strong class="text-gray-700">{{ $ageInMonths }}</strong>
                    </span>
                    @endif
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg border
                        {{ $patient->gender === 'male' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-pink-50 text-pink-700 border-pink-200' }}">
                        @if($patient->gender === 'male')
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M11 2a1 1 0 011-1h5a1 1 0 011 1v5a1 1 0 01-2 0V4.414l-5.293 5.293a1 1 0 01-1.414-1.414L14.586 3H13a1 1 0 01-1-1z"/>
                            <path fill-rule="evenodd" d="M5 9a4 4 0 100 8 4 4 0 000-8zm-6 4a6 6 0 1112 0A6 6 0 01-1 13z" clip-rule="evenodd"/>
                        </svg>
                        @else
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a5 5 0 100 10A5 5 0 0010 2zm0 12a7 7 0 110-14 7 7 0 010 14z" clip-rule="evenodd"/>
                            <path d="M10 14v4m-2-2h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        @endif
                        {{ __('app.' . $patient->gender) }}
                    </span>
                </div>
            </div>

            <div class="{{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }} flex items-center gap-3">
                <a href="{{ route('dashboard.patients.show', $patient) }}#vitals"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white rounded-xl shadow-sm
                          {{ $patient->gender === 'male' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-pink-600 hover:bg-pink-700' }} transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('app.add_measurement') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Last measurement summary card --}}
    @if($lastVital)
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @if($lastVital->weight)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4">
            <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.weight_kg') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $lastVital->weight }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $lastVital->created_at->format('Y-m-d') }}</p>
        </div>
        @endif
        @if($lastVital->height)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4">
            <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.height_cm') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $lastVital->height }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $lastVital->created_at->format('Y-m-d') }}</p>
        </div>
        @endif
        @if($lastVital->weight && $lastVital->height && $lastVital->height > 0)
        @php
            $hm  = $lastVital->height / 100;
            $bmi = round($lastVital->weight / ($hm * $hm), 1);
        @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4">
            <p class="text-xs text-gray-400 font-medium mb-1">BMI</p>
            <p class="text-2xl font-bold text-gray-900">{{ $bmi }}</p>
            <p class="text-xs text-gray-400 mt-1">kg/m²</p>
        </div>
        @endif
        @if($ageInMonths !== null)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4">
            <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.age_months') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $ageInMonths }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ __('app.months') ?? 'months' }}</p>
        </div>
        @endif
    </div>
    @endif

    {{-- No measurements notice --}}
    @if(empty($weightMeasurements) && empty($heightMeasurements))
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 mb-6 flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-amber-800">{{ __('app.no_measurements') }}</p>
    </div>
    @endif

    {{-- Charts --}}
    <div class="space-y-6">
        {{-- Weight & Height side-by-side on larger screens --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            {{-- Weight-for-Age Chart --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                    {{ __('app.weight_for_age') }}
                    <span class="text-xs font-normal text-gray-400 {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">
                        ({{ __('app.weight_kg') }})
                    </span>
                </h3>
                <div class="relative" style="height: 340px;">
                    <canvas id="weightChart"></canvas>
                </div>
            </div>

            {{-- Height-for-Age Chart --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>
                    {{ __('app.height_for_age') }}
                    <span class="text-xs font-normal text-gray-400 {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">
                        ({{ __('app.height_cm') }})
                    </span>
                </h3>
                <div class="relative" style="height: 340px;">
                    <canvas id="heightChart"></canvas>
                </div>
            </div>
        </div>

        {{-- BMI Chart --}}
        @if($showBmiChart)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-violet-500 inline-block"></span>
                {{ __('app.bmi_for_age') }}
                <span class="text-xs font-normal text-gray-400 {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">(kg/m²)</span>
            </h3>
            <div class="relative" style="height: 300px;">
                <canvas id="bmiChart"></canvas>
            </div>
        </div>
        @endif
    </div>

    {{-- Percentile legend --}}
    <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-sm font-bold text-gray-700 mb-3">{{ __('app.percentile') }} {{ __('app.growth_chart') }}</h3>
        <div class="flex flex-wrap gap-4 text-xs text-gray-600">
            <div class="flex items-center gap-1.5">
                <span class="w-8 h-1.5 rounded-full inline-block" style="background:#ef4444;"></span>
                3rd {{ __('app.percentile') }}
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-8 h-1.5 rounded-full inline-block" style="background:#f97316;"></span>
                15th {{ __('app.percentile') }}
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-8 h-1.5 rounded-full inline-block" style="background:#22c55e;"></span>
                50th {{ __('app.percentile') }}
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-8 h-1.5 rounded-full inline-block" style="background:#f97316;"></span>
                85th {{ __('app.percentile') }}
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-8 h-1.5 rounded-full inline-block" style="background:#ef4444;"></span>
                97th {{ __('app.percentile') }}
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-8 h-3 rounded inline-block" style="background:#6366f1;"></span>
                {{ $patient->name }} ({{ __('app.growth_tracking') }})
            </div>
        </div>
    </div>

    {{-- Chart.js Initialisation Script --}}
    <script>
    (function () {
        // ── Raw data from PHP ──────────────────────────────────────────────────
        const weightStd = @json($weightStandards);
        const heightStd = @json($heightStandards);
        const bmiStd    = @json($bmiStandards);

        const weightMeasurements = @json($weightMeasurements);
        const heightMeasurements = @json($heightMeasurements);
        const bmiMeasurements    = @json($bmiMeasurements);

        const ageMonths = {{ $ageInMonths ?? 'null' }};
        const locale    = '{{ app()->getLocale() }}';

        // ── Labels translation ─────────────────────────────────────────────────
        const t = {
            ageMonths:  locale === 'ar' ? 'العمر (شهور)' : 'Age (months)',
            weightKg:   locale === 'ar' ? 'الوزن (كجم)'  : 'Weight (kg)',
            heightCm:   locale === 'ar' ? 'الطول (سم)'   : 'Height (cm)',
            bmi:        'BMI (kg/m²)',
            patient:    locale === 'ar' ? 'المريض'        : 'Patient',
        };

        // ── Shared percentile dataset factory ─────────────────────────────────
        function percentileDatasets(std) {
            return [
                {
                    label: '97th',
                    data: std.months.map((m, i) => ({ x: m, y: std.p97[i] })),
                    borderColor: 'rgba(239,68,68,0.6)',
                    backgroundColor: 'rgba(239,68,68,0.08)',
                    borderWidth: 1.5,
                    borderDash: [4, 3],
                    pointRadius: 0,
                    fill: false,
                    tension: 0.4,
                    order: 10,
                },
                {
                    label: '85th',
                    data: std.months.map((m, i) => ({ x: m, y: std.p85[i] })),
                    borderColor: 'rgba(249,115,22,0.6)',
                    backgroundColor: 'rgba(249,115,22,0.10)',
                    borderWidth: 1.5,
                    borderDash: [4, 3],
                    pointRadius: 0,
                    fill: '+1', // fill toward 97th
                    tension: 0.4,
                    order: 9,
                },
                {
                    label: '50th',
                    data: std.months.map((m, i) => ({ x: m, y: std.p50[i] })),
                    borderColor: 'rgba(34,197,94,0.9)',
                    backgroundColor: 'rgba(34,197,94,0.08)',
                    borderWidth: 2,
                    pointRadius: 0,
                    fill: false,
                    tension: 0.4,
                    order: 8,
                },
                {
                    label: '15th',
                    data: std.months.map((m, i) => ({ x: m, y: std.p15[i] })),
                    borderColor: 'rgba(249,115,22,0.6)',
                    backgroundColor: 'rgba(249,115,22,0.10)',
                    borderWidth: 1.5,
                    borderDash: [4, 3],
                    pointRadius: 0,
                    fill: '-1', // fill toward 50th
                    tension: 0.4,
                    order: 7,
                },
                {
                    label: '3rd',
                    data: std.months.map((m, i) => ({ x: m, y: std.p3[i] })),
                    borderColor: 'rgba(239,68,68,0.6)',
                    backgroundColor: 'rgba(239,68,68,0.08)',
                    borderWidth: 1.5,
                    borderDash: [4, 3],
                    pointRadius: 0,
                    fill: '+1', // fill toward 15th
                    tension: 0.4,
                    order: 6,
                },
            ];
        }

        // ── Patient measurement dataset ────────────────────────────────────────
        function patientDataset(measurements, label, color) {
            return {
                label: label,
                data: measurements.map(m => ({ x: m.x, y: m.y })),
                borderColor: color,
                backgroundColor: color,
                borderWidth: 2.5,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: color,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                fill: false,
                tension: 0.35,
                order: 1,
                z: 10,
            };
        }

        // ── Shared chart options factory ───────────────────────────────────────
        function chartOptions(xLabel, yLabel) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 14,
                            boxHeight: 14,
                            padding: 12,
                            font: { size: 11 },
                            filter: (item) => {
                                // Only show patient + median in legend to keep it clean
                                return ['50th', t.patient].includes(item.text);
                            },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            title: (items) => xLabel + ': ' + items[0].parsed.x,
                            label: (item) => ' ' + item.dataset.label + ': ' + item.parsed.y,
                        },
                    },
                },
                scales: {
                    x: {
                        type: 'linear',
                        min: 0,
                        max: 60,
                        title: {
                            display: true,
                            text: xLabel,
                            font: { size: 11 },
                            color: '#6b7280',
                        },
                        ticks: {
                            stepSize: 6,
                            font: { size: 10 },
                            color: '#9ca3af',
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: yLabel,
                            font: { size: 11 },
                            color: '#6b7280',
                        },
                        ticks: {
                            font: { size: 10 },
                            color: '#9ca3af',
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                        },
                    },
                },
            };
        }

        // ── Render charts after DOM ready ─────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {

            // Weight Chart
            new Chart(document.getElementById('weightChart'), {
                type: 'line',
                data: {
                    datasets: [
                        ...percentileDatasets(weightStd),
                        patientDataset(weightMeasurements, t.patient, '#6366f1'),
                    ],
                },
                options: chartOptions(t.ageMonths, t.weightKg),
            });

            // Height Chart
            new Chart(document.getElementById('heightChart'), {
                type: 'line',
                data: {
                    datasets: [
                        ...percentileDatasets(heightStd),
                        patientDataset(heightMeasurements, t.patient, '#0ea5e9'),
                    ],
                },
                options: chartOptions(t.ageMonths, t.heightCm),
            });

            // BMI Chart (only rendered if element exists)
            const bmiEl = document.getElementById('bmiChart');
            if (bmiEl) {
                new Chart(bmiEl, {
                    type: 'line',
                    data: {
                        datasets: [
                            ...percentileDatasets(bmiStd),
                            patientDataset(bmiMeasurements, t.patient, '#8b5cf6'),
                        ],
                    },
                    options: chartOptions(t.ageMonths, t.bmi),
                });
            }
        });
    })();
    </script>

</x-dashboard-layout>
