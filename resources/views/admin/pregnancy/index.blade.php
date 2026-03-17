<x-dashboard-layout>
    <x-slot name="title">{{ __('app.pregnancy_tracker') }} — {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.pregnancy_tracker') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.patients.show', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ $patient->name }}
        </a>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-lg shadow-pink-500/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ __('app.pregnancy_tracker') }}</h1>
                <p class="text-sm text-gray-500">{{ $patient->name }}</p>
            </div>
        </div>
        @if(auth()->user()->hasPermission('patients.edit'))
        <a href="{{ route('dashboard.patients.pregnancy.create', $patient) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-500 to-rose-500 rounded-xl hover:from-pink-600 hover:to-rose-600 transition-all shadow-md shadow-pink-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.add_pregnancy') }}
        </a>
        @endif
    </div>

    @if($pregnancies->isEmpty())
    {{-- Empty state --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
        <div class="w-20 h-20 mx-auto mb-5 rounded-full bg-pink-50 flex items-center justify-center">
            <svg class="w-10 h-10 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <p class="text-lg font-semibold text-gray-700 mb-1">{{ __('app.no_pregnancies') }}</p>
        <p class="text-sm text-gray-400 mb-6">{{ $patient->name }}</p>
        @if(auth()->user()->hasPermission('patients.edit'))
        <a href="{{ route('dashboard.patients.pregnancy.create', $patient) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-pink-500 to-rose-500 rounded-xl hover:from-pink-600 hover:to-rose-600 transition-all shadow-md shadow-pink-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.add_pregnancy') }}
        </a>
        @endif
    </div>
    @else

    <div class="space-y-4">
        @foreach($pregnancies as $pregnancy)
        @php
            $isActive = $pregnancy->status === 'active';
            $week = $pregnancy->currentWeek();
            $progress = $pregnancy->progressPercentage();
            $trimester = $pregnancy->trimester();

            $statusColors = [
                'active'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'delivered'   => 'bg-blue-50 text-blue-700 border-blue-200',
                'miscarriage' => 'bg-red-50 text-red-700 border-red-200',
                'terminated'  => 'bg-gray-50 text-gray-700 border-gray-200',
            ];
        @endphp

        <div class="bg-white rounded-2xl border {{ $isActive ? 'border-pink-200 shadow-md shadow-pink-500/10' : 'border-gray-100 shadow-sm' }} overflow-hidden">
            {{-- Card Header --}}
            <div class="{{ $isActive ? 'bg-gradient-to-r from-pink-50 to-rose-50' : 'bg-gray-50/50' }} px-6 py-4 border-b {{ $isActive ? 'border-pink-100' : 'border-gray-100' }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($isActive)
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        @endif
                        <span class="text-sm font-bold {{ $isActive ? 'text-pink-800' : 'text-gray-700' }}">
                            {{ __('app.lmp_date') }}: {{ $pregnancy->lmp_date->format('Y-m-d') }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-lg border {{ $statusColors[$pregnancy->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                            {{ __('app.' . $pregnancy->status) }}
                        </span>
                    </div>
                    <a href="{{ route('dashboard.pregnancy.show', $pregnancy) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg {{ $isActive ? 'bg-pink-100 text-pink-700 hover:bg-pink-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-all">
                        {{ __('app.view') }}
                        <svg class="w-3.5 h-3.5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-5 mb-5">
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.edd_date') }}</p>
                        <p class="text-sm font-bold text-gray-900">{{ $pregnancy->edd_date->format('Y-m-d') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.current_week') }}</p>
                        <p class="text-sm font-bold {{ $isActive ? 'text-pink-600' : 'text-gray-900' }}">{{ $week }} / 40</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.trimester') }}</p>
                        <p class="text-sm font-bold text-gray-900">{{ $trimester }}{{ __('app.trimester') === 'Trimester' ? (($trimester == 1) ? 'st' : (($trimester == 2) ? 'nd' : 'rd')) : '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.days_remaining') }}</p>
                        <p class="text-sm font-bold {{ $isActive ? 'text-rose-600' : 'text-gray-900' }}">
                            @if($isActive)
                                {{ $pregnancy->daysRemaining() }}
                            @else
                                —
                            @endif
                        </p>
                    </div>
                </div>

                @if($isActive)
                {{-- Progress Bar --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs text-gray-500">{{ __('app.progress') ?? 'Progress' }}</span>
                        <span class="text-xs font-bold text-pink-600">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full bg-gradient-to-r from-pink-400 to-rose-500 transition-all duration-500"
                             style="width: {{ $progress }}%"></div>
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-between text-xs text-gray-400">
                    <span>{{ __('app.visits') ?? 'Visits' }}: <strong class="text-gray-700">{{ $pregnancy->visits->count() }}</strong></span>
                    @if($pregnancy->doctor)
                    <span>{{ __('app.doctor') }}: <strong class="text-gray-700">{{ $pregnancy->doctor->name }}</strong></span>
                    @endif
                    @if($pregnancy->status === 'delivered' && $pregnancy->delivery_date)
                    <span>{{ __('app.delivery_date') }}: <strong class="text-blue-700">{{ $pregnancy->delivery_date->format('Y-m-d') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</x-dashboard-layout>
