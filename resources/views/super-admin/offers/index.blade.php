<x-dashboard-layout>
    <x-slot name="title">{{ __('app.offers') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.super-admin-sidebar')
    </x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('app.offers') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.manage_offers_desc') }}</p>
        </div>
        <a href="{{ route('super.offers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/25 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            {{ __('app.create_offer') }}
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="type" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ __('app.all_types') }}</option>
                <option value="discount" {{ request('type') === 'discount' ? 'selected' : '' }}>{{ __('app.discount') }}</option>
                <option value="drug_offer" {{ request('type') === 'drug_offer' ? 'selected' : '' }}>{{ __('app.drug_offer') }}</option>
                <option value="promotion" {{ request('type') === 'promotion' ? 'selected' : '' }}>{{ __('app.promotion') }}</option>
            </select>
            <select name="status" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ __('app.all_statuses') }}</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('app.active') }}</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>{{ __('app.expired') }}</option>
                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>{{ __('app.upcoming') }}</option>
            </select>
        </form>
    </div>

    {{-- Offers Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($offers as $offer)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- Image --}}
            @if($offer->image)
            <div class="h-40 bg-gray-100 overflow-hidden">
                <img src="{{ Storage::url($offer->image) }}" alt="{{ $offer->title }}" class="w-full h-full object-cover">
            </div>
            @else
            <div class="h-32 bg-gradient-to-br {{ $offer->type === 'discount' ? 'from-emerald-500 to-teal-600' : ($offer->type === 'drug_offer' ? 'from-blue-500 to-indigo-600' : 'from-purple-500 to-pink-600') }} flex items-center justify-center">
                @if($offer->type === 'discount')
                    <svg class="w-12 h-12 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                @elseif($offer->type === 'drug_offer')
                    <svg class="w-12 h-12 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                @else
                    <svg class="w-12 h-12 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                @endif
            </div>
            @endif

            <div class="p-5">
                {{-- Type badge + Status --}}
                <div class="flex items-center justify-between mb-3">
                    @php
                        $typeBg = ['discount' => 'bg-emerald-50 text-emerald-700', 'drug_offer' => 'bg-blue-50 text-blue-700', 'promotion' => 'bg-purple-50 text-purple-700'];
                    @endphp
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg {{ $typeBg[$offer->type] }}">{{ __('app.' . $offer->type) }}</span>

                    @if($offer->isRunning())
                        <span class="flex items-center gap-1 text-[10px] font-bold text-emerald-600"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>{{ __('app.active') }}</span>
                    @elseif($offer->isExpired())
                        <span class="text-[10px] font-bold text-red-500">{{ __('app.expired') }}</span>
                    @else
                        <span class="text-[10px] font-bold text-amber-500">{{ __('app.upcoming') }}</span>
                    @endif
                </div>

                {{-- Title --}}
                <h3 class="text-base font-bold text-gray-900 mb-1">{{ app()->getLocale() === 'ar' ? $offer->title_ar : $offer->title_en }}</h3>
                <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ app()->getLocale() === 'ar' ? $offer->description_ar : $offer->description_en }}</p>

                {{-- Discount info --}}
                @if($offer->discount_percentage)
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-2xl font-black text-emerald-600">{{ rtrim(rtrim($offer->discount_percentage, '0'), '.') }}%</span>
                    <span class="text-xs text-gray-500">{{ __('app.discount') }}</span>
                </div>
                @elseif($offer->discount_amount)
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-2xl font-black text-emerald-600">{{ number_format($offer->discount_amount) }}</span>
                    <span class="text-xs text-gray-500">{{ __('app.egp') }}</span>
                </div>
                @endif

                {{-- Date range --}}
                <div class="flex items-center gap-2 text-xs text-gray-400 mb-4">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $offer->start_date->format('M d') }} - {{ $offer->end_date->format('M d, Y') }}
                </div>

                {{-- Target --}}
                <div class="text-xs text-gray-400 mb-4">
                    @if($offer->for_all_clinics)
                        <span class="bg-gray-50 px-2 py-1 rounded-lg">{{ __('app.all_clinics') }}</span>
                    @else
                        <span class="bg-gray-50 px-2 py-1 rounded-lg">{{ $offer->clinics->count() }} {{ __('app.clinics') }}</span>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('super.offers.edit', $offer) }}" class="flex-1 text-center py-2 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">{{ __('app.edit') }}</a>
                    <form method="POST" action="{{ route('super.offers.toggle', $offer) }}" class="flex-1">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full py-2 text-xs font-semibold {{ $offer->is_active ? 'text-amber-600 bg-amber-50 hover:bg-amber-100' : 'text-emerald-600 bg-emerald-50 hover:bg-emerald-100' }} rounded-xl transition-colors">
                            {{ $offer->is_active ? __('app.deactivate') : __('app.activate') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('super.offers.destroy', $offer) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <p class="text-gray-500 font-medium">{{ __('app.no_offers') }}</p>
                <a href="{{ route('super.offers.create') }}" class="inline-flex items-center gap-2 mt-4 text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    {{ __('app.create_offer') }}
                </a>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($offers->hasPages())
    <div class="mt-6">
        {{ $offers->withQueryString()->links() }}
    </div>
    @endif
</x-dashboard-layout>
