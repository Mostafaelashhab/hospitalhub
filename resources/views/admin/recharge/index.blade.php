<x-dashboard-layout>
    <x-slot name="title">{{ __('app.recharge_points') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.recharge_points') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'recharge'])
    </x-slot>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Current Balance Card --}}
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg shadow-indigo-500/20">
                <div class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 {{ app()->getLocale() === 'ar' ? '-translate-x-1/2' : 'translate-x-1/2' }}"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-indigo-200 text-sm font-medium">{{ __('app.current_balance') }}</p>
                        <p class="text-4xl font-bold mt-1">{{ number_format($clinic->wallet->balance ?? 0) }}</p>
                        <p class="text-indigo-200 text-sm mt-1">{{ __('app.points') }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/15 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                </div>
            </div>

            {{-- How Points Work --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('app.how_points_work') }}
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 border border-blue-100">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                            <span class="text-sm font-bold text-blue-600">1</span>
                        </div>
                        <p class="text-sm text-blue-800"><strong>{{ __('app.new_patient_cost') }}</strong> — {{ __('app.new_patient_cost_desc') }}</p>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-sm text-emerald-800"><strong>{{ __('app.free_features') }}</strong> — {{ __('app.free_features_desc') }}</p>
                    </div>
                </div>
            </div>

            {{-- Previous Requests --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">{{ __('app.recharge_history') }}</h3>
                </div>
                @if($requests->count())
                <div class="divide-y divide-gray-100">
                    @foreach($requests as $req)
                    <div class="px-6 py-4 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $req->status === 'approved' ? 'bg-emerald-50' : ($req->status === 'rejected' ? 'bg-red-50' : 'bg-amber-50') }}">
                            @if($req->status === 'approved')
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @elseif($req->status === 'rejected')
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @else
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold text-gray-900">{{ number_format($req->points) }} {{ __('app.points') }}</p>
                                @php
                                    $statusStyles = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $statusStyles[$req->status] ?? '' }}">
                                    {{ __('app.status_' . $req->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ __('app.payment_' . $req->payment_method) }}
                                @if($req->reference_number) — {{ $req->reference_number }} @endif
                                · {{ $req->created_at->format('Y-m-d H:i') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{ $requests->links() }}
                @else
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-gray-400">{{ __('app.no_recharge_requests') }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Recharge Form --}}
        <div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('app.request_recharge') }}
                </h3>

                <form method="POST" action="{{ route('dashboard.recharge.store') }}" class="space-y-4" x-data="{ method: '{{ old('payment_method', '') }}', selected: '' }">
                    @csrf

                    {{-- Points Amount --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.points_amount') }} <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            @foreach([100, 250, 500, 1000, 2500, 5000] as $amount)
                            <button type="button"
                                    @click="selected = {{ $amount }}; $refs.points_input.value = {{ $amount }}"
                                    :class="selected === {{ $amount }} ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-gray-300'"
                                    class="px-3 py-2.5 text-sm font-bold rounded-xl border transition-all text-center">
                                {{ number_format($amount) }}
                            </button>
                            @endforeach
                        </div>
                        <input type="number" name="points" x-ref="points_input" value="{{ old('points') }}" min="50" required
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"
                               placeholder="{{ __('app.custom_amount') }}">
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.payment_method') }} <span class="text-red-500">*</span></label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
                                   :class="method === 'instapay' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="payment_method" value="instapay" x-model="method" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ __('app.payment_instapay') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('app.instapay_desc') }}</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
                                   :class="method === 'vodafone_cash' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="payment_method" value="vodafone_cash" x-model="method" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ __('app.payment_vodafone_cash') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('app.vodafone_cash_desc') }}</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
                                   :class="method === 'collector' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="payment_method" value="collector" x-model="method" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ __('app.payment_collector') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('app.collector_desc') }}</p>
                                </div>
                            </label>
                        </div>

                        {{-- Collector Info Notice --}}
                        <div x-show="method === 'collector'" x-transition class="mt-3 space-y-2">
                            <div class="p-3 rounded-xl bg-amber-50 border border-amber-100">
                                <p class="text-sm text-amber-800 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ __('app.collector_points_notice') }}</span>
                                </p>
                            </div>
                            <div class="p-3 rounded-xl bg-blue-50 border border-blue-100">
                                <p class="text-sm text-blue-800 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ __('app.collector_timeframe') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Reference Number (hidden for collector) --}}
                    <div x-show="method !== 'collector'" x-transition>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.reference_number') }}</label>
                        <input type="text" name="reference_number" value="{{ old('reference_number') }}" dir="ltr"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"
                               placeholder="{{ __('app.reference_number_placeholder') }}">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.notes') }}</label>
                        <textarea name="notes" rows="2"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all resize-none">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-indigo-500/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        {{ __('app.send_recharge_request') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>
