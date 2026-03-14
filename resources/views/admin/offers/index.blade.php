<x-dashboard-layout>
    <x-slot name="title">{{ __('app.offers') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'offers'])
    </x-slot>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('app.offers') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('app.available_offers_desc') }}</p>
    </div>

    {{-- Offers Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($offers as $offer)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
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
                @php
                    $typeBg = ['discount' => 'bg-emerald-50 text-emerald-700', 'drug_offer' => 'bg-blue-50 text-blue-700', 'promotion' => 'bg-purple-50 text-purple-700'];
                @endphp
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg {{ $typeBg[$offer->type] }}">{{ __('app.' . $offer->type) }}</span>
                    <span class="flex items-center gap-1 text-[10px] font-bold text-emerald-600"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>{{ __('app.active') }}</span>
                </div>

                <h3 class="text-base font-bold text-gray-900 mb-1">{{ $offer->title }}</h3>
                <p class="text-xs text-gray-500 line-clamp-3 mb-3">{{ $offer->description }}</p>

                @if($offer->discount_percentage)
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-3xl font-black text-emerald-600">{{ rtrim(rtrim($offer->discount_percentage, '0'), '.') }}%</span>
                    <span class="text-sm text-gray-500">{{ __('app.discount') }}</span>
                </div>
                @elseif($offer->discount_amount)
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-3xl font-black text-emerald-600">{{ number_format($offer->discount_amount) }}</span>
                    <span class="text-sm text-gray-500">{{ __('app.egp') }}</span>
                </div>
                @endif

                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('app.valid_until') }} {{ $offer->end_date->format('M d, Y') }}
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
                <p class="text-gray-400 text-sm mt-1">{{ __('app.no_offers_desc') }}</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($offers->hasPages())
    <div class="mt-6">
        {{ $offers->links() }}
    </div>
    @endif
</x-dashboard-layout>
