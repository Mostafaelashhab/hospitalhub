<a href="{{ route('super.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('super.dashboard') ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ request()->routeIs('super.dashboard') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 14a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1v-5z"/></svg>
    {{ __('app.dashboard') }}
</a>
<a href="{{ route('super.clinics.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('super.clinics.*') ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ request()->routeIs('super.clinics.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    {{ __('app.manage_clinics') }}
</a>
<a href="{{ route('super.offers.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('super.offers.*') ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ request()->routeIs('super.offers.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
    {{ __('app.offers') }}
</a>
