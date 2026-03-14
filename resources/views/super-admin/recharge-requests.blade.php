<x-dashboard-layout>
    <x-slot name="title">{{ __('app.recharge_requests') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.recharge_requests') }}</x-slot>

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
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 mb-1">{{ __('app.status_pending') }}</p>
            <p class="text-2xl font-bold text-amber-600">{{ $pendingCount }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 mb-1">{{ __('app.total') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $rechargeRequests->total() }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <select name="status" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                <option value="">{{ __('app.all_statuses') }}</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('app.status_pending') }}</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('app.status_approved') }}</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('app.status_rejected') }}</option>
            </select>
            <select name="payment_method" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                <option value="">{{ __('app.all_payment_methods') }}</option>
                <option value="instapay" {{ request('payment_method') === 'instapay' ? 'selected' : '' }}>{{ __('app.payment_instapay') }}</option>
                <option value="vodafone_cash" {{ request('payment_method') === 'vodafone_cash' ? 'selected' : '' }}>{{ __('app.payment_vodafone_cash') }}</option>
                <option value="collector" {{ request('payment_method') === 'collector' ? 'selected' : '' }}>{{ __('app.payment_collector') }}</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
                {{ __('app.filter') }}
            </button>
            @if(request()->hasAny(['status', 'payment_method']))
            <a href="{{ route('super.recharge.index') }}" class="text-sm text-gray-500 hover:text-gray-700">{{ __('app.clear_filters') }}</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.clinic') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.points') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.payment_method') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.reference_number') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                        <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rechargeRequests as $req)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3.5">
                            <a href="{{ route('super.clinics.show', $req->clinic_id) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                {{ app()->getLocale() === 'ar' ? $req->clinic->name_ar : $req->clinic->name_en }}
                            </a>
                            @if($req->user)
                            <p class="text-xs text-gray-400">{{ $req->user->name }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-sm font-bold text-gray-900">{{ number_format($req->points) }}</td>
                        <td class="px-6 py-3.5">
                            @php
                                $methodStyles = [
                                    'instapay' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'vodafone_cash' => 'bg-red-50 text-red-700 border-red-200',
                                    'collector' => 'bg-purple-50 text-purple-700 border-purple-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-lg border {{ $methodStyles[$req->payment_method] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                {{ __('app.payment_' . $req->payment_method) }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-600 font-mono" dir="ltr">{{ $req->reference_number ?? '-' }}</td>
                        <td class="px-6 py-3.5">
                            @php
                                $reqStyles = [
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-lg border {{ $reqStyles[$req->status] ?? '' }}">
                                {{ __('app.status_' . $req->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-500">{{ $req->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-3.5">
                            @if($req->status === 'pending')
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('super.recharge.approve', $req) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                        {{ __('app.approve') }}
                                    </button>
                                </form>
                                <div x-data="{ showReject: false }" class="relative">
                                    <button @click="showReject = !showReject" type="button" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                        {{ __('app.reject') }}
                                    </button>
                                    <div x-show="showReject" x-transition @click.outside="showReject = false" x-cloak
                                         class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} top-full mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 p-3 z-10">
                                        <form method="POST" action="{{ route('super.recharge.reject', $req) }}" class="space-y-2">
                                            @csrf @method('PATCH')
                                            <textarea name="admin_notes" rows="2" placeholder="{{ __('app.rejection_reason') }}"
                                                      class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs focus:border-red-500 focus:ring-1 focus:ring-red-500 resize-none"></textarea>
                                            <button type="submit" class="w-full py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                                {{ __('app.confirm_reject') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @elseif($req->status === 'rejected' && $req->admin_notes)
                            <p class="text-xs text-gray-500 max-w-[150px] truncate" title="{{ $req->admin_notes }}">{{ $req->admin_notes }}</p>
                            @else
                            <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-sm">{{ __('app.no_recharge_requests') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $rechargeRequests->withQueryString()->links() }}
        </div>
    </div>
</x-dashboard-layout>
