<x-dashboard-layout>
    <x-slot name="title">{{ __('app.add_expense') }}</x-slot>
    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'expenses'])
    </x-slot>

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dashboard.expenses.index') }}" class="p-2 rounded-xl hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5 text-gray-500 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.add_expense') }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ __('app.add_expense_desc') }}</p>
        </div>
    </div>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('dashboard.expenses.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            @csrf

            {{-- Category --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.category') }} <span class="text-red-500">*</span></label>
                <select name="expense_category_id" required class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('app.select_category') }}</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('expense_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('expense_category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Amount + Date --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.amount') }} ({{ __('app.egp') }}) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount') }}" required class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    @error('amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', now()->format('Y-m-d')) }}" required class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    @error('expense_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Payment Method --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.payment_method') }} <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2" x-data="{ method: '{{ old('payment_method', 'cash') }}' }">
                    @foreach(['cash', 'card', 'bank_transfer', 'instapay'] as $m)
                    <label @click="method = '{{ $m }}'" :class="method === '{{ $m }}' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'" class="flex items-center justify-center gap-2 px-3 py-2.5 border rounded-xl cursor-pointer transition-all text-sm font-medium">
                        <input type="radio" name="payment_method" value="{{ $m }}" x-model="method" class="sr-only">
                        {{ __('app.' . $m) }}
                    </label>
                    @endforeach
                </div>
                @error('payment_method') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.description') }}</label>
                <textarea name="description" rows="3" class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('app.expense_description_placeholder') }}">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Receipt Upload --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.receipt') }}</label>
                <input type="file" name="receipt" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @error('receipt') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                    {{ __('app.save') }}
                </button>
                <a href="{{ route('dashboard.expenses.index') }}" class="px-6 py-2.5 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-100 transition-colors">
                    {{ __('app.cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-dashboard-layout>
