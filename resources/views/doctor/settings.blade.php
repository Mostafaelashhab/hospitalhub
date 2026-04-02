<x-dashboard-layout>
    <x-slot name="title">{{ __('app.doctor_settings') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.doctor_settings') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.doctor-sidebar', ['active' => 'settings'])
    </x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('app.doctor_settings') }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ __('app.doctor_settings_desc') }}</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
         class="mb-6 px-5 py-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    @endif

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

    <form method="POST" action="{{ route('doctor.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Consultation Fee --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.consultation_fee') }}</h3>
            <p class="text-sm text-gray-500 mb-4">{{ __('app.consultation_fee_desc') }}</p>

            <div class="max-w-xs">
                <div class="relative">
                    <input type="number" name="consultation_fee" value="{{ old('consultation_fee', $doctor->consultation_fee) }}" min="0" step="0.01" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-lg font-semibold text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all" dir="ltr">
                    <span class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">{{ __('app.currency') }}</span>
                </div>
            </div>
        </div>

        {{-- Schedule Link --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.schedule') }}</h3>
                    <p class="text-sm text-gray-500">{{ app()->getLocale() === 'ar' ? 'حدد أيام وأوقات عملك من صفحة الجدول' : 'Set your working days & hours from the schedule page' }}</p>
                </div>
                <a href="{{ route('doctor.schedule') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('app.schedule') }}
                </a>
            </div>
        </div>

        {{-- Service Pricing --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.service_pricing') }}</h3>
            <p class="text-sm text-gray-500 mb-4">{{ __('app.service_pricing_desc') }}</p>

            <div class="space-y-3">
                {{-- Standard specialty services --}}
                @foreach($specialtyServices as $service)
                @php
                    $doctorService = $doctor->services->firstWhere('id', $service->id);
                    $price = $doctorService ? $doctorService->pivot->price : '';
                    $isActive = $doctorService ? $doctorService->pivot->is_active : true;
                @endphp
                <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-gray-200 transition-all {{ !$isActive && $doctorService ? 'opacity-50' : '' }}">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ app()->getLocale() === 'ar' ? $service->name_ar : $service->name_en }}</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <div class="relative w-32">
                            <input type="number" name="services[{{ $service->id }}][price]" value="{{ old("services.{$service->id}.price", $price) }}" min="0" step="0.01" placeholder="0"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm font-semibold text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all" dir="ltr">
                            <span class="absolute {{ app()->getLocale() === 'ar' ? 'left-2' : 'right-2' }} top-1/2 -translate-y-1/2 text-xs text-gray-400">{{ __('app.currency') }}</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="services[{{ $service->id }}][is_active]" value="0">
                            <input type="checkbox" name="services[{{ $service->id }}][is_active]" value="1" {{ $isActive ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
                @endforeach

                {{-- Doctor's custom services --}}
                @foreach($customServices as $service)
                <div class="flex items-center gap-3 p-3 rounded-xl border border-indigo-100 bg-indigo-50/30 hover:border-indigo-200 transition-all">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ app()->getLocale() === 'ar' ? $service->name_ar : $service->name_en }}</p>
                        <span class="text-[10px] font-bold text-indigo-600 uppercase">{{ __('app.custom_service') }}</span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-sm font-bold text-gray-900" dir="ltr">{{ number_format($service->price, 2) }} {{ __('app.currency') }}</span>
                        <button type="button" onclick="if(confirm('{{ __('app.confirm_delete') }}')) document.getElementById('delete-service-{{ $service->id }}').submit()"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Add Custom Service --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{ open: false }">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.add_custom_service') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('app.add_custom_service_desc') }}</p>
                </div>
                <button type="button" @click="open = !open"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('app.add_service') }}
                </button>
            </div>

            <div x-show="open" x-transition class="mt-4 pt-4 border-t border-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">{{ __('app.name') }} ({{ __('app.arabic') }})</label>
                        <input type="text" name="custom_service_name_ar" placeholder="{{ __('app.service') }}..."
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">{{ __('app.name') }} ({{ __('app.english') }})</label>
                        <input type="text" name="custom_service_name_en" placeholder="Service..." dir="ltr"
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">{{ __('app.consultation_fee') }}</label>
                        <div class="relative">
                            <input type="number" name="custom_service_price" min="0" step="0.01" placeholder="0"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm font-semibold text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all" dir="ltr">
                            <span class="absolute {{ app()->getLocale() === 'ar' ? 'left-2' : 'right-2' }} top-1/2 -translate-y-1/2 text-xs text-gray-400">{{ __('app.currency') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ __('app.save') }}
            </button>
        </div>
    </form>

    {{-- Delete custom service forms (outside main form) --}}
    @foreach($customServices as $service)
    <form id="delete-service-{{ $service->id }}" method="POST" action="{{ route('doctor.services.destroy', $service) }}" class="hidden">
        @csrf @method('DELETE')
    </form>
    @endforeach
</x-dashboard-layout>
