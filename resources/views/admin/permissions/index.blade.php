<x-dashboard-layout>
    <x-slot name="title">{{ __('app.permissions') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.permissions') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'permissions'])
    </x-slot>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.permissions') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.permissions_desc') }}</p>
        </div>
        <button onclick="document.getElementById('add-role-modal').classList.remove('hidden')"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.add_role') }}
        </button>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Roles Overview --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach($clinicRoles as $clinicRole)
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border {{ $clinicRole->is_system ? 'bg-gray-50 border-gray-200' : 'bg-indigo-50 border-indigo-200' }}">
            <span class="text-sm font-semibold {{ $clinicRole->is_system ? 'text-gray-700' : 'text-indigo-700' }}">{{ $clinicRole->localizedName() }}</span>
            @if($clinicRole->is_system)
            <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">{{ __('app.system') }}</span>
            @else
            <form method="POST" action="{{ route('dashboard.permissions.roles.destroy', $clinicRole) }}" class="inline"
                  onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </form>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Permissions Matrix --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('dashboard.permissions.update') }}">
            @csrf

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }} text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.permission') }}</th>
                            @foreach($clinicRoles as $clinicRole)
                            <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg {{ $clinicRole->is_system ? ($clinicRole->slug === 'doctor' ? 'bg-purple-50 text-purple-700' : ($clinicRole->slug === 'accountant' ? 'bg-amber-50 text-amber-700' : 'bg-sky-50 text-sky-700')) : 'bg-indigo-50 text-indigo-700' }}">
                                    {{ $clinicRole->localizedName() }}
                                </span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($modules as $module => $permissions)
                        <tr class="bg-gray-50/30">
                            <td colspan="{{ $clinicRoles->count() + 1 }}" class="px-6 py-3">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.module_' . $module) }}</span>
                            </td>
                        </tr>
                        @foreach($permissions as $permission)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 text-sm text-gray-700 font-medium">
                                {{ __('app.perm_' . str_replace('.', '_', $permission)) }}
                            </td>
                            @foreach($clinicRoles as $clinicRole)
                            <td class="px-4 py-3 text-center">
                                <label class="inline-flex items-center justify-center cursor-pointer">
                                    <input type="checkbox"
                                           name="permissions[{{ $clinicRole->slug }}][]"
                                           value="{{ $permission }}"
                                           {{ isset($currentPermissions[$clinicRole->slug]) && in_array($permission, $currentPermissions[$clinicRole->slug]) ? 'checked' : '' }}
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

    {{-- Add Role Modal --}}
    <div id="add-role-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('app.add_role') }}</h3>
            <form method="POST" action="{{ route('dashboard.permissions.roles.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.name') }} ({{ __('app.english') }})</label>
                        <input type="text" name="name_en" required placeholder="e.g. Receptionist" dir="ltr"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.name') }} ({{ __('app.arabic') }})</label>
                        <input type="text" name="name_ar" required placeholder="مثال: استقبال"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-6">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('app.save') }}
                    </button>
                    <button type="button" onclick="document.getElementById('add-role-modal').classList.add('hidden')"
                            class="px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all">
                        {{ __('app.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
