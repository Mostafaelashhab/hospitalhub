<x-dashboard-layout>
    <x-slot name="title">{{ __('app.patient_history') }} - {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.patient_history') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.doctor-sidebar', ['active' => 'appointments'])
    </x-slot>

    {{-- Back --}}
    <div class="mb-6">
        <a href="javascript:history.back()" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    {{-- Patient Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-xl font-bold text-indigo-600">
                {{ mb_substr($patient->name, 0, 1) }}
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900">{{ $patient->name }}</h2>
                <div class="flex flex-wrap items-center gap-4 mt-1">
                    <span class="text-sm text-gray-500">{{ $patient->phone }}</span>
                    @if($patient->gender)
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-lg">{{ __('app.' . $patient->gender) }}</span>
                    @endif
                    @if($patient->date_of_birth)
                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} {{ __('app.years_old') }}</span>
                    @endif
                    @if($patient->blood_type)
                    <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-lg">{{ $patient->blood_type }}</span>
                    @endif
                </div>
            </div>
        </div>

        @if($patient->medical_history || $patient->allergies)
        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if($patient->medical_history)
            <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">{{ __('app.medical_history') }}</p>
                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $patient->medical_history }}</p>
            </div>
            @endif
            @if($patient->allergies)
            <div>
                <p class="text-xs font-semibold text-red-500 mb-1">{{ __('app.allergies') }}</p>
                <p class="text-sm text-red-700 bg-red-50 rounded-lg p-3">{{ $patient->allergies }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- All Diagnoses Timeline --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-900">{{ __('app.diagnoses_history') }} ({{ $allDiagnoses->count() }})</h3>
        </div>

        @forelse($allDiagnoses as $diag)
        <div class="px-6 py-5 border-b border-gray-50 last:border-0">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                    <span class="text-sm font-bold text-gray-900">{{ $diag->created_at->format('Y-m-d') }}</span>
                    <span class="text-xs text-gray-400">{{ __('app.doctor') }}: {{ $diag->doctor->name ?? '-' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 {{ app()->getLocale() === 'ar' ? 'mr-5' : 'ml-5' }}">
                @if($diag->complaint)
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">{{ __('app.complaint') }}</p>
                    <p class="text-sm text-gray-700">{{ $diag->complaint }}</p>
                </div>
                @endif
                @if($diag->diagnosis)
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">{{ __('app.diagnosis_text') }}</p>
                    <p class="text-sm text-gray-700">{{ $diag->diagnosis }}</p>
                </div>
                @endif
                @if($diag->prescription)
                <div>
                    <p class="text-xs font-semibold text-indigo-500 mb-1">{{ __('app.prescription') }}</p>
                    <div class="text-sm text-gray-700">
                        @foreach(array_filter(explode("\n", $diag->prescription)) as $med)
                        <span class="inline-block bg-indigo-50 text-indigo-700 text-xs px-2 py-1 rounded-lg mr-1 mb-1">{{ $med }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                @if($diag->lab_tests)
                <div>
                    <p class="text-xs font-semibold text-emerald-500 mb-1">{{ __('app.lab_tests') }}</p>
                    <div class="text-sm text-gray-700">
                        @foreach(array_filter(explode("\n", $diag->lab_tests)) as $test)
                        <span class="inline-block bg-emerald-50 text-emerald-700 text-xs px-2 py-1 rounded-lg mr-1 mb-1">{{ $test }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                @if($diag->radiology)
                <div>
                    <p class="text-xs font-semibold text-amber-500 mb-1">{{ __('app.radiology') }}</p>
                    <div class="text-sm text-gray-700">
                        @foreach(array_filter(explode("\n", $diag->radiology)) as $rad)
                        <span class="inline-block bg-amber-50 text-amber-700 text-xs px-2 py-1 rounded-lg mr-1 mb-1">{{ $rad }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                @if($diag->notes)
                <div class="sm:col-span-2">
                    <p class="text-xs font-semibold text-gray-500 mb-1">{{ __('app.notes') }}</p>
                    <p class="text-sm text-gray-700">{{ $diag->notes }}</p>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="px-6 py-12 text-center">
            <p class="text-gray-500 font-medium">{{ __('app.no_diagnoses_found') }}</p>
        </div>
        @endforelse
    </div>
</x-dashboard-layout>
