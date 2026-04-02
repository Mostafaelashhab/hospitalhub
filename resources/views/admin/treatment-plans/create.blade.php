@php $dp = auth()->user()->role === 'doctor' && request()->is('doctor/*') ? 'doctor' : 'dashboard'; @endphp
<x-dashboard-layout>
    <x-slot name="title">{{ __('app.create_treatment_plan') }} - {{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.create_treatment_plan') }}</x-slot>

    <x-slot name="sidebar">
        @if(auth()->user()->role === 'doctor' && request()->is('doctor/*'))
            @include('partials.doctor-sidebar', ['active' => 'appointments'])
        @else
            @include('partials.dashboard-sidebar', ['active' => 'patients'])
        @endif
    </x-slot>

    <div class="mb-6">
        <a href="{{ route($dp === 'doctor' ? 'doctor.treatment-plans.index' : 'dashboard.patients.treatment-plans.index', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ $patient->name }}
        </a>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route($dp === 'doctor' ? 'doctor.treatment-plans.store' : 'dashboard.patients.treatment-plans.store', $patient) }}"
          x-data="treatmentPlanForm()" @submit.prevent="submitForm($el)">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Info --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.plan_title') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="title" x-model="title" required
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                                   placeholder="{{ __('app.plan_title_placeholder') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.doctor') }} <span class="text-red-500">*</span></label>
                            <select name="doctor_id" x-model="doctorId" @change="loadServices()" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                                <option value="">{{ __('app.select_doctor') }}</option>
                                @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.discount') }}</label>
                            <input type="number" name="discount" x-model.number="discount" min="0" step="1"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.plan_notes') }}</label>
                            <input type="text" name="notes"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>

                {{-- Tooth Selector --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-2">{{ __('app.select_teeth') }}</h3>
                    <p class="text-xs text-gray-400 mb-4">{{ __('app.select_teeth') }}</p>

                    {{-- Simple Tooth Grid --}}
                    <div class="space-y-4">
                        {{-- Upper Jaw --}}
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ app()->getLocale()==='ar' ? 'الفك العلوي' : 'Upper Jaw' }}</p>
                            <div class="flex flex-wrap gap-1.5 justify-center">
                                @foreach(['18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28'] as $t)
                                <button type="button" @click="addToothItem('{{ $t }}')"
                                        :class="hasToothItem('{{ $t }}') ? 'bg-indigo-100 border-indigo-400 text-indigo-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:border-indigo-300 hover:bg-indigo-50'"
                                        class="w-9 h-9 rounded-lg border text-xs font-bold transition-all flex items-center justify-center">
                                    {{ $t }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        {{-- Lower Jaw --}}
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ app()->getLocale()==='ar' ? 'الفك السفلي' : 'Lower Jaw' }}</p>
                            <div class="flex flex-wrap gap-1.5 justify-center">
                                @foreach(['48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38'] as $t)
                                <button type="button" @click="addToothItem('{{ $t }}')"
                                        :class="hasToothItem('{{ $t }}') ? 'bg-indigo-100 border-indigo-400 text-indigo-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:border-indigo-300 hover:bg-indigo-50'"
                                        class="w-9 h-9 rounded-lg border text-xs font-bold transition-all flex items-center justify-center">
                                    {{ $t }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Add General Procedure --}}
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <button type="button" @click="addGeneralItem()"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            {{ __('app.add_item') }} ({{ __('app.general_procedure') }})
                        </button>
                    </div>
                </div>

                {{-- Items List --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-show="items.length > 0" x-transition>
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">{{ __('app.procedure') }} (<span x-text="items.length"></span>)</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        <template x-for="(item, idx) in items" :key="idx">
                            <div class="px-5 py-4 space-y-3">
                                <div class="flex items-center gap-3">
                                    {{-- Tooth Badge --}}
                                    <div class="shrink-0">
                                        <template x-if="item.tooth_number">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                                                <span class="text-sm font-black text-indigo-600" x-text="'#' + item.tooth_number"></span>
                                            </div>
                                        </template>
                                        <template x-if="!item.tooth_number">
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Fields --}}
                                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div class="sm:col-span-2">
                                            <select x-model="item.service_id" @change="onServiceChange(idx)"
                                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                                <option value="">{{ __('app.custom_procedure') }}</option>
                                                <template x-for="s in services" :key="s.id">
                                                    <option :value="s.id" x-text="s.name"></option>
                                                </template>
                                            </select>
                                            <input type="text" x-model="item.description" required
                                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm mt-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"
                                                   :placeholder="'{{ __('app.procedure') }}'">
                                        </div>
                                        <div>
                                            <input type="number" x-model.number="item.estimated_cost" min="0" step="1" required
                                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"
                                                   :placeholder="'{{ __('app.estimated_cost') }}'">
                                            <input type="text" x-model="item.notes"
                                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm mt-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"
                                                   :placeholder="'{{ __('app.notes') }}'">
                                        </div>
                                    </div>

                                    {{-- Remove --}}
                                    <button type="button" @click="removeItem(idx)" class="shrink-0 w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center transition-colors">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>

                                {{-- Hidden inputs --}}
                                <input type="hidden" :name="'items[' + idx + '][tooth_number]'" :value="item.tooth_number || ''">
                                <input type="hidden" :name="'items[' + idx + '][service_id]'" :value="item.service_id || ''">
                                <input type="hidden" :name="'items[' + idx + '][description]'" :value="item.description">
                                <input type="hidden" :name="'items[' + idx + '][estimated_cost]'" :value="item.estimated_cost">
                                <input type="hidden" :name="'items[' + idx + '][notes]'" :value="item.notes || ''">
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Right: Summary --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                    <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.estimated_total') }}</h3>
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">{{ __('app.procedure') }}</span>
                            <span class="text-sm font-bold text-gray-900" x-text="items.length"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">{{ __('app.estimated_total') }}</span>
                            <span class="text-sm font-bold text-gray-900" x-text="totalCost().toLocaleString()"></span>
                        </div>
                        <template x-if="discount > 0">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">{{ __('app.discount') }}</span>
                                <span class="text-sm font-bold text-red-600" x-text="'-' + discount.toLocaleString()"></span>
                            </div>
                        </template>
                        <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-700">{{ __('app.net_total') }}</span>
                            <span class="text-lg font-black text-indigo-600" x-text="netTotal().toLocaleString()"></span>
                        </div>
                    </div>

                    <button type="submit" :disabled="items.length === 0"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('app.save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
    function treatmentPlanForm() {
        return {
            title: '',
            doctorId: '',
            discount: 0,
            items: [],
            services: [],

            async loadServices() {
                if (!this.doctorId) { this.services = []; return; }
                try {
                    const res = await fetch(`/dashboard/doctors/${this.doctorId}/services`);
                    const data = await res.json();
                    this.services = data.map(s => ({
                        id: s.id,
                        name: '{{ app()->getLocale() }}' === 'ar' ? (s.name_ar || s.name_en) : (s.name_en || s.name_ar),
                        price: s.pivot_price ?? s.default_price ?? 0,
                    }));
                } catch(e) { this.services = []; }
            },

            addToothItem(tooth) {
                this.items.push({
                    tooth_number: tooth,
                    service_id: '',
                    description: '',
                    estimated_cost: 0,
                    notes: '',
                });
            },

            addGeneralItem() {
                this.items.push({
                    tooth_number: '',
                    service_id: '',
                    description: '',
                    estimated_cost: 0,
                    notes: '',
                });
            },

            hasToothItem(tooth) {
                return this.items.some(i => i.tooth_number === tooth);
            },

            removeItem(idx) {
                this.items.splice(idx, 1);
            },

            onServiceChange(idx) {
                const item = this.items[idx];
                const svc = this.services.find(s => s.id == item.service_id);
                if (svc) {
                    item.description = svc.name;
                    item.estimated_cost = svc.price;
                }
            },

            totalCost() {
                return this.items.reduce((sum, i) => sum + (Number(i.estimated_cost) || 0), 0);
            },

            netTotal() {
                return Math.max(0, this.totalCost() - (Number(this.discount) || 0));
            },

            submitForm(el) {
                if (this.items.length === 0) return;
                el.submit();
            }
        }
    }
    </script>
</x-dashboard-layout>
