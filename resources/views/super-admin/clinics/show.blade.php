<x-dashboard-layout>
    <x-slot name="title">{{ app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.view_details') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.super-admin-sidebar')
    </x-slot>

    {{-- Back button --}}
    <div class="mb-6">
        <a href="{{ route('super.clinics.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Clinic Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Info Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">{{ __('app.clinic_info_details') }}</h2>
                    @php
                        $statusStyles = [
                            'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'suspended' => 'bg-red-50 text-red-700 border-red-200',
                            'inactive' => 'bg-gray-50 text-gray-600 border-gray-200',
                        ];
                        $statusDots = [
                            'active' => 'bg-emerald-500',
                            'pending' => 'bg-amber-500',
                            'suspended' => 'bg-red-500',
                            'inactive' => 'bg-gray-400',
                        ];
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-lg border {{ $statusStyles[$clinic->status] ?? $statusStyles['inactive'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$clinic->status] ?? $statusDots['inactive'] }}"></span>
                        {{ __('app.' . $clinic->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.clinic_name_ar') }}</p>
                        <p class="text-gray-900 font-medium">{{ $clinic->name_ar }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.clinic_name_en') }}</p>
                        <p class="text-gray-900 font-medium">{{ $clinic->name_en }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.email') }}</p>
                        <p class="text-gray-900">{{ $clinic->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.phone') }}</p>
                        <p class="text-gray-900 font-mono" dir="ltr">{{ $clinic->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.specialty') }}</p>
                        <p class="text-gray-900">{{ $clinic->specialty ? (app()->getLocale() === 'ar' ? $clinic->specialty->name_ar : $clinic->specialty->name_en) : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.tax_number') }}</p>
                        <p class="text-gray-900 font-mono">{{ $clinic->tax_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.clinic_address_ar') }}</p>
                        <p class="text-gray-900">{{ $clinic->address_ar ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.clinic_address_en') }}</p>
                        <p class="text-gray-900">{{ $clinic->address_en ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.clinic_size') }}</p>
                        <p class="text-gray-900">{{ $clinic->clinic_size ? __('app.size_' . $clinic->clinic_size) : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.doctors_count') }}</p>
                        <p class="text-gray-900">{{ $clinic->doctors_count ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.working_hours') }}</p>
                        <p class="text-gray-900 font-mono" dir="ltr">{{ $clinic->working_hours_from ?? '-' }} - {{ $clinic->working_hours_to ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.registered_at') }}</p>
                        <p class="text-gray-900">{{ $clinic->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                {{-- Admin Info --}}
                @php $admin = $clinic->admin->first(); @endphp
                @if($admin)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-500 mb-4">{{ __('app.admin_info') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.admin_name') }}</p>
                            <p class="text-gray-900 font-medium">{{ $admin->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.admin_email') }}</p>
                            <p class="text-gray-900">{{ $admin->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">{{ __('app.admin_phone') }}</p>
                            <p class="text-gray-900 font-mono" dir="ltr">{{ $admin->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Status Actions --}}
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap gap-3">
                    @if($clinic->status === 'pending')
                    <form method="POST" action="{{ route('super.clinics.status', $clinic) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('app.approve') }}
                        </button>
                    </form>
                    @endif
                    @if($clinic->status === 'active')
                    <form method="POST" action="{{ route('super.clinics.status', $clinic) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="suspended">
                        <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            {{ __('app.suspend') }}
                        </button>
                    </form>
                    @endif
                    @if($clinic->status === 'suspended')
                    <form method="POST" action="{{ route('super.clinics.status', $clinic) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('app.activate') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Recharge Requests --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-base font-bold text-gray-900">{{ __('app.recharge_requests') }}</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
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
                                <td class="px-6 py-3.5 text-sm font-bold text-gray-900">{{ number_format($req->points) }}</td>
                                <td class="px-6 py-3.5 text-sm text-gray-600">{{ __('app.payment_' . $req->payment_method) }}</td>
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
                                            <div x-show="showReject" x-transition @click.outside="showReject = false"
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
                                    @else
                                    <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">{{ __('app.no_recharge_requests') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $rechargeRequests->links() }}
            </div>

            {{-- Transactions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-base font-bold text-gray-900">{{ __('app.transactions') }}</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.type') }}</th>
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.amount') }}</th>
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.balance') }}</th>
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.description') }}</th>
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($clinic->wallet->transactions ?? [] as $tx)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-semibold rounded-lg {{ $tx->type === 'credit' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                        {{ __('app.' . $tx->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-sm font-mono font-semibold {{ $tx->type === 'credit' ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $tx->type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount) }}
                                </td>
                                <td class="px-6 py-3.5 text-sm text-gray-900 font-mono">{{ number_format($tx->balance_after) }}</td>
                                <td class="px-6 py-3.5 text-sm text-gray-600">{{ $tx->description ?? '-' }}</td>
                                <td class="px-6 py-3.5 text-sm text-gray-500">{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">{{ __('app.no_clinics_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Wallet sidebar --}}
        <div class="space-y-6">
            {{-- Balance Card --}}
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 shadow-lg shadow-indigo-500/20 relative overflow-hidden">
                <div class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 {{ app()->getLocale() === 'ar' ? '-translate-x-1/2' : 'translate-x-1/2' }}"></div>
                <p class="text-indigo-200 text-sm mb-1 relative">{{ __('app.current_balance') }}</p>
                <p class="text-4xl font-bold text-white mb-1 relative">{{ number_format($clinic->wallet->balance ?? 0) }}</p>
                <p class="text-indigo-200 text-sm relative">{{ __('app.points') }}</p>
            </div>

            {{-- Add Points --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between text-gray-900 font-semibold">
                    <span class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        {{ __('app.add_points') }}
                    </span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <form x-show="open" x-transition method="POST" action="{{ route('super.clinics.add-points', $clinic) }}" class="mt-4 space-y-3">
                    @csrf
                    <input type="number" name="points" min="1" required placeholder="{{ __('app.points') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                    <input type="text" name="description" placeholder="{{ __('app.description') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                    <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        {{ __('app.add_points') }}
                    </button>
                </form>
            </div>

            {{-- Deduct Points --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between text-gray-900 font-semibold">
                    <span class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </div>
                        {{ __('app.deduct_points') }}
                    </span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <form x-show="open" x-transition method="POST" action="{{ route('super.clinics.deduct-points', $clinic) }}" class="mt-4 space-y-3">
                    @csrf
                    <input type="number" name="points" min="1" required placeholder="{{ __('app.points') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                    <input type="text" name="description" placeholder="{{ __('app.description') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                    <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        {{ __('app.deduct_points') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>
