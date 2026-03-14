<x-dashboard-layout>
    <x-slot name="title">{{ __('app.add_doctor') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.add_doctor') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'doctors'])
    </x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('app.add_doctor') }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ __('app.add_doctor_desc') }}</p>
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
        <form method="POST" action="{{ route('dashboard.doctors.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.doctor_name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.phone') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required dir="ltr"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.email') }} <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required dir="ltr"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                {{-- Specialty --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.specialty') }} <span class="text-red-500">*</span></label>
                    <select name="specialty_id" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">{{ __('app.select_specialty') }}</option>
                        @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                            {{ app()->getLocale() === 'ar' ? $specialty->name_ar : $specialty->name_en }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Consultation Fee --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.consultation_fee') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="consultation_fee" value="{{ old('consultation_fee', 0) }}" min="0" step="0.01" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
            </div>

            {{-- Bio --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.bio') }}</label>
                <textarea name="bio" rows="3"
                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none">{{ old('bio') }}</textarea>
            </div>

            {{-- Login Account --}}
            <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/50">
                <div class="flex items-center gap-2 text-xs text-indigo-600 bg-indigo-50 px-3 py-2 rounded-lg mb-4">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('app.doctor_account_auto_created') }}
                </div>

                <div class="max-w-sm">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.password') }} <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required dir="ltr"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('app.save') }}
                </button>
                <a href="{{ route('dashboard.doctors.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all">
                    {{ __('app.cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-dashboard-layout>
