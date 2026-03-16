@php $active = $active ?? ''; @endphp

{{-- Doctor Portal --}}
<a href="{{ route('doctor.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'dashboard' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'dashboard' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 14a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1v-5z"/></svg>
    {{ __('app.dashboard') }}
</a>

<a href="{{ route('doctor.appointments') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'appointments' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'appointments' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    {{ __('app.my_appointments') }}
</a>

<a href="{{ route('doctor.queue') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'queue' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'queue' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ __('app.waiting_queue') }}
</a>

<a href="{{ route('doctor.settings') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'settings' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'settings' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    {{ __('app.doctor_settings') }}
</a>

{{-- Clinic Management Section (based on permissions) --}}
@php
    $user = auth()->user();
    $hasClinicAccess = $user->hasPermission('patients.view') || $user->hasPermission('doctors.view') || $user->hasPermission('invoices.view') || $user->hasPermission('staff.view') || $user->isSoloDoctorAdmin();
@endphp

@if($hasClinicAccess)
<div class="my-3 border-t border-gray-100"></div>
<p class="px-4 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('app.clinic_dashboard') }}</p>

@if($user->hasPermission('appointments.view'))
<a href="{{ route('dashboard.appointments.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'admin-appointments' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'admin-appointments' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    {{ __('app.all_appointments') }}
</a>
@endif

@if($user->hasPermission('patients.view'))
<a href="{{ route('dashboard.patients.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'patients' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'patients' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    {{ __('app.patients') }}
</a>
@endif

@if($user->hasPermission('doctors.view'))
<a href="{{ route('dashboard.doctors.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'doctors' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'doctors' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ __('app.doctors') }}
</a>
@endif

@if($user->hasPermission('invoices.view'))
<a href="{{ route('dashboard.invoices.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'invoices' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'invoices' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    {{ __('app.invoices') }}
</a>

<a href="{{ route('dashboard.expenses.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'expenses' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'expenses' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
    {{ __('app.expenses') }}
</a>

<a href="{{ route('dashboard.ledger.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'ledger' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'ledger' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    {{ __('app.patient_ledger') }}
</a>
@endif

@if($user->hasPermission('appointments.view'))
<a href="{{ route('dashboard.diagnoses.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'diagnoses' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'diagnoses' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    {{ __('app.diagnoses') }}
</a>

<a href="{{ route('dashboard.reports.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'reports' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'reports' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
    {{ __('app.reports') }}
</a>
@endif

@if($user->hasPermission('staff.view'))
<a href="{{ route('dashboard.staff.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'staff' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'staff' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    {{ __('app.staff') }}
</a>
@endif

@if($user->hasPermission('permissions.manage'))
<a href="{{ route('dashboard.permissions.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'permissions' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'permissions' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
    {{ __('app.permissions') }}
</a>
@endif
@endif
