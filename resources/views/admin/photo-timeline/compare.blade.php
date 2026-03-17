<x-dashboard-layout>
    <x-slot name="title">{{ __('app.compare_photos') }} — {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.before_after') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.patients.photos.index', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.photo_timeline') }}
        </a>
    </div>

    <div x-data="{
        activeCategory: 'all',
        beforeId: '',
        afterId: '',
        beforeSrc: '',
        afterSrc: '',
        beforeLabel: '',
        afterLabel: '',
        beforeDate: '',
        afterDate: '',
        sliderPos: 50,
        isDragging: false,
        photos: {{ Js::from($photos->map(fn($p) => [
            'id'       => $p->id,
            'category' => $p->category,
            'src'      => $p->photoUrl(),
            'label'    => $p->label ?? '',
            'date'     => $p->taken_at->format('d M Y'),
            'taken_at' => $p->taken_at->toDateString(),
        ])) }},
        get filteredPhotos() {
            if (this.activeCategory === 'all') return this.photos;
            return this.photos.filter(p => p.category === this.activeCategory);
        },
        selectBefore(id) {
            this.beforeId = id;
            const p = this.photos.find(x => x.id == id);
            if (p) { this.beforeSrc = p.src; this.beforeLabel = p.label; this.beforeDate = p.date; }
        },
        selectAfter(id) {
            this.afterId = id;
            const p = this.photos.find(x => x.id == id);
            if (p) { this.afterSrc = p.src; this.afterLabel = p.label; this.afterDate = p.date; }
        },
        init() {
            const fp = this.filteredPhotos;
            if (fp.length >= 1) { this.selectBefore(fp[0].id); }
            if (fp.length >= 2) { this.selectAfter(fp[fp.length - 1].id); }
        },
        handleSliderMouseMove(event) {
            if (!this.isDragging) return;
            const rect = event.currentTarget.getBoundingClientRect();
            const x = event.clientX - rect.left;
            this.sliderPos = Math.min(100, Math.max(0, (x / rect.width) * 100));
        },
        handleSliderTouchMove(event) {
            const rect = event.currentTarget.getBoundingClientRect();
            const x = event.touches[0].clientX - rect.left;
            this.sliderPos = Math.min(100, Math.max(0, (x / rect.width) * 100));
        }
    }" x-init="init()">

        {{-- Page Header --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10m0-10a2 2 0 012 2h2a2 2 0 012-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">{{ __('app.compare_photos') }}</h1>
                    <p class="text-sm text-gray-500">{{ $patient->name }}</p>
                </div>
            </div>
        </div>

        @if($photos->count() < 2)
        {{-- Not enough photos --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
            <div class="w-20 h-20 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">{{ __('app.no_photos') }}</h3>
            <p class="text-sm text-gray-400 mb-6">You need at least 2 photos to compare.</p>
            <a href="{{ route('dashboard.patients.photos.index', $patient) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-violet-600 rounded-xl hover:bg-violet-700 transition-all shadow-sm">
                {{ __('app.upload_photos') }}
            </a>
        </div>
        @else

        {{-- Category Filter --}}
        @if($categories->count() > 1)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 p-1.5 overflow-x-auto">
            <div class="flex items-center gap-1 min-w-max">
                <button @click="activeCategory = 'all'; init()"
                    :class="activeCategory === 'all' ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                    {{ __('app.all') ?? 'All' }}
                </button>
                @foreach($categories as $cat)
                <button @click="activeCategory = '{{ $cat }}'; $nextTick(() => init())"
                    :class="activeCategory === '{{ $cat }}' ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                    {{ __('app.' . $cat) }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Photo Selectors --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            {{-- Before Selector --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    {{ __('app.select_before') }}
                </label>
                <select x-model="beforeId" @change="selectBefore($event.target.value)"
                    class="w-full rounded-xl border-gray-200 text-sm focus:border-violet-500 focus:ring-violet-500">
                    <option value="">— {{ __('app.select_before') }} —</option>
                    <template x-for="p in filteredPhotos" :key="p.id">
                        <option :value="p.id" x-text="(p.label || p.date) + ' (' + p.date + ')'"></option>
                    </template>
                </select>
            </div>

            {{-- After Selector --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    {{ __('app.select_after') }}
                </label>
                <select x-model="afterId" @change="selectAfter($event.target.value)"
                    class="w-full rounded-xl border-gray-200 text-sm focus:border-violet-500 focus:ring-violet-500">
                    <option value="">— {{ __('app.select_after') }} —</option>
                    <template x-for="p in filteredPhotos" :key="p.id">
                        <option :value="p.id" x-text="(p.label || p.date) + ' (' + p.date + ')'"></option>
                    </template>
                </select>
            </div>
        </div>

        {{-- Comparison Slider --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6"
            x-show="beforeSrc && afterSrc"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            {{-- Slider Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                    <span class="text-sm font-bold text-gray-900">{{ __('app.drag_to_compare') }}</span>
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span>
                        {{ __('app.select_before') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
                        {{ __('app.select_after') }}
                    </span>
                </div>
            </div>

            {{-- The Comparison Slider --}}
            <div class="relative select-none overflow-hidden bg-gray-900 cursor-col-resize"
                @mousedown="isDragging = true"
                @mouseup="isDragging = false"
                @mouseleave="isDragging = false"
                @mousemove="handleSliderMouseMove($event)"
                @touchmove.prevent="handleSliderTouchMove($event)"
                @touchend="isDragging = false">

                {{-- After Image (full width, background) --}}
                <img :src="afterSrc" alt="After"
                    class="w-full block object-cover max-h-[600px]"
                    style="display: block;">

                {{-- Before Image (clipped overlay) --}}
                <div class="absolute inset-0 overflow-hidden pointer-events-none"
                    :style="'width:' + sliderPos + '%'">
                    <img :src="beforeSrc" alt="Before"
                        class="block object-cover max-h-[600px]"
                        :style="'width: calc(100vw); max-width: none; min-width: calc(100% * 100 / Math.max(' + sliderPos + ', 1))'">
                </div>

                {{-- Range Input (invisible, handles drag) --}}
                <input type="range" min="0" max="100" step="0.1"
                    x-model="sliderPos"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-col-resize z-20"
                    style="margin: 0; padding: 0;">

                {{-- Divider Line --}}
                <div class="absolute top-0 bottom-0 w-0.5 bg-white shadow-[0_0_8px_rgba(0,0,0,0.5)] pointer-events-none z-10"
                    :style="'left:' + sliderPos + '%'">

                    {{-- Handle Button --}}
                    <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-11 h-11 bg-white rounded-full shadow-xl flex items-center justify-center border border-gray-200"
                        style="filter: drop-shadow(0 4px 16px rgba(0,0,0,0.35));">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l-4 3 4 3m8-6l4 3-4 3"/>
                        </svg>
                    </div>

                    {{-- Labels on divider --}}
                    <div class="absolute top-4 -translate-x-full pe-2 pointer-events-none">
                        <span class="inline-block bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-lg shadow whitespace-nowrap">
                            {{ __('app.select_before') }}
                        </span>
                    </div>
                    <div class="absolute top-4 ps-2 pointer-events-none">
                        <span class="inline-block bg-emerald-600 text-white text-xs font-bold px-2 py-1 rounded-lg shadow whitespace-nowrap">
                            {{ __('app.select_after') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Photo Info Bar --}}
            <div class="grid grid-cols-2 divide-x divide-gray-100 border-t border-gray-100">
                <div class="px-5 py-3 bg-blue-50/50">
                    <p class="text-xs font-bold text-blue-700 uppercase tracking-wide mb-0.5">{{ __('app.select_before') }}</p>
                    <p x-show="beforeLabel" x-text="beforeLabel" class="text-sm font-semibold text-gray-800"></p>
                    <p x-text="beforeDate" class="text-xs text-gray-400"></p>
                </div>
                <div class="px-5 py-3 bg-emerald-50/50">
                    <p class="text-xs font-bold text-emerald-700 uppercase tracking-wide mb-0.5">{{ __('app.select_after') }}</p>
                    <p x-show="afterLabel" x-text="afterLabel" class="text-sm font-semibold text-gray-800"></p>
                    <p x-text="afterDate" class="text-xs text-gray-400"></p>
                </div>
            </div>
        </div>

        {{-- Side-by-Side View (fallback / secondary) --}}
        <div x-show="beforeSrc && afterSrc"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">
            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 px-1">{{ __('app.before_after') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Before --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 border-b border-gray-100 bg-blue-50/70 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>
                        <span class="text-xs font-bold text-blue-800 uppercase tracking-wide">{{ __('app.select_before') }}</span>
                    </div>
                    <img :src="beforeSrc" alt="Before" class="w-full object-cover max-h-80">
                    <div class="px-4 py-3">
                        <p x-show="beforeLabel" x-text="beforeLabel" class="text-sm font-semibold text-gray-800 mb-0.5"></p>
                        <p x-text="beforeDate" class="text-xs text-gray-400"></p>
                    </div>
                </div>

                {{-- After --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 border-b border-gray-100 bg-emerald-50/70 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                        <span class="text-xs font-bold text-emerald-800 uppercase tracking-wide">{{ __('app.select_after') }}</span>
                    </div>
                    <img :src="afterSrc" alt="After" class="w-full object-cover max-h-80">
                    <div class="px-4 py-3">
                        <p x-show="afterLabel" x-text="afterLabel" class="text-sm font-semibold text-gray-800 mb-0.5"></p>
                        <p x-text="afterDate" class="text-xs text-gray-400"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- No selection state --}}
        <div x-show="!beforeSrc || !afterSrc" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10m0-10a2 2 0 012 2h2a2 2 0 012-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
            <p class="text-sm text-gray-400">{{ __('app.select_before') }} &amp; {{ __('app.select_after') }}</p>
        </div>

        @endif
    </div>
</x-dashboard-layout>
