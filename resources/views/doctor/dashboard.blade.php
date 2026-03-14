<x-dashboard-layout>
    <x-slot name="title">{{ __('app.doctor_portal') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.doctor_portal') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.doctor-sidebar', ['active' => 'dashboard'])
    </x-slot>

    {{-- Welcome --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-indigo-500/25">
            {{ mb_substr($doctor->name, 0, 1) }}
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('app.welcome_doctor') }}, {{ $doctor->name }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $doctor->specialty->{'name_' . app()->getLocale()} ?? '' }} &mdash; {{ now()->format('l, j F Y') }}</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.today_appointments') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['today_appointments'] }}</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.completed_today') }}</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['completed_today'] }}</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.pending_today') }}</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $stats['pending_today'] }}</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.total_patients') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_patients'] }}</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.total_diagnoses_count') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_diagnoses'] }}</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.week_appointments') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['week_appointments'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Queue Widget --}}
    @if($queueCurrent || $queueWaiting->count() > 0)
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-lg shadow-indigo-500/25 p-6 mb-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-base font-bold">{{ __('app.waiting_queue') }}</h3>
            </div>
            <a href="{{ route('doctor.queue') }}" class="text-sm font-semibold text-white/80 hover:text-white transition-colors">{{ __('app.view_all') }} &rarr;</a>
        </div>
        <div class="flex items-center gap-6">
            @if($queueCurrent)
            <div class="flex items-center gap-3 bg-white/20 rounded-xl px-4 py-3">
                <div class="w-10 h-10 rounded-lg bg-white/30 flex items-center justify-center font-bold">{{ $queueCurrent->queue_number }}</div>
                <div>
                    <p class="text-xs text-white/70">{{ __('app.current_patient') }}</p>
                    <p class="font-semibold">{{ $queueCurrent->patient->name ?? '-' }}</p>
                </div>
            </div>
            @endif
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center font-bold text-xl">{{ $queueWaiting->count() }}</div>
                <div>
                    <p class="text-xs text-white/70">{{ __('app.waiting') }}</p>
                    @if($queueWaiting->first())
                    <p class="font-semibold text-sm">{{ __('app.next') }}: {{ $queueWaiting->first()->patient->name ?? '-' }}</p>
                    @else
                    <p class="font-semibold text-sm">{{ __('app.no_waiting_patients') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Today's Appointments --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-900">{{ __('app.today_appointments') }}</h3>
            <a href="{{ route('doctor.appointments') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">{{ __('app.view_all') }}</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($todayAppointments as $apt)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-sm font-bold text-indigo-600">
                        {{ \Carbon\Carbon::parse($apt->appointment_time)->format('H:i') }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $apt->patient->name ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $apt->patient->phone ?? '' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @php
                        $statusColors = [
                            'scheduled' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'confirmed' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                            'no_show' => 'bg-gray-50 text-gray-700 border-gray-200',
                        ];
                    @endphp
                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-lg border {{ $statusColors[$apt->status] ?? '' }}">
                        {{ __('app.' . $apt->status) }}
                    </span>
                    <a href="{{ route('doctor.appointment.show', $apt) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        {{ __('app.view') }}
                    </a>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="w-14 h-14 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-gray-500 font-medium">{{ __('app.no_appointments_today') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Upcoming --}}
    @if($upcomingAppointments->count())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-900">{{ __('app.upcoming_appointments') }}</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($upcomingAppointments as $apt)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d') }}</p>
                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $apt->patient->name ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $apt->patient->phone ?? '' }}</p>
                    </div>
                </div>
                <a href="{{ route('doctor.appointment.show', $apt) }}" class="text-indigo-600 hover:text-indigo-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</x-dashboard-layout>
