@php $dp = auth()->user()->role === 'doctor' && request()->is('doctor/*') ? 'doctor' : 'dashboard'; @endphp
<x-dashboard-layout>
    <x-slot name="title">{{ __('app.treatment_plans') }} - {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.treatment_plans') }}</x-slot>

    <x-slot name="sidebar">
        @if(auth()->user()->role === 'doctor' && request()->is('doctor/*'))
            @include('partials.doctor-sidebar', ['active' => 'appointments'])
        @else
            @include('partials.dashboard-sidebar', ['active' => 'patients'])
        @endif
    </x-slot>

    <div class="mb-6 flex items-center justify-between gap-4 flex-wrap">
        <a href="{{ $dp === 'doctor' ? route('doctor.patient.history', $patient) : route('dashboard.patients.show', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ $patient->name }}
        </a>
        @if(auth()->user()->hasPermission('patients.edit'))
        <a href="{{ route($dp === 'doctor' ? 'doctor.treatment-plans.create' : 'dashboard.patients.treatment-plans.create', $patient) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.create_treatment_plan') }}
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if($plans->count())
    <div class="space-y-4">
        @foreach($plans as $plan)
        @php
            $progress = $plan->progressPercentage();
            $statusColors = [
                'draft' => 'bg-gray-50 text-gray-600 border-gray-200',
                'presented' => 'bg-blue-50 text-blue-700 border-blue-200',
                'accepted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'rejected' => 'bg-red-50 text-red-700 border-red-200',
                'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                'completed' => 'bg-green-50 text-green-700 border-green-200',
            ];
            $progressColor = $progress === 100 ? 'bg-green-500' : ($progress > 0 ? 'bg-indigo-500' : 'bg-gray-300');
            $done = $plan->items->where('status', 'completed')->count();
            $total = $plan->items->whereNotIn('status', ['cancelled'])->count();
        @endphp
        <a href="{{ route($dp === 'doctor' ? 'doctor.treatment-plans.show' : 'dashboard.treatment-plans.show', $plan) }}" class="block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all p-5">
            <div class="flex items-start justify-between gap-4 mb-3">
                <div class="min-w-0 flex-1">
                    <h3 class="text-base font-bold text-gray-900 truncate">{{ $plan->title }}</h3>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $plan->doctor->name }} &middot; {{ $plan->created_at->format('d M Y') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-lg border shrink-0 {{ $statusColors[$plan->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                    {{ __('app.plan_status_' . $plan->status) }}
                </span>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs text-gray-400 font-medium">{{ __('app.plan_progress') }}</span>
                        <span class="text-xs font-bold text-gray-600">{{ $done }}/{{ $total }}</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="{{ $progressColor }} h-full rounded-full transition-all" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
                <div class="text-end shrink-0">
                    <p class="text-lg font-black text-gray-900">{{ number_format($plan->netTotal(), 0) }}</p>
                    <p class="text-[10px] text-gray-400 font-medium">{{ __('app.estimated_total') }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        <p class="text-gray-500 font-medium">{{ __('app.no_treatment_plans') }}</p>
    </div>
    @endif
</x-dashboard-layout>
