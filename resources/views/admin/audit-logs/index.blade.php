<x-dashboard-layout>
    <x-slot name="title">{{ __('app.audit_logs') }}</x-slot>
    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'audit-logs'])
    </x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.audit_logs') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.audit_logs') }}</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            {{ $logs->total() }} {{ __('app.total_records') }}
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            {{-- Action filter --}}
            <div class="min-w-[160px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.filter_by_action') }}</label>
                <select name="action" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('app.all_actions') }}</option>
                    <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>
                        {{ ucfirst(__('app.created') ?? 'Created') }}
                    </option>
                    <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>
                        {{ ucfirst(__('app.updated') ?? 'Updated') }}
                    </option>
                    <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>
                        {{ ucfirst(__('app.deleted') ?? 'Deleted') }}
                    </option>
                    <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>
                        Login
                    </option>
                </select>
            </div>

            {{-- User filter --}}
            <div class="min-w-[160px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.audit_user') }}</label>
                <select name="user_id" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('app.all') }}</option>
                    @foreach($users as $u)
                        <option value="{{ $u->user_id }}" {{ request('user_id') == $u->user_id ? 'selected' : '' }}>
                            {{ $u->user_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Model type filter --}}
            <div class="min-w-[160px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.audit_model') }}</label>
                <select name="model_type" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('app.all') }}</option>
                    @foreach($modelTypes as $type)
                        <option value="{{ $type }}" {{ request('model_type') === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date from --}}
            <div class="min-w-[140px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.from') ?? 'From' }}</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            {{-- Date to --}}
            <div class="min-w-[140px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.to') ?? 'To' }}</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    {{ __('app.filter') ?? 'Filter' }}
                </button>
                @if(request()->hasAny(['action', 'user_id', 'model_type', 'date_from', 'date_to']))
                    <a href="{{ route('dashboard.audit-logs.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ __('app.clear') ?? 'Clear' }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($logs->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <p class="text-gray-500 font-medium">{{ __('app.no_audit_logs') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="text-start px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('app.audit_date') }}</th>
                            <th class="text-start px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('app.audit_user') }}</th>
                            <th class="text-start px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('app.audit_action') }}</th>
                            <th class="text-start px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('app.audit_model') }}</th>
                            <th class="text-start px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('app.audit_details') }}</th>
                            <th class="text-start px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('app.audit_ip') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                {{-- Date/Time --}}
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $log->created_at->format('Y-m-d') }}</div>
                                    <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>

                                {{-- User --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-600 shrink-0">
                                            {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-800">{{ $log->user_name ?? __('app.system') ?? 'System' }}</span>
                                    </div>
                                </td>

                                {{-- Action Badge --}}
                                <td class="px-4 py-3">
                                    @php
                                        $badgeClass = match($log->action) {
                                            'created' => 'bg-green-50 text-green-700 border border-green-200',
                                            'updated' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                            'deleted' => 'bg-red-50 text-red-700 border border-red-200',
                                            'login'   => 'bg-blue-50 text-blue-700 border border-blue-200',
                                            default   => 'bg-gray-50 text-gray-600 border border-gray-200',
                                        };
                                        $badgeIcon = match($log->action) {
                                            'created' => 'M12 4v16m8-8H4',
                                            'updated' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                                            'deleted' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
                                            'login'   => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1',
                                            default   => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $badgeClass }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $badgeIcon }}"/>
                                        </svg>
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>

                                {{-- Model --}}
                                <td class="px-4 py-3">
                                    @if($log->model_type)
                                        <div class="font-medium text-gray-800">{{ $log->model_type }}</div>
                                        @if($log->model_label)
                                            <div class="text-xs text-gray-400">{{ $log->model_label }}</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Details --}}
                                <td class="px-4 py-3 max-w-xs">
                                    @php
                                        $fields = [];
                                        if ($log->action === 'created' && $log->new_values) {
                                            $relevant = array_filter($log->new_values, fn($v) => $v !== null && $v !== '');
                                            $fields = array_slice($relevant, 0, 4, true);
                                        } elseif ($log->action === 'updated' && $log->new_values) {
                                            $fields = array_slice($log->new_values, 0, 4, true);
                                        } elseif ($log->action === 'deleted' && $log->old_values) {
                                            $relevant = array_filter($log->old_values, fn($v) => $v !== null && $v !== '');
                                            $fields = array_slice($relevant, 0, 4, true);
                                        }
                                    @endphp
                                    @if(!empty($fields))
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open"
                                                class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ count($log->new_values ?? $log->old_values ?? []) }} {{ __('app.fields') ?? 'fields' }}
                                            </button>
                                            <div x-show="open" @click.outside="open = false" x-cloak
                                                class="absolute z-10 mt-2 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} bg-white border border-gray-200 rounded-xl shadow-lg p-3 min-w-[260px] max-w-xs max-h-64 overflow-y-auto">
                                                @if($log->action === 'updated' && $log->old_values && $log->new_values)
                                                    <div class="space-y-2">
                                                        @foreach($log->new_values as $key => $newVal)
                                                            <div class="text-xs">
                                                                <span class="font-semibold text-gray-700 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                                    <span class="bg-red-50 text-red-600 px-1.5 py-0.5 rounded line-through">{{ $log->old_values[$key] ?? '—' }}</span>
                                                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ app()->getLocale() === 'ar' ? 'M19 12H5m7-7l-7 7 7 7' : 'M5 12h14m-7-7l7 7-7 7' }}"/>
                                                                    </svg>
                                                                    <span class="bg-green-50 text-green-600 px-1.5 py-0.5 rounded">{{ $newVal ?? '—' }}</span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="space-y-1.5">
                                                        @foreach($fields as $key => $val)
                                                            <div class="text-xs flex justify-between gap-2">
                                                                <span class="font-medium text-gray-500 capitalize shrink-0">{{ str_replace('_', ' ', $key) }}:</span>
                                                                <span class="text-gray-800 truncate">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- IP Address --}}
                                <td class="px-4 py-3">
                                    <span class="text-xs font-mono text-gray-500 bg-gray-50 px-2 py-1 rounded-lg">
                                        {{ $log->ip_address ?? '—' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
                <div class="px-4 py-4 border-t border-gray-100">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>
</x-dashboard-layout>
