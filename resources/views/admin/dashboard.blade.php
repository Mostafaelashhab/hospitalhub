<x-dashboard-layout>
    <x-slot name="title">{{ __('app.clinic_dashboard') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.clinic_dashboard') }}</x-slot>

    @php $isPending = $clinic->status === 'pending'; @endphp

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'dashboard'])
    </x-slot>

    {{-- Free Mode Banner --}}
    @if(\App\Models\PlatformSetting::isFreeModeActive($clinic))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-2xl p-5">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-emerald-800">{{ __('app.free_mode_banner_title') }}</h3>
                <p class="text-sm text-emerald-700 mt-1">{{ __('app.free_mode_banner_desc') }}</p>
                @php $freeModeUntil = \App\Models\PlatformSetting::get('free_mode_until'); @endphp
                @if($freeModeUntil)
                <p class="text-xs text-emerald-600 mt-2 font-semibold">{{ __('app.until') }} {{ $freeModeUntil }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Pending Review Banner --}}
    @if($isPending)
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-5">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-amber-800">{{ __('app.account_under_review') }}</h3>
                <p class="text-sm text-amber-700 mt-1">{{ __('app.account_under_review_desc') }}</p>
                <div class="flex items-center gap-2 mt-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-amber-100 text-amber-700 rounded-lg border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        {{ __('app.pending') }}
                    </span>
                    <span class="text-xs text-amber-600">{{ __('app.review_time_estimate') }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- WhatsApp Integration Banner --}}
    @if(!$isPending)
    <div class="mb-6 bg-white rounded-2xl border border-emerald-200 p-5">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="text-base font-bold text-gray-900">{{ app()->getLocale() === 'ar' ? 'إشعارات واتساب تلقائية' : 'Automatic WhatsApp Notifications' }}</h3>
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-700 rounded-full">{{ app()->getLocale() === 'ar' ? 'جديد' : 'NEW' }}</span>
                </div>
                <p class="text-sm text-gray-500 leading-relaxed">{{ app()->getLocale() === 'ar' ? 'المرضى هيوصلهم رسائل واتساب تلقائية: تأكيد الحجز، تذكير قبل الموعد، الأدوية والتحاليل والأشعة بعد الكشف، وتذكير بالإعادة.' : 'Patients now receive automatic WhatsApp messages: booking confirmation, appointment reminders, prescriptions & lab orders after diagnosis, and follow-up reminders.' }}</p>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'تأكيد حجز' : 'Booking confirm' }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'تذكير بالموعد' : 'Reminders' }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'أدوية وتحاليل' : 'Rx & Labs' }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'تذكير إعادة' : 'Follow-up' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Welcome Section --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 sm:gap-4 mb-2">
            <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-lg sm:text-xl font-bold text-white shadow-lg shadow-indigo-500/25 shrink-0">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="min-w-0">
                <h2 class="text-lg sm:text-2xl font-bold text-gray-900 truncate">{{ __('app.welcome_back') }}, {{ auth()->user()->name }}!</h2>
                <p class="text-gray-500 mt-0.5 text-sm truncate">{{ $clinic->name_en ?? $clinic->name_ar }} &mdash; {{ now()->format('l, F j, Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8 {{ $isPending ? 'opacity-50 pointer-events-none' : '' }}">
        {{-- Patients --}}
        <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">{{ __('app.patients') }}</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['patients_count'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.patients') }}</p>
        </div>

        {{-- Doctors --}}
        <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-lg">{{ __('app.doctors') }}</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['doctors_count'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.doctors') }}</p>
        </div>

        {{-- Today Appointments --}}
        <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">{{ __('app.today_appointments') }}</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['appointments_today'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.today_appointments') }}</p>
        </div>

        {{-- Month Appointments --}}
        <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-cyan-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="text-xs font-semibold text-cyan-600 bg-cyan-50 px-2.5 py-1 rounded-lg">{{ __('app.this_month') }}</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['appointments_month'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.this_month') }}</p>
        </div>

        {{-- Wallet Balance --}}
        <div class="stat-card bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 shadow-lg shadow-indigo-500/20 text-white relative overflow-hidden">
            <div class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 {{ app()->getLocale() === 'ar' ? '-translate-x-1/2' : 'translate-x-1/2' }}"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/15 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold tracking-wide">{{ number_format($stats['wallet_balance']) }}</p>
                <p class="text-indigo-200 text-sm mt-1">{{ __('app.points_balance') }}</p>
                <a href="{{ route('dashboard.recharge.index') }}" class="inline-flex items-center gap-1.5 mt-3 px-3 py-1 text-[11px] font-semibold bg-white/15 backdrop-blur rounded-lg text-white/90 hover:bg-white/25 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('app.recharge') }}
                </a>
            </div>
        </div>

        {{-- Unpaid Invoices --}}
        <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg">{{ __('app.unpaid_invoices') }}</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['invoices_unpaid'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.unpaid_invoices') }}</p>
        </div>
    </div>

    {{-- Today's Appointments --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden {{ $isPending ? 'opacity-50 pointer-events-none' : '' }}">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">{{ __('app.today_appointments') }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.time') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.patient') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.doctor') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($todayAppointments as $appointment)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900 font-mono font-medium" dir="ltr">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $appointment->patient->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $appointment->doctor->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $apptStyles = [
                                    'scheduled' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'completed' => 'bg-gray-50 text-gray-600 border-gray-200',
                                    'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                    'no_show' => 'bg-red-50 text-red-700 border-red-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-lg border {{ $apptStyles[$appointment->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ __('app.' . $appointment->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-gray-500 font-medium">{{ __('app.no_appointments_today') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
