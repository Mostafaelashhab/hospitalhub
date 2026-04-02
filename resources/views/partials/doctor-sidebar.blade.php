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

<a href="{{ route('doctor.schedule') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'schedule' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'schedule' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    {{ __('app.schedule') }}
</a>

<a href="{{ route('doctor.settings') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ $active === 'settings' ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ $active === 'settings' ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    {{ __('app.doctor_settings') }}
</a>
