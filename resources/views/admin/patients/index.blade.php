<x-dashboard-layout>
    <x-slot name="title">{{ __('app.patients') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.patients') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('app.patients') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.all_patients') }}</p>
        </div>
        @if(auth()->user()->hasPermission('patients.create'))
        <a href="{{ route('dashboard.patients.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('app.add_patient') }}
        </a>
        @endif
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('dashboard.patients.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="w-5 h-5 text-gray-400 absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_patients') }}"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
            </div>
            <select name="gender" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                <option value="">{{ __('app.gender') }}: {{ __('app.all') }}</option>
                <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>{{ __('app.male') }}</option>
                <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>{{ __('app.female') }}</option>
            </select>
            <select name="blood_type" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                <option value="">{{ __('app.blood_type') }}: {{ __('app.all') }}</option>
                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                <option value="{{ $bt }}" {{ request('blood_type') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                {{ __('app.search') }}
            </button>
        </form>
    </div>

    {{-- Patients Cards --}}
    <div class="space-y-4">
        @forelse($patients as $patient)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
            <div class="p-5">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    {{-- Patient Avatar & Name --}}
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $patient->gender === 'male' ? 'from-blue-500 to-cyan-600' : 'from-pink-500 to-rose-600' }} flex items-center justify-center text-white font-bold text-lg shadow-md {{ $patient->gender === 'male' ? 'shadow-blue-500/20' : 'shadow-pink-500/20' }} shrink-0">
                            {{ mb_substr($patient->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-gray-900 font-bold text-base truncate">{{ $patient->name }}</h3>
                            <p class="text-sm text-gray-500 truncate" dir="ltr">{{ $patient->phone }}</p>
                        </div>
                    </div>

                    {{-- Gender Badge --}}
                    <div class="shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border {{ $patient->gender === 'male' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-pink-50 text-pink-700 border-pink-200' }}">
                            {{ __('app.' . $patient->gender) }}
                        </span>
                    </div>
                </div>

                {{-- Details Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.email') }}</p>
                        <p class="text-sm text-gray-700 truncate">{{ $patient->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.blood_type') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $patient->blood_type ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.age') }}</p>
                        <p class="text-sm text-gray-700">{{ $patient->date_of_birth ? $patient->date_of_birth->age . ' ' . __('app.years') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.appointments') }}</p>
                        <p class="text-sm text-gray-700">{{ $patient->appointments()->count() }}</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-wrap mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('dashboard.patients.show', $patient) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        {{ __('app.view_details') }}
                    </a>
                    @if(auth()->user()->hasPermission('patients.edit'))
                    <a href="{{ route('dashboard.patients.edit', $patient) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        {{ __('app.edit') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-gray-500 font-medium">{{ __('app.no_patients_found') }}</p>
                <a href="{{ route('dashboard.patients.create') }}" class="mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-semibold">{{ __('app.add_patient') }} &rarr;</a>
            </div>
        </div>
        @endforelse
    </div>

    @if($patients->hasPages())
    <div class="mt-6">
        {{ $patients->withQueryString()->links() }}
    </div>
    @endif
</x-dashboard-layout>
