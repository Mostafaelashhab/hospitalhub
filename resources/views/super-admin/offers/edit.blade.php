<x-dashboard-layout>
    <x-slot name="title">{{ __('app.edit_offer') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.super-admin-sidebar')
    </x-slot>

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('super.offers.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
            <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('app.edit_offer') }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ app()->getLocale() === 'ar' ? $offer->title_ar : $offer->title_en }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{ type: '{{ old('type', $offer->type) }}' }">
        <form method="POST" action="{{ route('super.offers.update', $offer) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('super-admin.offers._form')

            <div class="mt-8 flex items-center gap-3">
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/25 transition-all">
                    {{ __('app.save_changes') }}
                </button>
                <a href="{{ route('super.offers.index') }}" class="px-6 py-3 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    {{ __('app.cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-dashboard-layout>
