<x-dashboard-layout>
    <x-slot name="title">{{ __('app.appointments') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.appointments') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'appointments'])
    </x-slot>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.appointments') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.all_appointments') }}</p>
        </div>
        @if(auth()->user()->hasPermission('appointments.create'))
        <a href="{{ route('dashboard.appointments.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.new_appointment') }}
        </a>
        @endif
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('dashboard.appointments.index') }}" class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="flex-1 relative">
                <svg class="w-5 h-5 text-gray-400 absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_appointments') }}"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
            </div>
            {{-- Date filter --}}
            <input type="date" name="date"  value="{{ request('date') }}"
                   class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
            {{-- Doctor filter --}}
            <select name="doctor_id" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                <option value="">{{ __('app.filter_by_doctor') }}: {{ __('app.all_doctors') }}</option>
                @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                @endforeach
            </select>
            {{-- Status filter --}}
            <select name="status" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                <option value="">{{ __('app.filter_by_status') }}: {{ __('app.all') }}</option>
                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>{{ __('app.scheduled') }}</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>{{ __('app.confirmed') }}</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('app.in_progress') }}</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('app.completed') }}</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('app.cancelled') }}</option>
                <option value="no_show" {{ request('status') === 'no_show' ? 'selected' : '' }}>{{ __('app.no_show') }}</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                {{ __('app.search') }}
            </button>
        </form>
    </div>

    {{-- Appointments Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.time') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.patient') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.doctor') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.service') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointments as $appointment)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}
                            @if($appointment->isRecurring())
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-md {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                {{ __('app.recurring') }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-mono" dir="ltr">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900 font-semibold">{{ $appointment->patient->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $appointment->patient->phone ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $appointment->doctor->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($appointment->services->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach($appointment->services->take(2) as $service)
                                    <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-md">
                                        {{ app()->getLocale() === 'ar' ? $service->name_ar : $service->name_en }}
                                    </span>
                                    @endforeach
                                    @if($appointment->services->count() > 2)
                                    <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-medium text-gray-500 bg-gray-50 border border-gray-200 rounded-md">
                                        +{{ $appointment->services->count() - 2 }}
                                    </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
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
                                $statusDots = [
                                    'scheduled' => 'bg-blue-500',
                                    'confirmed' => 'bg-emerald-500',
                                    'in_progress' => 'bg-amber-500',
                                    'completed' => 'bg-gray-400',
                                    'cancelled' => 'bg-red-500',
                                    'no_show' => 'bg-red-500',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-lg border {{ $statusStyles[$appointment->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$appointment->status] ?? 'bg-gray-400' }}"></span>
                                {{ __('app.' . $appointment->status) }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('dashboard.appointments.show', $appointment) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ __('app.view_details') }}
                                </a>
                                @if(auth()->user()->hasPermission('appointments.change_status') && in_array($appointment->status, ['scheduled', 'confirmed', 'in_progress']))
                                <a href="{{ route('dashboard.appointments.show', $appointment) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-700 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                                    {{ __('app.change_status') }}
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-gray-500 font-medium">{{ __('app.no_appointments_found') }}</p>
                                <a href="{{ route('dashboard.appointments.create') }}" class="mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-semibold">{{ __('app.new_appointment') }} &rarr;</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($appointments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $appointments->withQueryString()->links() }}
        </div>
        @endif
    </div>
</x-dashboard-layout>
