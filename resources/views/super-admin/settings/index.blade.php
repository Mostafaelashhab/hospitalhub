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

    {{-- Tabs Navigation --}}
    <div x-data="{ activeTab: 'general' }" class="max-w-2xl">
        <div class="flex gap-1 mb-6 bg-gray-100 rounded-xl p-1">
            <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ __('app.general') }}
            </button>
            <button @click="activeTab = 'whatsapp'" :class="activeTab === 'whatsapp' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                {{ __('app.whatsapp') }}
            </button>
            <button @click="activeTab = 'sms'" :class="activeTab === 'sms' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                {{ __('app.sms') }}
            </button>
        </div>

        {{-- General Tab --}}
        <div x-show="activeTab === 'general'" x-transition>
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

        {{-- WhatsApp Tab --}}
        <div x-show="activeTab === 'whatsapp'" x-transition>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('app.whatsapp') }}</h3>
                    <p class="text-sm text-gray-500 mb-1">{{ __('app.under_maintenance') }}</p>
                    <p class="text-xs text-gray-400">{{ __('app.under_maintenance_desc') }}</p>
                </div>
            </div>
        </div>

        {{-- SMS Tab --}}
        <div x-show="activeTab === 'sms'" x-transition>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('app.sms') }}</h3>
                    <p class="text-sm text-gray-500 mb-1">{{ __('app.under_maintenance') }}</p>
                    <p class="text-xs text-gray-400">{{ __('app.under_maintenance_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
