<x-dashboard-layout>
    <x-slot name="title">{{ __('app.diagnosis') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.diagnosis') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'appointments'])
    </x-slot>

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.appointments.show', $appointment) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    {{-- Page Header --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center">
            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('app.diagnosis') }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $appointment->patient->name ?? '-' }} &mdash; {{ __('app.appointment') }} #{{ $appointment->id }}</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('dashboard.diagnoses.store', $appointment) }}"
          x-data="{
              selectedZones: {{ json_encode($diagnosis?->diagram_data['selected_zones'] ?? []) }},
              zoneNotes: {{ json_encode($diagnosis?->diagram_data['zone_notes'] ?? (object)[]) }},
              activeZone: null,
              activeNote: '',
              toggleZone(zone) {
                  const idx = this.selectedZones.indexOf(zone);
                  if (idx > -1) {
                      this.selectedZones.splice(idx, 1);
                      delete this.zoneNotes[zone];
                      if (this.activeZone === zone) this.activeZone = null;
                  } else {
                      this.selectedZones.push(zone);
                      this.activeZone = zone;
                      this.activeNote = this.zoneNotes[zone] || '';
                  }
                  this.syncDiagramData();
              },
              selectZone(zone) {
                  this.activeZone = zone;
                  this.activeNote = this.zoneNotes[zone] || '';
              },
              saveNote() {
                  if (this.activeZone) {
                      if (this.activeNote.trim()) {
                          this.zoneNotes[this.activeZone] = this.activeNote.trim();
                      } else {
                          delete this.zoneNotes[this.activeZone];
                      }
                      this.syncDiagramData();
                  }
              },
              syncDiagramData() {
                  this.$refs.diagramInput.value = JSON.stringify({
                      selected_zones: this.selectedZones,
                      zone_notes: this.zoneNotes
                  });
              },
              isSelected(zone) {
                  return this.selectedZones.includes(zone);
              }
          }"
          x-init="syncDiagramData()">
        @csrf
        <input type="hidden" name="diagram_data" x-ref="diagramInput" value="">

        {{-- Global Diagram Styles --}}
        <style>
            /* Zoom & Pan */
            .diagram-viewport { overflow: hidden; position: relative; }
            .diagram-viewport svg { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); transform-origin: center center; }

            /* Selected zone pulse animation */
            @keyframes zonePulse { 0%,100%{filter:brightness(1)} 50%{filter:brightness(1.15)} }
            .diagram-viewport svg [style*="818cf8"], .diagram-viewport svg [fill="#818cf8"] { animation: zonePulse 2s ease-in-out infinite; }

            /* Fullscreen */
            .diagram-fullscreen { position: fixed !important; inset: 0; z-index: 9999; border-radius: 0 !important; max-height: 100vh; display: flex; flex-direction: column; }
            .diagram-fullscreen .diagram-viewport { flex: 1; max-height: calc(100vh - 60px); }
            .diagram-fullscreen .diagram-viewport svg { max-height: calc(100vh - 70px); }

            /* Grid pattern bg */
            .diagram-grid { background-image: radial-gradient(circle, #e5e7eb 1px, transparent 1px); background-size: 20px 20px; }

            /* Zone hover enhancement - all specialties */
            .diagram-viewport svg .gen-zone:hover,
            .diagram-viewport svg .ent-zone:hover,
            .diagram-viewport svg .bone-zone:hover,
            .diagram-viewport svg .zone-area:hover,
            .diagram-viewport svg .uro-zone:hover,
            .diagram-viewport svg .gyn-zone:hover,
            .diagram-viewport svg .dig-zone:hover,
            .diagram-viewport svg .brain-zone:hover,
            .diagram-viewport svg .derm-zone:hover,
            .diagram-viewport svg .cr-zone:hover,
            .diagram-viewport svg .cursor-pointer:hover { filter: drop-shadow(0 0 8px rgba(99,102,241,0.4)) brightness(1.08); transition: filter 0.2s ease; }

            /* Selected zone glow */
            .diagram-viewport svg .gen-zone[style*="818cf8"],
            .diagram-viewport svg .ent-zone[style*="818cf8"],
            .diagram-viewport svg .bone-zone[style*="818cf8"],
            .diagram-viewport svg .zone-area[style*="818cf8"],
            .diagram-viewport svg .uro-zone[style*="818cf8"],
            .diagram-viewport svg .gyn-zone[style*="818cf8"],
            .diagram-viewport svg .dig-zone[style*="818cf8"],
            .diagram-viewport svg .brain-zone[style*="818cf8"],
            .diagram-viewport svg .derm-zone[style*="818cf8"],
            .diagram-viewport svg .cr-zone[style*="818cf8"],
            .diagram-viewport svg .cursor-pointer[style*="818cf8"] { filter: drop-shadow(0 0 12px rgba(99,102,241,0.5)) drop-shadow(0 0 4px rgba(99,102,241,0.3)); }

            /* Crosshair cursor in diagram */
            .diagram-viewport svg { cursor: crosshair; }
            .diagram-viewport svg [class*="zone"]:hover,
            .diagram-viewport svg .cursor-pointer:hover { cursor: pointer; }

            /* Mini-map */
            .diagram-minimap { position: absolute; bottom: 12px; {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 12px; width: 120px; height: 90px; border: 2px solid rgba(99,102,241,0.3); border-radius: 8px; overflow: hidden; background: rgba(255,255,255,0.9); backdrop-filter: blur(4px); pointer-events: none; opacity: 0; transition: opacity 0.3s ease; }
            .diagram-viewport:hover .diagram-minimap { opacity: 1; }

            /* Selected zone badge animation */
            @keyframes badgeIn { from{transform:scale(0);opacity:0} to{transform:scale(1);opacity:1} }
            .zone-badge { animation: badgeIn 0.3s cubic-bezier(0.34,1.56,0.64,1); }
        </style>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Diagram Section --}}
            <div class="xl:col-span-2 space-y-6"
                 x-data="{
                     zoom: 1,
                     isFullscreen: false,
                     minZoom: 0.5,
                     maxZoom: 3,
                     zoomIn() { this.zoom = Math.min(this.zoom + 0.25, this.maxZoom); },
                     zoomOut() { this.zoom = Math.max(this.zoom - 0.25, this.minZoom); },
                     resetZoom() { this.zoom = 1; },
                     toggleFullscreen() { this.isFullscreen = !this.isFullscreen; if(!this.isFullscreen) this.zoom = 1; }
                 }"
                 @keydown.escape.window="isFullscreen = false">

                {{-- Interactive Diagram --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-lg shadow-gray-200/50 overflow-hidden transition-all duration-300"
                     :class="{ 'diagram-fullscreen': isFullscreen }">

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
                        </div>

                        <div class="flex items-center gap-1">
                            {{-- Zone counter --}}
                            <div x-show="selectedZones.length > 0" x-transition
                                 class="hidden sm:flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-100 text-indigo-700 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="selectedZones.length"></span>
                            </div>

                            {{-- Zoom controls --}}
                            <div class="flex items-center rounded-lg border border-gray-200 bg-white overflow-hidden">
                                <button type="button" @click="zoomOut()" class="p-1.5 transition-colors hover:bg-gray-50 text-gray-600 disabled:text-gray-300" :disabled="zoom <= minZoom">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 12h14"/></svg>
                                </button>
                                <button type="button" @click="resetZoom()" class="px-2 py-1 text-[10px] font-bold border-x border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors min-w-[40px] text-center"
                                        x-text="Math.round(zoom * 100) + '%'"></button>
                                <button type="button" @click="zoomIn()" class="p-1.5 transition-colors hover:bg-gray-50 text-gray-600 disabled:text-gray-300" :disabled="zoom >= maxZoom">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 5v14m-7-7h14"/></svg>
                                </button>
                            </div>

                            {{-- Fullscreen toggle --}}
                            <button type="button" @click="toggleFullscreen()" class="p-2 rounded-lg border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-all">
                                <svg x-show="!isFullscreen" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                <svg x-show="isFullscreen" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Diagram Viewport --}}
                    <div class="diagram-viewport diagram-grid p-4 sm:p-6 bg-gradient-to-br from-slate-50 via-white to-slate-50">
                        <div class="flex justify-center items-center" :style="'min-height:' + (isFullscreen ? 'calc(100vh - 120px)' : '400px')">
                            <div :style="'transform: scale(' + zoom + '); transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);'" class="w-full">
                                @include('components.diagrams.' . $diagramType)
                            </div>
                        </div>

                        {{-- Mini-map (shows when zoomed in) --}}
                        <div class="diagram-minimap" x-show="zoom > 1" x-transition.opacity>
                            <div style="transform: scale(0.15); transform-origin: top left; pointer-events: none; opacity: 0.6;">
                                @include('components.diagrams.' . $diagramType)
                            </div>
                        </div>
                    </div>

                    {{-- Bottom status bar --}}
                    <div class="px-4 py-2 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1.5 text-[10px] font-medium text-gray-400">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                {{ app()->getLocale() === 'ar' ? 'تفاعلي' : 'Interactive' }}
                            </span>
                            <span class="text-[10px] text-gray-300">|</span>
                            <span class="text-[10px] text-gray-400">
                                {{ app()->getLocale() === 'ar' ? 'اضغط على المنطقة لتحديدها' : 'Click zones to select' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <kbd class="hidden sm:inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-mono bg-white text-gray-400 border border-gray-200 shadow-sm">ESC</kbd>
                        </div>
                    </div>
                </div>

                {{-- Selected Zones --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-lg shadow-gray-200/50 overflow-hidden" x-show="selectedZones.length > 0" x-transition>
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50/50 to-purple-50/30 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">{{ __('app.marked_areas') }}</h3>
                                <p class="text-[10px] text-gray-500 mt-0.5"><span x-text="selectedZones.length"></span> {{ app()->getLocale() === 'ar' ? 'منطقة محددة' : 'zones selected' }}</p>
                            </div>
                        </div>
                        <button type="button" @click="selectedZones = []; zoneNotes = {}; activeZone = null; syncDiagramData();"
                                class="text-xs text-red-500 hover:text-red-700 font-semibold transition-colors px-2 py-1 rounded-md hover:bg-red-50">
                            {{ app()->getLocale() === 'ar' ? 'مسح الكل' : 'Clear all' }}
                        </button>
                    </div>
                    <div class="p-5">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <template x-for="(zone, idx) in selectedZones" :key="zone">
                                <button type="button"
                                        @click="selectZone(zone)"
                                        :class="activeZone === zone
                                            ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-500/25 scale-105'
                                            : 'bg-white text-gray-700 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 shadow-sm'"
                                        class="zone-badge inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-xl border transition-all duration-200">
                                    <span class="w-5 h-5 rounded-md flex items-center justify-center text-[9px] font-bold"
                                          :class="activeZone === zone ? 'bg-white/20 text-white' : 'bg-indigo-100 text-indigo-600'"
                                          x-text="idx + 1"></span>
                                    <span x-text="zone.replace(/_/g, ' ')" class="capitalize"></span>
                                    <span x-show="zoneNotes[zone]" class="w-1.5 h-1.5 rounded-full" :class="activeZone === zone ? 'bg-yellow-300' : 'bg-amber-400'"></span>
                                    <svg @click.stop="toggleZone(zone)" class="w-3.5 h-3.5 opacity-50 hover:opacity-100 transition-opacity"
                                         :class="activeZone === zone ? 'text-white' : 'text-gray-400 hover:text-red-500'"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </template>
                        </div>

                        {{-- Zone Note Input --}}
                        <div x-show="activeZone" x-transition class="border-t border-gray-100 pt-4">
                            <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 mb-2.5">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                {{ __('app.note_for') }} <span x-text="activeZone ? activeZone.replace(/_/g, ' ') : ''" class="text-indigo-600 capitalize"></span>
                            </label>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <input type="text" x-model="activeNote" @input.debounce.500ms="saveNote()"
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all {{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }}"
                                           :placeholder="'{{ __('app.add_note_for_area') }}'">
                                    <svg class="w-4 h-4 text-gray-400 absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() === 'ar' ? 'right-3.5' : 'left-3.5' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Text Fields Sidebar --}}
            <div class="space-y-6">
                {{-- Complaint --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.complaint') }}</label>
                    <textarea name="complaint" rows="3"
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none">{{ old('complaint', $diagnosis->complaint ?? '') }}</textarea>
                </div>

                {{-- Diagnosis --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.diagnosis_text') }}</label>
                    <textarea name="diagnosis" rows="3"
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none">{{ old('diagnosis', $diagnosis->diagnosis ?? '') }}</textarea>
                </div>

                {{-- Prescription with Drug Search --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                     x-data="{
                         drugQuery: '',
                         drugResults: [],
                         selectedDrugs: {{ json_encode($diagnosis?->prescription ? array_filter(array_map('trim', explode("\n", $diagnosis->prescription))) : []) }},
                         searching: false,
                         showResults: false,
                         async searchDrugs() {
                             if (this.drugQuery.length < 2) { this.drugResults = []; this.showResults = false; return; }
                             this.searching = true;
                             try {
                                 const res = await fetch('{{ route('dashboard.drugs.search') }}?q=' + encodeURIComponent(this.drugQuery));
                                 this.drugResults = await res.json();
                                 this.showResults = true;
                             } catch(e) { this.drugResults = []; }
                             this.searching = false;
                         },
                         addDrug(drug) {
                             const entry = drug.name + (drug.name_ar ? ' (' + drug.name_ar + ')' : '');
                             if (!this.selectedDrugs.includes(entry)) {
                                 this.selectedDrugs.push(entry);
                             }
                             this.drugQuery = '';
                             this.drugResults = [];
                             this.showResults = false;
                             this.syncPrescription();
                         },
                         removeDrug(idx) {
                             this.selectedDrugs.splice(idx, 1);
                             this.syncPrescription();
                         },
                         syncPrescription() {
                             this.$refs.prescriptionInput.value = this.selectedDrugs.join('\n');
                         }
                     }"
                     x-init="syncPrescription()"
                     @click.outside="showResults = false">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.prescription') }}</label>
                    <input type="hidden" name="prescription" x-ref="prescriptionInput" value="{{ old('prescription', $diagnosis?->prescription ?? '') }}">

                    {{-- Drug Search Input --}}
                    <div class="relative mb-3">
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" x-model="drugQuery" @input.debounce.400ms="searchDrugs()" @focus="drugQuery.length >= 2 && (showResults = true)"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                                   placeholder="{{ __('app.search_drug') }}">
                            <svg x-show="searching" class="w-4 h-4 text-indigo-500 absolute {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }} top-1/2 -translate-y-1/2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </div>

                        {{-- Results Dropdown --}}
                        <div x-show="showResults && drugResults.length > 0" x-transition
                             class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                            <template x-for="drug in drugResults" :key="drug.id">
                                <button type="button" @click="addDrug(drug)"
                                        class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-all border-b border-gray-50 last:border-0 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                        <img x-show="drug.image" :src="drug.image" class="w-full h-full object-cover" loading="lazy">
                                        <div x-show="!drug.image" class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="drug.name"></p>
                                        <p class="text-xs text-gray-500 truncate" x-text="drug.name_ar"></p>
                                    </div>
                                    <span x-show="drug.price > 0" class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg" x-text="drug.price + ' EGP'"></span>
                                </button>
                            </template>
                        </div>

                        {{-- No Results --}}
                        <div x-show="showResults && drugResults.length === 0 && drugQuery.length >= 2 && !searching" x-transition
                             class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg p-4 text-center">
                            <p class="text-sm text-gray-500">{{ __('app.no_drugs_found') }}</p>
                        </div>
                    </div>

                    {{-- Selected Drugs List --}}
                    <div class="space-y-2">
                        <template x-for="(drug, idx) in selectedDrugs" :key="idx">
                            <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-2">
                                <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                <span class="flex-1 text-sm text-indigo-800 font-medium" x-text="drug"></span>
                                <button type="button" @click="removeDrug(idx)" class="text-indigo-400 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <p x-show="selectedDrugs.length === 0" class="text-xs text-gray-400 mt-2">{{ __('app.search_drug_hint') }}</p>
                </div>

                {{-- Lab Tests --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                     x-data="{
                         labItems: {{ json_encode($diagnosis?->lab_tests ? array_filter(array_map('trim', explode("\n", $diagnosis->lab_tests))) : []) }},
                         labInput: '',
                         showLabSuggestions: false,
                         labSuggestions: [
                             {icon: '🩸', name: 'CBC - صورة دم كاملة'},
                             {icon: '🩸', name: 'Hemoglobin - هيموجلوبين'},
                             {icon: '🩸', name: 'ESR - سرعة ترسيب'},
                             {icon: '🩸', name: 'Platelets - صفائح دموية'},
                             {icon: '🩸', name: 'PT/INR - سيولة الدم'},
                             {icon: '🩸', name: 'Blood Group - فصيلة الدم'},
                             {icon: '🍬', name: 'FBS - سكر صائم'},
                             {icon: '🍬', name: 'RBS - سكر عشوائي'},
                             {icon: '🍬', name: 'PPBS - سكر بعد الأكل'},
                             {icon: '🍬', name: 'HbA1c - سكر تراكمي'},
                             {icon: '🍬', name: 'GTT - منحنى السكر'},
                             {icon: '🫀', name: 'Lipid Profile - دهون الدم'},
                             {icon: '🫀', name: 'Cholesterol - كوليسترول'},
                             {icon: '🫀', name: 'Triglycerides - دهون ثلاثية'},
                             {icon: '🫀', name: 'HDL - دهون نافعة'},
                             {icon: '🫀', name: 'LDL - دهون ضارة'},
                             {icon: '🫀', name: 'Troponin - تروبونين'},
                             {icon: '🫀', name: 'CK-MB - إنزيم القلب'},
                             {icon: '🫘', name: 'Creatinine - كرياتينين'},
                             {icon: '🫘', name: 'BUN/Urea - يوريا'},
                             {icon: '🫘', name: 'Uric Acid - حمض اليوريك'},
                             {icon: '🫘', name: 'eGFR - وظائف الكلى'},
                             {icon: '🟤', name: 'ALT/SGPT - إنزيم الكبد'},
                             {icon: '🟤', name: 'AST/SGOT - إنزيم الكبد'},
                             {icon: '🟤', name: 'ALP - الفوسفاتاز القلوي'},
                             {icon: '🟤', name: 'GGT - إنزيم الكبد'},
                             {icon: '🟤', name: 'Bilirubin - بيليروبين'},
                             {icon: '🟤', name: 'Albumin - ألبومين'},
                             {icon: '🟤', name: 'Total Protein - بروتين كلي'},
                             {icon: '🟤', name: 'LDH - نازعة هيدروجين اللاكتات'},
                             {icon: '🦴', name: 'Calcium - كالسيوم'},
                             {icon: '🦴', name: 'Phosphorus - فوسفور'},
                             {icon: '🦴', name: 'Vitamin D - فيتامين د'},
                             {icon: '🦴', name: 'Vitamin B12 - فيتامين ب12'},
                             {icon: '🦴', name: 'Iron - حديد'},
                             {icon: '🦴', name: 'Ferritin - فيريتين'},
                             {icon: '🦴', name: 'TIBC - سعة ربط الحديد'},
                             {icon: '🦴', name: 'Magnesium - ماغنسيوم'},
                             {icon: '⚡', name: 'Sodium - صوديوم'},
                             {icon: '⚡', name: 'Potassium - بوتاسيوم'},
                             {icon: '⚡', name: 'Chloride - كلوريد'},
                             {icon: '🦋', name: 'TSH - هرمون الغدة الدرقية'},
                             {icon: '🦋', name: 'Free T3 - هرمون T3'},
                             {icon: '🦋', name: 'Free T4 - هرمون T4'},
                             {icon: '🧪', name: 'CRP - بروتين سي التفاعلي'},
                             {icon: '🧪', name: 'RF - عامل الروماتويد'},
                             {icon: '🧪', name: 'ANA - أجسام مضادة للنواة'},
                             {icon: '🧪', name: 'Anti-CCP - مضاد CCP'},
                             {icon: '🧪', name: 'ASO - مضاد ستربتوليزين'},
                             {icon: '💛', name: 'Urine Analysis - تحليل بول'},
                             {icon: '💛', name: 'Urine Culture - مزرعة بول'},
                             {icon: '💛', name: '24h Urine Protein - بروتين بول 24 ساعة'},
                             {icon: '💩', name: 'Stool Analysis - تحليل براز'},
                             {icon: '💩', name: 'Stool Culture - مزرعة براز'},
                             {icon: '💩', name: 'H. Pylori Stool Ag - جرثومة المعدة'},
                             {icon: '💩', name: 'Occult Blood - دم خفي بالبراز'},
                             {icon: '🦠', name: 'Blood Culture - مزرعة دم'},
                             {icon: '🦠', name: 'Wound Culture - مزرعة جرح'},
                             {icon: '🦠', name: 'Sputum Culture - مزرعة بلغم'},
                             {icon: '🦠', name: 'Throat Culture - مزرعة حلق'},
                             {icon: '🧬', name: 'PSA - مستضد البروستاتا'},
                             {icon: '🧬', name: 'CA-125 - دلالة أورام المبيض'},
                             {icon: '🧬', name: 'CEA - دلالة أورام القولون'},
                             {icon: '🧬', name: 'AFP - دلالة أورام الكبد'},
                             {icon: '🧬', name: 'CA 19-9 - دلالة أورام البنكرياس'},
                             {icon: '🤰', name: 'Beta-HCG - هرمون الحمل'},
                             {icon: '🤰', name: 'Prolactin - هرمون الحليب'},
                             {icon: '🤰', name: 'FSH - هرمون محفز'},
                             {icon: '🤰', name: 'LH - هرمون ملوتن'},
                             {icon: '🤰', name: 'Estrogen - إستروجين'},
                             {icon: '🤰', name: 'Progesterone - بروجسترون'},
                             {icon: '🤰', name: 'Testosterone - تستوستيرون'},
                             {icon: '💉', name: 'Cortisol - كورتيزول'},
                             {icon: '💉', name: 'Insulin - إنسولين'},
                             {icon: '💉', name: 'PTH - هرمون الغدة الجاردرقية'},
                             {icon: '💉', name: 'Growth Hormone - هرمون النمو'},
                             {icon: '🩹', name: 'D-Dimer - دي دايمر'},
                             {icon: '🩹', name: 'Fibrinogen - فيبرينوجين'},
                             {icon: '🧫', name: 'Widal Test - تحليل التيفود'},
                             {icon: '🧫', name: 'Brucella - تحليل البروسيلا'},
                             {icon: '🧫', name: 'Hepatitis B - التهاب كبد ب'},
                             {icon: '🧫', name: 'Hepatitis C - التهاب كبد سي'},
                             {icon: '🧫', name: 'HIV - فيروس نقص المناعة'},
                             {icon: '🔬', name: 'ABG - غازات الدم'},
                             {icon: '🔬', name: 'Amylase - أميليز'},
                             {icon: '🔬', name: 'Lipase - ليباز'},
                         ],
                         addLab() {
                             if (this.labInput.trim() && !this.labItems.includes(this.labInput.trim())) {
                                 this.labItems.push(this.labInput.trim());
                                 this.labInput = '';
                                 this.syncLabs();
                             }
                         },
                         addLabSuggestion(name) {
                             if (!this.labItems.includes(name)) {
                                 this.labItems.push(name);
                                 this.syncLabs();
                             }
                         },
                         removeLab(idx) {
                             this.labItems.splice(idx, 1);
                             this.syncLabs();
                         },
                         syncLabs() {
                             this.$refs.labInput.value = this.labItems.join('\n');
                         }
                     }"
                     x-init="syncLabs()">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.lab_tests') }}</label>
                    <input type="hidden" name="lab_tests" x-ref="labInput" value="{{ old('lab_tests', $diagnosis?->lab_tests ?? '') }}">

                    <div class="flex gap-2 mb-3">
                        <input type="text" x-model="labInput" @keydown.enter.prevent="addLab()"
                               class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                               placeholder="{{ __('app.add_lab_test') }}">
                        <button type="button" @click="addLab()"
                                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>

                    {{-- Quick Suggestions Toggle --}}
                    <button type="button" @click="showLabSuggestions = !showLabSuggestions"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700 mb-3 transition-colors">
                        <svg class="w-4 h-4 transition-transform" :class="showLabSuggestions && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        {{ __('app.common_lab_tests') }}
                    </button>

                    {{-- Suggestions Grid --}}
                    <div x-show="showLabSuggestions" x-transition x-cloak class="mb-4 max-h-60 overflow-y-auto border border-emerald-100 rounded-xl p-3 bg-emerald-50/30">
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="s in labSuggestions" :key="s.name">
                                <button type="button" @click="addLabSuggestion(s.name)"
                                        :class="labItems.includes(s.name) ? 'bg-emerald-200 border-emerald-400 text-emerald-900' : 'bg-white border-gray-200 text-gray-700 hover:border-emerald-300 hover:bg-emerald-50'"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg border transition-all">
                                    <span x-text="s.icon"></span>
                                    <span x-text="s.name"></span>
                                    <svg x-show="labItems.includes(s.name)" class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Selected Items --}}
                    <div class="space-y-2">
                        <template x-for="(item, idx) in labItems" :key="idx">
                            <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 rounded-xl px-3 py-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                <span class="flex-1 text-sm text-emerald-800 font-medium" x-text="item"></span>
                                <button type="button" @click="removeLab(idx)" class="text-emerald-400 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <p x-show="labItems.length === 0" class="text-xs text-gray-400 mt-2">{{ __('app.lab_tests_hint') }}</p>
                </div>

                {{-- Radiology --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                     x-data="{
                         radioItems: {{ json_encode($diagnosis?->radiology ? array_filter(array_map('trim', explode("\n", $diagnosis->radiology))) : []) }},
                         radioInput: '',
                         showRadioSuggestions: false,
                         radioSuggestions: [
                             {icon: '🦴', name: 'X-Ray Chest - أشعة صدر'},
                             {icon: '🦴', name: 'X-Ray Spine Cervical - أشعة فقرات عنقية'},
                             {icon: '🦴', name: 'X-Ray Spine Lumbar - أشعة فقرات قطنية'},
                             {icon: '🦴', name: 'X-Ray Spine Dorsal - أشعة فقرات صدرية'},
                             {icon: '🦴', name: 'X-Ray Pelvis - أشعة حوض'},
                             {icon: '🦴', name: 'X-Ray Skull - أشعة جمجمة'},
                             {icon: '🦴', name: 'X-Ray Hand - أشعة يد'},
                             {icon: '🦴', name: 'X-Ray Foot - أشعة قدم'},
                             {icon: '🦴', name: 'X-Ray Knee - أشعة ركبة'},
                             {icon: '🦴', name: 'X-Ray Shoulder - أشعة كتف'},
                             {icon: '🦴', name: 'X-Ray Elbow - أشعة كوع'},
                             {icon: '🦴', name: 'X-Ray Ankle - أشعة كاحل'},
                             {icon: '🦴', name: 'X-Ray Wrist - أشعة رسغ'},
                             {icon: '🦴', name: 'X-Ray Abdomen - أشعة بطن'},
                             {icon: '🦴', name: 'X-Ray Sinuses - أشعة جيوب أنفية'},
                             {icon: '📡', name: 'Ultrasound Abdomen - سونار بطن'},
                             {icon: '📡', name: 'Ultrasound Pelvis - سونار حوض'},
                             {icon: '📡', name: 'Ultrasound Thyroid - سونار غدة درقية'},
                             {icon: '📡', name: 'Ultrasound Breast - سونار ثدي'},
                             {icon: '📡', name: 'Ultrasound Kidney - سونار كلى'},
                             {icon: '📡', name: 'Ultrasound Liver - سونار كبد'},
                             {icon: '📡', name: 'Ultrasound Prostate - سونار بروستاتا'},
                             {icon: '📡', name: 'Ultrasound Testicular - سونار خصية'},
                             {icon: '📡', name: 'Ultrasound Pregnancy - سونار حمل'},
                             {icon: '📡', name: 'Ultrasound Doppler - دوبلر أوعية'},
                             {icon: '📡', name: 'Ultrasound Carotid - دوبلر شرايين الرقبة'},
                             {icon: '📡', name: 'Ultrasound Echocardiography - إيكو قلب'},
                             {icon: '📡', name: 'Ultrasound Soft Tissue - سونار أنسجة رخوة'},
                             {icon: '📡', name: 'Ultrasound Joint - سونار مفصل'},
                             {icon: '🧲', name: 'MRI Brain - رنين مغناطيسي مخ'},
                             {icon: '🧲', name: 'MRI Spine Cervical - رنين فقرات عنقية'},
                             {icon: '🧲', name: 'MRI Spine Lumbar - رنين فقرات قطنية'},
                             {icon: '🧲', name: 'MRI Spine Dorsal - رنين فقرات صدرية'},
                             {icon: '🧲', name: 'MRI Knee - رنين ركبة'},
                             {icon: '🧲', name: 'MRI Shoulder - رنين كتف'},
                             {icon: '🧲', name: 'MRI Pelvis - رنين حوض'},
                             {icon: '🧲', name: 'MRI Abdomen - رنين بطن'},
                             {icon: '🧲', name: 'MRI Chest - رنين صدر'},
                             {icon: '🧲', name: 'MRI Hip - رنين فخذ'},
                             {icon: '🧲', name: 'MRI Wrist - رنين رسغ'},
                             {icon: '🧲', name: 'MRI Ankle - رنين كاحل'},
                             {icon: '🔄', name: 'CT Brain - أشعة مقطعية مخ'},
                             {icon: '🔄', name: 'CT Chest - أشعة مقطعية صدر'},
                             {icon: '🔄', name: 'CT Abdomen & Pelvis - مقطعية بطن وحوض'},
                             {icon: '🔄', name: 'CT Spine - مقطعية عمود فقري'},
                             {icon: '🔄', name: 'CT Sinuses - مقطعية جيوب أنفية'},
                             {icon: '🔄', name: 'CT Angiography - أشعة مقطعية بالصبغة'},
                             {icon: '🔄', name: 'CT Urography - مقطعية مسالك بولية'},
                             {icon: '🔄', name: 'CT Coronary - مقطعية شرايين القلب'},
                             {icon: '🔄', name: 'HRCT Chest - مقطعية صدر عالية الدقة'},
                             {icon: '🎯', name: 'Mammography - ماموجرام'},
                             {icon: '🎯', name: 'DEXA Scan - قياس هشاشة العظام'},
                             {icon: '🎯', name: 'Panoramic X-Ray - أشعة بانوراما أسنان'},
                             {icon: '🎯', name: 'Barium Swallow - أشعة بالباريوم'},
                             {icon: '🎯', name: 'Barium Enema - حقنة باريوم شرجية'},
                             {icon: '🎯', name: 'IVU/IVP - أشعة صبغة على المسالك'},
                             {icon: '🎯', name: 'HSG - أشعة صبغة على الرحم'},
                             {icon: '☢️', name: 'Bone Scan - مسح ذري للعظام'},
                             {icon: '☢️', name: 'Thyroid Scan - مسح ذري للغدة'},
                             {icon: '☢️', name: 'PET Scan - مسح بالأشعة المقطعية'},
                             {icon: '💓', name: 'ECG - رسم قلب'},
                             {icon: '💓', name: 'Holter Monitor - هولتر 24 ساعة'},
                             {icon: '🧠', name: 'EEG - رسم مخ'},
                             {icon: '💪', name: 'EMG - رسم عصب وعضلات'},
                             {icon: '👁️', name: 'OCT - تصوير مقطعي للشبكية'},
                             {icon: '👁️', name: 'Fundus Photography - تصوير قاع العين'},
                         ],
                         addRadio() {
                             if (this.radioInput.trim() && !this.radioItems.includes(this.radioInput.trim())) {
                                 this.radioItems.push(this.radioInput.trim());
                                 this.radioInput = '';
                                 this.syncRadio();
                             }
                         },
                         addRadioSuggestion(name) {
                             if (!this.radioItems.includes(name)) {
                                 this.radioItems.push(name);
                                 this.syncRadio();
                             }
                         },
                         removeRadio(idx) {
                             this.radioItems.splice(idx, 1);
                             this.syncRadio();
                         },
                         syncRadio() {
                             this.$refs.radioInput.value = this.radioItems.join('\n');
                         }
                     }"
                     x-init="syncRadio()">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.radiology') }}</label>
                    <input type="hidden" name="radiology" x-ref="radioInput" value="{{ old('radiology', $diagnosis?->radiology ?? '') }}">

                    <div class="flex gap-2 mb-3">
                        <input type="text" x-model="radioInput" @keydown.enter.prevent="addRadio()"
                               class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                               placeholder="{{ __('app.add_radiology') }}">
                        <button type="button" @click="addRadio()"
                                class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>

                    {{-- Quick Suggestions Toggle --}}
                    <button type="button" @click="showRadioSuggestions = !showRadioSuggestions"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-600 hover:text-amber-700 mb-3 transition-colors">
                        <svg class="w-4 h-4 transition-transform" :class="showRadioSuggestions && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        {{ __('app.common_radiology') }}
                    </button>

                    {{-- Suggestions Grid --}}
                    <div x-show="showRadioSuggestions" x-transition x-cloak class="mb-4 max-h-60 overflow-y-auto border border-amber-100 rounded-xl p-3 bg-amber-50/30">
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="s in radioSuggestions" :key="s.name">
                                <button type="button" @click="addRadioSuggestion(s.name)"
                                        :class="radioItems.includes(s.name) ? 'bg-amber-200 border-amber-400 text-amber-900' : 'bg-white border-gray-200 text-gray-700 hover:border-amber-300 hover:bg-amber-50'"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg border transition-all">
                                    <span x-text="s.icon"></span>
                                    <span x-text="s.name"></span>
                                    <svg x-show="radioItems.includes(s.name)" class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Selected Items --}}
                    <div class="space-y-2">
                        <template x-for="(item, idx) in radioItems" :key="idx">
                            <div class="flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-xl px-3 py-2">
                                <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="flex-1 text-sm text-amber-800 font-medium" x-text="item"></span>
                                <button type="button" @click="removeRadio(idx)" class="text-amber-400 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <p x-show="radioItems.length === 0" class="text-xs text-gray-400 mt-2">{{ __('app.radiology_hint') }}</p>
                </div>

                {{-- Notes --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.notes') }}</label>
                    <textarea name="notes" rows="3"
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none">{{ old('notes', $diagnosis->notes ?? '') }}</textarea>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('app.save_diagnosis') }}
                </button>
            </div>
        </div>
    </form>
</x-dashboard-layout>
