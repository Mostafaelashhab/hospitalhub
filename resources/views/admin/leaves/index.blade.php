<x-dashboard-layout>
    <x-slot name="title">{{ __('app.doctor_leaves') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.doctor_leaves') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'leaves'])
    </x-slot>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.doctor_leaves') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.leaves') }}</p>
        </div>
        @if(auth()->user()->hasPermission('doctors.create'))
        <a href="{{ route('dashboard.leaves.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.add_leave') }}
        </a>
        @endif
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('dashboard.leaves.index') }}" class="flex flex-col sm:flex-row gap-3">
            <select name="status" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                <option value="">{{ __('app.status') }}: {{ __('app.all') }}</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>{{ __('app.pending') }}</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('app.approved') }}</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('app.rejected') }}</option>
            </select>
        </form>
    </div>

    {{-- Leaves List --}}
    <div class="space-y-4">
        @forelse($leaves as $leave)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
            <div class="p-5">
                <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                    {{-- Doctor Avatar & Name --}}
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-purple-500/20 shrink-0">
                            {{ mb_substr($leave->doctor->name ?? '?', 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-gray-900 font-bold text-base truncate">{{ $leave->doctor->name ?? '-' }}</h3>
                            <p class="text-sm text-gray-500 mt-0.5">
                                <span class="font-medium text-gray-700">{{ __('app.leave_from') }}:</span>
                                {{ $leave->start_date->format('Y-m-d') }}
                                &mdash;
                                <span class="font-medium text-gray-700">{{ __('app.leave_to') }}:</span>
                                {{ $leave->end_date->format('Y-m-d') }}
                            </p>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div class="shrink-0">
                        @if($leave->status === 'approved')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border bg-emerald-50 text-emerald-700 border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ __('app.approved') }}
                        </span>
                        @elseif($leave->status === 'rejected')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border bg-red-50 text-red-700 border-red-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            {{ __('app.rejected') }}
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border bg-amber-50 text-amber-700 border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            {{ __('app.pending') }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Reason --}}
                @if($leave->reason)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.leave_reason') }}</p>
                    <p class="text-sm text-gray-700">{{ $leave->reason }}</p>
                </div>
                @endif

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-wrap mt-4 pt-4 border-t border-gray-100">
                    @if($leave->status === 'pending')
                        @if(auth()->user()->hasPermission('doctors.edit'))
                        <form method="POST" action="{{ route('dashboard.leaves.approve', $leave) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('app.approve') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('dashboard.leaves.reject', $leave) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                {{ __('app.reject') }}
                            </button>
                        </form>
                        @endif
                    @endif

                    @if(auth()->user()->hasPermission('doctors.edit'))
                    <form method="POST" action="{{ route('dashboard.leaves.destroy', $leave) }}"
                          onsubmit="return confirm('{{ __('app.delete') }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            {{ __('app.delete') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/><line x1="12" y1="11" x2="12" y2="17" stroke-linecap="round" stroke-width="2"/><line x1="9" y1="14" x2="15" y2="14" stroke-linecap="round" stroke-width="2"/></svg>
                </div>
                <p class="text-gray-500 font-medium">{{ __('app.no_leaves') }}</p>
                @if(auth()->user()->hasPermission('doctors.create'))
                <a href="{{ route('dashboard.leaves.create') }}" class="mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-semibold">{{ __('app.add_leave') }} &rarr;</a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    @if($leaves->hasPages())
    <div class="mt-6">
        {{ $leaves->withQueryString()->links() }}
    </div>
    @endif
</x-dashboard-layout>
