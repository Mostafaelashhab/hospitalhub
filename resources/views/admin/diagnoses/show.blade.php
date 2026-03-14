<x-dashboard-layout>
    <x-slot name="title">{{ __('app.diagnosis') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.diagnosis') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'appointments'])
    </x-slot>

    {{-- Diagram Enhancement Styles --}}
    <style>
        .show-diagram-viewport { overflow: hidden; position: relative; }
        .show-diagram-viewport svg { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
        @keyframes showZonePulse { 0%,100%{filter:brightness(1)} 50%{filter:brightness(1.15)} }
        .show-diagram-viewport svg [style*="818cf8"] { animation: showZonePulse 2s ease-in-out infinite; }
        .show-diagram-fullscreen { position: fixed !important; inset: 0; z-index: 9999; border-radius: 0 !important; display: flex; flex-direction: column; }
        .show-diagram-fullscreen .show-diagram-viewport { flex: 1; max-height: calc(100vh - 60px); }
        .show-diagram-grid { background-image: radial-gradient(circle, #e5e7eb 1px, transparent 1px); background-size: 20px 20px; }
        /* Zone hover enhancement - all specialties */
        .show-diagram-viewport svg .gen-zone:hover,
        .show-diagram-viewport svg .ent-zone:hover,
        .show-diagram-viewport svg .bone-zone:hover,
        .show-diagram-viewport svg .zone-area:hover,
        .show-diagram-viewport svg .uro-zone:hover,
        .show-diagram-viewport svg .gyn-zone:hover,
        .show-diagram-viewport svg .dig-zone:hover,
        .show-diagram-viewport svg .brain-zone:hover,
        .show-diagram-viewport svg .derm-zone:hover,
        .show-diagram-viewport svg .cursor-pointer:hover { filter: drop-shadow(0 0 8px rgba(99,102,241,0.4)) brightness(1.08); transition: filter 0.2s ease; cursor: pointer; }

        /* Selected zone glow on show page */
        .show-diagram-viewport svg [style*="818cf8"] { filter: drop-shadow(0 0 12px rgba(99,102,241,0.5)) drop-shadow(0 0 4px rgba(99,102,241,0.3)); }
    </style>

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
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-teal-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('app.diagnosis') }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $diagnosis->patient->name ?? '-' }} &mdash; {{ $diagnosis->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
        <a href="{{ route('dashboard.diagnoses.create', $diagnosis->appointment) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/25 transition-all hover:shadow-indigo-500/40 hover:scale-[1.02]">
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
             isSelected(zone) { return this.selectedZones.includes(zone); },
             zoom: 1,
             isFullscreen: false,
             zoomIn() { this.zoom = Math.min(this.zoom + 0.25, 3); },
             zoomOut() { this.zoom = Math.max(this.zoom - 0.25, 0.5); },
             resetZoom() { this.zoom = 1; },
             toggleFullscreen() { this.isFullscreen = !this.isFullscreen; if(!this.isFullscreen) this.zoom = 1; }
         }"
         @keydown.escape.window="isFullscreen = false">

        {{-- Diagram --}}
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-lg shadow-gray-200/50 overflow-hidden transition-all duration-300"
                 :class="{ 'show-diagram-fullscreen': isFullscreen }">

                {{-- Toolbar --}}
                <div class="px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-indigo-500"></div>
                            <h3 class="text-sm font-bold text-gray-900">{{ __('app.interactive_diagram') }}</h3>
                        </div>
                        <span class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold tracking-wide uppercase bg-indigo-50 text-indigo-500">
                            {{ ucfirst(str_replace('-', ' ', $diagramType)) }}
                        </span>
                        @if(count($selectedZones) > 0)
                        <span class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-600">
                            {{ count($selectedZones) }} {{ app()->getLocale() === 'ar' ? 'منطقة' : 'zones' }}
                        </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-1">
                        {{-- Zoom controls --}}
                        <div class="flex items-center rounded-lg border border-gray-200 bg-white overflow-hidden">
                            <button type="button" @click="zoomOut()" class="p-1.5 transition-colors hover:bg-gray-50 text-gray-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 12h14"/></svg>
                            </button>
                            <button type="button" @click="resetZoom()" class="px-2 py-1 text-[10px] font-bold border-x border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors min-w-[40px] text-center"
                                    x-text="Math.round(zoom * 100) + '%'"></button>
                            <button type="button" @click="zoomIn()" class="p-1.5 transition-colors hover:bg-gray-50 text-gray-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 5v14m-7-7h14"/></svg>
                            </button>
                        </div>

                        {{-- Fullscreen --}}
                        <button type="button" @click="toggleFullscreen()" class="p-2 rounded-lg border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 transition-all">
                            <svg x-show="!isFullscreen" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            <svg x-show="isFullscreen" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Diagram Viewport --}}
                <div class="show-diagram-viewport show-diagram-grid p-4 sm:p-6 bg-gradient-to-br from-slate-50 via-white to-slate-50">
                    <div class="flex justify-center items-center" :style="'min-height:' + (isFullscreen ? 'calc(100vh - 120px)' : '400px')">
                        <div :style="'transform: scale(' + zoom + '); transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);'" class="w-full">
                            @include('components.diagrams.' . $diagramType)
                        </div>
                    </div>
                </div>

                {{-- Bottom bar --}}
                <div class="px-4 py-2 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <span class="flex items-center gap-1.5 text-[10px] font-medium text-gray-400">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        {{ app()->getLocale() === 'ar' ? 'وضع المعاينة' : 'View Mode' }}
                    </span>
                    <kbd class="hidden sm:inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-mono bg-white text-gray-400 border border-gray-200 shadow-sm">ESC</kbd>
                </div>
            </div>

            {{-- Marked Areas --}}
            @if(count($selectedZones) > 0)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-lg shadow-gray-200/50 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50/50 to-purple-50/30 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">{{ __('app.marked_areas') }}</h3>
                        <p class="text-[10px] text-gray-500 mt-0.5">{{ count($selectedZones) }} {{ app()->getLocale() === 'ar' ? 'منطقة محددة' : 'zones marked' }}</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($selectedZones as $idx => $zone)
                        <div class="flex items-start gap-3 p-3.5 bg-gradient-to-r from-indigo-50 to-purple-50/30 rounded-xl border border-indigo-100/80 hover:shadow-md hover:border-indigo-200 transition-all">
                            <span class="w-6 h-6 rounded-lg bg-indigo-600 flex items-center justify-center text-[9px] font-bold text-white shrink-0 shadow-sm shadow-indigo-500/30">{{ $idx + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-indigo-900 capitalize">{{ str_replace('_', ' ', $zone) }}</p>
                                @if(!empty($zoneNotes[$zone]))
                                <p class="text-xs text-indigo-600/80 mt-1 flex items-start gap-1">
                                    <svg class="w-3 h-3 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                    {{ $zoneNotes[$zone] }}
                                </p>
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
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-lg shadow-gray-200/50 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-orange-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">{{ __('app.complaint') }}</h4>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $diagnosis->complaint }}</p>
            </div>
            @endif

            @if($diagnosis->diagnosis)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-lg shadow-gray-200/50 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-teal-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">{{ __('app.diagnosis_text') }}</h4>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $diagnosis->diagnosis }}</p>
            </div>
            @endif

            @if($diagnosis->prescription && $diagnosis->prescription->items->count())
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-lg shadow-gray-200/50 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900">{{ __('app.prescription') }}</h4>
                    </div>
                    <a href="{{ route('dashboard.prescriptions.print', $diagnosis->prescription) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        {{ __('app.print') }}
                    </a>
                </div>
                <div class="space-y-3">
                    @foreach($diagnosis->prescription->items as $index => $item)
                    <div class="bg-gray-50 rounded-xl p-3">
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="w-5 h-5 bg-indigo-100 text-indigo-700 rounded-md flex items-center justify-center text-[10px] font-bold">{{ $index + 1 }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $item->drug_name }}</span>
                        </div>
                        <div class="flex flex-wrap gap-3 {{ app()->getLocale() === 'ar' ? 'pr-7' : 'pl-7' }}">
                            @if($item->dosage)
                            <span class="text-xs text-gray-500"><span class="font-semibold text-gray-700">{{ __('app.dosage') }}:</span> {{ $item->dosage }}</span>
                            @endif
                            @if($item->frequency)
                            <span class="text-xs text-gray-500"><span class="font-semibold text-gray-700">{{ __('app.frequency') }}:</span> {{ __('app.' . $item->frequency) }}</span>
                            @endif
                            @if($item->duration)
                            <span class="text-xs text-gray-500"><span class="font-semibold text-gray-700">{{ __('app.duration') }}:</span> {{ $item->duration }}</span>
                            @endif
                            @if($item->instructions)
                            <span class="text-xs text-gray-500"><span class="font-semibold text-gray-700">{{ __('app.instructions') }}:</span> {{ $item->instructions }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($diagnosis->prescription->notes)
                <div class="mt-3 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2">
                    <p class="text-xs text-amber-700"><span class="font-semibold">{{ __('app.notes') }}:</span> {{ $diagnosis->prescription->notes }}</p>
                </div>
                @endif
            </div>
            @elseif($diagnosis->prescription_text ?? $diagnosis->prescription)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-lg shadow-gray-200/50 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">{{ __('app.prescription') }}</h4>
                </div>
                <div class="space-y-1.5">
                    @foreach(array_filter(explode("\n", $diagnosis->prescription)) as $drug)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                        {{ trim($drug) }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($diagnosis->lab_tests)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-lg shadow-gray-200/50 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">{{ __('app.lab_tests') }}</h4>
                </div>
                <div class="space-y-1.5">
                    @foreach(array_filter(explode("\n", $diagnosis->lab_tests)) as $test)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                        {{ trim($test) }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($diagnosis->radiology)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-lg shadow-gray-200/50 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">{{ __('app.radiology') }}</h4>
                </div>
                <div class="space-y-1.5">
                    @foreach(array_filter(explode("\n", $diagnosis->radiology)) as $radio)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>
                        {{ trim($radio) }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($diagnosis->notes)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-lg shadow-gray-200/50 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">{{ __('app.notes') }}</h4>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $diagnosis->notes }}</p>
            </div>
            @endif

            {{-- Doctor & Date Info --}}
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl shadow-lg shadow-gray-900/20 p-6 text-white">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">{{ __('app.doctor') }}</p>
                            <p class="text-sm font-bold text-white">{{ $diagnosis->doctor->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="border-t border-white/10 pt-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">{{ __('app.date') }}</p>
                            <p class="text-sm font-bold text-white">{{ $diagnosis->created_at->format('Y-m-d h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
