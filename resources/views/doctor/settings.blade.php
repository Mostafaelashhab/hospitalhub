<x-dashboard-layout>
    <x-slot name="title">{{ __('app.doctor_settings') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.doctor_settings') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.doctor-sidebar', ['active' => 'settings'])
    </x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('app.doctor_settings') }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ __('app.doctor_settings_desc') }}</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
         class="mb-6 px-5 py-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    @endif

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

    <form method="POST" action="{{ route('doctor.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Consultation Fee --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.consultation_fee') }}</h3>
            <p class="text-sm text-gray-500 mb-4">{{ __('app.consultation_fee_desc') }}</p>

            <div class="max-w-xs">
                <div class="relative">
                    <input type="number" name="consultation_fee" value="{{ old('consultation_fee', $doctor->consultation_fee) }}" min="0" step="0.01" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-lg font-semibold text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all" dir="ltr">
                    <span class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">{{ __('app.currency') }}</span>
                </div>
            </div>
        </div>

        {{-- Working Days --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{
            days: {{ json_encode(old('working_days', $doctor->working_days ?? [])) }},
            toggle(day) {
                const idx = this.days.indexOf(day);
                if (idx > -1) this.days.splice(idx, 1);
                else this.days.push(day);
            },
            isActive(day) {
                return this.days.includes(day);
            }
        }">
            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.working_days') }}</h3>
            <p class="text-sm text-gray-500 mb-4">{{ __('app.working_days_desc') }}</p>

            {{-- Days Grid --}}
            <div class="grid grid-cols-7 gap-2 mb-6">
                @php
                    $weekDays = [
                        'saturday' => ['en' => 'Sat', 'ar' => 'سبت'],
                        'sunday' => ['en' => 'Sun', 'ar' => 'أحد'],
                        'monday' => ['en' => 'Mon', 'ar' => 'إثنين'],
                        'tuesday' => ['en' => 'Tue', 'ar' => 'ثلاثاء'],
                        'wednesday' => ['en' => 'Wed', 'ar' => 'أربعاء'],
                        'thursday' => ['en' => 'Thu', 'ar' => 'خميس'],
                        'friday' => ['en' => 'Fri', 'ar' => 'جمعة'],
                    ];
                @endphp

                @foreach($weekDays as $key => $labels)
                <button type="button" @click="toggle('{{ $key }}')"
                        :class="isActive('{{ $key }}') ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-500/25' : 'bg-gray-50 text-gray-700 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50'"
                        class="flex flex-col items-center justify-center py-3 px-2 rounded-xl border-2 transition-all duration-200 cursor-pointer">
                    <span class="text-xs font-bold">{{ app()->getLocale() === 'ar' ? $labels['ar'] : $labels['en'] }}</span>
                </button>
                @endforeach

                <template x-for="day in days" :key="day">
                    <input type="hidden" name="working_days[]" :value="day">
                </template>
            </div>

            {{-- Working Hours --}}
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('app.working_hours') }}</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">{{ __('app.from') }}</label>
                    <input type="time" name="working_from" value="{{ old('working_from', $doctor->working_from ? \Carbon\Carbon::parse($doctor->working_from)->format('H:i') : '') }}" dir="ltr"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">{{ __('app.to') }}</label>
                    <input type="time" name="working_to" value="{{ old('working_to', $doctor->working_to ? \Carbon\Carbon::parse($doctor->working_to)->format('H:i') : '') }}" dir="ltr"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ __('app.save') }}
            </button>
        </div>
    </form>
</x-dashboard-layout>
