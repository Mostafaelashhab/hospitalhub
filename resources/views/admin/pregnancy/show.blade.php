<x-dashboard-layout>
    <x-slot name="title">{{ __('app.pregnancy_tracker') }} — {{ $pregnancy->patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.pregnancy_tracker') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    @php
        $isActive   = $pregnancy->status === 'active';
        $week       = $pregnancy->currentWeek();
        $trimester  = $pregnancy->trimester();
        $daysLeft   = $pregnancy->daysRemaining();
        $progress   = $pregnancy->progressPercentage();

        // SVG circle progress
        $radius     = 54;
        $circumf    = 2 * pi() * $radius;
        $dashOffset = $circumf - ($progress / 100) * $circumf;

        // Baby size by week
        $babySizes = [
            4 => ['🌱', 'Poppy seed'], 5 => ['🫐', 'Apple seed'], 6 => ['🫐', 'Blueberry'],
            7 => ['🫐', 'Blueberry'], 8 => ['🫑', 'Kidney bean'], 9 => ['🍇', 'Grape'],
            10 => ['🫒', 'Kumquat'], 11 => ['🌶️', 'Fig'], 12 => ['🍋', 'Lime'],
            13 => ['🍑', 'Peach'], 14 => ['🍋', 'Lemon'], 15 => ['🍎', 'Apple'],
            16 => ['🥑', 'Avocado'], 17 => ['🍐', 'Pear'], 18 => ['🫑', 'Bell pepper'],
            19 => ['🥭', 'Mango'], 20 => ['🍌', 'Banana'], 21 => ['🥕', 'Carrot'],
            22 => ['🌽', 'Corn'], 23 => ['🍆', 'Eggplant'], 24 => ['🌽', 'Corn'],
            25 => ['🥦', 'Cauliflower'], 26 => ['🥬', 'Lettuce'], 27 => ['🥦', 'Broccoli'],
            28 => ['🍆', 'Eggplant'], 29 => ['🎃', 'Butternut squash'], 30 => ['🥥', 'Coconut'],
            31 => ['🥥', 'Coconut'], 32 => ['🎃', 'Jicama'], 33 => ['🍍', 'Pineapple'],
            34 => ['🍍', 'Pineapple'], 35 => ['🍈', 'Honeydew'], 36 => ['🥗', 'Head of lettuce'],
            37 => ['🍉', 'Small watermelon'], 38 => ['🎃', 'Pumpkin'], 39 => ['🎃', 'Pumpkin'],
            40 => ['🎃', 'Watermelon'],
        ];
        $weekClamped = max(4, min(40, $week));
        $sizeInfo = $babySizes[$weekClamped] ?? ['🤱', ''];

        // Milestones
        $milestones = [
            ['week' => 4,  'label' => 'Positive test'],
            ['week' => 8,  'label' => 'First heartbeat'],
            ['week' => 12, 'label' => '1st Trimester / NT Scan'],
            ['week' => 16, 'label' => 'Gender visible'],
            ['week' => 20, 'label' => 'Anatomy scan'],
            ['week' => 24, 'label' => 'Viability milestone'],
            ['week' => 28, 'label' => '3rd Trimester'],
            ['week' => 32, 'label' => 'Baby positioning'],
            ['week' => 36, 'label' => 'Full term soon'],
            ['week' => 37, 'label' => 'Early term'],
            ['week' => 40, 'label' => 'Due date'],
        ];
    @endphp

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
        <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-4' : 'pl-4' }} space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Back --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.patients.pregnancy.index', $pregnancy->patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.pregnancies') }}
        </a>
    </div>

    {{-- ===== HERO PROGRESS SECTION ===== --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-pink-600 via-rose-500 to-pink-700 rounded-3xl shadow-2xl shadow-pink-500/30 mb-8 p-8">
        {{-- Background decoration --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/5 rounded-full"></div>
            <div class="absolute -bottom-8 -left-8 w-48 h-48 bg-white/5 rounded-full"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white/3 rounded-full"></div>
        </div>

        <div class="relative grid grid-cols-1 md:grid-cols-3 gap-8 items-center">

            {{-- Left: Circular Progress --}}
            <div class="flex flex-col items-center">
                <div class="relative w-36 h-36">
                    <svg class="w-36 h-36 -rotate-90" viewBox="0 0 128 128">
                        <circle cx="64" cy="64" r="{{ $radius }}" stroke="rgba(255,255,255,0.15)" stroke-width="10" fill="none"/>
                        <circle cx="64" cy="64" r="{{ $radius }}"
                                stroke="white"
                                stroke-width="10"
                                fill="none"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $circumf }}"
                                stroke-dashoffset="{{ $dashOffset }}"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-black text-white">{{ $week }}</span>
                        <span class="text-xs text-pink-200 font-semibold">/40 wks</span>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <p class="text-white/70 text-xs uppercase tracking-widest font-semibold">{{ __('app.current_week') }}</p>
                    @if($isActive)
                    <p class="text-white text-sm font-bold mt-0.5">{{ $progress }}% {{ __('app.complete') ?? 'complete' }}</p>
                    @else
                    <p class="text-white text-sm font-bold mt-0.5 capitalize">{{ $pregnancy->status }}</p>
                    @endif
                </div>
            </div>

            {{-- Middle: Key Dates --}}
            <div class="text-center space-y-4">
                {{-- Baby size --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 rounded-full backdrop-blur-sm">
                    <span class="text-2xl">{{ $sizeInfo[0] }}</span>
                    <span class="text-white/90 text-sm font-semibold">{{ $sizeInfo[1] }}</span>
                </div>

                {{-- EDD --}}
                <div>
                    <p class="text-pink-200 text-xs uppercase tracking-widest font-semibold">{{ __('app.edd_date') }}</p>
                    <p class="text-white text-2xl font-black mt-0.5">{{ $pregnancy->edd_date->format('d M Y') }}</p>
                </div>

                @if($isActive && $daysLeft > 0)
                {{-- Days countdown --}}
                <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 rounded-2xl backdrop-blur-sm border border-white/20">
                    <div class="text-center">
                        <span class="text-white text-2xl font-black block">{{ $daysLeft }}</span>
                        <span class="text-pink-200 text-xs font-semibold">{{ __('app.days_remaining') }}</span>
                    </div>
                </div>
                @elseif($pregnancy->status === 'delivered')
                <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 rounded-2xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-white font-bold text-sm">{{ __('app.delivered') ?? 'Delivered' }} {{ $pregnancy->delivery_date?->format('d M Y') }}</span>
                </div>
                @endif
            </div>

            {{-- Right: Trimester Indicator --}}
            <div>
                <p class="text-pink-200 text-xs uppercase tracking-widest font-semibold text-center mb-4">{{ __('app.trimester') }}</p>
                <div class="space-y-3">
                    @foreach([1 => ['Wk 1–12', 'from-pink-300 to-rose-400'], 2 => ['Wk 13–27', 'from-rose-400 to-pink-500'], 3 => ['Wk 28–40', 'from-pink-500 to-rose-600']] as $t => [$label, $grad])
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black {{ $trimester === $t ? 'bg-white text-pink-600 shadow-lg' : 'bg-white/20 text-white/70' }}">
                            {{ $t }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold {{ $trimester === $t ? 'text-white' : 'text-white/60' }}">
                                    @if($t === 1) {{ __('app.trimester') }} 1 @elseif($t === 2) {{ __('app.trimester') }} 2 @else {{ __('app.trimester') }} 3 @endif
                                </span>
                                <span class="text-xs {{ $trimester === $t ? 'text-pink-200' : 'text-white/40' }}">{{ $label }}</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-white/10 overflow-hidden">
                                @if($trimester > $t)
                                <div class="h-full rounded-full bg-white/80"></div>
                                @elseif($trimester === $t)
                                @php
                                    $triProgress = $t === 1 ? min(100, ($week / 12) * 100) : ($t === 2 ? min(100, (($week - 12) / 15) * 100) : min(100, (($week - 27) / 13) * 100));
                                @endphp
                                <div class="h-full rounded-full bg-white" style="width: {{ $triProgress }}%"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MILESTONES TIMELINE ===== --}}
    @if($isActive || $pregnancy->status === 'delivered')
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <h2 class="text-base font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Pregnancy Milestones
        </h2>

        <div class="relative">
            {{-- Connector Line --}}
            <div class="absolute top-5 left-5 right-5 h-0.5 bg-gray-100 hidden sm:block" style="z-index:0"></div>

            <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-11 gap-2">
                @foreach($milestones as $milestone)
                @php
                    $mWeek = $milestone['week'];
                    $passed  = $week > $mWeek;
                    $current = $week === $mWeek || ($week > $mWeek - 2 && $week <= $mWeek);
                    $upcoming = $week < $mWeek - 1;
                @endphp
                <div class="flex flex-col items-center relative" style="z-index:1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all
                        @if($passed) bg-emerald-500 border-emerald-500 text-white
                        @elseif($current) bg-pink-500 border-pink-500 text-white shadow-lg shadow-pink-400/40 scale-110
                        @else bg-white border-gray-200 text-gray-400 @endif">
                        {{ $mWeek }}
                    </div>
                    <p class="text-center mt-2 text-xs leading-tight
                        @if($passed) text-emerald-600 font-medium
                        @elseif($current) text-pink-600 font-bold
                        @else text-gray-400 @endif">
                        {{ $milestone['label'] }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== MAIN COLUMN: Visits ===== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Add Visit --}}
            @if($isActive && auth()->user()->hasPermission('patients.edit'))
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{ open: {{ $pregnancy->visits->isEmpty() ? 'true' : 'false' }} }">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('app.visit_added') ?? 'Add Visit' }}
                    </h2>
                    <button @click="open = !open"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-pink-50 text-pink-700 border border-pink-200 hover:bg-pink-100 transition-all">
                        <svg class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span x-text="open ? '{{ __('app.cancel') }}' : '{{ __('app.add') ?? 'Add' }}'"></span>
                    </button>
                </div>

                <div x-show="open" x-transition x-cloak class="px-6 py-6 bg-pink-50/30">
                    <form action="{{ route('dashboard.pregnancy.visit', $pregnancy) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.date') ?? 'Visit Date' }} <span class="text-red-500">*</span></label>
                                <input type="date" name="visit_date" required value="{{ date('Y-m-d') }}"
                                       class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.weight') }} (kg)</label>
                                <input type="number" step="0.1" name="weight" placeholder="65.5"
                                       class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.blood_pressure_systolic') }} (mmHg)</label>
                                <input type="number" name="blood_pressure_systolic" placeholder="120"
                                       class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.blood_pressure_diastolic') }} (mmHg)</label>
                                <input type="number" name="blood_pressure_diastolic" placeholder="80"
                                       class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.fundal_height') }} (cm)</label>
                                <input type="number" step="0.1" name="fundal_height" placeholder="24.0"
                                       class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.fetal_heart_rate') }} (bpm)</label>
                                <input type="number" name="fetal_heart_rate" placeholder="140"
                                       class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.presentation') }}</label>
                                <select name="presentation" class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500">
                                    <option value="">—</option>
                                    <option value="cephalic">{{ __('app.cephalic') }}</option>
                                    <option value="breech">{{ __('app.breech') }}</option>
                                    <option value="transverse">{{ __('app.transverse') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.next_visit') }}</label>
                                <input type="date" name="next_visit_date"
                                       class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.notes') }}</label>
                            <textarea name="notes" rows="3"
                                      class="w-full rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500 resize-none"
                                      placeholder="{{ __('app.notes') }}..."></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-pink-500 to-rose-500 rounded-xl hover:from-pink-600 hover:to-rose-600 transition-all shadow-md shadow-pink-500/25">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('app.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- Visit Records --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        {{ __('app.visits') ?? 'Visit Records' }}
                        <span class="text-xs font-normal text-gray-400">({{ $pregnancy->visits->count() }})</span>
                    </h2>
                </div>

                @if($pregnancy->visits->isEmpty())
                <div class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-sm text-gray-400">{{ __('app.no_visits_recorded') ?? 'No visits recorded yet' }}</p>
                </div>
                @else
                <div class="divide-y divide-gray-50">
                    @foreach($pregnancy->visits->sortByDesc('visit_date') as $visit)
                    <div class="px-6 py-5 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100 flex items-center justify-center">
                                    <span class="text-xs font-black text-indigo-600">W{{ $visit->gestational_week }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $visit->visit_date->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ __('app.gestational_week') }} {{ $visit->gestational_week }}</p>
                                </div>
                            </div>
                            @if($visit->next_visit_date)
                            <div class="text-right">
                                <p class="text-xs text-gray-400">{{ __('app.next_visit') }}</p>
                                <p class="text-xs font-semibold text-emerald-600">{{ $visit->next_visit_date->format('d M Y') }}</p>
                            </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                            @if($visit->weight)
                            <div class="bg-blue-50 rounded-xl px-3 py-2">
                                <p class="text-xs text-blue-400 font-medium mb-0.5">{{ __('app.weight') }}</p>
                                <p class="text-sm font-bold text-blue-700">{{ $visit->weight }} kg</p>
                            </div>
                            @endif
                            @if($visit->blood_pressure_systolic && $visit->blood_pressure_diastolic)
                            <div class="bg-rose-50 rounded-xl px-3 py-2">
                                <p class="text-xs text-rose-400 font-medium mb-0.5">{{ __('app.blood_pressure') ?? 'BP' }}</p>
                                <p class="text-sm font-bold text-rose-700" dir="ltr">{{ $visit->blood_pressure_systolic }}/{{ $visit->blood_pressure_diastolic }}</p>
                            </div>
                            @endif
                            @if($visit->fundal_height)
                            <div class="bg-violet-50 rounded-xl px-3 py-2">
                                <p class="text-xs text-violet-400 font-medium mb-0.5">{{ __('app.fundal_height') }}</p>
                                <p class="text-sm font-bold text-violet-700">{{ $visit->fundal_height }} cm</p>
                            </div>
                            @endif
                            @if($visit->fetal_heart_rate)
                            <div class="bg-pink-50 rounded-xl px-3 py-2">
                                <p class="text-xs text-pink-400 font-medium mb-0.5">{{ __('app.fetal_heart_rate') }}</p>
                                <p class="text-sm font-bold text-pink-700">{{ $visit->fetal_heart_rate }} bpm</p>
                            </div>
                            @endif
                        </div>

                        @if($visit->presentation || $visit->notes)
                        <div class="mt-3 space-y-1">
                            @if($visit->presentation)
                            <p class="text-xs text-gray-500">
                                <span class="font-semibold">{{ __('app.presentation') }}:</span>
                                {{ __('app.' . $visit->presentation) }}
                            </p>
                            @endif
                            @if($visit->notes)
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $visit->notes }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Mark as Delivered --}}
            @if($isActive && auth()->user()->hasPermission('patients.edit'))
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden"
                 x-data="{ open: false }">
                <div class="px-6 py-5 border-b border-blue-100 flex items-center justify-between bg-blue-50/50">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('app.mark_delivered') }}
                    </h2>
                    <button @click="open = !open"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-sm">
                        {{ __('app.mark_delivered') }}
                    </button>
                </div>

                <div x-show="open" x-transition x-cloak class="px-6 py-6">
                    <form action="{{ route('dashboard.pregnancy.complete', $pregnancy) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.delivery_date') }} <span class="text-red-500">*</span></label>
                                <input type="date" name="delivery_date" required value="{{ date('Y-m-d') }}"
                                       class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.delivery_type') }} <span class="text-red-500">*</span></label>
                                <select name="delivery_type" required class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">—</option>
                                    <option value="normal">{{ __('app.normal_delivery') }}</option>
                                    <option value="cesarean">{{ __('app.cesarean') }}</option>
                                    <option value="assisted">{{ __('app.assisted_delivery') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.baby_gender') }}</label>
                                <select name="baby_gender" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">—</option>
                                    <option value="male">{{ __('app.male') }}</option>
                                    <option value="female">{{ __('app.female') }}</option>
                                    <option value="unknown">{{ __('app.unknown') ?? 'Unknown' }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.baby_weight') }} (kg)</label>
                                <input type="number" step="0.01" name="baby_weight" placeholder="3.20"
                                       class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500" dir="ltr">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.notes') }}</label>
                            <textarea name="notes" rows="2"
                                      class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 resize-none"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('app.confirm') ?? 'Confirm' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- Delivery Summary (if delivered) --}}
            @if($pregnancy->status === 'delivered')
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="text-base font-bold text-gray-900">{{ __('app.pregnancy_completed') }}</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                    @if($pregnancy->delivery_date)
                    <div class="bg-white/70 rounded-xl p-3">
                        <p class="text-xs text-blue-400 font-medium mb-1">{{ __('app.delivery_date') }}</p>
                        <p class="text-sm font-bold text-blue-800">{{ $pregnancy->delivery_date->format('d M Y') }}</p>
                    </div>
                    @endif
                    @if($pregnancy->delivery_type)
                    <div class="bg-white/70 rounded-xl p-3">
                        <p class="text-xs text-blue-400 font-medium mb-1">{{ __('app.delivery_type') }}</p>
                        <p class="text-sm font-bold text-blue-800">{{ __('app.' . $pregnancy->delivery_type . '_delivery') ?? $pregnancy->delivery_type }}</p>
                    </div>
                    @endif
                    @if($pregnancy->baby_gender)
                    <div class="bg-white/70 rounded-xl p-3">
                        <p class="text-xs text-blue-400 font-medium mb-1">{{ __('app.baby_gender') }}</p>
                        <p class="text-sm font-bold text-blue-800 capitalize">{{ __('app.' . $pregnancy->baby_gender) }}</p>
                    </div>
                    @endif
                    @if($pregnancy->baby_weight)
                    <div class="bg-white/70 rounded-xl p-3">
                        <p class="text-xs text-blue-400 font-medium mb-1">{{ __('app.baby_weight') }}</p>
                        <p class="text-sm font-bold text-blue-800">{{ $pregnancy->baby_weight }} kg</p>
                    </div>
                    @endif
                </div>
                @if($pregnancy->notes)
                <p class="mt-4 text-sm text-blue-700 leading-relaxed">{{ $pregnancy->notes }}</p>
                @endif
            </div>
            @endif

        </div>

        {{-- ===== SIDEBAR ===== --}}
        <div class="space-y-5">

            {{-- Quick Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('app.info') ?? 'Quick Info' }}
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-xs text-gray-500">{{ __('app.lmp_date') }}</span>
                        <span class="text-xs font-bold text-gray-900" dir="ltr">{{ $pregnancy->lmp_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-xs text-gray-500">{{ __('app.edd_date') }}</span>
                        <span class="text-xs font-bold text-pink-600" dir="ltr">{{ $pregnancy->edd_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-xs text-gray-500">{{ __('app.current_week') }}</span>
                        <span class="text-xs font-bold text-gray-900">{{ $week }} / 40</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-xs text-gray-500">{{ __('app.blood_type') }}</span>
                        <span class="text-xs font-bold text-gray-900">{{ $pregnancy->patient->blood_type ?? '—' }}</span>
                    </div>
                    @if($pregnancy->doctor)
                    <div class="flex items-center justify-between py-2">
                        <span class="text-xs text-gray-500">{{ __('app.doctor') }}</span>
                        <span class="text-xs font-bold text-indigo-600">{{ $pregnancy->doctor->name }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Patient Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('app.patient') }}</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center text-sm font-black text-white">
                        {{ mb_substr($pregnancy->patient->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $pregnancy->patient->name }}</p>
                        <p class="text-xs text-gray-400" dir="ltr">{{ $pregnancy->patient->phone }}</p>
                    </div>
                </div>
                <a href="{{ route('dashboard.patients.show', $pregnancy->patient) }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition-all">
                    {{ __('app.view_patient') ?? 'View Patient' }}
                </a>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('app.actions') }}</h3>
                <div class="space-y-2">
                    <a href="{{ route('dashboard.patients.pregnancy.index', $pregnancy->patient) }}"
                       class="w-full inline-flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        {{ __('app.all_pregnancies') ?? __('app.pregnancies') }}
                    </a>
                    @if(auth()->user()->hasPermission('patients.edit'))
                    <form method="POST" action="{{ route('dashboard.pregnancy.destroy', $pregnancy) }}"
                          onsubmit="return confirm('{{ __('app.confirm_delete') ?? 'Are you sure?' }}')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            {{ __('app.delete') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>

        </div>
    </div>

</x-dashboard-layout>
