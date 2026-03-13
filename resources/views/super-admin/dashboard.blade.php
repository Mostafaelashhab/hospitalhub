<x-dashboard-layout>
    <x-slot name="title">{{ __('app.super_admin_panel') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.dashboard') }}</x-slot>

    <x-slot name="sidebar">
        <a href="{{ route('super.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('super.dashboard') ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
            <svg class="w-5 h-5 {{ request()->routeIs('super.dashboard') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 14a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1v-5z"/></svg>
            {{ __('app.dashboard') }}
        </a>
        <a href="{{ route('super.clinics.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('super.clinics.*') ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
            <svg class="w-5 h-5 {{ request()->routeIs('super.clinics.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            {{ __('app.manage_clinics') }}
        </a>
    </x-slot>

    {{-- Welcome Section --}}
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

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        {{-- Total Clinics --}}
        <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">{{ __('app.total') }}</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_clinics'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.all_clinics') }}</p>
        </div>

        {{-- Active --}}
        <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">{{ __('app.active') }}</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['active_clinics'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.clinics') }}</p>
        </div>

        {{-- Pending --}}
        <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg">{{ __('app.pending') }}</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_clinics'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.clinics') }}</p>
        </div>

        {{-- Total Users --}}
        <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-lg">{{ __('app.total') }}</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.patients') }} & {{ __('app.doctors') }}</p>
        </div>
    </div>

    {{-- Quick Overview Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
        {{-- Clinic Status Distribution --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-5">{{ __('app.status') }} Overview</h3>
            <div class="space-y-4">
                @php
                    $total = max($stats['total_clinics'], 1);
                    $activePercent = round(($stats['active_clinics'] / $total) * 100);
                    $pendingPercent = round(($stats['pending_clinics'] / $total) * 100);
                    $suspendedPercent = round(($stats['suspended_clinics'] / $total) * 100);
                @endphp
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-600 font-medium">{{ __('app.active') }}</span>
                        <span class="text-gray-900 font-semibold">{{ $stats['active_clinics'] }} <span class="text-gray-400 font-normal">({{ $activePercent }}%)</span></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $activePercent }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-600 font-medium">{{ __('app.pending') }}</span>
                        <span class="text-gray-900 font-semibold">{{ $stats['pending_clinics'] }} <span class="text-gray-400 font-normal">({{ $pendingPercent }}%)</span></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pendingPercent }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-600 font-medium">{{ __('app.suspended') }}</span>
                        <span class="text-gray-900 font-semibold">{{ $stats['suspended_clinics'] }} <span class="text-gray-400 font-normal">({{ $suspendedPercent }}%)</span></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full transition-all duration-500" style="width: {{ $suspendedPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900 mb-5">{{ __('app.actions') }}</h3>
            <div class="space-y-3">
                <a href="{{ route('super.clinics.index') }}" class="flex items-center gap-4 p-3.5 bg-gray-50 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <div class="w-10 h-10 bg-indigo-100 group-hover:bg-indigo-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ __('app.manage_clinics') }}</p>
                        <p class="text-xs text-gray-500">{{ __('app.view_details') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @if($stats['pending_clinics'] > 0)
                <a href="{{ route('super.clinics.index') }}?status=pending" class="flex items-center gap-4 p-3.5 bg-amber-50 hover:bg-amber-100 rounded-xl transition-all duration-200 group">
                    <div class="w-10 h-10 bg-amber-100 group-hover:bg-amber-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ __('app.pending') }} {{ __('app.clinics') }}</p>
                        <p class="text-xs text-amber-600 font-medium">{{ $stats['pending_clinics'] }} {{ __('app.pending') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif
            </div>
        </div>

        {{-- System Info --}}
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 shadow-lg shadow-indigo-500/20 text-white relative overflow-hidden">
            <div class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 {{ app()->getLocale() === 'ar' ? '-translate-x-1/2' : 'translate-x-1/2' }}"></div>
            <div class="absolute bottom-0 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} w-20 h-20 bg-white/5 rounded-full translate-y-1/2 {{ app()->getLocale() === 'ar' ? 'translate-x-1/2' : '-translate-x-1/2' }}"></div>
            <div class="relative">
                <h3 class="text-sm font-semibold text-indigo-200 mb-2">{{ __('app.app_name') }}</h3>
                <p class="text-3xl font-bold mb-1">{{ $stats['total_clinics'] }}</p>
                <p class="text-indigo-200 text-sm mb-4">{{ __('app.all_clinics') }}</p>
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-1.5">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full"></div>
                        <span class="text-indigo-100">{{ $stats['active_clinics'] }} {{ __('app.active') }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2 h-2 bg-amber-400 rounded-full"></div>
                        <span class="text-indigo-100">{{ $stats['pending_clinics'] }} {{ __('app.pending') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Clinics Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-900">{{ __('app.all_clinics') }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ __('app.manage_clinics') }}</p>
            </div>
            <a href="{{ route('super.clinics.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-all duration-200">
                {{ __('app.view_details') }}
                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.name') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.specialty') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.balance') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.registered_at') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentClinics as $clinic)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('super.clinics.show', $clinic) }}" class="text-gray-900 hover:text-indigo-600 font-semibold transition-colors">
                                {{ app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $clinic->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $clinic->specialty ? (app()->getLocale() === 'ar' ? $clinic->specialty->name_ar : $clinic->specialty->name_en) : '-' }}</span>
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
