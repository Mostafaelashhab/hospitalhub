<x-dashboard-layout>
    <x-slot name="title">{{ __('app.ai_radiology') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.ai_radiology') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.patients.show', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    {{-- Under Maintenance --}}
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center max-w-md mx-auto">
            <div class="w-20 h-20 rounded-2xl bg-violet-50 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ __('app.under_maintenance') }}</h2>
            <p class="text-sm text-gray-500 leading-relaxed mb-6">{{ __('app.under_maintenance_desc') }}</p>
            <a href="{{ route('dashboard.patients.show', $patient) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-all">
                <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('app.back_to_patient') }}
            </a>
        </div>
    </div>
</x-dashboard-layout>
