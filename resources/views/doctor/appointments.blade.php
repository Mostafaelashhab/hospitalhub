<x-dashboard-layout>
    <x-slot name="title">{{ __('app.my_appointments') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.my_appointments') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.doctor-sidebar', ['active' => 'appointments'])
    </x-slot>

    @php
        $isAr = app()->getLocale() === 'ar';
        $align = $isAr ? 'right' : 'left';
        $today = now()->toDateString();
        $daysInMonth = $startOfMonth->daysInMonth;
        $firstDayOfWeek = $startOfMonth->dayOfWeek;
        $startOffset = ($firstDayOfWeek + 1) % 7;

        $prevMonth = $startOfMonth->copy()->subMonth();
        $nextMonth = $startOfMonth->copy()->addMonth();

        $dayNames = $isAr
            ? ['س','ح','ن','ث','ر','خ','ج']
            : ['Sat','Sun','Mon','Tue','Wed','Thu','Fri'];

        $monthNames = $isAr
            ? ['','يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر']
            : ['','January','February','March','April','May','June','July','August','September','October','November','December'];

        $statusStyles = [
            'scheduled' => 'bg-blue-50 text-blue-700 border-blue-200',
            'confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
            'completed' => 'bg-gray-50 text-gray-600 border-gray-200',
            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
            'no_show' => 'bg-red-50 text-red-700 border-red-200',
        ];
        $statusDots = [
            'scheduled' => 'bg-blue-500',
            'confirmed' => 'bg-emerald-500',
            'in_progress' => 'bg-amber-500',
            'completed' => 'bg-gray-400',
            'cancelled' => 'bg-red-500',
            'no_show' => 'bg-red-500',
        ];
    @endphp

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.my_appointments') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $monthNames[$month] }} {{ $year }}</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Calendar --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                {{-- Month Navigation --}}
                <div class="flex items-center justify-between mb-5">
                    <a href="?month={{ $prevMonth->month }}&year={{ $prevMonth->year }}"
                       class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-gray-100 transition-all text-gray-500">
                        <svg class="w-5 h-5 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <h3 class="text-base font-bold text-gray-900">{{ $monthNames[$month] }} {{ $year }}</h3>
                    <a href="?month={{ $nextMonth->month }}&year={{ $nextMonth->year }}"
                       class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-gray-100 transition-all text-gray-500">
                        <svg class="w-5 h-5 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Day Names --}}
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach($dayNames as $dayName)
                    <div class="text-center text-[11px] font-bold text-gray-500 py-2">{{ $dayName }}</div>
                    @endforeach
                </div>

                {{-- Calendar Days --}}
                <div class="grid grid-cols-7 gap-1">
                    @for($i = 0; $i < $startOffset; $i++)
                    <div></div>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $count = $appointmentCounts[$dateStr] ?? 0;
                        $isSelected = $selectedDate === $dateStr;
                        $isToday = $today === $dateStr;
                    @endphp
                    <a href="?month={{ $month }}&year={{ $year }}&date={{ $dateStr }}&status={{ request('status') }}"
                       class="relative flex flex-col items-center justify-center py-2 rounded-xl text-sm transition-all
                              {{ $isSelected ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : ($isToday ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-700 hover:bg-gray-50') }}">
                        <span class="font-medium">{{ $day }}</span>
                        @if($count > 0)
                        <span class="text-[9px] font-bold {{ $isSelected ? 'text-indigo-200' : 'text-indigo-500' }}">{{ $count }}</span>
                        @endif
                    </a>
                    @endfor
                </div>

                {{-- Today button --}}
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="?month={{ now()->month }}&year={{ now()->year }}&date={{ $today }}"
                       class="flex items-center justify-center gap-2 w-full py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $isAr ? 'اليوم' : 'Today' }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Day Appointments --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                {{-- Day Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat($isAr ? 'l j F' : 'l, F j') }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $dayAppointments->count() }} {{ $isAr ? 'موعد' : 'appointments' }}</p>
                    </div>
                    {{-- Status filter --}}
                    <div class="flex items-center gap-2">
                        @foreach(['scheduled' => ($isAr ? 'محجوز' : 'Scheduled'), 'confirmed' => ($isAr ? 'مؤكد' : 'Confirmed'), 'in_progress' => ($isAr ? 'جاري' : 'In Progress')] as $statusKey => $statusLabel)
                        <a href="?month={{ $month }}&year={{ $year }}&date={{ $selectedDate }}&status={{ request('status') === $statusKey ? '' : $statusKey }}"
                           class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold rounded-lg border transition-all
                                  {{ request('status') === $statusKey ? $statusStyles[$statusKey] : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50' }}">
                            {{ $statusLabel }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Appointments List --}}
                <div class="divide-y divide-gray-50">
                    @forelse($dayAppointments as $appointment)
                    @php
                        $transitions = [
                            'scheduled' => ['confirmed', 'cancelled', 'no_show'],
                            'confirmed' => ['in_progress', 'cancelled', 'no_show'],
                            'in_progress' => ['completed', 'cancelled'],
                        ];
                        $nextSteps = $transitions[$appointment->status] ?? [];
                        $quickAction = match($appointment->status) {
                            'scheduled' => 'confirmed',
                            'confirmed' => 'in_progress',
                            'in_progress' => 'completed',
                            default => null,
                        };
                        $quickLabel = match($quickAction) {
                            'confirmed' => ($isAr ? 'تأكيد' : 'Confirm'),
                            'in_progress' => ($isAr ? 'ابدأ الكشف' : 'Start'),
                            'completed' => ($isAr ? 'إنهاء' : 'Complete'),
                            default => '',
                        };
                        $quickColor = match($quickAction) {
                            'confirmed' => 'bg-emerald-600 hover:bg-emerald-700 text-white',
                            'in_progress' => 'bg-amber-500 hover:bg-amber-600 text-white',
                            'completed' => 'bg-indigo-600 hover:bg-indigo-700 text-white',
                            default => '',
                        };
                    @endphp
                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/50 transition-colors">
                        {{-- Time --}}
                        <div class="text-center shrink-0 w-16">
                            <p class="text-sm font-bold text-gray-900 font-mono" dir="ltr">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i') }}</p>
                            <p class="text-[10px] font-semibold text-gray-400 font-mono" dir="ltr">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('A') }}</p>
                        </div>

                        {{-- Status line --}}
                        <div class="w-1 h-12 rounded-full {{ $statusDots[$appointment->status] ?? 'bg-gray-300' }} shrink-0"></div>

                        {{-- Patient info --}}
                        <a href="{{ route('doctor.appointment.show', $appointment) }}" class="flex-1 min-w-0 group">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">{{ $appointment->patient->name ?? '-' }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $appointment->patient->phone ?? '' }}
                                @if($appointment->services->isNotEmpty())
                                 — {{ $appointment->services->first()->{$isAr ? 'name_ar' : 'name_en'} }}
                                @endif
                            </p>
                        </a>

                        {{-- Status badge --}}
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-lg border shrink-0 {{ $statusStyles[$appointment->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$appointment->status] ?? 'bg-gray-400' }}"></span>
                            {{ __('app.' . $appointment->status) }}
                        </span>

                        {{-- Quick Actions --}}
                        @if($quickAction)
                        <div class="flex items-center gap-1.5 shrink-0">
                            <form method="POST" action="{{ route('doctor.appointment.status', $appointment) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $quickAction }}">
                                <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $quickColor }}">
                                    {{ $quickLabel }}
                                </button>
                            </form>

                            @if(count($nextSteps) > 1)
                            @foreach($nextSteps as $nextStatus)
                                @if($nextStatus !== $quickAction)
                                <form method="POST" action="{{ route('doctor.appointment.status', $appointment) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $nextStatus }}">
                                    <button type="submit" class="px-2.5 py-1.5 text-[11px] font-semibold rounded-lg border transition-all {{ $statusStyles[$nextStatus] ?? 'bg-gray-50 text-gray-600 border-gray-200' }} hover:opacity-80">
                                        {{ __('app.' . $nextStatus) }}
                                    </button>
                                </form>
                                @endif
                            @endforeach
                            @endif
                        </div>
                        @elseif(in_array($appointment->status, ['completed', 'cancelled', 'no_show']))
                        <a href="{{ route('doctor.appointment.show', $appointment) }}" class="text-xs font-medium text-gray-400 hover:text-indigo-600 transition-colors shrink-0">
                            {{ $isAr ? 'التفاصيل' : 'Details' }}
                        </a>
                        @endif
                    </div>
                    @empty
                    <div class="px-6 py-16 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-gray-500 font-medium">{{ $isAr ? 'لا يوجد مواعيد في هذا اليوم' : 'No appointments on this day' }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
