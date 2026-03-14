<x-dashboard-layout>
    <x-slot name="title">{{ __('app.appointment_details') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.appointment_details') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'appointments'])
    </x-slot>

    @php
        $statusStyles = [
            'scheduled' => 'bg-blue-50 text-blue-700 border-blue-200',
            'confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
            'completed' => 'bg-gray-50 text-gray-600 border-gray-200',
            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
            'no_show' => 'bg-red-50 text-red-700 border-red-200',
        ];
        $statusDots = [
            'scheduled' => 'bg-blue-500',
            'confirmed' => 'bg-emerald-500',
            'in_progress' => 'bg-amber-500',
            'completed' => 'bg-gray-400',
            'cancelled' => 'bg-red-500',
            'no_show' => 'bg-red-500',
        ];
    @endphp

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.appointments.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5 text-gray-600 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('app.appointment_details') }}</h2>
                <p class="text-sm text-gray-500 mt-1">#{{ $appointment->id }} &mdash; {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F j, Y') }}</p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-xl border {{ $statusStyles[$appointment->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
            <span class="w-2 h-2 rounded-full {{ $statusDots[$appointment->status] ?? 'bg-gray-400' }}"></span>
            {{ __('app.' . $appointment->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Appointment Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">{{ __('app.appointment_details') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('app.date') }}</p>
                            <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('app.time') }}</p>
                            <p class="text-sm font-semibold text-gray-900 font-mono" dir="ltr">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('app.consultation_fee') }}</p>
                            <p class="text-sm font-semibold text-gray-900">{{ number_format($appointment->doctor->consultation_fee ?? 0) }} {{ __('app.points') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('app.status') }}</p>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-lg border {{ $statusStyles[$appointment->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$appointment->status] ?? 'bg-gray-400' }}"></span>
                                {{ __('app.' . $appointment->status) }}
                            </span>
                        </div>
                    </div>
                    @if($appointment->notes)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('app.notes') }}</p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $appointment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Recurring Group --}}
            @if($appointment->isRecurring())
            <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-indigo-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <h3 class="text-base font-bold text-gray-900">{{ __('app.recurring_appointment') }}</h3>
                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-lg">{{ __('app.' . $appointment->recurrence_type) }}</span>
                </div>
                <div class="p-6">
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($appointment->recurringGroup()->orderBy('appointment_date')->get() as $relatedAppt)
                        <a href="{{ route('dashboard.appointments.show', $relatedAppt) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ $relatedAppt->id === $appointment->id ? 'bg-indigo-50 border border-indigo-200' : 'hover:bg-gray-50' }} transition-all">
                            <span class="font-medium {{ $relatedAppt->id === $appointment->id ? 'text-indigo-700' : 'text-gray-700' }}">
                                {{ \Carbon\Carbon::parse($relatedAppt->appointment_date)->format('Y-m-d') }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-md border {{ $statusStyles[$relatedAppt->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$relatedAppt->status] ?? 'bg-gray-400' }}"></span>
                                {{ __('app.' . $relatedAppt->status) }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Patient Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">{{ __('app.patient') }}</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                            <span class="text-blue-600 font-bold text-lg">{{ mb_substr($appointment->patient->name ?? '?', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-gray-900 font-semibold">{{ $appointment->patient->name ?? '-' }}</p>
                            <p class="text-sm text-gray-500">{{ $appointment->patient->phone ?? '' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('app.email') }}</p>
                            <p class="text-sm text-gray-700">{{ $appointment->patient->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('app.phone') }}</p>
                            <p class="text-sm text-gray-700 font-mono" dir="ltr">{{ $appointment->patient->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Doctor Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">{{ __('app.doctor') }}</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                            <span class="text-purple-600 font-bold text-lg">{{ mb_substr($appointment->doctor->name ?? '?', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-gray-900 font-semibold">{{ $appointment->doctor->name ?? '-' }}</p>
                            <p class="text-sm text-gray-500">{{ $appointment->doctor->phone ?? '' }}</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('app.consultation_fee') }}</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($appointment->doctor->consultation_fee ?? 0) }} <span class="text-sm font-normal text-gray-500">{{ __('app.points') }}</span></p>
                    </div>
                </div>
            </div>

            {{-- Diagnosis Button --}}
            <div class="bg-white rounded-2xl border border-teal-200 shadow-sm overflow-hidden">
                <a href="{{ route('dashboard.diagnoses.create', $appointment) }}" class="flex items-center gap-3 px-6 py-5 hover:bg-teal-50 transition-all">
                    <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-teal-700">{{ __('app.diagnosis') }}</h3>
                        <p class="text-xs text-teal-600">{{ $appointment->diagnosis ? __('app.view_edit_diagnosis') : __('app.add_diagnosis') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-teal-400 {{ app()->getLocale() === 'ar' ? 'mr-auto rotate-180' : 'ml-auto' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Change Status --}}
            @if(in_array($appointment->status, ['scheduled', 'confirmed', 'in_progress']))
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">{{ __('app.change_status') }}</h3>
                </div>
                <div class="p-6 space-y-2">
                    @foreach(['scheduled', 'confirmed', 'in_progress', 'cancelled', 'no_show'] as $s)
                        @if($s !== $appointment->status)
                        <form method="POST" action="{{ route('dashboard.appointments.status', $appointment) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $s }}">
                            <button type="submit" class="w-full {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }} px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-100 rounded-xl hover:bg-gray-100 transition-all flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full {{ $statusDots[$s] ?? 'bg-gray-400' }}"></span>
                                {{ __('app.' . $s) }}
                            </button>
                        </form>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Complete Appointment --}}
            <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm overflow-hidden" x-data="{ showForm: {{ request()->get('complete') ? 'true' : 'false' }} }">
                <button type="button" @click="showForm = !showForm" class="w-full px-6 py-5 flex items-center gap-3 hover:bg-emerald-50 transition-all">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <h3 class="text-base font-bold text-emerald-700">{{ __('app.mark_completed') }}</h3>
                    <svg class="w-4 h-4 text-emerald-500 {{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }} transition-transform" :class="showForm ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="showForm" x-transition x-cloak class="px-6 pb-6 border-t border-emerald-100">
                    @if($errors->any())
                    <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                        @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif
                    <form method="POST" action="{{ route('dashboard.appointments.status', $appointment) }}" class="space-y-4 pt-4">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">

                        {{-- Consultation Fee --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.consultation_fee') }}</label>
                            <input type="number" name="amount" value="{{ $appointment->doctor->consultation_fee ?? 0 }}" min="0" step="0.01"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                        </div>

                        {{-- Follow-up Toggle --}}
                        <div x-data="{ followUp: false }">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="create_followup" value="1" x-model="followUp"
                                       class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="text-xs font-semibold text-gray-600">{{ __('app.schedule_followup') }}</span>
                            </label>

                            <div x-show="followUp" x-transition class="mt-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.followup_date') }}</label>
                                    <input type="date" name="followup_date"
                                           class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('app.mark_completed') }}
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
