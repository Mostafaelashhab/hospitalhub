<x-dashboard-layout>
    <x-slot name="title">{{ __('app.patient_timeline') }} - {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.patient_timeline') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.patients.show', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    {{-- Patient Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $patient->gender === 'male' ? 'from-blue-500 to-cyan-600' : 'from-pink-500 to-rose-600' }} flex items-center justify-center text-xl font-bold text-white shadow-lg">
                {{ mb_substr($patient->name, 0, 1) }}
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900">{{ $patient->name }}</h2>
                <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                    <span dir="ltr">{{ $patient->phone }}</span>
                    @if($patient->date_of_birth)
                    <span>{{ $patient->date_of_birth->age }} {{ __('app.years_old') }}</span>
                    @endif
                    @if($patient->blood_type)
                    <span class="font-bold text-red-500">{{ $patient->blood_type }}</span>
                    @endif
                </div>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-indigo-600">{{ $timeline->where('type', 'appointment')->count() }}</p>
                <p class="text-xs text-gray-500">{{ __('app.total_visits') }}</p>
            </div>
        </div>

        {{-- Medical Summary --}}
        @if($patient->medical_history || $patient->allergies)
        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if($patient->allergies)
            <div class="flex items-start gap-2 bg-red-50 rounded-xl px-4 py-3">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <div>
                    <p class="text-xs font-bold text-red-700">{{ __('app.allergies') }}</p>
                    <p class="text-sm text-red-600">{{ $patient->allergies }}</p>
                </div>
            </div>
            @endif
            @if($patient->medical_history)
            <div class="flex items-start gap-2 bg-amber-50 rounded-xl px-4 py-3">
                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <div>
                    <p class="text-xs font-bold text-amber-700">{{ __('app.medical_history') }}</p>
                    <p class="text-sm text-amber-600">{{ $patient->medical_history }}</p>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- Timeline --}}
    @if($timeline->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-gray-500 text-sm">{{ __('app.no_timeline_data') }}</p>
    </div>
    @else
    <div class="relative">
        {{-- Vertical line --}}
        <div class="absolute {{ app()->getLocale() === 'ar' ? 'right-6' : 'left-6' }} top-0 bottom-0 w-0.5 bg-gray-200"></div>

        <div class="space-y-6">
            @php $lastDate = null; @endphp
            @foreach($timeline as $item)
                @php
                    $currentDate = $item['date'];
                    $showDate = $currentDate !== $lastDate;
                    $lastDate = $currentDate;
                @endphp

                {{-- Date Separator --}}
                @if($showDate)
                <div class="relative flex items-center gap-4 {{ app()->getLocale() === 'ar' ? 'pr-0' : 'pl-0' }}">
                    <div class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center z-10 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($currentDate)->format('l, d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($currentDate)->diffForHumans() }}</p>
                    </div>
                </div>
                @endif

                {{-- Timeline Item --}}
                <div class="relative {{ app()->getLocale() === 'ar' ? 'pr-16' : 'pl-16' }}">
                    {{-- Dot --}}
                    @php
                        $dotColors = [
                            'appointment' => 'bg-blue-500',
                            'diagnosis' => 'bg-emerald-500',
                            'invoice' => 'bg-amber-500',
                        ];
                    @endphp
                    <div class="absolute {{ app()->getLocale() === 'ar' ? 'right-[18px]' : 'left-[18px]' }} top-6 w-6 h-6 rounded-full {{ $dotColors[$item['type']] }} flex items-center justify-center z-10 ring-4 ring-white">
                        @if($item['type'] === 'appointment')
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
                        @elseif($item['type'] === 'diagnosis')
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        @else
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                        @endif
                    </div>

                    {{-- Card --}}
                    @if($item['type'] === 'appointment')
                    @php $apt = $item['data']; @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg bg-blue-50 text-blue-700 border border-blue-200">{{ __('app.appointment') }}</span>
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
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg border {{ $statusStyles[$apt->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                    {{ __('app.' . $apt->status) }}
                                </span>
                            </div>
                            @if($apt->appointment_time)
                            <span class="text-xs text-gray-500 font-mono" dir="ltr">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            @if($apt->doctor)
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $apt->doctor->name }}
                            </div>
                            @endif
                            <a href="{{ route('dashboard.appointments.show', $apt) }}" class="text-indigo-600 hover:text-indigo-700 font-medium text-xs">{{ __('app.view') }} &rarr;</a>
                        </div>
                    </div>

                    @elseif($item['type'] === 'diagnosis')
                    @php $diag = $item['data']; @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">{{ __('app.diagnosis') }}</span>
                            @if($diag->doctor)
                            <span class="text-xs text-gray-500">{{ $diag->doctor->name }}</span>
                            @endif
                        </div>

                        <div class="space-y-2">
                            @if($diag->complaint)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 mb-0.5">{{ __('app.complaint') }}</p>
                                <p class="text-sm text-gray-700">{{ Str::limit($diag->complaint, 150) }}</p>
                            </div>
                            @endif

                            @if($diag->diagnosis)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 mb-0.5">{{ __('app.diagnosis_text') }}</p>
                                <p class="text-sm text-gray-700">{{ Str::limit($diag->diagnosis, 150) }}</p>
                            </div>
                            @endif

                            @if($diag->prescription)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 mb-0.5">{{ __('app.prescription') }}</p>
                                <p class="text-sm text-gray-700">{{ Str::limit($diag->prescription, 150) }}</p>
                            </div>
                            @endif

                            @if($diag->lab_tests)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 mb-0.5">{{ __('app.lab_tests') }}</p>
                                <p class="text-sm text-gray-700">{{ Str::limit($diag->lab_tests, 100) }}</p>
                            </div>
                            @endif

                            @if($diag->radiology)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 mb-0.5">{{ __('app.radiology') }}</p>
                                <p class="text-sm text-gray-700">{{ Str::limit($diag->radiology, 100) }}</p>
                            </div>
                            @endif
                        </div>

                        @if($diag->appointment_id)
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <a href="{{ route('dashboard.diagnoses.show', $diag) }}" class="text-indigo-600 hover:text-indigo-700 font-medium text-xs">{{ __('app.view_edit_diagnosis') }} &rarr;</a>
                        </div>
                        @endif
                    </div>

                    @elseif($item['type'] === 'invoice')
                    @php $inv = $item['data']; @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-50 text-amber-700 border border-amber-200">{{ __('app.invoice') }}</span>
                            @php
                                $invStatusStyles = [
                                    'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'unpaid' => 'bg-red-50 text-red-700 border-red-200',
                                    'partial' => 'bg-amber-50 text-amber-700 border-amber-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg border {{ $invStatusStyles[$inv->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ __('app.' . $inv->status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                <span class="font-bold text-gray-900">{{ number_format($inv->total, 2) }}</span> {{ __('app.currency') }}
                                @if($inv->discount > 0)
                                <span class="text-xs text-red-500 mx-1">({{ __('app.discount') }}: {{ number_format($inv->discount, 2) }})</span>
                                @endif
                            </div>
                            <a href="{{ route('dashboard.invoices.show', $inv) }}" class="text-indigo-600 hover:text-indigo-700 font-medium text-xs">{{ __('app.view') }} &rarr;</a>
                        </div>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif
</x-dashboard-layout>
