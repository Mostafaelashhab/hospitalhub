<x-dashboard-layout>
    <x-slot name="title">{{ __('app.chart_history') }} — {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.chart_history') }}</x-slot>

    <x-slot name="sidebar">
        @if(auth()->user()->role === 'doctor' && request()->is('doctor/*'))
            @include('partials.doctor-sidebar', ['active' => 'dental-chart'])
        @else
            @include('partials.dashboard-sidebar', ['active' => 'patients'])
        @endif
    </x-slot>

    @php $isDoctor = auth()->user()->role === 'doctor' && request()->is('doctor/*'); @endphp
    {{-- Back --}}
    <div class="mb-5">
        <a href="{{ $isDoctor ? route('doctor.dental-chart.show', $patient) : route('dashboard.patients.dental-chart.show', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.dental_chart') }}
        </a>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.chart_history') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $patient->name }}</p>
        </div>
        <a href="{{ $isDoctor ? route('doctor.dental-chart.show', $patient) : route('dashboard.patients.dental-chart.show', $patient) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.dental_chart') }}
        </a>
    </div>

    @if($charts->isEmpty())
    {{-- Empty state --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
        <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <p class="text-gray-500 text-sm">{{ __('app.no_dental_charts') }}</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($charts as $chart)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:border-indigo-200 transition-colors">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                {{-- Left: date + doctor + summary --}}
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">
                            {{ $chart->created_at->format('d M Y') }}
                            <span class="text-gray-400 font-normal text-xs ms-2">{{ $chart->created_at->format('H:i') }}</span>
                        </p>
                        @if($chart->doctor)
                        <p class="text-xs text-gray-500 mt-0.5">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $chart->doctor->name }}
                            </span>
                        </p>
                        @endif
                        {{-- Mini status summary --}}
                        @php
                            $toothData = $chart->tooth_data ?? [];
                            $counts = [];
                            foreach ($toothData as $tooth) {
                                $s = $tooth['status'] ?? 'healthy';
                                if ($s !== 'healthy') {
                                    $counts[$s] = ($counts[$s] ?? 0) + 1;
                                }
                            }
                            $statusColors = [
                                'cavity'     => 'bg-red-100 text-red-700',
                                'filling'    => 'bg-gray-100 text-gray-700',
                                'crown'      => 'bg-yellow-100 text-yellow-700',
                                'extraction' => 'bg-gray-800 text-white',
                                'implant'    => 'bg-blue-100 text-blue-700',
                                'root_canal' => 'bg-orange-100 text-orange-700',
                                'bridge'     => 'bg-purple-100 text-purple-700',
                                'veneer'     => 'bg-cyan-100 text-cyan-700',
                                'missing'    => 'bg-slate-100 text-slate-600',
                            ];
                        @endphp
                        @if(count($counts) > 0)
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach($counts as $status => $count)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $count }} {{ __('app.' . $status) }}
                            </span>
                            @endforeach
                        </div>
                        @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 mt-2">
                            {{ __('app.healthy') }}
                        </span>
                        @endif
                        @if($chart->notes)
                        <p class="text-xs text-gray-400 mt-1.5 italic line-clamp-1">{{ $chart->notes }}</p>
                        @endif
                    </div>
                </div>

                {{-- Right: view button --}}
                <div class="shrink-0">
                    {{-- We link back to the main chart page; future enhancement: view a specific chart snapshot --}}
                    <a href="{{ route('dashboard.patients.dental-chart.show', $patient) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        {{ __('app.view') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($charts->hasPages())
    <div class="mt-6">
        {{ $charts->links() }}
    </div>
    @endif
    @endif

</x-dashboard-layout>
