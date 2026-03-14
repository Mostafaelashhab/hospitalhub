<x-dashboard-layout>
    <x-slot name="title">{{ __('app.insurance_providers') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.insurance_providers') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'insurance'])
    </x-slot>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('app.insurance_providers') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.manage_insurance_providers_desc') }}</p>
        </div>
        <button onclick="document.getElementById('addProviderModal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.add_provider') }}
        </button>
    </div>

    {{-- Providers List --}}
    @if($providers->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($providers as $provider)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition-shadow"
             x-data="{ editing: false }">
            {{-- View Mode --}}
            <div x-show="!editing">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">{{ $provider->name }}</h3>
                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $provider->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-50 text-gray-500 border-gray-200' }}">
                                {{ $provider->is_active ? __('app.active') : __('app.inactive') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button @click="editing = true" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('dashboard.insurance.destroy', $provider) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase">{{ __('app.coverage_percentage') }}</p>
                        <p class="text-lg font-bold text-indigo-600">{{ $provider->coverage_percentage }}%</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase">{{ __('app.max_coverage') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $provider->max_coverage ? number_format($provider->max_coverage, 2) . ' ' . __('app.currency') : __('app.unlimited') }}</p>
                    </div>
                    @if($provider->phone)
                    <div>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase">{{ __('app.phone') }}</p>
                        <p class="text-sm text-gray-700" dir="ltr">{{ $provider->phone }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase">{{ __('app.patients') }}</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $provider->patient_insurances_count }}</p>
                    </div>
                </div>
            </div>

            {{-- Edit Mode --}}
            <div x-show="editing" x-cloak>
                <form method="POST" action="{{ route('dashboard.insurance.update', $provider) }}">
                    @csrf @method('PUT')
                    <div class="space-y-3">
                        <input type="text" name="name" value="{{ $provider->name }}" required placeholder="{{ __('app.provider_name') }}"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="phone" value="{{ $provider->phone }}" placeholder="{{ __('app.phone') }}" dir="ltr"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                            <input type="email" name="email" value="{{ $provider->email }}" placeholder="{{ __('app.email') }}" dir="ltr"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">{{ __('app.coverage_percentage') }}</label>
                                <input type="number" name="coverage_percentage" value="{{ $provider->coverage_percentage }}" min="0" max="100" step="0.01" required
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-gray-500 uppercase mb-1">{{ __('app.max_coverage') }}</label>
                                <input type="number" name="max_coverage" value="{{ $provider->max_coverage }}" min="0" step="0.01"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                            </div>
                        </div>
                        <select name="is_active" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                            <option value="1" {{ $provider->is_active ? 'selected' : '' }}>{{ __('app.active') }}</option>
                            <option value="0" {{ !$provider->is_active ? 'selected' : '' }}>{{ __('app.inactive') }}</option>
                        </select>
                        <textarea name="notes" rows="2" placeholder="{{ __('app.notes') }}"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all resize-none">{{ $provider->notes }}</textarea>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all">{{ __('app.save') }}</button>
                            <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-all">{{ __('app.cancel') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        <p class="text-gray-500 text-sm mb-4">{{ __('app.no_insurance_providers') }}</p>
        <button onclick="document.getElementById('addProviderModal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.add_provider') }}
        </button>
    </div>
    @endif

    {{-- Add Provider Modal --}}
    <div id="addProviderModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('app.add_provider') }}</h3>
            <form method="POST" action="{{ route('dashboard.insurance.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.provider_name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.phone') }}</label>
                        <input type="text" name="phone" dir="ltr" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.email') }}</label>
                        <input type="email" name="email" dir="ltr" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.coverage_percentage') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="coverage_percentage" value="0" min="0" max="100" step="0.01" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.max_coverage') }}</label>
                        <input type="number" name="max_coverage" min="0" step="0.01" placeholder="{{ __('app.unlimited') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.notes') }}</label>
                    <textarea name="notes" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all resize-none"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">{{ __('app.save') }}</button>
                    <button type="button" onclick="document.getElementById('addProviderModal').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-all">{{ __('app.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
