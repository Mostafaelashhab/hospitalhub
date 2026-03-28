<x-dashboard-layout>
    <x-slot name="title">{{ __('app.super_admin_panel') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.dashboard') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.super-admin-sidebar')
    </x-slot>

    @php $isAr = app()->getLocale() === 'ar'; $align = $isAr ? 'right' : 'left'; @endphp

    {{-- Welcome --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xl font-bold text-white shadow-lg shadow-indigo-500/25">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('app.welcome_back') }}, {{ auth()->user()->name }}!</h2>
                <p class="text-gray-500 mt-0.5">{{ __('app.super_admin_panel') }} &mdash; {{ now()->format('l, F j, Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Top Stats Row --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
        {{-- Total Clinics --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_clinics']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $isAr ? 'العيادات' : 'Clinics' }}</p>
        </div>

        {{-- Active Clinics --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_clinics']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $isAr ? 'نشطة' : 'Active' }}</p>
        </div>

        {{-- Total Patients --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_patients']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $isAr ? 'المرضى' : 'Patients' }}</p>
        </div>

        {{-- Total Doctors --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_doctors']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $isAr ? 'الأطباء' : 'Doctors' }}</p>
        </div>

        {{-- Today Appointments --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-cyan-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['appointments_today']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $isAr ? 'مواعيد اليوم' : 'Today' }}</p>
        </div>

        {{-- Pending Clinics --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm {{ $stats['pending_clinics'] > 0 ? 'ring-2 ring-amber-200' : '' }}">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-bold {{ $stats['pending_clinics'] > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $stats['pending_clinics'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $isAr ? 'بانتظار المراجعة' : 'Pending' }}</p>
        </div>
    </div>

    {{-- Financial + WhatsApp Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        {{-- Revenue This Month --}}
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-emerald-500/20">
            <div class="absolute top-0 {{ $isAr ? 'left-0' : 'right-0' }} w-20 h-20 bg-white/10 rounded-full -translate-y-1/2 {{ $isAr ? '-translate-x-1/2' : 'translate-x-1/2' }}"></div>
            <div class="relative">
                <p class="text-emerald-200 text-xs font-medium mb-2">{{ $isAr ? 'إيرادات الشهر' : 'Revenue This Month' }}</p>
                <p class="text-2xl font-bold">{{ number_format($stats['revenue_month'], 2) }}</p>
                <p class="text-emerald-200 text-[11px] mt-1">{{ $isAr ? 'إجمالي: ' : 'Total: ' }}{{ number_format($stats['revenue_total'], 2) }}</p>
            </div>
        </div>

        {{-- Wallet Balance --}}
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-indigo-500/20">
            <div class="absolute top-0 {{ $isAr ? 'left-0' : 'right-0' }} w-20 h-20 bg-white/10 rounded-full -translate-y-1/2 {{ $isAr ? '-translate-x-1/2' : 'translate-x-1/2' }}"></div>
            <div class="relative">
                <p class="text-indigo-200 text-xs font-medium mb-2">{{ $isAr ? 'رصيد المحافظ' : 'Wallets Balance' }}</p>
                <p class="text-2xl font-bold">{{ number_format($stats['total_wallet_balance']) }}</p>
                <p class="text-indigo-200 text-[11px] mt-1">{{ __('app.points') }}</p>
            </div>
        </div>

        {{-- Unpaid Invoices --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['invoices_unpaid']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $isAr ? 'فواتير غير مدفوعة' : 'Unpaid Invoices' }}</p>
        </div>

        {{-- WhatsApp Status --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm" x-data="{ online: null, checking: true }" x-init="
            fetch('{{ route('whatsapp.health') }}').then(r => r.json()).then(d => { online = d.online; checking = false; }).catch(() => { online = false; checking = false; })
        ">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" :class="checking ? 'bg-gray-50' : (online ? 'bg-emerald-50' : 'bg-red-50')">
                    <svg class="w-5 h-5" :class="checking ? 'text-gray-400' : (online ? 'text-emerald-600' : 'text-red-600')" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <template x-if="checking">
                    <span class="flex items-center gap-1.5">
                        <svg class="animate-spin w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span class="text-sm text-gray-400">{{ $isAr ? 'جاري الفحص...' : 'Checking...' }}</span>
                    </span>
                </template>
                <template x-if="!checking && online">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ $isAr ? 'متصل' : 'Connected' }}
                    </span>
                </template>
                <template x-if="!checking && !online">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-red-50 text-red-700 rounded-lg border border-red-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        {{ $isAr ? 'غير متصل' : 'Disconnected' }}
                    </span>
                </template>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ $isAr ? 'حالة الواتساب' : 'WhatsApp Status' }}</p>
        </div>
    </div>

    {{-- Growth + Appointment Status --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
        {{-- Monthly Registration Trend --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $isAr ? 'نمو العيادات' : 'Clinic Registrations' }}</h3>
            <p class="text-xs text-gray-400 mb-5">{{ $isAr ? 'آخر 12 شهر' : 'Last 12 months' }}</p>
            @php $maxTrend = max(1, ...array_column($monthlyTrend, 'count')); @endphp
            <div class="flex items-end gap-2 h-40">
                @foreach($monthlyTrend as $m)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <span class="text-[10px] font-semibold text-gray-500">{{ $m['count'] }}</span>
                    <div class="w-full bg-indigo-100 rounded-t-lg transition-all duration-500 relative group" style="height: {{ max(($m['count'] / $maxTrend) * 100, 4) }}%">
                        <div class="absolute inset-0 bg-indigo-500 rounded-t-lg opacity-80 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <span class="text-[10px] text-gray-400">{{ $m['label'] }}</span>
                </div>
                @endforeach
            </div>
            <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-gray-900">{{ $stats['new_clinics_month'] }}</span>
                    <span class="text-xs text-gray-500">{{ $isAr ? 'هذا الشهر' : 'This month' }}</span>
                </div>
                @if($stats['new_clinics_last_month'] > 0)
                @php $change = round(($stats['new_clinics_month'] - $stats['new_clinics_last_month']) / $stats['new_clinics_last_month'] * 100); @endphp
                <span class="inline-flex items-center gap-0.5 text-xs font-semibold {{ $change >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                    <svg class="w-3 h-3 {{ $change < 0 ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    {{ abs($change) }}%
                </span>
                @endif
            </div>
        </div>

        {{-- Appointment Status Breakdown --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $isAr ? 'حالة المواعيد' : 'Appointment Status' }}</h3>
            <p class="text-xs text-gray-400 mb-5">{{ $isAr ? 'هذا الشهر' : 'This month' }}</p>
            @php
                $totalAppts = array_sum($appointmentStatuses) ?: 1;
                $statusConfig = [
                    'completed' => ['color' => 'emerald', 'label' => $isAr ? 'مكتمل' : 'Completed'],
                    'scheduled' => ['color' => 'blue', 'label' => $isAr ? 'محجوز' : 'Scheduled'],
                    'confirmed' => ['color' => 'cyan', 'label' => $isAr ? 'مؤكد' : 'Confirmed'],
                    'in_progress' => ['color' => 'amber', 'label' => $isAr ? 'جاري' : 'In Progress'],
                    'cancelled' => ['color' => 'red', 'label' => $isAr ? 'ملغي' : 'Cancelled'],
                    'no_show' => ['color' => 'gray', 'label' => $isAr ? 'لم يحضر' : 'No Show'],
                ];
            @endphp
            <div class="space-y-3">
                @foreach($statusConfig as $status => $config)
                    @if(($appointmentStatuses[$status] ?? 0) > 0)
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-600 font-medium">{{ $config['label'] }}</span>
                            <span class="text-gray-900 font-semibold">{{ $appointmentStatuses[$status] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-{{ $config['color'] }}-500 h-2 rounded-full" style="width: {{ round(($appointmentStatuses[$status] ?? 0) / $totalAppts * 100) }}%"></div>
                        </div>
                    </div>
                    @endif
                @endforeach
                @if(array_sum($appointmentStatuses) === 0)
                <div class="text-center py-8">
                    <p class="text-sm text-gray-400">{{ $isAr ? 'لا توجد مواعيد هذا الشهر' : 'No appointments this month' }}</p>
                </div>
                @endif
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">{{ $isAr ? 'إجمالي: ' : 'Total: ' }}<span class="font-semibold text-gray-900">{{ number_format($stats['appointments_month']) }}</span> {{ $isAr ? 'موعد' : 'appointments' }}</p>
            </div>
        </div>
    </div>

    {{-- Top Clinics Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
        {{-- Top by Patients --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $isAr ? 'أعلى العيادات بعدد المرضى' : 'Top Clinics by Patients' }}
            </h3>
            @php $maxPatients = $topClinicsByPatients->max('patients_count') ?: 1; @endphp
            <div class="space-y-3">
                @forelse($topClinicsByPatients as $i => $c)
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-lg bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <a href="{{ route('super.clinics.show', $c) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600 truncate transition-colors">{{ $isAr ? $c->name_ar : $c->name_en }}</a>
                            <span class="text-sm font-semibold text-gray-900 ms-2">{{ number_format($c->patients_count) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ round($c->patients_count / $maxPatients * 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">{{ $isAr ? 'لا توجد بيانات' : 'No data' }}</p>
                @endforelse
            </div>
        </div>

        {{-- Top by Appointments --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $isAr ? 'أعلى العيادات بالمواعيد (الشهر)' : 'Top Clinics by Appointments (Month)' }}
            </h3>
            @php $maxAppts = $topClinicsByAppointments->max('appointments_count') ?: 1; @endphp
            <div class="space-y-3">
                @forelse($topClinicsByAppointments as $i => $c)
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-lg bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <a href="{{ route('super.clinics.show', $c) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600 truncate transition-colors">{{ $isAr ? $c->name_ar : $c->name_en }}</a>
                            <span class="text-sm font-semibold text-gray-900 ms-2">{{ number_format($c->appointments_count) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-cyan-500 h-1.5 rounded-full" style="width: {{ round($c->appointments_count / $maxAppts * 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">{{ $isAr ? 'لا توجد بيانات' : 'No data' }}</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Actions + Status Distribution --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
        {{-- Clinic Status Distribution --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-5">{{ $isAr ? 'توزيع العيادات' : 'Clinic Distribution' }}</h3>
            @php
                $total = max($stats['total_clinics'], 1);
                $activePercent = round(($stats['active_clinics'] / $total) * 100);
                $pendingPercent = round(($stats['pending_clinics'] / $total) * 100);
                $suspendedPercent = round(($stats['suspended_clinics'] / $total) * 100);
            @endphp
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-600 font-medium">{{ __('app.active') }}</span>
                        <span class="text-gray-900 font-semibold">{{ $stats['active_clinics'] }} <span class="text-gray-400 font-normal">({{ $activePercent }}%)</span></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $activePercent }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-600 font-medium">{{ __('app.pending') }}</span>
                        <span class="text-gray-900 font-semibold">{{ $stats['pending_clinics'] }} <span class="text-gray-400 font-normal">({{ $pendingPercent }}%)</span></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $pendingPercent }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-600 font-medium">{{ __('app.suspended') }}</span>
                        <span class="text-gray-900 font-semibold">{{ $stats['suspended_clinics'] }} <span class="text-gray-400 font-normal">({{ $suspendedPercent }}%)</span></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $suspendedPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-5">{{ __('app.actions') }}</h3>
            <div class="space-y-3">
                <a href="{{ route('super.clinics.index') }}" class="flex items-center gap-4 p-3 bg-gray-50 hover:bg-indigo-50 rounded-xl transition-all group">
                    <div class="w-9 h-9 bg-indigo-100 group-hover:bg-indigo-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ __('app.manage_clinics') }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @if($stats['pending_clinics'] > 0)
                <a href="{{ route('super.clinics.index') }}?status=pending" class="flex items-center gap-4 p-3 bg-amber-50 hover:bg-amber-100 rounded-xl transition-all group">
                    <div class="w-9 h-9 bg-amber-100 group-hover:bg-amber-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $stats['pending_clinics'] }} {{ $isAr ? 'عيادة بانتظار المراجعة' : 'Pending Review' }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif
                <a href="{{ route('super.whatsapp.index') }}" class="flex items-center gap-4 p-3 bg-gray-50 hover:bg-emerald-50 rounded-xl transition-all group">
                    <div class="w-9 h-9 bg-emerald-100 group-hover:bg-emerald-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $isAr ? 'رسائل الواتساب' : 'WhatsApp Messages' }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        {{-- Send Notification --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm" x-data="{ target: 'all' }">
            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                {{ __('app.send_notification') }}
            </h3>
            <form method="POST" action="{{ route('super.send-notification') }}" class="space-y-3">
                @csrf
                <select name="target" x-model="target" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all">{{ __('app.all_clinic_admins') }}</option>
                    <option value="clinic">{{ __('app.specific_clinic') }}</option>
                </select>
                <div x-show="target === 'clinic'" x-transition>
                    <select name="clinic_id" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('app.select_clinic') }}</option>
                        @foreach(\App\Models\Clinic::orderBy('name_' . app()->getLocale())->get() as $c)
                            <option value="{{ $c->id }}">{{ $isAr ? $c->name_ar : $c->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="text" name="title" placeholder="{{ __('app.notification_title') }}" required class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                <textarea name="body" rows="2" placeholder="{{ __('app.notification_body') }}" required class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 resize-none"></textarea>
                <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/25 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                    {{ __('app.send') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">{{ $isAr ? 'آخر المستخدمين' : 'Recent Users' }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $isAr ? 'آخر الحسابات المسجلة في المنصة' : 'Latest registered accounts' }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-{{ $align }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">{{ __('app.name') }}</th>
                        <th class="text-{{ $align }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">{{ __('app.email') }}</th>
                        <th class="text-{{ $align }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">{{ $isAr ? 'الدور' : 'Role' }}</th>
                        <th class="text-{{ $align }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">{{ $isAr ? 'العيادة' : 'Clinic' }}</th>
                        <th class="text-{{ $align }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">{{ __('app.registered_at') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentUsers as $u)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-600">{{ mb_substr($u->name, 0, 1) }}</div>
                                <span class="text-sm font-medium text-gray-900">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $u->email }}</td>
                        <td class="px-6 py-4">
                            @php
                                $roleStyles = [
                                    'admin' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'doctor' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'employee' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                    'patient' => 'bg-blue-50 text-blue-700 border-blue-200',
                                ];
                            @endphp
                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-lg border {{ $roleStyles[$u->role] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ __('app.' . $u->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $u->clinic ? ($isAr ? $u->clinic->name_ar : $u->clinic->name_en) : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $u->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">{{ $isAr ? 'لا توجد بيانات' : 'No data' }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Clinics Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-900">{{ __('app.all_clinics') }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ __('app.manage_clinics') }}</p>
            </div>
            <a href="{{ route('super.clinics.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-all">
                {{ __('app.view_details') }}
                <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-{{ $align }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">{{ __('app.name') }}</th>
                        <th class="text-{{ $align }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">{{ __('app.specialty') }}</th>
                        <th class="text-{{ $align }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">{{ __('app.status') }}</th>
                        <th class="text-{{ $align }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">{{ __('app.balance') }}</th>
                        <th class="text-{{ $align }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">{{ __('app.registered_at') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentClinics as $clinic)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('super.clinics.show', $clinic) }}" class="text-gray-900 hover:text-indigo-600 font-semibold transition-colors">
                                {{ $isAr ? $clinic->name_ar : $clinic->name_en }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $clinic->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $clinic->specialty ? ($isAr ? $clinic->specialty->name_ar : $clinic->specialty->name_en) : '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusStyles = [
                                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'suspended' => 'bg-red-50 text-red-700 border-red-200',
                                    'inactive' => 'bg-gray-50 text-gray-600 border-gray-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-lg border {{ $statusStyles[$clinic->status] ?? $statusStyles['inactive'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $clinic->status === 'active' ? 'bg-emerald-500' : ($clinic->status === 'pending' ? 'bg-amber-500' : ($clinic->status === 'suspended' ? 'bg-red-500' : 'bg-gray-400')) }}"></span>
                                {{ __('app.' . $clinic->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($clinic->wallet->balance ?? 0) }}</span>
                            <span class="text-xs text-gray-400">{{ __('app.points') }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $clinic->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <p class="text-gray-500 font-medium">{{ __('app.no_clinics_found') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
