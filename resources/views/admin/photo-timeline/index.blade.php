<x-dashboard-layout>
    <x-slot name="title">{{ __('app.photo_timeline') }} — {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.photo_timeline') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    {{-- Success / Error Messages --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
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

    {{-- Back Link --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('dashboard.patients.show', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ $patient->name }}
        </a>
        @if($photos->count() >= 2)
        <a href="{{ route('dashboard.patients.photos.compare', $patient) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-violet-700 bg-violet-50 border border-violet-200 rounded-xl hover:bg-violet-100 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10m0-10a2 2 0 012 2h2a2 2 0 012-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
            {{ __('app.compare_photos') }}
        </a>
        @endif
    </div>

    <div x-data="{
        showUpload: false,
        activeCategory: 'all',
        lightbox: false,
        lightboxSrc: '',
        lightboxLabel: '',
        lightboxDate: '',
        openLightbox(src, label, date) {
            this.lightboxSrc = src;
            this.lightboxLabel = label;
            this.lightboxDate = date;
            this.lightbox = true;
        }
    }">

        {{-- Page Header --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">{{ __('app.photo_timeline') }}</h1>
                        <p class="text-sm text-gray-500">{{ $patient->name }} &mdash; {{ $photos->count() }} {{ __('app.photos') ?? 'photos' }}</p>
                    </div>
                </div>
                @if(auth()->user()->hasPermission('patients.edit'))
                <button @click="showUpload = !showUpload" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-violet-700 bg-violet-50 border border-violet-200 rounded-xl hover:bg-violet-100 transition-all">
                    <svg class="w-4 h-4 transition-transform" :class="showUpload ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('app.upload_photos') }}
                </button>
                @endif
            </div>
        </div>

        {{-- Upload Section --}}
        @if(auth()->user()->hasPermission('patients.edit'))
        <div x-show="showUpload" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak class="bg-white rounded-2xl border border-violet-100 shadow-sm mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b border-violet-100 bg-violet-50/50 flex items-center gap-2">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <h2 class="text-sm font-bold text-violet-800">{{ __('app.upload_photos') }}</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('dashboard.patients.photos.store', $patient) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        {{-- File Input --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.upload_photos') }} <span class="text-red-500">*</span></label>
                            <input type="file" name="photos[]" multiple required accept="image/jpeg,image/png,image/webp"
                                class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer border border-gray-200 rounded-xl p-1">
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP &mdash; {{ __('app.max_file_size_10mb') ?? 'max 5MB each' }}</p>
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.photo_category') }} <span class="text-red-500">*</span></label>
                            <select name="category" required class="w-full rounded-xl border-gray-200 text-sm focus:border-violet-500 focus:ring-violet-500">
                                @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ __('app.' . $cat) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Taken At --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.taken_at') }} <span class="text-red-500">*</span></label>
                            <input type="date" name="taken_at" required value="{{ old('taken_at', date('Y-m-d')) }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        {{-- Label --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.photo_label') }}</label>
                            <input type="text" name="label" value="{{ old('label') }}" placeholder="{{ __('app.before_after') }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.notes') }}</label>
                            <input type="text" name="notes" value="{{ old('notes') }}" placeholder="{{ __('app.notes') }}..." class="w-full rounded-xl border-gray-200 text-sm focus:border-violet-500 focus:ring-violet-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showUpload = false" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">
                            {{ __('app.cancel') }}
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-violet-600 rounded-xl hover:bg-violet-700 transition-all shadow-sm shadow-violet-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            {{ __('app.upload') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- Category Filter Tabs --}}
        @if($photos->count())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 p-1.5 overflow-x-auto">
            <div class="flex items-center gap-1 min-w-max">
                <button @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                    {{ __('app.all') ?? 'All' }}
                    <span class="ms-1.5 text-xs opacity-70">({{ $photos->count() }})</span>
                </button>
                @foreach($categories as $cat)
                @if($grouped->has($cat))
                <button @click="activeCategory = '{{ $cat }}'"
                    :class="activeCategory === '{{ $cat }}' ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                    {{ __('app.' . $cat) }}
                    <span class="ms-1.5 text-xs opacity-70">({{ $grouped[$cat]->count() }})</span>
                </button>
                @endif
                @endforeach
            </div>
        </div>

        {{-- Photo Timeline --}}
        @php
            $photosByDate = $photos->groupBy(fn($p) => $p->taken_at->format('Y-m-d'));
        @endphp

        @foreach($photosByDate as $date => $datePhotos)
        <div x-show="activeCategory === 'all' || {{ json_encode($datePhotos->pluck('category')->unique()->values()->toArray()) }}.includes(activeCategory)"
            class="mb-8">
            {{-- Date Header --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-2 bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-2">
                    <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span>
                </div>
                <div class="flex-1 h-px bg-gray-100"></div>
                <span class="text-xs text-gray-400">{{ $datePhotos->count() }} {{ __('app.photos') ?? 'photos' }}</span>
            </div>

            {{-- Photos Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($datePhotos as $photo)
                <div x-show="activeCategory === 'all' || activeCategory === '{{ $photo->category }}'"
                    class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:border-violet-200 transition-all duration-200">

                    {{-- Photo Thumbnail --}}
                    <div class="aspect-square overflow-hidden cursor-pointer relative"
                        @click="openLightbox('{{ $photo->photoUrl() }}', '{{ addslashes($photo->label ?? '') }}', '{{ $photo->taken_at->format('d M Y') }}')">
                        <img src="{{ $photo->photoUrl() }}" alt="{{ $photo->label }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-in-out">
                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        </div>
                    </div>

                    {{-- Card Footer --}}
                    <div class="p-3">
                        @php
                            $catColors = [
                                'skin'  => 'bg-orange-50 text-orange-700 border-orange-200',
                                'teeth' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                'face'  => 'bg-pink-50 text-pink-700 border-pink-200',
                                'body'  => 'bg-blue-50 text-blue-700 border-blue-200',
                                'wound' => 'bg-red-50 text-red-700 border-red-200',
                                'other' => 'bg-gray-50 text-gray-600 border-gray-200',
                            ];
                        @endphp
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-lg border {{ $catColors[$photo->category] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ __('app.' . $photo->category) }}
                            </span>
                            @if(auth()->user()->hasPermission('patients.edit'))
                            <form action="{{ route('dashboard.photo-records.destroy', $photo) }}" method="POST"
                                onsubmit="return confirm('{{ __('app.confirm_delete') }}')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-300 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="{{ __('app.delete') }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                        @if($photo->label)
                        <p class="text-xs font-semibold text-gray-800 truncate mb-1">{{ $photo->label }}</p>
                        @endif
                        @if($photo->notes)
                        <p class="text-xs text-gray-400 truncate">{{ $photo->notes }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
            <div class="w-20 h-20 rounded-2xl bg-violet-50 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">{{ __('app.no_photos') }}</h3>
            <p class="text-sm text-gray-400 mb-6">{{ __('app.upload_photos') }}</p>
            @if(auth()->user()->hasPermission('patients.edit'))
            <button @click="showUpload = true" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-violet-600 rounded-xl hover:bg-violet-700 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('app.upload_photos') }}
            </button>
            @endif
        </div>
        @endif

        {{-- Lightbox Modal --}}
        <div x-show="lightbox" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="lightbox = false"
            @click.self="lightbox = false"
            class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4">

            <div class="relative max-w-4xl max-h-[90vh] w-full flex flex-col items-center">
                {{-- Close Button --}}
                <button @click="lightbox = false" class="absolute -top-12 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} p-2 text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                {{-- Image --}}
                <img :src="lightboxSrc" :alt="lightboxLabel" class="max-h-[75vh] max-w-full object-contain rounded-xl shadow-2xl">

                {{-- Caption --}}
                <div class="mt-4 text-center">
                    <p x-show="lightboxLabel" x-text="lightboxLabel" class="text-white font-semibold text-base mb-1"></p>
                    <p x-text="lightboxDate" class="text-white/60 text-sm"></p>
                </div>
            </div>
        </div>

    </div>
</x-dashboard-layout>
