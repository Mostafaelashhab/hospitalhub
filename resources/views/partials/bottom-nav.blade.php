@php
    $user = auth()->user();
    $isSuperAdmin = $user->role === 'super_admin';
    $isDoctor = request()->is('doctor*');
    $currentRoute = request()->route()?->getName() ?? '';

    // Detect active tab from current route
    if ($isSuperAdmin) {
        if (str_contains($currentRoute, 'clinics')) $active = 'clinics';
        else $active = 'dashboard';
    } elseif ($isDoctor) {
        if (str_contains($currentRoute, 'appointment')) $active = 'appointments';
        elseif (str_contains($currentRoute, 'settings')) $active = 'settings';
        else $active = 'dashboard';
    } else {
        if (str_contains($currentRoute, 'appointments') || str_contains($currentRoute, 'diagnoses')) $active = 'appointments';
        elseif (str_contains($currentRoute, 'patients')) $active = 'patients';
        elseif (str_contains($currentRoute, 'invoices')) $active = 'invoices';
        elseif (str_contains($currentRoute, 'doctors')) $active = 'doctors';
        elseif (str_contains($currentRoute, 'staff') || str_contains($currentRoute, 'permissions')) $active = 'staff';
        elseif (str_contains($currentRoute, 'settings') || str_contains($currentRoute, 'branches') || str_contains($currentRoute, 'website')) $active = 'settings';
        elseif ($currentRoute === 'dashboard') $active = 'dashboard';
        else $active = '';
    }
@endphp

{{-- Bottom Navigation - Mobile Only --}}
<nav class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-xl border-t border-gray-200 lg:hidden" style="padding-bottom: env(safe-area-inset-bottom, 0px);">
    <div class="flex items-center justify-around h-16 px-1">
        @if($isSuperAdmin)
            {{-- Super Admin --}}
            <a href="{{ route('super.dashboard') }}" class="bottom-nav-item {{ $active === 'dashboard' ? 'bottom-nav-active' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 14a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1v-5z"/></svg>
                <span>{{ __('app.dashboard') }}</span>
            </a>
            <a href="{{ route('super.clinics.index') }}" class="bottom-nav-item {{ $active === 'clinics' ? 'bottom-nav-active' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>{{ __('app.clinics') }}</span>
            </a>

        @elseif($isDoctor)
            {{-- Doctor --}}
            <a href="{{ route('doctor.dashboard') }}" class="bottom-nav-item {{ $active === 'dashboard' ? 'bottom-nav-active' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>{{ __('app.dashboard') }}</span>
            </a>
            <a href="{{ route('doctor.appointments') }}" class="bottom-nav-item {{ $active === 'appointments' ? 'bottom-nav-active' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>{{ __('app.appointments') }}</span>
            </a>
            <a href="{{ route('doctor.settings') }}" class="bottom-nav-item {{ $active === 'settings' ? 'bottom-nav-active' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ __('app.settings') }}</span>
            </a>

        @else
            {{-- Admin/Staff --}}
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
        <button @click="sidebarOpen = true" class="bottom-nav-item">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <span>{{ __('app.more') }}</span>
        </button>
    </div>
</nav>
