<x-dashboard-layout>
    <x-slot name="title">{{ __('app.new_appointment') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.new_appointment') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'appointments'])
    </x-slot>

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('app.new_appointment') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.book_appointment') }}</p>
        </div>
        <a href="{{ route('dashboard.appointments.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
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

    {{-- Booking Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-900">{{ __('app.appointment_details') }}</h3>
        </div>
        <form method="POST" action="{{ route('dashboard.appointments.store') }}" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="appointmentForm()">
                {{-- Patient --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.patient') }} <span class="text-red-500">*</span></label>
                    <select name="patient_id" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">{{ __('app.select_patient') }}</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ (old('patient_id', request('patient_id')) == $patient->id) ? 'selected' : '' }}>{{ $patient->name }} - {{ $patient->phone }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Doctor --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.doctor') }} <span class="text-red-500">*</span></label>
                    <select name="doctor_id" required x-model="doctorId" @change="fetchServices(); fetchSlots()"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">{{ __('app.select_doctor') }}</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }} ({{ number_format($doctor->consultation_fee) }} {{ __('app.currency') }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Services (Multi-select) --}}
                <div x-show="services.length > 0" x-transition class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.services') }}</label>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 max-h-52 overflow-y-auto space-y-2">
                        <template x-for="service in services" :key="service.id">
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-all"
                                   :class="selectedServices.includes(service.id) ? 'border-indigo-300 bg-indigo-50' : 'border-gray-100 hover:border-gray-200 bg-white'">
                                <input type="checkbox" :value="service.id" name="service_ids[]"
                                       @change="toggleService(service.id)"
                                       :checked="selectedServices.includes(service.id)"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="flex-1 text-sm font-medium text-gray-800" x-text="service.name"></span>
                                <span x-show="service.price" class="text-sm font-bold text-indigo-600 shrink-0" dir="ltr"
                                      x-text="service.price + ' {{ __('app.currency') }}'"></span>
                            </label>
                        </template>
                    </div>
                    <div x-show="servicesTotal > 0" class="mt-2 flex items-center justify-between px-1">
                        <span class="text-xs text-gray-500">{{ __('app.total') }}</span>
                        <span class="text-sm font-bold text-indigo-700" dir="ltr" x-text="servicesTotal.toFixed(2) + ' {{ __('app.currency') }}'"></span>
                    </div>
                </div>

                {{-- Date --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.appointment_date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="appointment_date" value="{{ old('appointment_date') }}" required min="{{ date('Y-m-d') }}"
                           x-model="appointmentDate" @change="fetchSlots()" @input="fetchSlots()"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                {{-- Time Slots --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.appointment_time') }} <span class="text-red-500">*</span></label>
                    <input type="hidden" name="appointment_time" :value="selectedTime" required>

                    {{-- Leave Warning --}}
                    <template x-if="onLeave">
                        <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700 flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ app()->getLocale() === 'ar' ? 'الدكتور في إجازة في التاريخ ده' : 'Doctor is on leave on this date' }}
                        </div>
                    </template>

                    {{-- No Slots --}}
                    <template x-if="!onLeave && slotsLoaded && slots.length === 0">
                        <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-sm text-amber-700 flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ app()->getLocale() === 'ar' ? 'مفيش مواعيد متاحة — الدكتور مش شغال اليوم ده' : 'No slots — doctor doesn\'t work this day' }}
                        </div>
                    </template>

                    {{-- Loading --}}
                    <template x-if="slotsLoading">
                        <div class="flex justify-center py-4">
                            <svg class="animate-spin w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </div>
                    </template>

                    {{-- Slots Grid --}}
                    <template x-if="!onLeave && !slotsLoading && slots.length > 0">
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 max-h-48 overflow-y-auto p-1">
                            <template x-for="slot in slots" :key="slot.time">
                                <button type="button" @click="slot.available && (selectedTime = slot.time)"
                                        :disabled="!slot.available"
                                        :class="{
                                            'bg-indigo-600 text-white border-indigo-600 shadow-md': selectedTime === slot.time,
                                            'bg-white text-gray-700 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50': selectedTime !== slot.time && slot.available,
                                            'bg-gray-100 text-gray-300 border-gray-100 cursor-not-allowed line-through': !slot.available
                                        }"
                                        class="px-2 py-2 text-xs font-semibold rounded-lg border transition-all text-center"
                                        x-text="slot.label"></button>
                            </template>
                        </div>
                    </template>

                    {{-- Prompt to select doctor + date --}}
                    <template x-if="!slotsLoaded && !slotsLoading && !onLeave">
                        <p class="text-xs text-gray-400 py-3">{{ app()->getLocale() === 'ar' ? 'اختر الدكتور والتاريخ أولاً' : 'Select doctor & date first' }}</p>
                    </template>
                </div>

                {{-- Notes --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.notes') }}</label>
                    <textarea name="notes" rows="3" placeholder="{{ __('app.notes') }}..."
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Recurring Appointment Section --}}
            <div class="mt-6 pt-6 border-t border-gray-100" x-data="{ recurrenceType: '{{ old('recurrence_type', 'none') }}' }">
                <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ __('app.recurring_appointment') }}
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Recurrence Type --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.recurrence_type') }}</label>
                        <select name="recurrence_type" x-model="recurrenceType"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="none">{{ __('app.no_recurrence') }}</option>
                            <option value="daily">{{ __('app.daily') }}</option>
                            <option value="weekly">{{ __('app.weekly') }}</option>
                            <option value="biweekly">{{ __('app.biweekly') }}</option>
                            <option value="monthly">{{ __('app.monthly') }}</option>
                        </select>
                    </div>

                    {{-- Recurrence Count --}}
                    <div x-show="recurrenceType !== 'none'" x-transition>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.recurrence_count') }}</label>
                        <input type="number" name="recurrence_count" value="{{ old('recurrence_count', 4) }}" min="2" max="52"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                        <p class="text-xs text-gray-400 mt-1.5">{{ __('app.recurrence_count_hint') }}</p>
                    </div>
                </div>

                {{-- Info box --}}
                <div x-show="recurrenceType !== 'none'" x-transition class="mt-4 bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3">
                    <p class="text-sm text-indigo-700 flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('app.recurring_info') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('app.book_appointment') }}
                </button>
                <a href="{{ route('dashboard.appointments.index') }}" class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                    {{ __('app.cancel') }}
                </a>
            </div>
        </form>
    </div>

    <script>
        function appointmentForm() {
            return {
                doctorId: '{{ old('doctor_id') }}',
                appointmentDate: '{{ old('appointment_date') }}',
                selectedTime: '{{ old('appointment_time', '') }}',
                selectedServices: [],
                services: [],
                slots: [],
                slotsLoaded: false,
                slotsLoading: false,
                onLeave: false,
                get servicesTotal() {
                    return this.services
                        .filter(s => this.selectedServices.includes(s.id))
                        .reduce((sum, s) => sum + (s.price || 0), 0);
                },
                init() {
                    if (this.doctorId) this.fetchServices();
                    if (this.doctorId && this.appointmentDate) this.fetchSlots();
                },
                toggleService(id) {
                    const idx = this.selectedServices.indexOf(id);
                    if (idx > -1) this.selectedServices.splice(idx, 1);
                    else this.selectedServices.push(id);
                },
                async fetchServices() {
                    this.services = [];
                    this.selectedServices = [];
                    if (!this.doctorId) return;
                    try {
                        const res = await fetch(`/dashboard/doctors/${this.doctorId}/services`);
                        this.services = await res.json();
                    } catch (e) {
                        this.services = [];
                    }
                },
                async fetchSlots() {
                    this.slots = [];
                    this.slotsLoaded = false;
                    this.onLeave = false;
                    this.selectedTime = '';
                    if (!this.doctorId || !this.appointmentDate) return;
                    this.slotsLoading = true;
                    try {
                        const res = await fetch(`/dashboard/appointments/available-slots?doctor_id=${this.doctorId}&date=${this.appointmentDate}`);
                        const data = await res.json();
                        this.slots = data.slots || [];
                        this.onLeave = data.on_leave || false;
                    } catch (e) {
                        this.slots = [];
                    }
                    this.slotsLoading = false;
                    this.slotsLoaded = true;
                }
            }
        }
    </script>
</x-dashboard-layout>
