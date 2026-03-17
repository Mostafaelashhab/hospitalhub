<x-dashboard-layout>
    <x-slot name="title">{{ __('app.add_pregnancy') }} — {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.add_pregnancy') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    {{-- Back --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.patients.pregnancy.index', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.pregnancy_tracker') }}
        </a>
    </div>

    <div class="max-w-xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-lg shadow-pink-500/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ __('app.add_pregnancy') }}</h1>
                <p class="text-sm text-gray-500">{{ $patient->name }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8"
             x-data="{
                 lmpDate: '',
                 eddDate: '',
                 currentWeek: 0,
                 trimester: 0,
                 calcEDD() {
                     if (!this.lmpDate) { this.eddDate = ''; this.currentWeek = 0; return; }
                     const lmp = new Date(this.lmpDate);
                     const edd = new Date(lmp);
                     edd.setDate(edd.getDate() + 280);
                     this.eddDate = edd.toISOString().slice(0, 10);

                     const today = new Date();
                     const diffDays = Math.floor((today - lmp) / (1000 * 60 * 60 * 24));
                     this.currentWeek = Math.min(Math.floor(diffDays / 7), 42);
                     if (this.currentWeek <= 12) this.trimester = 1;
                     else if (this.currentWeek <= 27) this.trimester = 2;
                     else this.trimester = 3;
                 }
             }">

            <form action="{{ route('dashboard.patients.pregnancy.store', $patient) }}" method="POST">
                @csrf

                {{-- LMP Date --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ __('app.lmp_date') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           name="lmp_date"
                           required
                           max="{{ date('Y-m-d') }}"
                           x-model="lmpDate"
                           @change="calcEDD()"
                           class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500 @error('lmp_date') border-red-400 @enderror"
                           value="{{ old('lmp_date') }}"
                    >
                    @error('lmp_date')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">{{ __('app.lmp_date') }}</p>
                </div>

                {{-- EDD Display (auto-calculated) --}}
                <div x-show="eddDate" class="mb-6 p-4 bg-pink-50 border border-pink-200 rounded-xl">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs font-bold text-pink-700 uppercase tracking-wide">{{ __('app.edd_date') }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-xs text-pink-500 font-medium mb-0.5">{{ __('app.edd_date') }}</p>
                            <p class="text-base font-bold text-pink-800" x-text="eddDate"></p>
                        </div>
                        <div>
                            <p class="text-xs text-pink-500 font-medium mb-0.5">{{ __('app.current_week') }}</p>
                            <p class="text-base font-bold text-pink-800" x-text="currentWeek + ' / 40'"></p>
                        </div>
                        <div>
                            <p class="text-xs text-pink-500 font-medium mb-0.5">{{ __('app.trimester') }}</p>
                            <p class="text-base font-bold text-pink-800" x-text="trimester"></p>
                        </div>
                    </div>
                </div>

                {{-- Doctor --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.doctor') }}</label>
                    <select name="doctor_id" class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500">
                        <option value="">— {{ __('app.no_doctor') ?? __('app.optional') }} —</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Notes --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.notes') }}</label>
                    <textarea name="notes" rows="3"
                              class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500 resize-none"
                              placeholder="{{ __('app.notes') }}...">{{ old('notes') }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-pink-500 to-rose-500 rounded-xl hover:from-pink-600 hover:to-rose-600 transition-all shadow-md shadow-pink-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('app.save') }}
                    </button>
                    <a href="{{ route('dashboard.patients.pregnancy.index', $patient) }}"
                       class="px-6 py-3 text-sm font-semibold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">
                        {{ __('app.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-dashboard-layout>
