<x-dashboard-layout>
    <x-slot name="title">{{ __('app.platform_settings') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.platform_settings') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.super-admin-sidebar')
    </x-slot>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="max-w-2xl">

        {{-- General Tab --}}
        <div>
            <form method="POST" action="{{ route('super.settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Points Pricing --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        {{ __('app.points_pricing') }}
                    </h3>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.point_price_per_patient') }}</label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="point_price" value="{{ old('point_price', $settings['point_price']) }}" min="0" step="0.1" required
                                   class="w-40 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                            <span class="text-sm text-gray-500">{{ __('app.points_per_new_patient') }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">{{ __('app.point_price_hint') }}</p>
                        @error('point_price')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Free Mode --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{ enabled: {{ old('free_mode_enabled', $settings['free_mode_enabled']) == '1' ? 'true' : 'false' }} }">
                    <h3 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                        <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                        </div>
                        {{ __('app.free_mode') }}
                    </h3>

                    <div class="space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="free_mode_enabled" value="1" x-model="enabled"
                                   class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ __('app.enable_free_mode') }}</p>
                                <p class="text-xs text-gray-500">{{ __('app.free_mode_desc') }}</p>
                            </div>
                        </label>

                        <div x-show="enabled" x-transition class="ps-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.free_mode_until') }}</label>
                            <input type="date" name="free_mode_until" value="{{ old('free_mode_until', $settings['free_mode_until']) }}"
                                   class="w-60 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                            <p class="text-xs text-gray-400 mt-2">{{ __('app.free_mode_until_hint') }}</p>
                            @error('free_mode_until')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="p-3 rounded-xl bg-blue-50 border border-blue-100">
                            <p class="text-xs text-blue-700 flex items-start gap-2">
                                <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ __('app.free_mode_per_clinic_hint') }}</span>
                            </p>
                        </div>

                        @if($settings['free_mode_enabled'] == '1')
                        <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                            <p class="text-sm text-emerald-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <strong>{{ __('app.free_mode_active') }}</strong>
                                @if($settings['free_mode_until'])
                                    — {{ __('app.until') }} {{ $settings['free_mode_until'] }}
                                @else
                                    — {{ __('app.indefinitely') }}
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Accounts --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-2 flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        {{ __('app.payment_accounts') }}
                    </h3>
                    <p class="text-xs text-gray-400 mb-5">{{ __('app.payment_accounts_desc') }}</p>

                    <div class="space-y-5">
                        {{-- InstaPay --}}
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 space-y-3">
                            <p class="text-sm font-semibold text-gray-900">{{ __('app.payment_instapay') }}</p>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">{{ __('app.instapay_account_name') }}</label>
                                <input type="text" name="instapay_account_name" value="{{ old('instapay_account_name', $settings['instapay_account_name']) }}"
                                       class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                @error('instapay_account_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">{{ __('app.instapay_account_number') }}</label>
                                <input type="text" name="instapay_account_number" value="{{ old('instapay_account_number', $settings['instapay_account_number']) }}" dir="ltr"
                                       class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                @error('instapay_account_number')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Vodafone Cash --}}
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 space-y-3">
                            <p class="text-sm font-semibold text-gray-900">{{ __('app.payment_vodafone_cash') }}</p>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">{{ __('app.vodafone_account_name') }}</label>
                                <input type="text" name="vodafone_account_name" value="{{ old('vodafone_account_name', $settings['vodafone_account_name']) }}"
                                       class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                @error('vodafone_account_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">{{ __('app.vodafone_account_number') }}</label>
                                <input type="text" name="vodafone_account_number" value="{{ old('vodafone_account_number', $settings['vodafone_account_number']) }}" dir="ltr"
                                       class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                @error('vodafone_account_number')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-indigo-500/20 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('app.save_settings') }}
                </button>
            </form>
        </div>

    </div>
</x-dashboard-layout>
