<x-dashboard-layout>
    <x-slot name="title">{{ __('app.reports') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'reports'])
    </x-slot>

    {{-- Header + Date Filter --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('app.reports') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.reports_desc') }}</p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <input type="date" name="from" value="{{ $from }}" class="text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
            <span class="text-gray-400 text-sm">-</span>
            <input type="date" name="to" value="{{ $to }}" class="text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                {{ __('app.filter') }}
            </button>
        </form>
    </div>

    {{-- Overview Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="stat-card bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_appointments']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ __('app.total_appointments') }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['completed_appointments']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ __('app.completed') }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['new_patients']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ __('app.new_patients') }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_revenue'], 0) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ __('app.total_revenue') }} ({{ __('app.egp') }})</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_revenue'], 0) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ __('app.pending_revenue') }} ({{ __('app.egp') }})</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
        {{-- Appointments Chart --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('app.monthly_appointments') }}</h3>
            <div class="space-y-3">
                @php $maxAppt = $appointmentsChart->max('total') ?: 1; @endphp
                @foreach($appointmentsChart as $month)
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-gray-600 font-medium">{{ \Carbon\Carbon::parse($month->month . '-01')->translatedFormat('M Y') }}</span>
                        <span class="text-gray-900 font-semibold">{{ $month->total }} <span class="text-gray-400 font-normal">({{ $month->completed }} {{ __('app.completed') }})</span></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-500" style="width: {{ ($month->total / $maxAppt) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
                @if($appointmentsChart->isEmpty())
                <p class="text-center text-sm text-gray-400 py-8">{{ __('app.no_data') }}</p>
                @endif
            </div>
        </div>

        {{-- Revenue Chart --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('app.monthly_revenue') }}</h3>
            <div class="space-y-3">
                @php $maxRev = $revenueChart->max('revenue') ?: 1; @endphp
                @foreach($revenueChart as $month)
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-gray-600 font-medium">{{ \Carbon\Carbon::parse($month->month . '-01')->translatedFormat('M Y') }}</span>
                        <span class="text-gray-900 font-semibold">{{ number_format($month->revenue, 0) }} {{ __('app.egp') }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 transition-all duration-500" style="width: {{ ($month->revenue / $maxRev) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
                @if($revenueChart->isEmpty())
                <p class="text-center text-sm text-gray-400 py-8">{{ __('app.no_data') }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Middle Row: Appointment Status + Payment Methods --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
        {{-- Appointment Status Breakdown --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('app.appointment_status') }}</h3>
            @php
                $statusColors = [
                    'scheduled' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-700', 'bgLight' => 'bg-blue-50'],
                    'confirmed' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-700', 'bgLight' => 'bg-indigo-50'],
                    'in_progress' => ['bg' => 'bg-amber-500', 'text' => 'text-amber-700', 'bgLight' => 'bg-amber-50'],
                    'completed' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'bgLight' => 'bg-emerald-50'],
                    'cancelled' => ['bg' => 'bg-red-500', 'text' => 'text-red-700', 'bgLight' => 'bg-red-50'],
                    'no_show' => ['bg' => 'bg-gray-500', 'text' => 'text-gray-700', 'bgLight' => 'bg-gray-50'],
                ];
                $totalAppts = max($appointmentStatuses->sum(), 1);
            @endphp
            {{-- Progress bar --}}
            <div class="flex h-4 rounded-full overflow-hidden mb-4">
                @foreach($appointmentStatuses as $status => $count)
                <div class="{{ $statusColors[$status]['bg'] ?? 'bg-gray-400' }}" style="width: {{ ($count / $totalAppts) * 100 }}%" title="{{ __('app.' . $status) }}: {{ $count }}"></div>
                @endforeach
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($appointmentStatuses as $status => $count)
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full {{ $statusColors[$status]['bg'] ?? 'bg-gray-400' }}"></span>
                    <span class="text-xs text-gray-600">{{ __('app.' . $status) }}</span>
                    <span class="text-xs font-bold text-gray-900 {{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }}">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Payment Methods --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('app.payment_methods') }}</h3>
            @php
                $payColors = ['cash' => 'from-emerald-500 to-green-500', 'card' => 'from-blue-500 to-indigo-500', 'bank_transfer' => 'from-purple-500 to-pink-500'];
                $payIcons = ['cash' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'card' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'bank_transfer' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'];
                $totalPay = max($paymentMethods->sum('total'), 1);
            @endphp
            <div class="space-y-4">
                @forelse($paymentMethods as $pm)
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $payColors[$pm->payment_method] ?? 'from-gray-400 to-gray-500' }} flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $payIcons[$pm->payment_method] ?? '' }}"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ __('app.' . $pm->payment_method) }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($pm->total, 0) }} {{ __('app.egp') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $payColors[$pm->payment_method] ?? 'from-gray-400 to-gray-500' }}" style="width: {{ ($pm->total / $totalPay) * 100 }}%"></div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $pm->count }} {{ __('app.invoices') }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-sm text-gray-400 py-8">{{ __('app.no_data') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Bottom Row: Doctor Performance + Top Diagnoses --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Doctor Performance --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900">{{ __('app.doctor_performance') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.doctor') }}</th>
                            <th class="text-center px-3 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.total') }}</th>
                            <th class="text-center px-3 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.completed') }}</th>
                            <th class="text-center px-3 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.rate') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($doctorPerformance as $doc)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-3">
                                <span class="text-sm font-medium text-gray-900">{{ $doc->doctor_name }}</span>
                            </td>
                            <td class="text-center px-3 py-3 text-sm text-gray-600">{{ $doc->total_appointments }}</td>
                            <td class="text-center px-3 py-3 text-sm font-semibold text-emerald-600">{{ $doc->completed }}</td>
                            <td class="text-center px-3 py-3">
                                @php $rate = $doc->total_appointments > 0 ? round(($doc->completed / $doc->total_appointments) * 100) : 0; @endphp
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded-lg {{ $rate >= 80 ? 'bg-emerald-50 text-emerald-700' : ($rate >= 50 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                    {{ $rate }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">{{ __('app.no_data') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Diagnoses --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900">{{ __('app.top_diagnoses') }}</h3>
            </div>
            <div class="p-5 space-y-3">
                @php $maxDiag = $topDiagnoses->max('count') ?: 1; @endphp
                @forelse($topDiagnoses as $i => $diag)
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px] font-bold shrink-0">{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-0.5">
                            <span class="text-sm text-gray-700 truncate">{{ Str::limit($diag->diagnosis, 40) }}</span>
                            <span class="text-xs font-bold text-gray-900 shrink-0 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ $diag->count }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="h-full rounded-full bg-indigo-400" style="width: {{ ($diag->count / $maxDiag) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-sm text-gray-400 py-8">{{ __('app.no_data') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-dashboard-layout>
