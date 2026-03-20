<x-dashboard-layout>
    <x-slot name="title">{{ __('app.schedule') }} - {{ $doctor->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.schedule') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'doctors'])
    </x-slot>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ app()->getLocale() === 'ar' ? 'جدول مواعيد' : 'Schedule for' }} {{ $doctor->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ app()->getLocale() === 'ar' ? 'حدد أيام وأوقات العمل — ممكن تضيف أكتر من فترة في اليوم' : 'Set working days & hours — multiple shifts per day allowed' }}</p>
        </div>
        <a href="{{ route('dashboard.doctors.show', $doctor) }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
            {{ __('app.back') }}
        </a>
    </div>

    <form method="POST" action="{{ route('dashboard.doctors.schedule.update', $doctor) }}"
          x-data="scheduleManager()" class="space-y-6">
        @csrf

        {{-- Days --}}
        @foreach($days as $dayKey => $dayLabel)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- Day Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                         :class="dayHasSchedule('{{ $dayKey }}') ? 'bg-indigo-100' : 'bg-gray-100'">
                        <span class="text-xs font-bold"
                              :class="dayHasSchedule('{{ $dayKey }}') ? 'text-indigo-600' : 'text-gray-400'">{{ strtoupper(substr($dayKey, 0, 2)) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $dayLabel }}</p>
                        <p class="text-xs text-gray-400" x-text="countShifts('{{ $dayKey }}') + ' {{ app()->getLocale() === 'ar' ? 'فترة' : 'shift(s)' }}'"></p>
                    </div>
                </div>
                <button type="button" @click="addShift('{{ $dayKey }}')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    {{ app()->getLocale() === 'ar' ? 'إضافة فترة' : 'Add shift' }}
                </button>
            </div>

            {{-- Shifts --}}
            <template x-for="(shift, idx) in getShifts('{{ $dayKey }}')" :key="shift.id">
                <div class="flex items-center gap-3 px-6 py-3 border-b border-gray-50 last:border-0">
                    <input type="hidden" :name="'schedules['+shift.idx+'][day]'" value="{{ $dayKey }}">

                    <div class="flex-1 grid grid-cols-3 gap-3">
                        {{-- Start Time --}}
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-400 mb-1">{{ app()->getLocale() === 'ar' ? 'من' : 'From' }}</label>
                            <select :name="'schedules['+shift.idx+'][start_time]'" x-model="shift.start_time" required
                                    class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <template x-for="t in timeOptions" :key="t.val">
                                    <option :value="t.val" x-text="t.label" :selected="shift.start_time === t.val"></option>
                                </template>
                            </select>
                        </div>

                        {{-- End Time --}}
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-400 mb-1">{{ app()->getLocale() === 'ar' ? 'إلى' : 'To' }}</label>
                            <select :name="'schedules['+shift.idx+'][end_time]'" x-model="shift.end_time" required
                                    class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <template x-for="t in timeOptions" :key="'e'+t.val">
                                    <option :value="t.val" x-text="t.label" :selected="shift.end_time === t.val"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Slot Duration --}}
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-400 mb-1">{{ app()->getLocale() === 'ar' ? 'مدة الكشف' : 'Slot' }}</label>
                            <select :name="'schedules['+shift.idx+'][slot_duration]'" x-model="shift.slot_duration" required
                                    class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="15">15 {{ app()->getLocale() === 'ar' ? 'د' : 'min' }}</option>
                                <option value="20">20 {{ app()->getLocale() === 'ar' ? 'د' : 'min' }}</option>
                                <option value="30">30 {{ app()->getLocale() === 'ar' ? 'د' : 'min' }}</option>
                                <option value="45">45 {{ app()->getLocale() === 'ar' ? 'د' : 'min' }}</option>
                                <option value="60">60 {{ app()->getLocale() === 'ar' ? 'د' : 'min' }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Delete --}}
                    <button type="button" @click="removeShift(shift.id)" class="p-2 text-gray-300 hover:text-red-500 transition shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </template>

            {{-- Empty state --}}
            <template x-if="countShifts('{{ $dayKey }}') === 0">
                <div class="px-6 py-4 text-center">
                    <p class="text-xs text-gray-300">{{ app()->getLocale() === 'ar' ? 'لا يوجد فترات عمل — اليوم إجازة' : 'No shifts — day off' }}</p>
                </div>
            </template>
        </div>
        @endforeach

        <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ __('app.save') }}
        </button>
    </form>

    <script>
    function scheduleManager() {
        // Generate time options (every 15 min)
        let timeOptions = [];
        for (let h = 0; h < 24; h++) {
            for (let m = 0; m < 60; m += 15) {
                let hh = String(h).padStart(2, '0');
                let mm = String(m).padStart(2, '0');
                let val = hh + ':' + mm;
                let h12 = h === 0 ? 12 : (h > 12 ? h - 12 : h);
                let ampm = h < 12 ? 'AM' : 'PM';
                timeOptions.push({ val, label: h12 + ':' + mm + ' ' + ampm });
            }
        }

        // Load existing schedules
        let existing = @php
            echo json_encode($schedules->map(function($s) {
                return [
                    'day' => $s->day,
                    'start_time' => \Carbon\Carbon::parse($s->start_time)->format('H:i'),
                    'end_time' => \Carbon\Carbon::parse($s->end_time)->format('H:i'),
                    'slot_duration' => (string) $s->slot_duration,
                ];
            })->values());
        @endphp;

        let nextId = 1;
        let shifts = existing.map((s, i) => ({ ...s, id: nextId++, idx: i }));

        return {
            shifts,
            timeOptions,
            getShifts(day) {
                return this.shifts.filter(s => s.day === day);
            },
            countShifts(day) {
                return this.shifts.filter(s => s.day === day).length;
            },
            dayHasSchedule(day) {
                return this.shifts.some(s => s.day === day);
            },
            addShift(day) {
                this.shifts.push({
                    id: nextId++,
                    idx: this.shifts.length,
                    day: day,
                    start_time: '09:00',
                    end_time: '17:00',
                    slot_duration: '30',
                });
                this.reindex();
            },
            removeShift(id) {
                this.shifts = this.shifts.filter(s => s.id !== id);
                this.reindex();
            },
            reindex() {
                this.shifts.forEach((s, i) => s.idx = i);
            }
        };
    }
    </script>
</x-dashboard-layout>
