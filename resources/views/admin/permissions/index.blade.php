<x-dashboard-layout>
    <x-slot name="title">{{ __('app.permissions') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.permissions') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'permissions'])
    </x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('app.permissions') }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ __('app.permissions_desc') }}</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Permissions Matrix --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('dashboard.permissions.update') }}">
            @csrf

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }} text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.permission') }}</th>
                            @foreach($roles as $role)
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg {{ $role === 'accountant' ? 'bg-amber-50 text-amber-700' : 'bg-sky-50 text-sky-700' }}">
                                    {{ __('app.' . $role) }}
                                </span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($modules as $module => $permissions)
                        <tr class="bg-gray-50/30">
                            <td colspan="{{ count($roles) + 1 }}" class="px-6 py-3">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.module_' . $module) }}</span>
                            </td>
                        </tr>
                        @foreach($permissions as $permission)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 text-sm text-gray-700 font-medium">
                                {{ __('app.perm_' . str_replace('.', '_', $permission)) }}
                            </td>
                            @foreach($roles as $role)
                            <td class="px-6 py-3 text-center">
                                <label class="inline-flex items-center justify-center cursor-pointer">
                                    <input type="checkbox"
                                           name="permissions[{{ $role }}][]"
                                           value="{{ $permission }}"
                                           {{ isset($currentPermissions[$role]) && in_array($permission, $currentPermissions[$role]) ? 'checked' : '' }}
                                           class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                                </label>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Actions --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('app.save') }}
                </button>
            </div>
        </form>
    </div>
</x-dashboard-layout>
