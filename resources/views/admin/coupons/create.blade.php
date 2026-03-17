<x-dashboard-layout>
    <x-slot name="title">{{ __('app.add_coupon') }}</x-slot>
    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'coupons'])
    </x-slot>

    <div class="max-w-2xl">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('dashboard.coupons.index') }}" class="p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ __('app.add_coupon') }}</h2>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{ couponType: '{{ old('type', 'percentage') }}' }">
            <form method="POST" action="{{ route('dashboard.coupons.store') }}">
                @csrf

                <div class="space-y-5">
                    {{-- Code --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.coupon_code') }}</label>
                        <input type="text"
                            name="code"
                            value="{{ old('code') }}"
                            placeholder="SAVE20 · XXXX-XXXX"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-mono uppercase focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('code') border-red-300 @enderror"
                            oninput="this.value=this.value.toUpperCase()">
                        <p class="mt-1 text-xs text-gray-400">{{ __('app.auto_generate_hint') }}</p>
                        @error('code')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Name EN / AR --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.coupon_name') }} (EN)</label>
                            <input type="text"
                                name="name_en"
                                value="{{ old('name_en') }}"
                                placeholder="Summer Discount"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name_en') border-red-300 @enderror">
                            @error('name_en')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.coupon_name') }} (AR)</label>
                            <input type="text"
                                name="name_ar"
                                value="{{ old('name_ar') }}"
                                placeholder="خصم الصيف"
                                dir="rtl"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name_ar') border-red-300 @enderror">
                            @error('name_ar')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Type & Value --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.coupon_type') }}</label>
                            <select name="type"
                                x-model="couponType"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('type') border-red-300 @enderror">
                                <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>{{ __('app.percentage') }}</option>
                                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>{{ __('app.fixed_amount') }}</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                {{ __('app.discount_value') }}
                                <span x-show="couponType === 'percentage'" class="text-gray-400">(%)</span>
                            </label>
                            <input type="number"
                                name="value"
                                value="{{ old('value') }}"
                                min="0.01"
                                step="0.01"
                                placeholder="10"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('value') border-red-300 @enderror">
                            @error('value')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Min Amount & Max Discount (percentage only) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.min_amount') }} <span class="text-gray-400 text-xs">({{ __('app.optional') }})</span></label>
                            <input type="number"
                                name="min_amount"
                                value="{{ old('min_amount') }}"
                                min="0"
                                step="0.01"
                                placeholder="100"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('min_amount') border-red-300 @enderror">
                            @error('min_amount')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div x-show="couponType === 'percentage'" x-transition>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.max_discount') }} <span class="text-gray-400 text-xs">({{ __('app.optional') }})</span></label>
                            <input type="number"
                                name="max_discount"
                                value="{{ old('max_discount') }}"
                                min="0"
                                step="0.01"
                                placeholder="50"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('max_discount') border-red-300 @enderror">
                            @error('max_discount')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Max Uses --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.max_uses') }} <span class="text-gray-400 text-xs">({{ __('app.optional') }})</span></label>
                            <input type="number"
                                name="max_uses"
                                value="{{ old('max_uses') }}"
                                min="1"
                                step="1"
                                placeholder="100"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('max_uses') border-red-300 @enderror">
                            @error('max_uses')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.max_uses_per_patient') }} <span class="text-gray-400 text-xs">({{ __('app.optional') }})</span></label>
                            <input type="number"
                                name="max_uses_per_patient"
                                value="{{ old('max_uses_per_patient') }}"
                                min="1"
                                step="1"
                                placeholder="1"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('max_uses_per_patient') border-red-300 @enderror">
                            @error('max_uses_per_patient')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Validity Dates --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.valid_from') }} <span class="text-gray-400 text-xs">({{ __('app.optional') }})</span></label>
                            <input type="date"
                                name="valid_from"
                                value="{{ old('valid_from') }}"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('valid_from') border-red-300 @enderror">
                            @error('valid_from')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.valid_to') }} <span class="text-gray-400 text-xs">({{ __('app.optional') }})</span></label>
                            <input type="date"
                                name="valid_to"
                                value="{{ old('valid_to') }}"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('valid_to') border-red-300 @enderror">
                            @error('valid_to')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 mt-8 pt-5 border-t border-gray-100">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                        {{ __('app.save') }}
                    </button>
                    <a href="{{ route('dashboard.coupons.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                        {{ __('app.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
