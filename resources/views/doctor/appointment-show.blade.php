<x-dashboard-layout>
    <x-slot name="title">{{ __('app.appointment') }} #{{ $appointment->id }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.appointment') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.doctor-sidebar', ['active' => 'appointments'])
    </x-slot>

    {{-- Back --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('doctor.appointments') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>

        {{-- Status actions --}}
        <div class="flex items-center gap-2">
            @if(in_array($appointment->status, ['scheduled', 'confirmed']))
            <form method="POST" action="{{ route('doctor.appointment.status', $appointment) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="in_progress">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                    {{ __('app.start_examination') }}
                </button>
            </form>
            @endif
            @if($appointment->status === 'in_progress')
            <form method="POST" action="{{ route('doctor.appointment.status', $appointment) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('app.mark_completed') }}
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Patient Info Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-lg font-bold text-indigo-600">
                    {{ mb_substr($appointment->patient->name ?? '?', 0, 1) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $appointment->patient->name ?? '-' }}</h3>
                    <div class="flex items-center gap-4 mt-1">
                        <span class="text-sm text-gray-500">{{ $appointment->patient->phone ?? '' }}</span>
                        @if($appointment->patient?->gender)
                        <span class="text-xs text-gray-400">{{ __('app.' . $appointment->patient->gender) }}</span>
                        @endif
                        @if($appointment->patient?->date_of_birth)
                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age }} {{ __('app.years_old') }}</span>
                        @endif
                        @if($appointment->patient?->blood_type)
                        <span class="text-xs font-semibold text-red-500 bg-red-50 px-2 py-0.5 rounded-lg">{{ $appointment->patient->blood_type }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ route('doctor.patient.history', $appointment->patient) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-purple-700 bg-purple-50 border border-purple-200 rounded-xl hover:bg-purple-100 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                {{ __('app.patient_history') }}
            </a>
        </div>

        {{-- Medical history & allergies --}}
        @if($appointment->patient?->medical_history || $appointment->patient?->allergies)
        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if($appointment->patient->medical_history)
            <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">{{ __('app.medical_history') }}</p>
                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $appointment->patient->medical_history }}</p>
            </div>
            @endif
            @if($appointment->patient->allergies)
            <div>
                <p class="text-xs font-semibold text-red-500 mb-1">{{ __('app.allergies') }}</p>
                <p class="text-sm text-red-700 bg-red-50 rounded-lg p-3">{{ $appointment->patient->allergies }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- Diagnosis Form - reuse the same form as admin --}}
    @php $diagnosis = $appointment->diagnosis; @endphp
    <form method="POST" action="{{ route('doctor.diagnosis.store', $appointment) }}"
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

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Diagram --}}
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">{{ __('app.interactive_diagram') }}</h3>
                        <span class="text-xs text-gray-400">{{ __('app.click_to_mark') }}</span>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-center">
                            @include('components.diagrams.' . $diagramType)
                        </div>
                    </div>
                </div>

                {{-- Selected Zones --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-show="selectedZones.length > 0" x-transition>
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h3 class="text-base font-bold text-gray-900">{{ __('app.marked_areas') }} (<span x-text="selectedZones.length"></span>)</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <template x-for="zone in selectedZones" :key="zone">
                                <button type="button" @click="selectZone(zone)"
                                        :class="activeZone === zone ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-gray-50 text-gray-700 border-gray-200'"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                    <span x-text="zone.replace(/_/g, ' ')"></span>
                                    <span x-show="zoneNotes[zone]" class="text-indigo-400">*</span>
                                </button>
                            </template>
                        </div>
                        <div x-show="activeZone" x-transition class="border-t border-gray-100 pt-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-2">
                                {{ __('app.note_for') }} <span x-text="activeZone ? activeZone.replace(/_/g, ' ') : ''" class="text-indigo-600"></span>
                            </label>
                            <input type="text" x-model="activeNote" @input.debounce.500ms="saveNote()"
                                   class="flex-1 w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                                   :placeholder="'{{ __('app.add_note_for_area') }}'">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Fields --}}
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

                {{-- Prescription --}}
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
                                 const res = await fetch('{{ route('doctor.drugs.search') }}?q=' + encodeURIComponent(this.drugQuery));
                                 this.drugResults = await res.json();
                                 this.showResults = true;
                             } catch(e) { this.drugResults = []; }
                             this.searching = false;
                         },
                         addDrug(drug) {
                             const entry = drug.name + (drug.name_ar ? ' (' + drug.name_ar + ')' : '');
                             if (!this.selectedDrugs.includes(entry)) this.selectedDrugs.push(entry);
                             this.drugQuery = ''; this.drugResults = []; this.showResults = false;
                             this.syncPrescription();
                         },
                         removeDrug(idx) { this.selectedDrugs.splice(idx, 1); this.syncPrescription(); },
                         syncPrescription() { this.$refs.prescriptionInput.value = this.selectedDrugs.join('\n'); }
                     }"
                     x-init="syncPrescription()"
                     @click.outside="showResults = false">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.prescription') }}</label>
                    <input type="hidden" name="prescription" x-ref="prescriptionInput" value="{{ old('prescription', $diagnosis?->prescription ?? '') }}">
                    <div class="relative mb-3">
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" x-model="drugQuery" @input.debounce.400ms="searchDrugs()" @focus="drugQuery.length >= 2 && (showResults = true)"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                                   placeholder="{{ __('app.search_drug') }}">
                        </div>
                        <div x-show="showResults && drugResults.length > 0" x-transition
                             class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                            <template x-for="drug in drugResults" :key="drug.id">
                                <button type="button" @click="addDrug(drug)"
                                        class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-all border-b border-gray-50 last:border-0 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="drug.name"></p>
                                        <p class="text-xs text-gray-500 truncate" x-text="drug.name_ar"></p>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <template x-for="(drug, idx) in selectedDrugs" :key="idx">
                            <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-2">
                                <span class="flex-1 text-sm text-indigo-800 font-medium" x-text="drug"></span>
                                <button type="button" @click="removeDrug(idx)" class="text-indigo-400 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Lab Tests (simple textarea for doctor - they can type freely) --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.lab_tests') }}</label>
                    <textarea name="lab_tests" rows="3"
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none"
                              placeholder="{{ __('app.add_lab_test') }}">{{ old('lab_tests', $diagnosis->lab_tests ?? '') }}</textarea>
                </div>

                {{-- Radiology --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.radiology') }}</label>
                    <textarea name="radiology" rows="3"
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none"
                              placeholder="{{ __('app.add_radiology') }}">{{ old('radiology', $diagnosis->radiology ?? '') }}</textarea>
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
