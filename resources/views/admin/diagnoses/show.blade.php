<x-dashboard-layout>
    <x-slot name="title">{{ __('app.diagnosis') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.diagnosis') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'appointments'])
    </x-slot>

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.appointments.show', $diagnosis->appointment) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('app.diagnosis') }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $diagnosis->patient->name ?? '-' }} &mdash; {{ $diagnosis->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
        <a href="{{ route('dashboard.diagnoses.create', $diagnosis->appointment) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            {{ __('app.edit') }}
        </a>
    </div>

    @php
        $diagramData = $diagnosis->diagram_data ?? [];
        $selectedZones = $diagramData['selected_zones'] ?? [];
        $zoneNotes = $diagramData['zone_notes'] ?? [];
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6"
         x-data="{
             selectedZones: {{ json_encode($selectedZones) }},
             zoneNotes: {{ json_encode((object)$zoneNotes) }},
             toggleZone() {},
             isSelected(zone) { return this.selectedZones.includes(zone); }
         }">
        {{-- Diagram --}}
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">{{ __('app.interactive_diagram') }}</h3>
                </div>
                <div class="p-6">
                    <div class="flex justify-center">
                        @include('components.diagrams.' . $diagramType)
                    </div>
                </div>
            </div>

            {{-- Marked Areas --}}
            @if(count($selectedZones) > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">{{ __('app.marked_areas') }} ({{ count($selectedZones) }})</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($selectedZones as $zone)
                        <div class="flex items-start gap-3 p-3 bg-indigo-50 rounded-xl border border-indigo-100">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 mt-1.5 shrink-0"></span>
                            <div>
                                <p class="text-sm font-semibold text-indigo-800">{{ str_replace('_', ' ', ucfirst($zone)) }}</p>
                                @if(!empty($zoneNotes[$zone]))
                                <p class="text-xs text-indigo-600 mt-0.5">{{ $zoneNotes[$zone] }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Text Fields --}}
        <div class="space-y-6">
            @if($diagnosis->complaint)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ __('app.complaint') }}</h4>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $diagnosis->complaint }}</p>
            </div>
            @endif

            @if($diagnosis->diagnosis)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ __('app.diagnosis_text') }}</h4>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $diagnosis->diagnosis }}</p>
            </div>
            @endif

            @if($diagnosis->prescription)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ __('app.prescription') }}</h4>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $diagnosis->prescription }}</p>
            </div>
            @endif

            @if($diagnosis->notes)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ __('app.notes') }}</h4>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $diagnosis->notes }}</p>
            </div>
            @endif

            {{-- Doctor & Date Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('app.doctor') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $diagnosis->doctor->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('app.date') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $diagnosis->created_at->format('Y-m-d h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
