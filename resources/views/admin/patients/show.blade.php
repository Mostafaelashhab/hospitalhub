<x-dashboard-layout>
    <x-slot name="title">{{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.patient_details') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.patients.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    {{-- Chronic Diseases Alert --}}
    @if($patient->activeChronicDiseases->count())
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <span class="text-sm font-bold text-amber-800">{{ __('app.chronic_diseases_alert') }}</span>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($patient->activeChronicDiseases as $disease)
            @php
                $severityColors = [
                    'mild' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                    'moderate' => 'bg-orange-100 text-orange-800 border-orange-300',
                    'severe' => 'bg-red-100 text-red-800 border-red-300',
                ];
            @endphp
            <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-lg border {{ $severityColors[$disease->severity] ?? 'bg-gray-100 text-gray-700 border-gray-300' }}">
                {{ $disease->disease_name }}
                <span class="opacity-70">({{ __('app.' . $disease->severity) }})</span>
            </span>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Patient Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Info Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $patient->gender === 'male' ? 'from-blue-500 to-cyan-600' : 'from-pink-500 to-rose-600' }} flex items-center justify-center text-2xl font-bold text-white shadow-lg {{ $patient->gender === 'male' ? 'shadow-blue-500/20' : 'shadow-pink-500/20' }}">
                        {{ mb_substr($patient->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $patient->name }}</h2>
                        <p class="text-sm text-gray-500" dir="ltr">{{ $patient->phone }}</p>
                    </div>
                    <div class="{{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }}">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border {{ $patient->gender === 'male' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-pink-50 text-pink-700 border-pink-200' }}">
                            {{ __('app.' . $patient->gender) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.email') }}</p>
                        <p class="text-sm text-gray-900">{{ $patient->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.date_of_birth') }}</p>
                        <p class="text-sm text-gray-900">{{ $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.age') }}</p>
                        <p class="text-sm text-gray-900">{{ $patient->date_of_birth ? $patient->date_of_birth->age . ' ' . __('app.years') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.blood_type') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $patient->blood_type ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.national_id') }}</p>
                        <p class="text-sm text-gray-900 font-mono" dir="ltr">{{ $patient->national_id ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.address') }}</p>
                        <p class="text-sm text-gray-900">{{ $patient->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Emergency Contact --}}
            @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ __('app.emergency_contact') }}
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.contact_name') }}</p>
                        <p class="text-sm text-gray-900">{{ $patient->emergency_contact_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.contact_phone') }}</p>
                        <p class="text-sm text-gray-900" dir="ltr">{{ $patient->emergency_contact_phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.contact_relation') }}</p>
                        <p class="text-sm text-gray-900">{{ $patient->emergency_contact_relation ?? '-' }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Medical Info Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.medical_info') }}</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.allergies') }}</p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $patient->allergies ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.medical_history') }}</p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $patient->medical_history ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Vital Signs --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ showForm: false }">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        {{ __('app.vital_signs') }}
                    </h3>
                    @if(auth()->user()->hasPermission('patients.edit'))
                    <button @click="showForm = !showForm" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('app.record_vitals') }}
                    </button>
                    @endif
                </div>

                {{-- Record Vitals Form --}}
                <div x-show="showForm" x-transition x-cloak class="px-6 py-5 bg-gray-50/50 border-b border-gray-100">
                    <form action="{{ route('dashboard.patients.vitals.store', $patient) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.blood_pressure_systolic') }}</label>
                                <input type="number" step="0.1" name="blood_pressure_systolic" placeholder="120" class="w-full rounded-xl border-gray-200 text-sm focus:border-rose-500 focus:ring-rose-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.blood_pressure_diastolic') }}</label>
                                <input type="number" step="0.1" name="blood_pressure_diastolic" placeholder="80" class="w-full rounded-xl border-gray-200 text-sm focus:border-rose-500 focus:ring-rose-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.heart_rate') }} (bpm)</label>
                                <input type="number" step="0.1" name="heart_rate" placeholder="72" class="w-full rounded-xl border-gray-200 text-sm focus:border-rose-500 focus:ring-rose-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.temperature') }} (°C)</label>
                                <input type="number" step="0.1" name="temperature" placeholder="37.0" class="w-full rounded-xl border-gray-200 text-sm focus:border-rose-500 focus:ring-rose-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.weight') }} (kg)</label>
                                <input type="number" step="0.1" name="weight" placeholder="70" class="w-full rounded-xl border-gray-200 text-sm focus:border-rose-500 focus:ring-rose-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.height') }} (cm)</label>
                                <input type="number" step="0.1" name="height" placeholder="170" class="w-full rounded-xl border-gray-200 text-sm focus:border-rose-500 focus:ring-rose-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.blood_sugar') }} (mg/dL)</label>
                                <input type="number" step="0.1" name="blood_sugar" placeholder="100" class="w-full rounded-xl border-gray-200 text-sm focus:border-rose-500 focus:ring-rose-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.oxygen_saturation') }} (%)</label>
                                <input type="number" step="0.1" name="oxygen_saturation" placeholder="98" class="w-full rounded-xl border-gray-200 text-sm focus:border-rose-500 focus:ring-rose-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.respiratory_rate') }} (/min)</label>
                                <input type="number" step="0.1" name="respiratory_rate" placeholder="16" class="w-full rounded-xl border-gray-200 text-sm focus:border-rose-500 focus:ring-rose-500" dir="ltr">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.notes') }}</label>
                            <input type="text" name="notes" class="w-full rounded-xl border-gray-200 text-sm focus:border-rose-500 focus:ring-rose-500">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('app.save') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Latest Vitals Display --}}
                @if($patient->latestVitals)
                <div class="px-6 py-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-gray-400">{{ __('app.last_recorded') }}: {{ $patient->latestVitals->created_at->format('Y-m-d H:i') }}
                            @if($patient->latestVitals->recorder)
                            — {{ $patient->latestVitals->recorder->name }}
                            @endif
                        </p>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @if($patient->latestVitals->blood_pressure)
                        <div class="bg-red-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-red-600 font-medium mb-1">{{ __('app.blood_pressure') }}</p>
                            <p class="text-lg font-bold text-red-700" dir="ltr">{{ $patient->latestVitals->blood_pressure }}</p>
                            <p class="text-[10px] text-red-400">mmHg</p>
                        </div>
                        @endif
                        @if($patient->latestVitals->heart_rate)
                        <div class="bg-pink-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-pink-600 font-medium mb-1">{{ __('app.heart_rate') }}</p>
                            <p class="text-lg font-bold text-pink-700" dir="ltr">{{ intval($patient->latestVitals->heart_rate) }}</p>
                            <p class="text-[10px] text-pink-400">bpm</p>
                        </div>
                        @endif
                        @if($patient->latestVitals->temperature)
                        <div class="bg-amber-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-amber-600 font-medium mb-1">{{ __('app.temperature') }}</p>
                            <p class="text-lg font-bold text-amber-700" dir="ltr">{{ number_format($patient->latestVitals->temperature, 1) }}</p>
                            <p class="text-[10px] text-amber-400">°C</p>
                        </div>
                        @endif
                        @if($patient->latestVitals->oxygen_saturation)
                        <div class="bg-blue-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-blue-600 font-medium mb-1">{{ __('app.oxygen_saturation') }}</p>
                            <p class="text-lg font-bold text-blue-700" dir="ltr">{{ intval($patient->latestVitals->oxygen_saturation) }}%</p>
                            <p class="text-[10px] text-blue-400">SpO₂</p>
                        </div>
                        @endif
                        @if($patient->latestVitals->weight)
                        <div class="bg-emerald-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-emerald-600 font-medium mb-1">{{ __('app.weight') }}</p>
                            <p class="text-lg font-bold text-emerald-700" dir="ltr">{{ number_format($patient->latestVitals->weight, 1) }}</p>
                            <p class="text-[10px] text-emerald-400">kg</p>
                        </div>
                        @endif
                        @if($patient->latestVitals->height)
                        <div class="bg-indigo-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-indigo-600 font-medium mb-1">{{ __('app.height') }}</p>
                            <p class="text-lg font-bold text-indigo-700" dir="ltr">{{ number_format($patient->latestVitals->height, 1) }}</p>
                            <p class="text-[10px] text-indigo-400">cm</p>
                        </div>
                        @endif
                        @if($patient->latestVitals->bmi)
                        <div class="bg-purple-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-purple-600 font-medium mb-1">{{ __('app.bmi') }}</p>
                            <p class="text-lg font-bold text-purple-700" dir="ltr">{{ $patient->latestVitals->bmi }}</p>
                            <p class="text-[10px] text-purple-400">kg/m²</p>
                        </div>
                        @endif
                        @if($patient->latestVitals->blood_sugar)
                        <div class="bg-orange-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-orange-600 font-medium mb-1">{{ __('app.blood_sugar') }}</p>
                            <p class="text-lg font-bold text-orange-700" dir="ltr">{{ intval($patient->latestVitals->blood_sugar) }}</p>
                            <p class="text-[10px] text-orange-400">mg/dL</p>
                        </div>
                        @endif
                        @if($patient->latestVitals->respiratory_rate)
                        <div class="bg-teal-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-teal-600 font-medium mb-1">{{ __('app.respiratory_rate') }}</p>
                            <p class="text-lg font-bold text-teal-700" dir="ltr">{{ intval($patient->latestVitals->respiratory_rate) }}</p>
                            <p class="text-[10px] text-teal-400">/min</p>
                        </div>
                        @endif
                    </div>
                    @if($patient->latestVitals->notes)
                    <p class="text-xs text-gray-500 mt-3">{{ $patient->latestVitals->notes }}</p>
                    @endif
                </div>

                {{-- Vitals History --}}
                @if($patient->vitalSigns->count() > 1)
                <div class="px-6 pb-5" x-data="{ showHistory: false }">
                    <button @click="showHistory = !showHistory" class="text-xs font-semibold text-rose-600 hover:text-rose-700 transition-colors">
                        <span x-show="!showHistory">{{ __('app.show_history') }} ({{ $patient->vitalSigns->count() }})</span>
                        <span x-show="showHistory" x-cloak>{{ __('app.hide_history') }}</span>
                    </button>
                    <div x-show="showHistory" x-transition x-cloak class="mt-3 overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-3 py-2 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} font-semibold text-gray-500">{{ __('app.date') }}</th>
                                    <th class="px-3 py-2 text-center font-semibold text-gray-500">{{ __('app.blood_pressure') }}</th>
                                    <th class="px-3 py-2 text-center font-semibold text-gray-500">{{ __('app.heart_rate') }}</th>
                                    <th class="px-3 py-2 text-center font-semibold text-gray-500">{{ __('app.temperature') }}</th>
                                    <th class="px-3 py-2 text-center font-semibold text-gray-500">{{ __('app.weight') }}</th>
                                    <th class="px-3 py-2 text-center font-semibold text-gray-500">{{ __('app.blood_sugar') }}</th>
                                    <th class="px-3 py-2 text-center font-semibold text-gray-500">SpO₂</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($patient->vitalSigns as $vital)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-3 py-2 text-gray-700">{{ $vital->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-3 py-2 text-center text-gray-700" dir="ltr">{{ $vital->blood_pressure ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center text-gray-700" dir="ltr">{{ $vital->heart_rate ? intval($vital->heart_rate) : '-' }}</td>
                                    <td class="px-3 py-2 text-center text-gray-700" dir="ltr">{{ $vital->temperature ? number_format($vital->temperature, 1) : '-' }}</td>
                                    <td class="px-3 py-2 text-center text-gray-700" dir="ltr">{{ $vital->weight ? number_format($vital->weight, 1) : '-' }}</td>
                                    <td class="px-3 py-2 text-center text-gray-700" dir="ltr">{{ $vital->blood_sugar ? intval($vital->blood_sugar) : '-' }}</td>
                                    <td class="px-3 py-2 text-center text-gray-700" dir="ltr">{{ $vital->oxygen_saturation ? intval($vital->oxygen_saturation) . '%' : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                @else
                <div class="px-6 py-8 text-center">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <p class="text-sm text-gray-400">{{ __('app.no_vitals_recorded') }}</p>
                </div>
                @endif
            </div>

            {{-- Chronic Diseases --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ showForm: false }">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        {{ __('app.chronic_diseases') }}
                        <span class="text-xs font-normal text-gray-400">({{ $patient->activeChronicDiseases->count() }})</span>
                    </h3>
                    @if(auth()->user()->hasPermission('patients.edit'))
                    <button @click="showForm = !showForm" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('app.add_disease') }}
                    </button>
                    @endif
                </div>

                {{-- Add Disease Form --}}
                <div x-show="showForm" x-transition x-cloak class="px-6 py-5 bg-gray-50/50 border-b border-gray-100">
                    <form action="{{ route('dashboard.patients.diseases.store', $patient) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.disease_name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="disease_name" required class="w-full rounded-xl border-gray-200 text-sm focus:border-amber-500 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.severity') }} <span class="text-red-500">*</span></label>
                                <select name="severity" required class="w-full rounded-xl border-gray-200 text-sm focus:border-amber-500 focus:ring-amber-500">
                                    <option value="mild">{{ __('app.mild') }}</option>
                                    <option value="moderate">{{ __('app.moderate') }}</option>
                                    <option value="severe">{{ __('app.severe') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.diagnosed_date') }}</label>
                                <input type="date" name="diagnosed_date" class="w-full rounded-xl border-gray-200 text-sm focus:border-amber-500 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.notes') }}</label>
                                <input type="text" name="notes" class="w-full rounded-xl border-gray-200 text-sm focus:border-amber-500 focus:ring-amber-500">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-amber-600 rounded-xl hover:bg-amber-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('app.save') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Disease List --}}
                @if($patient->activeChronicDiseases->count())
                <div class="divide-y divide-gray-100">
                    @foreach($patient->activeChronicDiseases as $disease)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">
                        @php
                            $sevStyles = [
                                'mild' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'moderate' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'severe' => 'bg-red-50 text-red-700 border-red-200',
                            ];
                        @endphp
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold text-gray-900">{{ $disease->disease_name }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $sevStyles[$disease->severity] ?? '' }}">{{ __('app.' . $disease->severity) }}</span>
                            </div>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                @if($disease->diagnosed_date)
                                <span>{{ __('app.diagnosed') }}: {{ $disease->diagnosed_date->format('Y-m-d') }}</span>
                                @endif
                                @if($disease->notes)
                                <span>{{ $disease->notes }}</span>
                                @endif
                            </div>
                        </div>
                        @if(auth()->user()->hasPermission('patients.edit'))
                        <div class="flex items-center gap-1 shrink-0">
                            <form method="POST" action="{{ route('dashboard.patients.diseases.toggle', $disease) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="{{ __('app.toggle_status') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('dashboard.patients.diseases.destroy', $disease) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="{{ __('app.delete') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-8 text-center">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-gray-400">{{ __('app.no_chronic_diseases') }}</p>
                </div>
                @endif
            </div>

            {{-- Medications --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ showForm: false }">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        {{ __('app.medications') }}
                        <span class="text-xs font-normal text-gray-400">({{ $patient->activeMedications->count() }})</span>
                    </h3>
                    @if(auth()->user()->hasPermission('patients.edit'))
                    <button @click="showForm = !showForm" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('app.add_medication') }}
                    </button>
                    @endif
                </div>

                {{-- Add Medication Form --}}
                <div x-show="showForm" x-transition x-cloak class="px-6 py-5 bg-gray-50/50 border-b border-gray-100">
                    <form action="{{ route('dashboard.patients.medications.store', $patient) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.medication_name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="medication_name" required class="w-full rounded-xl border-gray-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.dosage') }}</label>
                                <input type="text" name="dosage" placeholder="{{ __('app.dosage_placeholder') }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.frequency') }}</label>
                                <input type="text" name="frequency" placeholder="{{ __('app.frequency_placeholder') }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.prescribed_by') }}</label>
                                <input type="text" name="prescribed_by" class="w-full rounded-xl border-gray-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.start_date') }}</label>
                                <input type="date" name="start_date" class="w-full rounded-xl border-gray-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.end_date') }}</label>
                                <input type="date" name="end_date" class="w-full rounded-xl border-gray-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.notes') }}</label>
                            <input type="text" name="notes" class="w-full rounded-xl border-gray-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('app.save') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Medication List --}}
                @if($patient->activeMedications->count())
                <div class="divide-y divide-gray-100">
                    @foreach($patient->activeMedications as $med)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900">{{ $med->medication_name }}</p>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-gray-500">
                                @if($med->dosage)
                                <span>{{ __('app.dosage') }}: <strong>{{ $med->dosage }}</strong></span>
                                @endif
                                @if($med->frequency)
                                <span>{{ __('app.frequency') }}: <strong>{{ $med->frequency }}</strong></span>
                                @endif
                                @if($med->prescribed_by)
                                <span>{{ __('app.prescribed_by') }}: {{ $med->prescribed_by }}</span>
                                @endif
                                @if($med->start_date)
                                <span>{{ __('app.from') }}: {{ $med->start_date->format('Y-m-d') }}</span>
                                @endif
                                @if($med->end_date)
                                <span>{{ __('app.to') }}: {{ $med->end_date->format('Y-m-d') }}</span>
                                @endif
                            </div>
                            @if($med->notes)
                            <p class="text-xs text-gray-400 mt-1">{{ $med->notes }}</p>
                            @endif
                        </div>
                        @if(auth()->user()->hasPermission('patients.edit'))
                        <div class="flex items-center gap-1 shrink-0">
                            <form method="POST" action="{{ route('dashboard.patients.medications.toggle', $med) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="{{ __('app.toggle_status') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('dashboard.patients.medications.destroy', $med) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="{{ __('app.delete') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-8 text-center">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    <p class="text-sm text-gray-400">{{ __('app.no_medications') }}</p>
                </div>
                @endif
            </div>

            {{-- Medical Notes --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ showForm: false }">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ __('app.medical_notes') }}
                        <span class="text-xs font-normal text-gray-400">({{ $patient->medicalNotes->count() }})</span>
                    </h3>
                    @if(auth()->user()->hasPermission('patients.edit'))
                    <button @click="showForm = !showForm" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('app.add_note') }}
                    </button>
                    @endif
                </div>

                {{-- Add Note Form --}}
                <div x-show="showForm" x-transition x-cloak class="px-6 py-5 bg-gray-50/50 border-b border-gray-100">
                    <form action="{{ route('dashboard.patients.notes.store', $patient) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.note_type') }} <span class="text-red-500">*</span></label>
                            <select name="type" required class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="general">{{ __('app.note_general') }}</option>
                                <option value="follow_up">{{ __('app.note_follow_up') }}</option>
                                <option value="pre_op">{{ __('app.note_pre_op') }}</option>
                                <option value="post_op">{{ __('app.note_post_op') }}</option>
                                <option value="referral">{{ __('app.note_referral') }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.note_content') }} <span class="text-red-500">*</span></label>
                            <textarea name="note" rows="4" required class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('app.save') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Notes List --}}
                @if($patient->medicalNotes->count())
                <div class="divide-y divide-gray-100">
                    @foreach($patient->medicalNotes as $note)
                    @php
                        $noteStyles = [
                            'general' => 'bg-gray-50 text-gray-600 border-gray-200',
                            'follow_up' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'pre_op' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'post_op' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'referral' => 'bg-purple-50 text-purple-700 border-purple-200',
                        ];
                    @endphp
                    <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $noteStyles[$note->type] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ __('app.note_' . $note->type) }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $note->created_at->format('Y-m-d H:i') }}</span>
                            @if($note->creator)
                            <span class="text-xs text-gray-400">— {{ $note->creator->name }}</span>
                            @endif
                            @if(auth()->user()->hasPermission('patients.edit'))
                            <form method="POST" action="{{ route('dashboard.patients.notes.destroy', $note) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}')" class="{{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1 text-gray-400 hover:text-red-600 rounded transition-all" title="{{ __('app.delete') }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $note->note }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-8 text-center">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-sm text-gray-400">{{ __('app.no_medical_notes') }}</p>
                </div>
                @endif
            </div>

            {{-- Insurance --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ showAssign: false }">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        {{ __('app.insurance') }}
                    </h3>
                    @if(auth()->user()->hasPermission('patients.edit') && $insuranceProviders->count())
                    <button @click="showAssign = !showAssign" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('app.assign_insurance') }}
                    </button>
                    @endif
                </div>

                {{-- Assign Insurance Form --}}
                <div x-show="showAssign" x-transition x-cloak class="px-6 py-5 bg-gray-50/50 border-b border-gray-100">
                    <form action="{{ route('dashboard.patients.insurance.store', $patient) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.insurance_provider') }} <span class="text-red-500">*</span></label>
                                <select name="insurance_provider_id" required class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('app.select_provider') }}</option>
                                    @foreach($insuranceProviders as $provider)
                                    <option value="{{ $provider->id }}">{{ $provider->name }} ({{ $provider->coverage_percentage }}%)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.policy_number') }}</label>
                                <input type="text" name="policy_number" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.member_id') }}</label>
                                <input type="text" name="member_id" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.expiry_date') }}</label>
                                <input type="date" name="expiry_date" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.notes') }}</label>
                            <input type="text" name="notes" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('app.assign') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Active Insurance --}}
                @if($patient->activeInsurance)
                <div class="px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold text-gray-900">{{ $patient->activeInsurance->provider->name }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-md border bg-emerald-50 text-emerald-700 border-emerald-200">{{ __('app.active') }}</span>
                                @if($patient->activeInsurance->isExpired())
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-md border bg-red-50 text-red-700 border-red-200">{{ __('app.expired') }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                <span>{{ __('app.coverage') }}: <strong class="text-indigo-600">{{ $patient->activeInsurance->provider->coverage_percentage }}%</strong></span>
                                @if($patient->activeInsurance->policy_number)
                                <span>{{ __('app.policy') }}: <strong>{{ $patient->activeInsurance->policy_number }}</strong></span>
                                @endif
                                @if($patient->activeInsurance->expiry_date)
                                <span>{{ __('app.expires') }}: <strong>{{ $patient->activeInsurance->expiry_date }}</strong></span>
                                @endif
                            </div>
                        </div>
                        @if(auth()->user()->hasPermission('patients.edit'))
                        <form method="POST" action="{{ route('dashboard.patients.insurance.remove', $patient->activeInsurance) }}" onsubmit="return confirm('{{ __('app.confirm_remove_insurance') }}')">
                            @csrf @method('PATCH')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="{{ __('app.remove') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @else
                <div class="px-6 py-8 text-center">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <p class="text-sm text-gray-400">{{ __('app.no_insurance_assigned') }}</p>
                </div>
                @endif
            </div>

            {{-- File Attachments --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ showUpload: false, category: 'other' }">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        {{ __('app.file_attachments') }}
                        <span class="text-xs font-normal text-gray-400">({{ $patient->files->count() }})</span>
                    </h3>
                    @if(auth()->user()->hasPermission('patients.edit'))
                    <button @click="showUpload = !showUpload" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('app.upload_files') }}
                    </button>
                    @endif
                </div>

                {{-- Upload Form --}}
                <div x-show="showUpload" x-transition x-cloak class="px-6 py-5 bg-gray-50/50 border-b border-gray-100">
                    <form action="{{ route('dashboard.patients.files.store', $patient) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.files') }} <span class="text-red-500">*</span></label>
                                <input type="file" name="files[]" multiple required class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.dicom,.dcm">
                                <p class="text-xs text-gray-400 mt-1">{{ __('app.max_file_size_10mb') }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.category') }} <span class="text-red-500">*</span></label>
                                <select name="category" x-model="category" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="lab_result">{{ __('app.lab_result') }}</option>
                                    <option value="radiology">{{ __('app.radiology') }}</option>
                                    <option value="prescription">{{ __('app.prescription') }}</option>
                                    <option value="report">{{ __('app.medical_report') }}</option>
                                    <option value="id_document">{{ __('app.id_document') }}</option>
                                    <option value="insurance">{{ __('app.insurance_doc') }}</option>
                                    <option value="other" selected>{{ __('app.other') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.notes') }}</label>
                            <input type="text" name="notes" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('app.file_notes_placeholder') }}">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                {{ __('app.upload') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Files List --}}
                @if($patient->files->count())
                <div class="divide-y divide-gray-100">
                    @foreach($patient->files as $file)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">
                        {{-- File Icon --}}
                        <div class="shrink-0">
                            @if($file->isImage())
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @elseif($file->isPdf())
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            @else
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            @endif
                        </div>

                        {{-- File Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $file->name }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                @php
                                    $categoryStyles = [
                                        'lab_result' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'radiology' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                        'prescription' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'report' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'id_document' => 'bg-gray-50 text-gray-600 border-gray-200',
                                        'insurance' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'other' => 'bg-gray-50 text-gray-600 border-gray-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $categoryStyles[$file->category] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                    {{ __('app.file_cat_' . $file->category) }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $file->formattedSize() }}</span>
                                <span class="text-xs text-gray-400">{{ $file->created_at->format('Y-m-d') }}</span>
                            </div>
                            @if($file->notes)
                            <p class="text-xs text-gray-500 mt-1">{{ $file->notes }}</p>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('dashboard.patients.files.download', $file) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="{{ __('app.download') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                            @if(auth()->user()->hasPermission('patients.edit'))
                            <form action="{{ route('dashboard.patients.files.destroy', $file) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete_file') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="{{ __('app.delete') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <p class="text-sm text-gray-400">{{ __('app.no_files_yet') }}</p>
                </div>
                @endif
            </div>

            {{-- Recent Appointments --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">{{ __('app.recent_appointments') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.doctor') }}</th>
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($patient->appointments as $appointment)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $appointment->doctor->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusStyles = [
                                            'scheduled' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'completed' => 'bg-gray-50 text-gray-600 border-gray-200',
                                            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                            'no_show' => 'bg-red-50 text-red-700 border-red-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-lg border {{ $statusStyles[$appointment->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                        {{ __('app.' . $appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-400 text-sm">{{ __('app.no_appointments_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- QR Code --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.patient_qr') }}</h3>
                <div class="inline-block p-3 bg-white rounded-xl border border-gray-200">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate(route('dashboard.patients.show', $patient)) !!}
                </div>
                <p class="text-xs text-gray-400 mt-3">{{ __('app.scan_qr_hint') }}</p>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.actions') }}</h3>
                <div class="space-y-3">
                    <a href="{{ route('dashboard.patients.timeline', $patient) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('app.patient_timeline') }}
                    </a>
                    <a href="{{ route('dashboard.appointments.create', ['patient_id' => $patient->id]) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ __('app.schedule_follow_up') }}
                    </a>
                    <a href="{{ route('dashboard.patients.edit', $patient) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        {{ __('app.edit_patient') }}
                    </a>
                </div>
            </div>

            {{-- Stats --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.statistics') }}</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.total_appointments') }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $patient->appointments->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.completed') }}</span>
                        <span class="text-sm font-bold text-emerald-600">{{ $patient->appointments->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.cancelled') }}</span>
                        <span class="text-sm font-bold text-red-600">{{ $patient->appointments->where('status', 'cancelled')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.chronic_diseases') }}</span>
                        <span class="text-sm font-bold text-amber-600">{{ $patient->activeChronicDiseases->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.active_medications') }}</span>
                        <span class="text-sm font-bold text-emerald-600">{{ $patient->activeMedications->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.registered_at') }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $patient->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
