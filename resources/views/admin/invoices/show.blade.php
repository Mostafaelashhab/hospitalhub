<x-dashboard-layout>
    <x-slot name="title">{{ __('app.invoice') }} #{{ $invoice->id }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.invoice') }} #{{ $invoice->id }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'invoices'])
    </x-slot>

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.invoices.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Invoice Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Invoice Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('app.invoice') }} #{{ $invoice->id }}</h3>
                    </div>
                    @php
                        $statusColors = [
                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'unpaid' => 'bg-red-50 text-red-700 border-red-200',
                            'partial' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'refunded' => 'bg-gray-50 text-gray-700 border-gray-200',
                        ];
                    @endphp
                    <span class="inline-flex px-3 py-1.5 text-sm font-semibold rounded-xl border {{ $statusColors[$invoice->status] ?? '' }}">
                        {{ __('app.' . $invoice->status) }}
                    </span>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Patient Info --}}
                        <div class="space-y-4">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.patient_info') }}</h4>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $invoice->patient->name ?? '-' }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $invoice->patient->phone ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Appointment Info --}}
                        <div class="space-y-4">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.appointment_info') }}</h4>
                            <div>
                                @if($invoice->appointment)
                                <p class="text-sm text-gray-700">{{ __('app.doctor') }}: <span class="font-semibold">{{ $invoice->appointment->doctor->name ?? '-' }}</span></p>
                                <p class="text-sm text-gray-500 mt-1">{{ $invoice->appointment->appointment_date }} {{ $invoice->appointment->appointment_time }}</p>
                                @else
                                <p class="text-sm text-gray-500">-</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Amount Breakdown --}}
                    <div class="mt-8 border-t border-gray-100 pt-6">
                        <div class="space-y-3">
                            {{-- Invoice Items --}}
                            @if($invoice->items->isNotEmpty())
                            <div class="space-y-1.5 mb-3 pb-3 border-b border-gray-100">
                                @foreach($invoice->items as $item)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">{{ $item->description }}</span>
                                    <span class="text-sm font-medium text-gray-900" dir="ltr">{{ number_format($item->total, 2) }} {{ __('app.currency') }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">{{ __('app.amount') }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($invoice->amount, 2) }} {{ __('app.currency') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">{{ __('app.discount') }}</span>
                                <span class="text-sm font-medium text-red-500">- {{ number_format($invoice->discount, 2) }} {{ __('app.currency') }}</span>
                            </div>
                            @if($invoice->insurance_coverage > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    {{ __('app.insurance_coverage') }}
                                    @if($invoice->insuranceProvider)
                                    <span class="text-xs text-gray-400">({{ $invoice->insuranceProvider->name }})</span>
                                    @endif
                                </span>
                                <span class="text-sm font-medium text-blue-600">- {{ number_format($invoice->insurance_coverage, 2) }} {{ __('app.currency') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">{{ __('app.patient_share') }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($invoice->patient_share, 2) }} {{ __('app.currency') }}</span>
                            </div>
                            @endif
                            <div class="border-t border-gray-200 pt-3 flex items-center justify-between">
                                <span class="text-base font-bold text-gray-900">{{ __('app.total') }}</span>
                                <span class="text-xl font-bold text-indigo-600">{{ number_format($invoice->total, 2) }} {{ __('app.currency') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Extra Info --}}
                    <div class="mt-6 border-t border-gray-100 pt-6 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">{{ __('app.payment_method') }}</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $invoice->payment_method ? __('app.' . $invoice->payment_method) : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">{{ __('app.date') }}</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $invoice->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>

                    @if($invoice->notes)
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <p class="text-xs text-gray-500 font-medium mb-2">{{ __('app.notes') }}</p>
                        <p class="text-sm text-gray-700 bg-gray-50 rounded-xl p-4">{{ $invoice->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Edit Sidebar --}}
        @if(auth()->user()->hasPermission('invoices.edit'))
        <div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.update_invoice') }}</h3>

                <form method="POST" action="{{ route('dashboard.invoices.update', $invoice) }}">
                    @csrf
                    @method('PUT')

                    {{-- Status --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.status') }}</label>
                        <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                            <option value="unpaid" {{ $invoice->status === 'unpaid' ? 'selected' : '' }}>{{ __('app.unpaid') }}</option>
                            <option value="paid" {{ $invoice->status === 'paid' ? 'selected' : '' }}>{{ __('app.paid') }}</option>
                            <option value="partial" {{ $invoice->status === 'partial' ? 'selected' : '' }}>{{ __('app.partial') }}</option>
                            <option value="refunded" {{ $invoice->status === 'refunded' ? 'selected' : '' }}>{{ __('app.refunded') }}</option>
                        </select>
                    </div>

                    {{-- Payment Method --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.payment_method') }}</label>
                        <select name="payment_method" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                            <option value="">{{ __('app.select_payment_method') }}</option>
                            <option value="cash" {{ $invoice->payment_method === 'cash' ? 'selected' : '' }}>{{ __('app.cash') }}</option>
                            <option value="card" {{ $invoice->payment_method === 'card' ? 'selected' : '' }}>{{ __('app.card') }}</option>
                            <option value="bank_transfer" {{ $invoice->payment_method === 'bank_transfer' ? 'selected' : '' }}>{{ __('app.bank_transfer') }}</option>
                            <option value="instapay" {{ $invoice->payment_method === 'instapay' ? 'selected' : '' }}>{{ __('app.instapay') }}</option>
                        </select>
                    </div>

                    {{-- Discount --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.discount') }}</label>
                        <input type="number" name="discount" value="{{ $invoice->discount }}" step="0.01" min="0"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>

                    {{-- Notes --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.notes') }}</label>
                        <textarea name="notes" rows="3"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all resize-none">{{ $invoice->notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('app.update_invoice') }}
                    </button>
                </form>

                @if($errors->any())
                <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</x-dashboard-layout>
