<x-dashboard-layout>
    <x-slot name="title">{{ __('app.dental_chart') }} — {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.dental_chart') }}</x-slot>

    <x-slot name="sidebar">
        @if(auth()->user()->role === 'doctor' && request()->is('doctor/*'))
            @include('partials.doctor-sidebar', ['active' => 'dental-chart'])
        @else
            @include('partials.dashboard-sidebar', ['active' => 'patients'])
        @endif
    </x-slot>

    @php $isDoctor = auth()->user()->role === 'doctor' && request()->is('doctor/*'); @endphp

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Back --}}
    <div class="mb-5">
        <a href="{{ $isDoctor ? route('doctor.patient.history', $patient) : route('dashboard.patients.show', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ $patient->name }}
        </a>
    </div>

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.dental_chart') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $patient->name }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ $isDoctor ? route('doctor.dental-chart.pdf', $patient) : route('dashboard.patients.dental-chart.pdf', $patient) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                PDF
            </a>
            <a href="{{ $isDoctor ? route('doctor.dental-chart.history', $patient) : route('dashboard.patients.dental-chart.history', $patient) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('app.chart_history') }}
            </a>
        </div>
    </div>

    {{-- Description --}}
    <div class="mb-6 bg-indigo-50 border border-indigo-100 rounded-xl px-5 py-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-sm font-semibold text-indigo-800">{{ app()->getLocale() === 'ar' ? 'كيف تستخدم شارت الأسنان؟' : 'How to use the Dental Chart?' }}</p>
                <p class="text-xs text-indigo-600 mt-1 leading-relaxed">{{ app()->getLocale() === 'ar' ? 'اضغط على أي سنة لتحديدها، ثم اختر حالتها من القائمة وأضف ملاحظات. اضغط "حفظ" لتسجيل الشارت.' : 'Click any tooth to select it, choose its status from the panel, add notes, then click "Save" to record.' }}</p>
            </div>
        </div>
    </div>

    {{-- Dental Chart --}}
    <div x-data="dentalChart(@js($toothData))" class="space-y-6">

        {{-- Tooth Grid --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            {{-- Upper Jaw --}}
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest text-center mb-3">{{ app()->getLocale() === 'ar' ? 'الفك العلوي' : 'UPPER JAW' }}</p>
            <div class="flex justify-center gap-1 sm:gap-1.5 mb-2 flex-wrap">
                @foreach(['18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28'] as $i => $num)
                @if($i === 8)<div class="w-px bg-gray-200 mx-1 sm:mx-2 self-stretch"></div>@endif
                <button type="button" @click="selectTooth('{{ $num }}')"
                    :class="{
                        'ring-2 ring-indigo-500 ring-offset-2': selectedTooth === '{{ $num }}',
                        'opacity-30': filterStatus && teeth['{{ $num }}'].status !== filterStatus
                    }"
                    :style="'background:' + statusColors[teeth['{{ $num }}'].status] + ';border-color:' + statusStrokeColors[teeth['{{ $num }}'].status]"
                    class="relative w-10 h-14 sm:w-12 sm:h-16 rounded-lg border-2 flex flex-col items-center justify-center transition-all hover:scale-105 cursor-pointer">
                    <span class="text-[10px] font-black text-gray-700" x-text="'{{ $num }}'"></span>
                    <span class="text-[8px] font-semibold mt-0.5" :style="'color:' + statusStrokeColors[teeth['{{ $num }}'].status]" x-text="statusLabels[teeth['{{ $num }}'].status]?.substring(0, 4)"></span>
                    <span x-show="teeth['{{ $num }}'].notes" class="absolute -top-1 -right-1 w-3 h-3 bg-orange-400 rounded-full border border-white"></span>
                </button>
                @endforeach
            </div>

            {{-- Divider --}}
            <div class="border-t border-dashed border-gray-200 my-4"></div>

            {{-- Lower Jaw --}}
            <div class="flex justify-center gap-1 sm:gap-1.5 mb-2 flex-wrap">
                @foreach(['48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38'] as $i => $num)
                @if($i === 8)<div class="w-px bg-gray-200 mx-1 sm:mx-2 self-stretch"></div>@endif
                <button type="button" @click="selectTooth('{{ $num }}')"
                    :class="{
                        'ring-2 ring-indigo-500 ring-offset-2': selectedTooth === '{{ $num }}',
                        'opacity-30': filterStatus && teeth['{{ $num }}'].status !== filterStatus
                    }"
                    :style="'background:' + statusColors[teeth['{{ $num }}'].status] + ';border-color:' + statusStrokeColors[teeth['{{ $num }}'].status]"
                    class="relative w-10 h-14 sm:w-12 sm:h-16 rounded-lg border-2 flex flex-col items-center justify-center transition-all hover:scale-105 cursor-pointer">
                    <span class="text-[10px] font-black text-gray-700" x-text="'{{ $num }}'"></span>
                    <span class="text-[8px] font-semibold mt-0.5" :style="'color:' + statusStrokeColors[teeth['{{ $num }}'].status]" x-text="statusLabels[teeth['{{ $num }}'].status]?.substring(0, 4)"></span>
                    <span x-show="teeth['{{ $num }}'].notes" class="absolute -top-1 -right-1 w-3 h-3 bg-orange-400 rounded-full border border-white"></span>
                </button>
                @endforeach
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest text-center mt-3">{{ app()->getLocale() === 'ar' ? 'الفك السفلي' : 'LOWER JAW' }}</p>
        </div>

        {{-- Legend / Filter --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-700 mb-3">{{ __('app.tooth_status') }}</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($statuses as $status)
                <button type="button"
                    @click="filterStatus = (filterStatus === '{{ $status }}' ? null : '{{ $status }}')"
                    :class="filterStatus === '{{ $status }}' ? 'ring-2 ring-offset-1 ring-gray-400' : ''"
                    :style="'background:' + statusColors['{{ $status }}'] + ';border-color:' + statusStrokeColors['{{ $status }}']"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all text-gray-700">
                    <span class="w-3 h-3 rounded-full border border-black/10" :style="'background:' + statusDotColors['{{ $status }}']"></span>
                    {{ __('app.' . $status) }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Selected Tooth Editor --}}
        <div x-show="selectedTooth" x-transition class="bg-white rounded-2xl border-2 border-indigo-200 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <span class="text-lg font-black text-indigo-600" x-text="'#' + selectedTooth"></span>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ app()->getLocale() === 'ar' ? 'سنة رقم' : 'Tooth' }} <span x-text="selectedTooth"></span></p>
                    <p class="text-xs text-gray-500" x-text="statusLabels[teeth[selectedTooth]?.status]"></p>
                </div>
                <button type="button" @click="selectedTooth = null" class="ms-auto w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Status buttons --}}
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 mb-2">{{ app()->getLocale() === 'ar' ? 'اختر الحالة' : 'Select Status' }}</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($statuses as $status)
                    <button type="button" @click="setStatus('{{ $status }}')"
                        :class="selectedTooth && teeth[selectedTooth].status === '{{ $status }}' ? 'ring-2 ring-offset-1 ring-indigo-500 scale-105' : ''"
                        :style="'background:' + statusColors['{{ $status }}'] + ';border-color:' + statusStrokeColors['{{ $status }}']"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold border transition-all text-gray-700">
                        <span class="w-3 h-3 rounded-full border border-black/10" :style="'background:' + statusDotColors['{{ $status }}']"></span>
                        {{ __('app.' . $status) }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">{{ __('app.notes') }}</label>
                <textarea x-model="teeth[selectedTooth].notes" rows="2"
                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent resize-none"
                    placeholder="{{ app()->getLocale() === 'ar' ? 'ملاحظات عن السنة...' : 'Notes about this tooth...' }}"></textarea>
            </div>
        </div>

        {{-- Save Form --}}
        <form method="POST"
              action="{{ $isDoctor ? route('doctor.dental-chart.store', $patient) : route('dashboard.patients.dental-chart.store', $patient) }}"
              @submit.prevent="submitChart($el)">
            @csrf
            <input type="hidden" name="tooth_data" x-ref="toothDataInput">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale() === 'ar' ? 'ملاحظات عامة' : 'General Notes' }}</label>
                <textarea name="notes" rows="3"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent resize-none"
                    placeholder="{{ app()->getLocale() === 'ar' ? 'ملاحظات عامة عن حالة الأسنان...' : 'General notes about the dental condition...' }}"
                >{{ old('notes', $chart?->notes) }}</textarea>
            </div>

            {{-- Summary --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
                <div class="flex flex-wrap gap-4 text-sm">
                    <template x-for="s in allStatuses" :key="s">
                        <div class="flex items-center gap-1.5" x-show="countStatus(s) > 0">
                            <span class="w-3 h-3 rounded-full border border-black/10" :style="'background:' + statusDotColors[s]"></span>
                            <span class="text-gray-500" x-text="statusLabels[s]"></span>
                            <span class="font-bold text-gray-900" x-text="countStatus(s)"></span>
                        </div>
                    </template>
                </div>
            </div>

            <button type="submit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ __('app.save_chart') }}
            </button>
        </form>
    </div>

    <script>
    function dentalChart(initialTeeth) {
        return {
            teeth: initialTeeth,
            selectedTooth: null,
            filterStatus: null,
            allStatuses: ['healthy','cavity','filling','crown','extraction','implant','root_canal','bridge','veneer','missing'],
            statusLabels: {
                healthy: '{{ __("app.healthy") }}', cavity: '{{ __("app.cavity") }}', filling: '{{ __("app.filling") }}',
                crown: '{{ __("app.crown") }}', extraction: '{{ __("app.extraction") }}', implant: '{{ __("app.implant") }}',
                root_canal: '{{ __("app.root_canal") }}', bridge: '{{ __("app.bridge") }}', veneer: '{{ __("app.veneer") }}', missing: '{{ __("app.missing") }}',
            },
            statusColors: {
                healthy: '#f0fdf4', cavity: '#fef2f2', filling: '#f3f4f6', crown: '#fefce8',
                extraction: '#f3f4f6', implant: '#eff6ff', root_canal: '#fff7ed',
                bridge: '#faf5ff', veneer: '#ecfeff', missing: '#f9fafb',
            },
            statusStrokeColors: {
                healthy: '#86efac', cavity: '#ef4444', filling: '#9ca3af', crown: '#eab308',
                extraction: '#374151', implant: '#3b82f6', root_canal: '#f97316',
                bridge: '#a855f7', veneer: '#06b6d4', missing: '#d1d5db',
            },
            statusDotColors: {
                healthy: '#22c55e', cavity: '#ef4444', filling: '#9ca3af', crown: '#eab308',
                extraction: '#374151', implant: '#3b82f6', root_canal: '#f97316',
                bridge: '#a855f7', veneer: '#06b6d4', missing: '#94a3b8',
            },
            selectTooth(num) { this.selectedTooth = (this.selectedTooth === num) ? null : num; },
            setStatus(status) { if (this.selectedTooth) this.teeth[this.selectedTooth].status = status; },
            countStatus(s) { return Object.values(this.teeth).filter(t => t.status === s).length; },
            submitChart(form) { this.$refs.toothDataInput.value = JSON.stringify(this.teeth); form.submit(); },
        };
    }
    </script>
</x-dashboard-layout>
