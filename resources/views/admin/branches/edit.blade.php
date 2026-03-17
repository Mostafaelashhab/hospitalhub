<x-dashboard-layout>
    <x-slot name="title">{{ __('app.edit_branch') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.edit_branch') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'branches'])
    </x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.branches.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors mb-3">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
        <h2 class="text-2xl font-bold text-gray-900">{{ __('app.edit_branch') }}</h2>
        @if($branch->is_main)
        <span class="inline-flex items-center gap-1 mt-2 px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            {{ __('app.main_branch') }}
        </span>
        @endif
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('dashboard.branches.update', $branch) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.branch_name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $branch->name) }}" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}" dir="ltr"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.city') }}</label>
                    <input type="text" name="city" value="{{ old('city', $branch->city) }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                @if(!$branch->is_main)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.status') }}</label>
                    <select name="is_active"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="1" {{ old('is_active', $branch->is_active) ? 'selected' : '' }}>{{ __('app.active') }}</option>
                        <option value="0" {{ !old('is_active', $branch->is_active) ? 'selected' : '' }}>{{ __('app.inactive') }}</option>
                    </select>
                </div>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.address') }}</label>
                <textarea name="address" rows="2"
                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none">{{ old('address', $branch->address) }}</textarea>
            </div>

            {{-- Map Location Picker --}}
            <div x-data="mapPicker({ lat: {{ old('latitude', $branch->latitude ?? 30.0444) }}, lng: {{ old('longitude', $branch->longitude ?? 31.2357) }}, hasLocation: {{ old('latitude', $branch->latitude) ? 'true' : 'false' }} })">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    {{ __('app.location_on_map') }}
                </label>
                <p class="text-xs text-gray-500 mb-3">{{ __('app.click_map_to_set') }}</p>

                {{-- Search Box --}}
                <div class="relative mb-3">
                    <input type="text" x-ref="searchBox" x-model="searchQuery" @keydown.enter.prevent="searchLocation()"
                           placeholder="{{ __('app.search_location') }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all {{ app()->getLocale() === 'ar' ? 'pl-10' : 'pr-10' }}">
                    <button type="button" @click="searchLocation()"
                            class="absolute {{ app()->getLocale() === 'ar' ? 'left-2' : 'right-2' }} top-1/2 -translate-y-1/2 p-1.5 text-gray-400 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>

                {{-- Map Container --}}
                <div x-ref="mapContainer" class="w-full h-72 sm:h-80 rounded-xl border-2 border-gray-200 overflow-hidden shadow-sm z-0"></div>

                {{-- Coordinates Display & Reset --}}
                <div class="flex items-center justify-between mt-2">
                    <div class="text-xs text-gray-500" x-show="hasLocation" x-transition>
                        <span class="font-mono bg-gray-100 px-2 py-0.5 rounded" x-text="'Lat: ' + selectedLat.toFixed(6) + ' | Lng: ' + selectedLng.toFixed(6)"></span>
                    </div>
                    <button type="button" x-show="hasLocation" @click="resetLocation()" class="text-xs text-red-600 hover:text-red-700 font-medium transition-colors">
                        {{ __('app.reset_location') }}
                    </button>
                </div>

                <input type="hidden" name="latitude" :value="hasLocation ? selectedLat : ''">
                <input type="hidden" name="longitude" :value="hasLocation ? selectedLng : ''">
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('app.save') }}
                </button>
                <a href="{{ route('dashboard.branches.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all">
                    {{ __('app.cancel') }}
                </a>
            </div>
        </form>
    </div>
    {{-- Leaflet CSS & JS (Free - OpenStreetMap) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>.leaflet-control-attribution{font-size:9px!important;}</style>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mapPicker', (config) => ({
            map: null,
            marker: null,
            selectedLat: config.lat,
            selectedLng: config.lng,
            hasLocation: config.hasLocation,
            searchQuery: '',

            init() {
                this.$nextTick(() => {
                    this.map = L.map(this.$refs.mapContainer, { zoomControl: true }).setView([this.selectedLat, this.selectedLng], this.hasLocation ? 15 : 6);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap',
                        maxZoom: 19,
                    }).addTo(this.map);

                    if (this.hasLocation) {
                        this.marker = L.marker([this.selectedLat, this.selectedLng], { draggable: true }).addTo(this.map);
                        this.marker.on('dragend', (e) => {
                            const pos = e.target.getLatLng();
                            this.selectedLat = pos.lat;
                            this.selectedLng = pos.lng;
                        });
                    }

                    this.map.on('click', (e) => {
                        this.selectedLat = e.latlng.lat;
                        this.selectedLng = e.latlng.lng;
                        this.hasLocation = true;
                        if (this.marker) {
                            this.marker.setLatLng(e.latlng);
                        } else {
                            this.marker = L.marker(e.latlng, { draggable: true }).addTo(this.map);
                            this.marker.on('dragend', (ev) => {
                                const pos = ev.target.getLatLng();
                                this.selectedLat = pos.lat;
                                this.selectedLng = pos.lng;
                            });
                        }
                    });

                    setTimeout(() => this.map.invalidateSize(), 200);
                });
            },

            async searchLocation() {
                if (!this.searchQuery.trim()) return;
                try {
                    const resp = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}&limit=1`);
                    const data = await resp.json();
                    if (data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lng = parseFloat(data[0].lon);
                        this.selectedLat = lat;
                        this.selectedLng = lng;
                        this.hasLocation = true;
                        this.map.setView([lat, lng], 16);
                        if (this.marker) {
                            this.marker.setLatLng([lat, lng]);
                        } else {
                            this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                            this.marker.on('dragend', (ev) => {
                                const pos = ev.target.getLatLng();
                                this.selectedLat = pos.lat;
                                this.selectedLng = pos.lng;
                            });
                        }
                    }
                } catch (e) { console.error('Search failed:', e); }
            },

            resetLocation() {
                this.hasLocation = false;
                if (this.marker) {
                    this.map.removeLayer(this.marker);
                    this.marker = null;
                }
                this.map.setView([30.0444, 31.2357], 6);
            },
        }));
    });
    </script>
</x-dashboard-layout>
