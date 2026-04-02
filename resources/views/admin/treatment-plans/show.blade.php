@php $dp = auth()->user()->role === 'doctor' && request()->is('doctor/*') ? 'doctor' : 'dashboard'; @endphp
<x-dashboard-layout>
    <x-slot name="title">{{ $plan->title }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.treatment_plan') }}</x-slot>

    <x-slot name="sidebar">
        @if(auth()->user()->role === 'doctor' && request()->is('doctor/*'))
            @include('partials.doctor-sidebar', ['active' => 'appointments'])
        @else
            @include('partials.dashboard-sidebar', ['active' => 'patients'])
        @endif
    </x-slot>

    @php
        $progress = $plan->progressPercentage();
        $done = $plan->items->where('status', 'completed')->count();
        $total = $plan->items->whereNotIn('status', ['cancelled'])->count();
        $statusColors = [
            'draft' => 'bg-gray-50 text-gray-600 border-gray-200',
            'presented' => 'bg-blue-50 text-blue-700 border-blue-200',
            'accepted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'rejected' => 'bg-red-50 text-red-700 border-red-200',
            'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
            'completed' => 'bg-green-50 text-green-700 border-green-200',
        ];
        $itemStatusColors = [
            'pending' => 'bg-gray-50 text-gray-600 border-gray-200',
            'scheduled' => 'bg-blue-50 text-blue-600 border-blue-200',
            'completed' => 'bg-green-50 text-green-700 border-green-200',
            'cancelled' => 'bg-red-50 text-red-600 border-red-200',
        ];
    @endphp

    {{-- Back + Success --}}
    <div class="mb-6 flex items-center justify-between gap-4 flex-wrap">
        <a href="{{ route($dp === 'doctor' ? 'doctor.treatment-plans.index' : 'dashboard.patients.treatment-plans.index', $plan->patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.treatment_plans') }}
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Header Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $plan->title }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $plan->patient->name }} &middot; {{ $plan->doctor->name }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg border {{ $statusColors[$plan->status] }}">
                        {{ __('app.plan_status_' . $plan->status) }}
                    </span>
                </div>

                {{-- Progress Bar --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-700">{{ __('app.plan_progress') }}</span>
                        <span class="text-sm font-bold text-indigo-600">{{ $progress }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="{{ $progress === 100 ? 'bg-green-500' : 'bg-indigo-500' }} h-full rounded-full transition-all" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">{{ str_replace([':done', ':total'], [$done, $total], __('app.items_completed')) }}</p>
                </div>

                @if($plan->notes)
                <div class="bg-gray-50 rounded-xl px-4 py-3">
                    <p class="text-sm text-gray-600">{{ $plan->notes }}</p>
                </div>
                @endif
            </div>

            {{-- Items Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">{{ __('app.procedure') }}</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($plan->items as $item)
                    <div class="px-6 py-4 flex items-center gap-4 {{ $item->status === 'completed' ? 'bg-green-50/30' : '' }}">
                        {{-- Tooth Badge --}}
                        <div class="shrink-0">
                            @if($item->tooth_number)
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                                <span class="text-sm font-black text-indigo-600">#{{ $item->tooth_number }}</span>
                            </div>
                            @else
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 {{ $item->status === 'completed' ? 'line-through text-gray-500' : '' }}">{{ $item->description }}</p>
                            @if($item->notes)
                            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $item->notes }}</p>
                            @endif
                        </div>

                        {{-- Cost --}}
                        <div class="text-end shrink-0">
                            <p class="text-sm font-bold text-gray-900">{{ number_format($item->estimated_cost, 0) }}</p>
                        </div>

                        {{-- Status + Action --}}
                        <div class="shrink-0 flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $itemStatusColors[$item->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ __('app.plan_status_' . ($item->status === 'pending' ? 'draft' : $item->status)) }}
                            </span>
                            @if($item->status === 'pending' && in_array($plan->status, ['accepted', 'in_progress']))
                            <form method="POST" action="{{ route($dp === 'doctor' ? 'doctor.treatment-plans.items.complete' : 'dashboard.treatment-plans.items.complete', [$plan, $item]) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 flex items-center justify-center transition-colors" title="{{ __('app.mark_completed') }}">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Financial Summary --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.estimated_total') }}</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.estimated_total') }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($plan->estimated_total, 0) }}</span>
                    </div>
                    @if($plan->discount > 0)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.discount') }}</span>
                        <span class="text-sm font-bold text-red-600">-{{ number_format($plan->discount, 0) }}</span>
                    </div>
                    @endif
                    <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-700">{{ __('app.net_total') }}</span>
                        <span class="text-lg font-black text-indigo-600">{{ number_format($plan->netTotal(), 0) }}</span>
                    </div>
                </div>
            </div>

            {{-- Status Actions --}}
            @if(auth()->user()->hasPermission('patients.edit'))
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.actions') }}</h3>
                <div class="space-y-2">
                    @php $transitions = \App\Models\TreatmentPlan::allowedTransitions()[$plan->status] ?? []; @endphp

                    @foreach($transitions as $transition)
                    @php
                        $btnStyles = [
                            'presented' => 'text-blue-700 bg-blue-50 border-blue-200 hover:bg-blue-100',
                            'accepted' => 'text-emerald-700 bg-emerald-50 border-emerald-200 hover:bg-emerald-100',
                            'rejected' => 'text-red-700 bg-red-50 border-red-200 hover:bg-red-100',
                            'in_progress' => 'text-amber-700 bg-amber-50 border-amber-200 hover:bg-amber-100',
                            'completed' => 'text-green-700 bg-green-50 border-green-200 hover:bg-green-100',
                            'draft' => 'text-gray-700 bg-gray-50 border-gray-200 hover:bg-gray-100',
                        ];
                        $labels = [
                            'presented' => __('app.present_to_patient'),
                            'accepted' => __('app.accept_plan'),
                            'rejected' => __('app.reject_plan'),
                            'in_progress' => __('app.start_treatment'),
                            'completed' => __('app.mark_completed'),
                            'draft' => __('app.back_to_draft'),
                        ];
                    @endphp
                    <form method="POST" action="{{ route($dp === 'doctor' ? 'doctor.treatment-plans.status' : 'dashboard.treatment-plans.status', $plan) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $transition }}">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold border rounded-xl transition-all {{ $btnStyles[$transition] ?? 'text-gray-700 bg-gray-50 border-gray-200' }}">
                            {{ $labels[$transition] ?? $transition }}
                        </button>
                    </form>
                    @endforeach

                    @if(in_array($plan->status, ['draft', 'rejected']))
                    <form method="POST" action="{{ route($dp === 'doctor' ? 'doctor.treatment-plans.show' : 'dashboard.treatment-plans.destroy', $plan) }}" onsubmit="return confirm('{{ __('app.confirm_delete_plan') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            {{ __('app.delete') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif

            {{-- Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.details') }}</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">{{ __('app.created_at') }}</span>
                        <span class="font-medium text-gray-900">{{ $plan->created_at->format('d M Y') }}</span>
                    </div>
                    @if($plan->presented_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">{{ __('app.plan_status_presented') }}</span>
                        <span class="font-medium text-gray-900">{{ $plan->presented_at->format('d M Y') }}</span>
                    </div>
                    @endif
                    @if($plan->accepted_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">{{ __('app.plan_status_accepted') }}</span>
                        <span class="font-medium text-gray-900">{{ $plan->accepted_at->format('d M Y') }}</span>
                    </div>
                    @endif
                    @if($plan->completed_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">{{ __('app.plan_status_completed') }}</span>
                        <span class="font-medium text-gray-900">{{ $plan->completed_at->format('d M Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
