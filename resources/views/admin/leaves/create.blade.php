<x-dashboard-layout>
    <x-slot name="title">{{ __('app.add_leave') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.add_leave') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'leaves'])
    </x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('app.add_leave') }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ __('app.doctor_leaves') }}</p>
    </div>

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

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('dashboard.leaves.store') }}" class="space-y-5">
            @csrf

            {{-- Doctor --}}
            <div x-data="{ open: false, selected: '{{ old('doctor_id', '') }}', selectedName: '{{ old('doctor_id') ? $doctors->firstWhere('id', old('doctor_id'))?->name : '' }}' }" class="relative">
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.doctors') }} <span class="text-red-500">*</span></label>
                <input type="hidden" name="doctor_id" :value="selected" required>
                <button type="button" @click="open = !open" @click.away="open = false"
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-start flex items-center justify-between focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <span :class="selected ? 'text-gray-900' : 'text-gray-400'" x-text="selected ? selectedName : '— {{ __('app.doctors') }} —'"></span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition.opacity class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                    @foreach($doctors as $doctor)
                    <button type="button" @click="selected = '{{ $doctor->id }}'; selectedName = '{{ $doctor->name }}'; open = false"
                            :class="selected == '{{ $doctor->id }}' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-900 hover:bg-gray-50'"
                            class="w-full px-4 py-2.5 text-sm text-start transition-colors">
                        {{ $doctor->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Date Range --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.leave_from') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.leave_to') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
            </div>

            {{-- Reason --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.leave_reason') }} <span class="text-red-500">*</span></label>
                <textarea name="reason" rows="3" required
                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none">{{ old('reason') }}</textarea>
            </div>

            {{-- Cancel Appointments --}}
            <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/50">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="cancel_appointments" value="1"
                           {{ old('cancel_appointments') ? 'checked' : '' }}
                           class="w-4 h-4 mt-0.5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 shrink-0">
                    <span>
                        <span class="block text-sm font-semibold text-gray-800">{{ __('app.cancel_appointments_label') }}</span>
                        <span class="block text-xs text-gray-500 mt-0.5">{{ __('app.cancel_appointments_help') }}</span>
                    </span>
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('app.save') }}
                </button>
                <a href="{{ route('dashboard.leaves.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all">
                    {{ __('app.cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-dashboard-layout>
