<x-dashboard-layout>
    <x-slot name="title">{{ __('app.patient_ledger') }}</x-slot>
    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'ledger'])
    </x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.patient_ledger') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.patient_ledger_desc') }}</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.total_outstanding_debt') }}</p>
                    <p class="text-lg font-bold text-red-600">{{ number_format($totalDebt, 2) }} {{ __('app.egp') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.patients_with_debt') }}</p>
                    <p class="text-lg font-bold text-gray-900">{{ $debtorsCount }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.search') }}</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_patient') }}..." class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="min-w-[150px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.filter') }}</label>
                <select name="filter" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="debtors" {{ $filter === 'debtors' ? 'selected' : '' }}>{{ __('app.debtors_only') }}</option>
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>{{ __('app.all_patients') }}</option>
                    <option value="creditors" {{ $filter === 'creditors' ? 'selected' : '' }}>{{ __('app.creditors_only') }}</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                {{ __('app.filter') }}
            </button>
        </form>
    </div>

    {{-- Patients List --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.patient') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.phone') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.balance') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.status') }}</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3">
                            <span class="text-sm font-medium text-gray-900">{{ $patient->name }}</span>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $patient->phone ?? '-' }}</td>
                        <td class="px-5 py-3">
                            @php $bal = (float)$patient->ledger_balance; @endphp
                            <span class="text-sm font-bold {{ $bal > 0 ? 'text-red-600' : ($bal < 0 ? 'text-emerald-600' : 'text-gray-400') }}">
                                {{ number_format(abs($bal), 2) }} {{ __('app.egp') }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            @if($bal > 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-lg bg-red-50 text-red-700">{{ __('app.owes_money') }}</span>
                            @elseif($bal < 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-lg bg-emerald-50 text-emerald-700">{{ __('app.overpaid') }}</span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-lg bg-gray-50 text-gray-500">{{ __('app.settled') }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('dashboard.ledger.show', $patient) }}" class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                {{ __('app.view_details') }}
                                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm text-gray-400">{{ __('app.no_debts') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($patients->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $patients->withQueryString()->links() }}
        </div>
        @endif
    </div>
</x-dashboard-layout>
