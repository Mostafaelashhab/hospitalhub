<x-dashboard-layout>
    <x-slot name="title">{{ __('app.patient_ledger') }} - {{ $patient->name }}</x-slot>
    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'ledger'])
    </x-slot>

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dashboard.ledger.index') }}" class="p-2 rounded-xl hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5 text-gray-500 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $patient->name }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ __('app.patient_ledger') }}</p>
        </div>
    </div>

    {{-- Balance Card + Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
        {{-- Balance --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs text-gray-500 font-medium mb-1">{{ __('app.current_balance') }}</p>
            @php $isDebt = $balance > 0; @endphp
            <p class="text-3xl font-bold {{ $isDebt ? 'text-red-600' : ($balance < 0 ? 'text-emerald-600' : 'text-gray-400') }}">
                {{ number_format(abs($balance), 2) }} {{ __('app.egp') }}
            </p>
            <p class="text-xs mt-1 {{ $isDebt ? 'text-red-500' : 'text-emerald-500' }}">
                {{ $isDebt ? __('app.owes_money') : ($balance < 0 ? __('app.overpaid') : __('app.settled')) }}
            </p>
        </div>

        {{-- Record Payment --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-3">{{ __('app.record_payment') }}</h3>
            <form method="POST" action="{{ route('dashboard.ledger.payment', $patient) }}" class="space-y-3">
                @csrf
                <input type="number" name="amount" step="0.01" min="0.01" required placeholder="{{ __('app.amount') }}" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                <select name="payment_method" required class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="cash">{{ __('app.cash') }}</option>
                    <option value="card">{{ __('app.card') }}</option>
                    <option value="bank_transfer">{{ __('app.bank_transfer') }}</option>
                    <option value="instapay">{{ __('app.instapay') }}</option>
                </select>
                <input type="text" name="description" placeholder="{{ __('app.notes') }}" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors">
                    {{ __('app.record_payment') }}
                </button>
            </form>
        </div>

        {{-- Add Debt --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-3">{{ __('app.add_debt') }}</h3>
            <form method="POST" action="{{ route('dashboard.ledger.debt', $patient) }}" class="space-y-3">
                @csrf
                <input type="number" name="amount" step="0.01" min="0.01" required placeholder="{{ __('app.amount') }}" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                <input type="text" name="description" placeholder="{{ __('app.description') }}" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition-colors">
                    {{ __('app.add_debt') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Transaction History --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-900">{{ __('app.transaction_history') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.type') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.description') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.payment_method') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.amount') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.balance') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($entries as $entry)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $entry->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-5 py-3">
                            @if($entry->type === 'debit')
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-lg bg-red-50 text-red-700">{{ __('app.debit') }}</span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-lg bg-emerald-50 text-emerald-700">{{ __('app.credit') }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $entry->description ?: '-' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-500">{{ $entry->payment_method ? __('app.' . $entry->payment_method) : '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="text-sm font-semibold {{ $entry->type === 'debit' ? 'text-red-600' : 'text-emerald-600' }}">
                                {{ $entry->type === 'debit' ? '+' : '-' }}{{ number_format($entry->amount, 2) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ number_format($entry->balance_after, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <p class="text-sm text-gray-400">{{ __('app.no_transactions') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($entries->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $entries->links() }}
        </div>
        @endif
    </div>
</x-dashboard-layout>
