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

        {{-- Status actions (state machine enforced) --}}
        <div class="flex items-center gap-2">
            @if($appointment->canTransitionTo('in_progress'))
            <form method="POST" action="{{ route('doctor.appointment.status', $appointment) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="in_progress">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                    {{ __('app.start_examination') }}
                </button>
            </form>
            @endif
            @if($appointment->canTransitionTo('completed'))
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
                @php $rxItems = $diagnosis?->prescription?->items ?? collect(); @endphp
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                     x-data="{
                         drugQuery: '',
                         drugResults: [],
                         searching: false,
                         showResults: false,
                         rxDrugs: {{ json_encode($rxItems->map(fn($item) => [
                             'drug_name' => $item->drug_name,
                             'dosage' => $item->dosage ?? '',
                             'frequency' => $item->frequency ?? '',
                             'duration' => $item->duration ?? '',
                             'instructions' => $item->instructions ?? '',
                         ])->values()->toArray()) }},
                         rxNotes: {{ json_encode($diagnosis?->prescription?->notes ?? '') }},
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
                             const name = drug.name + (drug.name_ar ? ' (' + drug.name_ar + ')' : '');
                             if (this.rxDrugs.find(d => d.drug_name === name)) return;
                             this.rxDrugs.push({ drug_name: name, dosage: '', frequency: '', duration: '', instructions: '' });
                             this.drugQuery = ''; this.drugResults = []; this.showResults = false;
                             this.syncOldPrescription();
                         },
                         addCustomDrug() {
                             if (this.drugQuery.trim().length < 2) return;
                             this.rxDrugs.push({ drug_name: this.drugQuery.trim(), dosage: '', frequency: '', duration: '', instructions: '' });
                             this.drugQuery = ''; this.drugResults = []; this.showResults = false;
                             this.syncOldPrescription();
                         },
                         removeDrug(idx) { this.rxDrugs.splice(idx, 1); this.syncOldPrescription(); },
                         syncOldPrescription() { this.$refs.prescriptionInput.value = this.rxDrugs.map(d => d.drug_name).join('\n'); }
                     }"
                     x-init="syncOldPrescription()"
                     @click.outside="showResults = false">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            {{ __('app.prescription') }}
                        </span>
                    </label>
                    <input type="hidden" name="prescription" x-ref="prescriptionInput" value="{{ old('prescription', $diagnosis?->prescription ?? '') }}">
                    <input type="hidden" name="rx_drugs_json" :value="JSON.stringify(rxDrugs)">
                    <input type="hidden" name="rx_notes" :value="rxNotes">

                    {{-- Drug Search --}}
                    <div class="relative mb-4">
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" x-model="drugQuery" @input.debounce.400ms="searchDrugs()" @focus="drugQuery.length >= 2 && (showResults = true)"
                                   @keydown.enter.prevent="addCustomDrug()"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                                   placeholder="{{ __('app.search_drug') }}">
                        </div>
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
                                    <span x-show="drug.price > 0" class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg" x-text="drug.price + ' {{ __('app.egp') }}'"></span>
                                </button>
                            </template>
                        </div>
                        <div x-show="showResults && drugResults.length === 0 && drugQuery.length >= 2 && !searching" x-transition
                             class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg p-4 text-center">
                            <p class="text-sm text-gray-500">{{ __('app.no_drugs_found') }}</p>
                            <button type="button" @click="addCustomDrug()" class="mt-2 text-sm text-indigo-600 hover:text-indigo-700 font-semibold">{{ __('app.add_custom_drug') }}</button>
                        </div>
                    </div>

                    {{-- Prescription Items --}}
                    <div class="space-y-3">
                        <template x-for="(rx, idx) in rxDrugs" :key="idx">
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 bg-indigo-100 text-indigo-700 rounded-lg flex items-center justify-center text-xs font-bold" x-text="idx + 1"></span>
                                        <span class="text-sm font-semibold text-gray-900" x-text="rx.drug_name"></span>
                                    </div>
                                    <button type="button" @click="removeDrug(idx)" class="text-gray-400 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">{{ __('app.dosage') }}</label>
                                        <input type="text" x-model="rx.dosage" placeholder="{{ __('app.dosage_placeholder') }}"
                                               class="w-full bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">{{ __('app.frequency') }}</label>
                                        <select x-model="rx.frequency"
                                                class="w-full bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                            <option value="">--</option>
                                            <option value="once_daily">{{ __('app.once_daily') }}</option>
                                            <option value="twice_daily">{{ __('app.twice_daily') }}</option>
                                            <option value="three_daily">{{ __('app.three_daily') }}</option>
                                            <option value="four_daily">{{ __('app.four_daily') }}</option>
                                            <option value="as_needed">{{ __('app.as_needed') }}</option>
                                            <option value="before_meals">{{ __('app.before_meals') }}</option>
                                            <option value="after_meals">{{ __('app.after_meals') }}</option>
                                            <option value="at_bedtime">{{ __('app.at_bedtime') }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">{{ __('app.duration') }}</label>
                                        <input type="text" x-model="rx.duration" placeholder="{{ __('app.duration_placeholder') }}"
                                               class="w-full bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">{{ __('app.instructions') }}</label>
                                        <input type="text" x-model="rx.instructions" placeholder="{{ __('app.instructions_placeholder') }}"
                                               class="w-full bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <p x-show="rxDrugs.length === 0" class="text-xs text-gray-400 mt-2">{{ __('app.search_drug_hint') }}</p>

                    {{-- Prescription Notes --}}
                    <div x-show="rxDrugs.length > 0" x-transition class="mt-3">
                        <input type="text" x-model="rxNotes" placeholder="{{ __('app.prescription_notes_placeholder') }}"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
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

                    <button type="button" @click="showLabSuggestions = !showLabSuggestions"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700 mb-3 transition-colors">
                        <svg class="w-4 h-4 transition-transform" :class="showLabSuggestions && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        {{ __('app.common_lab_tests') }}
                    </button>

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

                    <button type="button" @click="showRadioSuggestions = !showRadioSuggestions"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-600 hover:text-amber-700 mb-3 transition-colors">
                        <svg class="w-4 h-4 transition-transform" :class="showRadioSuggestions && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        {{ __('app.common_radiology') }}
                    </button>

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

                {{-- Services --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="servicesPicker()">
                    <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        {{ __('app.services') }}
                    </h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto" x-show="allServices.length > 0">
                        <template x-for="service in allServices" :key="service.id">
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-all"
                                   :class="selected.includes(service.id) ? 'border-indigo-300 bg-indigo-50' : 'border-gray-100 hover:border-gray-200'">
                                <input type="checkbox" :value="service.id" name="service_ids[]"
                                       @change="toggle(service.id)"
                                       :checked="selected.includes(service.id)"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="flex-1 text-sm font-medium text-gray-800" x-text="service.name"></span>
                                <span x-show="service.price" class="text-xs font-bold text-indigo-600" dir="ltr"
                                      x-text="service.price + ' {{ __('app.currency') }}'"></span>
                            </label>
                        </template>
                    </div>
                    <p x-show="allServices.length === 0" class="text-xs text-gray-400">{{ __('app.no_services_yet') }}</p>
                    <div x-show="total > 0" class="mt-3 pt-3 border-t border-gray-100 flex justify-between">
                        <span class="text-xs text-gray-500">{{ __('app.total') }}</span>
                        <span class="text-sm font-bold text-indigo-700" dir="ltr" x-text="total.toFixed(2) + ' {{ __('app.currency') }}'"></span>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('app.save_diagnosis') }}
                </button>
            </div>
        </div>
    </form>

    <script>
        function servicesPicker() {
            return {
                allServices: @json($availableServices ?? []),
                selected: @json($appointment->services->pluck('id')->toArray()),
                get total() {
                    return this.allServices
                        .filter(s => this.selected.includes(s.id))
                        .reduce((sum, s) => sum + (s.price || 0), 0);
                },
                toggle(id) {
                    const idx = this.selected.indexOf(id);
                    if (idx > -1) this.selected.splice(idx, 1);
                    else this.selected.push(id);
                }
            }
        }
    </script>
</x-dashboard-layout>
