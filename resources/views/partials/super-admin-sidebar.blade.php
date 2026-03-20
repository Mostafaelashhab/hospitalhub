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
<a href="{{ route('super.recharge.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('super.recharge.*') ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ request()->routeIs('super.recharge.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    {{ __('app.recharge_requests') }}
    @php $pendingRechargeCount = \App\Models\RechargeRequest::where('status', 'pending')->count(); @endphp
    @if($pendingRechargeCount > 0)
    <span class="ms-auto inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full">{{ $pendingRechargeCount }}</span>
    @endif
</a>
<a href="{{ route('super.whatsapp.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('super.whatsapp.*') ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ request()->routeIs('super.whatsapp.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
    {{ app()->getLocale() === 'ar' ? 'رسائل واتساب' : 'WhatsApp Messages' }}
    @php $unreadWa = \App\Models\WhatsappMessage::where('is_read', false)->count(); @endphp
    @if($unreadWa > 0)
    <span class="ms-auto inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-green-500 rounded-full">{{ $unreadWa }}</span>
    @endif
</a>
<a href="{{ route('super.settings.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('super.settings.*') ? 'nav-link-active' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition-all duration-200">
    <svg class="w-5 h-5 {{ request()->routeIs('super.settings.*') ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    {{ __('app.settings') }}
</a>
