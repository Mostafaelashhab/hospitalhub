<x-dashboard-layout>
    <x-slot name="title">{{ $doctor->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.doctor_details') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'doctors'])
    </x-slot>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.doctors.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Doctor Info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-2xl font-bold text-white shadow-lg shadow-purple-500/20">
                        {{ mb_substr($doctor->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $doctor->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $doctor->phone }}</p>
                    </div>
                    <div class="{{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }}">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border {{ $doctor->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $doctor->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                            {{ $doctor->is_active ? __('app.active') : __('app.inactive') }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.phone') }}</p>
                        <p class="text-sm text-gray-900 font-mono" dir="ltr">{{ $doctor->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.email') }}</p>
                        <p class="text-sm text-gray-900">{{ $doctor->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.consultation_fee') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ number_format($doctor->consultation_fee, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.total_appointments') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $doctor->appointments->count() }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.registered_at') }}</p>
                        <p class="text-sm text-gray-900">{{ $doctor->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>

                @if($doctor->bio)
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <p class="text-xs text-gray-400 font-medium mb-2">{{ __('app.bio') }}</p>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $doctor->bio }}</p>
                </div>
                @endif
            </div>

            {{-- Recent Appointments --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">{{ __('app.recent_appointments') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.patient') }}</th>
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($doctor->appointments as $appointment)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $appointment->patient->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusStyles = [
                                            'scheduled' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'completed' => 'bg-gray-50 text-gray-600 border-gray-200',
                                            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                            'no_show' => 'bg-red-50 text-red-700 border-red-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-lg border {{ $statusStyles[$appointment->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                        {{ __('app.' . $appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-400 text-sm">{{ __('app.no_appointments_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar Actions --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.actions') }}</h3>
                <div class="space-y-3">
                    <a href="{{ route('dashboard.doctors.edit', $doctor) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        {{ __('app.edit_doctor') }}
                    </a>
                    <a href="{{ route('dashboard.doctors.schedule', $doctor) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'جدول المواعيد' : 'Schedule' }}
                    </a>
                    <form method="POST" action="{{ route('dashboard.doctors.toggle', $doctor) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold {{ $doctor->is_active ? 'text-red-700 bg-red-50 border border-red-200 hover:bg-red-100' : 'text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100' }} rounded-xl transition-all">
                            @if($doctor->is_active)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            {{ __('app.deactivate') }}
                            @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('app.activate') }}
                            @endif
                        </button>
                    </form>
                    <form method="POST" action="{{ route('dashboard.doctors.destroy', $doctor) }}" onsubmit="return confirm('{{ __('app.confirm_delete_doctor') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            {{ __('app.delete_doctor') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Stats Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.statistics') }}</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.total_appointments') }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $doctor->appointments->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.completed') }}</span>
                        <span class="text-sm font-bold text-emerald-600">{{ $doctor->appointments->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.cancelled') }}</span>
                        <span class="text-sm font-bold text-red-600">{{ $doctor->appointments->where('status', 'cancelled')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
