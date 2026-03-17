<x-dashboard-layout>
    <x-slot name="title">{{ __('app.patient_reviews') }}</x-slot>
    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'reviews'])
    </x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.patient_reviews') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.total_reviews') }}: {{ $stats['total'] }}</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        {{-- Average Rating --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 col-span-2 sm:col-span-1">
            <div class="flex flex-col gap-2">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.avg_rating') }}</p>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-2xl font-bold text-gray-900">{{ $stats['avg_rating'] }}</span>
                        <span class="text-sm text-gray-400">/ 5</span>
                    </div>
                    <div class="flex gap-0.5 mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($stats['avg_rating']))
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @else
                                <svg class="w-3.5 h-3.5 text-gray-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Reviews --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ __('app.total_reviews') }}</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        {{-- 5-Star --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">5 ★</p>
                    <p class="text-lg font-bold text-green-600">{{ $stats['five_star'] }}</p>
                </div>
            </div>
        </div>

        {{-- 1-Star --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">1 ★</p>
                    <p class="text-lg font-bold text-red-500">{{ $stats['one_star'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            {{-- Doctor Filter --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('app.doctor') }}</label>
                <select name="doctor_id" class="w-full rounded-xl border border-gray-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">{{ __('app.all') }}</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Rating Filter --}}
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('app.avg_rating') }}</label>
                <select name="rating" class="w-full rounded-xl border border-gray-200 text-sm px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">{{ __('app.all') }}</option>
                    @for($r = 5; $r >= 1; $r--)
                        <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>
                            {{ $r }} ★
                        </option>
                    @endfor
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                    {{ __('app.search') }}
                </button>
                @if(request('doctor_id') || request('rating'))
                    <a href="{{ route('dashboard.reviews.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                        {{ __('app.cancel') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Session Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Reviews Grid --}}
    @if($reviews->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <p class="text-gray-500 text-sm font-medium">{{ __('app.no_reviews') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($reviews as $review)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3 {{ !$review->is_visible ? 'opacity-60' : '' }}">
                    {{-- Header: Patient & Date --}}
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-indigo-600">{{ mb_substr($review->patient->name ?? '?', 0, 1) }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $review->patient->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        {{-- Visibility badge --}}
                        <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $review->is_visible ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $review->is_visible ? __('app.visible') : __('app.hidden') }}
                        </span>
                    </div>

                    {{-- Doctor --}}
                    @if($review->doctor)
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $review->doctor->name }}</span>
                        </div>
                    @endif

                    {{-- Star Rating --}}
                    <div class="flex gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @else
                                <svg class="w-4 h-4 text-gray-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @endif
                        @endfor
                        <span class="text-xs text-gray-400 {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">{{ $review->rating }}/5</span>
                    </div>

                    {{-- Comment --}}
                    @if($review->comment)
                        <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">{{ $review->comment }}</p>
                    @else
                        <p class="text-sm text-gray-400 italic">—</p>
                    @endif

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 pt-1 border-t border-gray-50 mt-auto">
                        {{-- Toggle Visibility --}}
                        <form method="POST" action="{{ route('dashboard.reviews.toggle', $review) }}" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg {{ $review->is_visible ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'bg-green-50 text-green-700 hover:bg-green-100' }} transition-colors">
                                @if($review->is_visible)
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    {{ __('app.hidden') }}
                                @else
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ __('app.visible') }}
                                @endif
                            </button>
                        </form>

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('dashboard.reviews.destroy', $review) }}"
                            x-data
                            @submit.prevent="if(confirm('{{ __('app.confirm_delete') }}')) $el.submit()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($reviews->hasPages())
            <div class="mt-6">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        @endif
    @endif
</x-dashboard-layout>
