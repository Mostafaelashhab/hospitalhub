@php
    $user = auth()->user();
    $isSuperAdmin = $user->role === 'super_admin';
    $isDoctor = request()->is('doctor*');
    $currentRoute = request()->route()?->getName() ?? '';

    // Detect active tab from current route
    if ($isSuperAdmin) {
        if (str_contains($currentRoute, 'clinics')) $active = 'clinics';
        elseif (str_contains($currentRoute, 'recharge')) $active = 'recharge';
        elseif (str_contains($currentRoute, 'offers') || str_contains($currentRoute, 'settings')) $active = 'more';
        else $active = 'dashboard';
    } elseif ($isDoctor) {
        if (str_contains($currentRoute, 'queue')) $active = 'queue';
        elseif (str_contains($currentRoute, 'appointment')) $active = 'appointments';
        elseif (str_contains($currentRoute, 'settings')) $active = 'settings';
        else $active = 'dashboard';
    } else {
        if (str_contains($currentRoute, 'appointments') || str_contains($currentRoute, 'diagnoses')) $active = 'appointments';
        elseif (str_contains($currentRoute, 'patients')) $active = 'patients';
        elseif (str_contains($currentRoute, 'invoices')) $active = 'invoices';
        elseif (str_contains($currentRoute, 'doctors')) $active = 'doctors';
        elseif (str_contains($currentRoute, 'insurance')) $active = 'more';
        elseif (str_contains($currentRoute, 'staff') || str_contains($currentRoute, 'permissions')) $active = 'more';
        elseif (str_contains($currentRoute, 'settings') || str_contains($currentRoute, 'branches') || str_contains($currentRoute, 'website')) $active = 'more';
        elseif ($currentRoute === 'dashboard') $active = 'dashboard';
        else $active = '';
    }
@endphp

