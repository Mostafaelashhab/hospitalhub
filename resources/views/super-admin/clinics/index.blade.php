<x-dashboard-layout>
    <x-slot name="title">{{ __('app.manage_clinics') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.manage_clinics') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.super-admin-sidebar')
    </x-slot>

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('app.manage_clinics') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.all_clinics') }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('super.clinics.index') }}" class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="flex-1 relative">
                <svg class="w-5 h-5 text-gray-400 absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_placeholder') }}"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
            </div>
            {{-- Status filter --}}
            <select name="status" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                <option value="">{{ __('app.filter_by_status') }}: {{ __('app.all') }}</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('app.active') }}</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('app.pending') }}</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>{{ __('app.suspended') }}</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('app.inactive') }}</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                {{ __('app.search') }}
            </button>
        </form>
    </div>

    {{-- Clinics List --}}
    @php
        $statusStyles = [
            'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            'suspended' => 'bg-red-50 text-red-700 border-red-200',
            'inactive' => 'bg-gray-50 text-gray-600 border-gray-200',
        ];
        $statusDots = [
            'active' => 'bg-emerald-500',
            'pending' => 'bg-amber-500',
            'suspended' => 'bg-red-500',
            'inactive' => 'bg-gray-400',
        ];
        $statusIcons = [
            'active' => 'bg-emerald-100',
            'pending' => 'bg-amber-100',
            'suspended' => 'bg-red-100',
            'inactive' => 'bg-gray-100',
        ];
    @endphp

    <div class="space-y-4">
        @forelse($clinics as $clinic)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
            <div class="p-5">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    {{-- Clinic Avatar & Name --}}
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-indigo-500/20 shrink-0">
                            {{ mb_substr(app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-gray-900 font-bold text-base truncate">{{ app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en }}</h3>
                            <p class="text-sm text-gray-500 truncate">{{ $clinic->email }}</p>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div class="shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border {{ $statusStyles[$clinic->status] ?? $statusStyles['inactive'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$clinic->status] ?? $statusDots['inactive'] }}"></span>
                            {{ __('app.' . $clinic->status) }}
                        </span>
                    </div>
                </div>

                {{-- Details Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.phone') }}</p>
                        <p class="text-sm text-gray-700 font-mono" dir="ltr">{{ $clinic->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.specialty') }}</p>
                        <p class="text-sm text-gray-700">{{ $clinic->specialty ? (app()->getLocale() === 'ar' ? $clinic->specialty->name_ar : $clinic->specialty->name_en) : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.balance') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ number_format($clinic->wallet->balance ?? 0) }} <span class="text-xs font-normal text-gray-400">{{ __('app.points') }}</span></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.registered_at') }}</p>
                        <p class="text-sm text-gray-700">{{ $clinic->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('super.clinics.show', $clinic) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        {{ __('app.view_details') }}
                    </a>
                    @if($clinic->status === 'pending')
                    <form method="POST" action="{{ route('super.clinics.status', $clinic) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('app.approve') }}
                        </button>
                    </form>
                    @endif
                    @if($clinic->status === 'active')
                    <form method="POST" action="{{ route('super.clinics.status', $clinic) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="suspended">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            {{ __('app.suspend') }}
                        </button>
                    </form>
                    @endif
                    @if($clinic->status === 'suspended')
                    <form method="POST" action="{{ route('super.clinics.status', $clinic) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('app.activate') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <p class="text-gray-500 font-medium">{{ __('app.no_clinics_found') }}</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($clinics->hasPages())
    <div class="mt-6">
        {{ $clinics->withQueryString()->links() }}
    </div>
    @endif
</x-dashboard-layout>
