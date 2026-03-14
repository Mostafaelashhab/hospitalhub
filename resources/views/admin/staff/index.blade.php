<x-dashboard-layout>
    <x-slot name="title">{{ __('app.staff') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.staff') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'staff'])
    </x-slot>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.staff') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.manage_staff_desc') }}</p>
        </div>
        @if(auth()->user()->hasPermission('staff.create'))
        <a href="{{ route('dashboard.staff.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.add_staff') }}
        </a>
        @endif
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('dashboard.staff.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="w-5 h-5 text-gray-400 absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_staff') }}"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
            </div>
            <select name="role" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                <option value="">{{ __('app.all_roles') }}</option>
                <option value="accountant" {{ request('role') === 'accountant' ? 'selected' : '' }}>{{ __('app.accountant') }}</option>
                <option value="secretary" {{ request('role') === 'secretary' ? 'selected' : '' }}>{{ __('app.secretary') }}</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                {{ __('app.search') }}
            </button>
        </form>
    </div>

    {{-- Staff Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($staff as $member)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $member->role === 'accountant' ? 'from-amber-400 to-orange-500' : 'from-sky-400 to-blue-500' }} flex items-center justify-center shadow-md">
                        <span class="text-white font-bold text-lg">{{ mb_substr($member->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">{{ $member->name }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-md {{ $member->role === 'accountant' ? 'bg-amber-50 text-amber-700' : 'bg-sky-50 text-sky-700' }}">
                            {{ __('app.' . $member->role) }}
                        </span>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-md {{ $member->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $member->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    {{ $member->is_active ? __('app.active') : __('app.inactive') }}
                </span>
            </div>

            <div class="space-y-2 mb-4">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span dir="ltr">{{ $member->email }}</span>
                </div>
                @if($member->phone)
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span dir="ltr">{{ $member->phone }}</span>
                </div>
                @endif
            </div>

            @if(auth()->user()->hasPermission('staff.edit'))
            <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('dashboard.staff.edit', $member) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ __('app.edit') }}
                </a>
                <form method="POST" action="{{ route('dashboard.staff.toggle', $member) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold {{ $member->is_active ? 'text-red-700 bg-red-50 border-red-200 hover:bg-red-100' : 'text-emerald-700 bg-emerald-50 border-emerald-200 hover:bg-emerald-100' }} border rounded-lg transition-all">
                        {{ $member->is_active ? __('app.deactivate') : __('app.activate') }}
                    </button>
                </form>
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <p class="text-gray-500 font-medium">{{ __('app.no_staff_found') }}</p>
                @if(auth()->user()->hasPermission('staff.create'))
                <a href="{{ route('dashboard.staff.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700 font-semibold">{{ __('app.add_staff') }} &rarr;</a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    @if($staff->hasPages())
    <div class="mt-6">
        {{ $staff->withQueryString()->links() }}
    </div>
    @endif
</x-dashboard-layout>
