<x-dashboard-layout>
    <x-slot name="title">{{ __('app.settings') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.settings') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'settings'])
    </x-slot>

    {{-- Success --}}
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

    {{-- Errors --}}
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div x-data="{ tab: '{{ auth()->user()->isAdmin() ? 'clinic' : (auth()->user()->isDoctor() ? 'doctor' : 'profile') }}' }" class="space-y-6">

        {{-- Tabs --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-1.5 flex flex-wrap gap-1">
            @if(auth()->user()->isAdmin())
            <button @click="tab = 'clinic'" :class="tab === 'clinic' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                {{ __('app.clinic_info') }}
            </button>
            @endif
            @if(auth()->user()->isDoctor() && $doctor)
            <button @click="tab = 'doctor'" :class="tab === 'doctor' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('app.doctor_settings') }}
            </button>
            @endif
            <button @click="tab = 'profile'" :class="tab === 'profile' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                {{ __('app.profile') }}
            </button>
        </div>

        {{-- ═══════════════════════════════════════ --}}
        {{-- ═══ CLINIC SETTINGS (Admin Only) ══════ --}}
        {{-- ═══════════════════════════════════════ --}}
        @if(auth()->user()->isAdmin())
        <div x-show="tab === 'clinic'" x-transition>
            <form method="POST" action="{{ route('dashboard.settings.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <input type="hidden" name="section" value="clinic">

                <div class="space-y-6">
                    {{-- Logo & Basic Info --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.clinic_info') }}</h3>
                        <p class="text-sm text-gray-500 mb-6">{{ __('app.clinic_info_desc') }}</p>

                        {{-- Logo --}}
                        <div class="mb-6" x-data="{ preview: '{{ $clinic->logo ? Storage::url($clinic->logo) : '' }}' }">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.logo') }}</label>
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden">
                                    <template x-if="preview">
                                        <img :src="preview" class="w-full h-full object-cover rounded-2xl">
                                    </template>
                                    <template x-if="!preview">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </template>
                                </div>
                                <div>
                                    <label class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl cursor-pointer transition-all border border-gray-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        {{ __('app.upload') }}
                                        <input type="file" name="logo" accept="image/*" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                                    </label>
                                    <p class="text-[11px] text-gray-400 mt-1">PNG, JPG — {{ __('app.max') }} 2MB</p>
                                </div>
                            </div>
                        </div>

                        {{-- Clinic Names --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.clinic_name_en') }}</label>
                                <input type="text" name="name_en" value="{{ old('name_en', $clinic->name_en) }}" required
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.clinic_name_ar') }}</label>
                                <input type="text" name="name_ar" value="{{ old('name_ar', $clinic->name_ar) }}" required dir="rtl"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                        </div>

                        {{-- Phone & Email --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.phone') }}</label>
                                <input type="text" name="clinic_phone" value="{{ old('clinic_phone', $clinic->phone) }}" required dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.email') }}</label>
                                <input type="email" name="clinic_email" value="{{ old('clinic_email', $clinic->email) }}" dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.address_en') }}</label>
                                <input type="text" name="address_en" value="{{ old('address_en', $clinic->address_en) }}"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.address_ar') }}</label>
                                <input type="text" name="address_ar" value="{{ old('address_ar', $clinic->address_ar) }}" dir="rtl"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                        </div>

                        {{-- City --}}
                        <div class="max-w-xs">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.city') }}</label>
                            <input type="text" name="city" value="{{ old('city', $clinic->city) }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    {{-- Working Hours --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{
                        days: {{ json_encode(old('working_days', $clinic->working_days ?? [])) }},
                        toggle(day) { const i = this.days.indexOf(day); if (i > -1) this.days.splice(i, 1); else this.days.push(day); },
                        isActive(day) { return this.days.includes(day); }
                    }">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.working_hours') }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ __('app.working_hours_desc') }}</p>

                        {{-- Days --}}
                        <div class="grid grid-cols-4 sm:grid-cols-7 gap-2 mb-6">
                            @php $clinicDays = ['sat'=>['en'=>'Sat','ar'=>'سبت'],'sun'=>['en'=>'Sun','ar'=>'أحد'],'mon'=>['en'=>'Mon','ar'=>'إثنين'],'tue'=>['en'=>'Tue','ar'=>'ثلاثاء'],'wed'=>['en'=>'Wed','ar'=>'أربعاء'],'thu'=>['en'=>'Thu','ar'=>'خميس'],'fri'=>['en'=>'Fri','ar'=>'جمعة']]; @endphp
                            @foreach($clinicDays as $key => $labels)
                            <button type="button" @click="toggle('{{ $key }}')"
                                    :class="isActive('{{ $key }}') ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-500/25' : 'bg-gray-50 text-gray-700 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50'"
                                    class="flex flex-col items-center justify-center py-3 px-2 rounded-xl border-2 transition-all duration-200 cursor-pointer">
                                <span class="text-xs font-bold">{{ app()->getLocale() === 'ar' ? $labels['ar'] : $labels['en'] }}</span>
                            </button>
                            @endforeach
                            <template x-for="day in days" :key="day">
                                <input type="hidden" name="working_days[]" :value="day">
                            </template>
                        </div>

                        {{-- Hours --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">{{ __('app.from') }}</label>
                                <input type="time" name="working_hours_from" value="{{ old('working_hours_from', $clinic->working_hours_from) }}" dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">{{ __('app.to') }}</label>
                                <input type="time" name="working_hours_to" value="{{ old('working_hours_to', $clinic->working_hours_to) }}" dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="flex">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('app.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        {{-- ═══════════════════════════════════════ --}}
        {{-- ═══ DOCTOR SETTINGS ═══════════════════ --}}
        {{-- ═══════════════════════════════════════ --}}
        @if(auth()->user()->isDoctor() && $doctor)
        <div x-show="tab === 'doctor'" x-transition>
            <form method="POST" action="{{ route('dashboard.settings.update') }}">
                @csrf @method('PUT')
                <input type="hidden" name="section" value="doctor">

                <div class="space-y-6">
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

                    {{-- Bio --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.bio') }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ __('app.bio_desc') }}</p>
                        <textarea name="bio" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all resize-none">{{ old('bio', $doctor->bio) }}</textarea>
                    </div>

                    {{-- Working Days & Hours --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{
                        days: {{ json_encode(old('working_days', $doctor->working_days ?? [])) }},
                        toggle(day) { const i = this.days.indexOf(day); if (i > -1) this.days.splice(i, 1); else this.days.push(day); },
                        isActive(day) { return this.days.includes(day); }
                    }">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.working_days') }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ __('app.working_days_desc') }}</p>

                        <div class="grid grid-cols-4 sm:grid-cols-7 gap-2 mb-6">
                            @php $weekDays = ['sat'=>['en'=>'Sat','ar'=>'سبت'],'sun'=>['en'=>'Sun','ar'=>'أحد'],'mon'=>['en'=>'Mon','ar'=>'إثنين'],'tue'=>['en'=>'Tue','ar'=>'ثلاثاء'],'wed'=>['en'=>'Wed','ar'=>'أربعاء'],'thu'=>['en'=>'Thu','ar'=>'خميس'],'fri'=>['en'=>'Fri','ar'=>'جمعة']]; @endphp
                            @foreach($weekDays as $key => $labels)
                            <button type="button" @click="toggle('{{ $key }}')"
                                    :class="isActive('{{ $key }}') ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-500/25' : 'bg-gray-50 text-gray-700 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50'"
                                    class="flex flex-col items-center justify-center py-3 px-2 rounded-xl border-2 transition-all duration-200 cursor-pointer">
                                <span class="text-xs font-bold">{{ app()->getLocale() === 'ar' ? $labels['ar'] : $labels['en'] }}</span>
                            </button>
                            @endforeach
                            <template x-for="day in days" :key="day">
                                <input type="hidden" name="working_days[]" :value="day">
                            </template>
                        </div>

                        <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('app.working_hours') }}</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">{{ __('app.from') }}</label>
                                <input type="time" name="working_from" value="{{ old('working_from', $doctor->working_from ? \Carbon\Carbon::parse($doctor->working_from)->format('H:i') : '') }}" dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">{{ __('app.to') }}</label>
                                <input type="time" name="working_to" value="{{ old('working_to', $doctor->working_to ? \Carbon\Carbon::parse($doctor->working_to)->format('H:i') : '') }}" dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="flex">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('app.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        {{-- ═══════════════════════════════════════ --}}
        {{-- ═══ PROFILE (All Roles) ═══════════════ --}}
        {{-- ═══════════════════════════════════════ --}}
        <div x-show="tab === 'profile'" x-transition>
            <form method="POST" action="{{ route('dashboard.settings.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <input type="hidden" name="section" value="profile">

                <div class="space-y-6">
                    {{-- Avatar & Name --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.personal_info') }}</h3>
                        <p class="text-sm text-gray-500 mb-6">{{ __('app.personal_info_desc') }}</p>

                        {{-- Avatar --}}
                        <div class="mb-6" x-data="{ preview: '{{ $user->avatar ? Storage::url($user->avatar) : '' }}' }">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.avatar') }}</label>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-gray-100 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden">
                                    <template x-if="preview">
                                        <img :src="preview" class="w-full h-full object-cover rounded-full">
                                    </template>
                                    <template x-if="!preview">
                                        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </template>
                                </div>
                                <label class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl cursor-pointer transition-all border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    {{ __('app.upload') }}
                                    <input type="file" name="avatar" accept="image/*" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                                </label>
                            </div>
                        </div>

                        {{-- Name & Phone --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.name') }}</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.phone') }}</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" dir="ltr"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                        </div>

                        {{-- Email (read-only) --}}
                        <div class="max-w-md">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.email') }}</label>
                            <input type="email" value="{{ $user->email }}" disabled dir="ltr"
                                   class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                        </div>

                        {{-- Role badge --}}
                        <div class="mt-4 flex items-center gap-2">
                            <span class="text-xs font-semibold text-gray-500">{{ __('app.role') }}:</span>
                            <span class="px-3 py-1 text-xs font-bold rounded-lg bg-indigo-50 text-indigo-700">{{ __(('app.' . $user->role)) }}</span>
                        </div>
                    </div>

                    {{-- Change Password --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('app.change_password') }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ __('app.change_password_desc') }}</p>

                        <div class="space-y-4 max-w-md">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.current_password') }}</label>
                                <input type="password" name="current_password"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.new_password') }}</label>
                                <input type="password" name="password"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.confirm_password') }}</label>
                                <input type="password" name="password_confirmation"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="flex">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('app.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>


    </div>
</x-dashboard-layout>