{{-- Bottom Navigation - Mobile Only --}}
<div x-data="{ moreOpen: false }" class="lg:hidden">
    {{-- Bottom Sheet Overlay --}}
    <div x-show="moreOpen" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="moreOpen = false" class="fixed inset-0 z-40 bg-black/40" x-cloak></div>

    {{-- Bottom Sheet --}}
    <div x-show="moreOpen" x-transition:enter="transition-transform duration-300 ease-out" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition-transform duration-200 ease-in" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="fixed bottom-0 inset-x-0 z-50 bg-white rounded-t-2xl shadow-2xl" style="padding-bottom: env(safe-area-inset-bottom, 0px);" x-cloak>
        {{-- Handle --}}
        <div class="flex justify-center pt-3 pb-2">
            <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
        </div>

        {{-- User Info --}}
        <div class="flex items-center gap-3 px-5 pb-3 border-b border-gray-100">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-sm font-bold text-white">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
            </div>
        </div>

        {{-- Menu Items --}}
        <div class="py-2 px-3 max-h-[60vh] overflow-y-auto">
            @if($isSuperAdmin)
                <a href="{{ route('super.offers.index') }}" class="bottom-sheet-item">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    {{ __('app.offers') }}
                </a>
                <a href="{{ route('super.settings.index') }}" class="bottom-sheet-item">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ __('app.settings') }}
                </a>
            @elseif($isDoctor)
                @if($user->isSoloDoctorAdmin())
                <a href="{{ route('dashboard') }}" class="bottom-sheet-item">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ __('app.admin_dashboard') }}
                </a>
                @endif
            @else
                {{-- Admin/Staff extra items --}}
                @if($user->hasPermission('doctors.view'))
                <a href="{{ route('dashboard.doctors.index') }}" class="bottom-sheet-item">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('app.doctors') }}
                </a>
                @endif

                <a href="{{ route('dashboard.insurance.index') }}" class="bottom-sheet-item">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    {{ __('app.insurance') }}
                </a>

                <a href="{{ route('dashboard.diagnoses.index') }}" class="bottom-sheet-item">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    {{ __('app.diagnoses') }}
                </a>

                <a href="{{ route('dashboard.branches.index') }}" class="bottom-sheet-item">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ __('app.branches') }}
                </a>

                @if($user->hasPermission('staff.view'))
                <a href="{{ route('dashboard.staff.index') }}" class="bottom-sheet-item">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    {{ __('app.staff') }}
                </a>
                @endif

                @if($user->hasPermission('permissions.manage'))
                <a href="{{ route('dashboard.permissions.index') }}" class="bottom-sheet-item">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    {{ __('app.permissions') }}
                </a>
                @endif

                @if($user->isDoctor())
                <a href="{{ route('doctor.dashboard') }}" class="bottom-sheet-item text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ __('app.doctor_portal') }}
                </a>
                @endif

                <a href="{{ route('dashboard.settings.index') }}" class="bottom-sheet-item">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ __('app.settings') }}
                </a>
            @endif

            {{-- Language Switcher --}}
            <div class="flex items-center gap-2 mt-2 pt-3 border-t border-gray-100 px-1">
                @if(app()->getLocale() === 'ar')
                <a href="{{ route('lang.switch', 'en') }}" class="flex-1 flex items-center justify-center py-2.5 text-sm font-medium rounded-xl transition-all text-gray-500 bg-gray-50 border border-gray-100">
                    English
                </a>
                <a href="{{ route('lang.switch', 'ar') }}" class="flex-1 flex items-center justify-center py-2.5 text-sm font-medium rounded-xl transition-all bg-indigo-50 text-indigo-700 border border-indigo-200">
                    العربية
                </a>
                @else
                <a href="{{ route('lang.switch', 'ar') }}" class="flex-1 flex items-center justify-center py-2.5 text-sm font-medium rounded-xl transition-all text-gray-500 bg-gray-50 border border-gray-100">
                    العربية
                </a>
                <a href="{{ route('lang.switch', 'en') }}" class="flex-1 flex items-center justify-center py-2.5 text-sm font-medium rounded-xl transition-all bg-indigo-50 text-indigo-700 border border-indigo-200">
                    English
                </a>
                @endif
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    {{ __('app.logout') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Tab Bar --}}
    <nav class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-xl border-t border-gray-200" style="padding-bottom: env(safe-area-inset-bottom, 0px);">
        <div class="flex items-center justify-around h-16 px-1">
            @if($isSuperAdmin)
                <a href="{{ route('super.dashboard') }}" class="bottom-nav-item {{ $active === 'dashboard' ? 'bottom-nav-active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('app.dashboard') }}</span>
                </a>
                <a href="{{ route('super.clinics.index') }}" class="bottom-nav-item {{ $active === 'clinics' ? 'bottom-nav-active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>{{ __('app.clinics') }}</span>
                </a>
                <a href="{{ route('super.recharge.index') }}" class="bottom-nav-item {{ $active === 'recharge' ? 'bottom-nav-active' : '' }} relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>{{ __('app.recharge') }}</span>
                    @php $pendingRecharges = \App\Models\RechargeRequest::where('status', 'pending')->count(); @endphp
                    @if($pendingRecharges > 0)
                    <span class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-1/2 -translate-x-3' : 'right-1/2 translate-x-3' }} -translate-y-0.5 w-4 h-4 text-[9px] font-bold text-white bg-red-500 rounded-full flex items-center justify-center">{{ $pendingRecharges > 9 ? '9+' : $pendingRecharges }}</span>
                    @endif
                </a>

            @elseif($isDoctor)
                <a href="{{ route('doctor.dashboard') }}" class="bottom-nav-item {{ $active === 'dashboard' ? 'bottom-nav-active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('app.dashboard') }}</span>
                </a>
                <a href="{{ route('doctor.queue') }}" class="bottom-nav-item {{ $active === 'queue' ? 'bottom-nav-active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('app.queue') }}</span>
                </a>
                <a href="{{ route('doctor.appointments') }}" class="bottom-nav-item {{ $active === 'appointments' ? 'bottom-nav-active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ __('app.appointments') }}</span>
                </a>

            @else
                <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ $active === 'dashboard' ? 'bottom-nav-active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('app.dashboard') }}</span>
                </a>

                @if($user->hasPermission('appointments.view'))
                <a href="{{ route('dashboard.appointments.index') }}" class="bottom-nav-item {{ $active === 'appointments' ? 'bottom-nav-active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ __('app.appointments') }}</span>
                </a>
                @endif

                @if($user->hasPermission('patients.view'))
                <a href="{{ route('dashboard.patients.index') }}" class="bottom-nav-item {{ $active === 'patients' ? 'bottom-nav-active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ __('app.patients') }}</span>
                </a>
                @endif

                @if($user->hasPermission('invoices.view'))
                <a href="{{ route('dashboard.invoices.index') }}" class="bottom-nav-item {{ $active === 'invoices' ? 'bottom-nav-active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('app.invoices') }}</span>
                </a>
                @endif
            @endif

            {{-- More --}}
            <button @click="moreOpen = !moreOpen" class="bottom-nav-item {{ $active === 'more' ? 'bottom-nav-active' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                <span>{{ __('app.more') }}</span>
            </button>
        </div>
    </nav>
</div>
