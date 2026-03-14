<x-dashboard-layout>
    <x-slot name="title">{{ __('app.waiting_queue') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.waiting_queue') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.doctor-sidebar', ['active' => 'queue'])
    </x-slot>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.waiting_queue') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.today') }} &mdash; {{ now()->format('l, F j') }}</p>
        </div>
    </div>

    {{-- Current Patient --}}
    @if($currentPatient)
    <div class="mb-6 bg-emerald-50 border-2 border-emerald-300 rounded-2xl p-6">
        <div class="flex items-center gap-2 mb-3">
            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
            <h3 class="text-sm font-bold text-emerald-700 uppercase tracking-wider">{{ __('app.current_patient') }}</h3>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-emerald-200 flex items-center justify-center">
                    <span class="text-emerald-800 font-bold text-xl">{{ $currentPatient->queue_number }}</span>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $currentPatient->patient->name ?? '-' }}</p>
                    <p class="text-sm text-gray-500">{{ $currentPatient->patient->phone ?? '' }}</p>
                </div>
            </div>
            <a href="{{ route('doctor.appointment.show', $currentPatient) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                {{ __('app.view_details') }}
            </a>
        </div>
    </div>
    @endif

    {{-- Called Patient --}}
    @if($calledPatient)
    <div class="mb-6 bg-blue-50 border-2 border-blue-300 rounded-2xl p-6">
        <div class="flex items-center gap-2 mb-3">
            <span class="w-3 h-3 rounded-full bg-blue-500 animate-pulse"></span>
            <h3 class="text-sm font-bold text-blue-700 uppercase tracking-wider">{{ __('app.called_patient') }}</h3>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-blue-200 flex items-center justify-center">
                    <span class="text-blue-800 font-bold text-xl">{{ $calledPatient->queue_number }}</span>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $calledPatient->patient->name ?? '-' }}</p>
                    <p class="text-sm text-gray-500">{{ $calledPatient->patient->phone ?? '' }}</p>
                    <p class="text-xs text-blue-600 mt-0.5">{{ __('app.called_at') }}: {{ $calledPatient->called_at?->format('h:i A') }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('doctor.appointment.start-from-queue', $calledPatient) }}">
                @csrf @method('PATCH')
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('app.start_examination') }}
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Waiting List --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-900">{{ __('app.waiting_patients') }} ({{ $waitingPatients->count() }})</h3>
        </div>

        @if($waitingPatients->count() > 0)
        <div class="divide-y divide-gray-100">
            @foreach($waitingPatients as $appointment)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <span class="text-amber-800 font-bold text-sm">{{ $appointment->queue_number }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $appointment->patient->name ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $appointment->patient->phone ?? '' }} &mdash; {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                        @if($appointment->checked_in_at)
                        <p class="text-[10px] text-gray-400">{{ __('app.waiting_since') }}: {{ $appointment->checked_in_at->format('h:i A') }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('doctor.appointment.call', $appointment) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            {{ __('app.call') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('doctor.appointment.start-from-queue', $appointment) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('app.start') }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-6 py-16 text-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-gray-500 font-medium">{{ __('app.no_waiting_patients') }}</p>
            </div>
        </div>
        @endif
    </div>
</x-dashboard-layout>
