<x-dashboard-layout>
    <x-slot name="title">{{ __('app.expenses') }}</x-slot>
    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'expenses'])
    </x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.expenses') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.expenses_desc') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dashboard.expenses.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('app.add_expense') }}
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.total_expenses') }}</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($stats['total'], 2) }} {{ __('app.egp') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.this_month') }}</p>
                    <p class="text-lg font-bold text-amber-600">{{ number_format($stats['this_month'], 2) }} {{ __('app.egp') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.total_records') }}</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['count'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.search') }}</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search') }}..." class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="min-w-[150px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.category') }}</label>
                <select name="category" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('app.all') }}</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[130px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.payment_method') }}</label>
                <select name="payment_method" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('app.all') }}</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>{{ __('app.cash') }}</option>
                    <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>{{ __('app.card') }}</option>
                    <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>{{ __('app.bank_transfer') }}</option>
                    <option value="instapay" {{ request('payment_method') === 'instapay' ? 'selected' : '' }}>{{ __('app.instapay') }}</option>
                </select>
            </div>
            <div class="min-w-[130px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.from') }}</label>
                <input type="date" name="from" value="{{ request('from') }}" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="min-w-[130px]">
                <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.to') }}</label>
                <input type="date" name="to" value="{{ request('to') }}" class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                {{ __('app.filter') }}
            </button>
        </form>
    </div>

    {{-- Expenses Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.category') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.description') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.payment_method') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.amount') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase">{{ __('app.added_by') }}</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($expenses as $expense)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $expense->expense_date->format('Y-m-d') }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center gap-1.5 text-sm">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $expense->category->color ?? '#6366f1' }}"></span>
                                {{ $expense->category->name }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ Str::limit($expense->description, 40) ?: '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-lg bg-gray-100 text-gray-700">
                                {{ __('app.' . $expense->payment_method) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-sm font-semibold text-red-600">{{ number_format($expense->amount, 2) }} {{ __('app.egp') }}</td>
                        <td class="px-5 py-3 text-sm text-gray-500">{{ $expense->creator->name ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('dashboard.expenses.edit', $expense) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('dashboard.expenses.destroy', $expense) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                            <p class="text-sm text-gray-400">{{ __('app.no_expenses') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $expenses->withQueryString()->links() }}
        </div>
        @endif
    </div>

    {{-- Categories Management (collapsible) --}}
    <div x-data="{ open: false }" class="mt-6">
        <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4 transition-transform" :class="open && 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            {{ __('app.manage_categories') }}
        </button>
        <div x-show="open" x-transition class="mt-3 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                @foreach($categories as $cat)
                <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full" style="background-color: {{ $cat->color }}"></span>
                        <span class="text-sm text-gray-700">{{ $cat->name }}</span>
                    </div>
                    @if(!$cat->is_default)
                    <form method="POST" action="{{ route('dashboard.expenses.categories.destroy', $cat) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                        @csrf @method('DELETE')
                        <button class="text-gray-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>
            <form method="POST" action="{{ route('dashboard.expenses.categories.store') }}" class="flex flex-wrap items-end gap-3 pt-3 border-t border-gray-100">
                @csrf
                <div class="flex-1 min-w-[150px]">
                    <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.name_en') }}</label>
                    <input type="text" name="name_en" required class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.name_ar') }}</label>
                    <input type="text" name="name_ar" required class="w-full text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="w-[80px]">
                    <label class="text-xs font-medium text-gray-500 mb-1 block">{{ __('app.color') }}</label>
                    <input type="color" name="color" value="#6366f1" class="w-full h-[38px] border-gray-200 rounded-xl cursor-pointer">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                    {{ __('app.add') }}
                </button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
