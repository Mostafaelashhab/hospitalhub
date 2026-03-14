<x-dashboard-layout>
    <x-slot name="title">{{ $patient->name }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.patient_details') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
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
        <a href="{{ route('dashboard.patients.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Patient Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Info Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $patient->gender === 'male' ? 'from-blue-500 to-cyan-600' : 'from-pink-500 to-rose-600' }} flex items-center justify-center text-2xl font-bold text-white shadow-lg {{ $patient->gender === 'male' ? 'shadow-blue-500/20' : 'shadow-pink-500/20' }}">
                        {{ mb_substr($patient->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $patient->name }}</h2>
                        <p class="text-sm text-gray-500" dir="ltr">{{ $patient->phone }}</p>
                    </div>
                    <div class="{{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }}">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border {{ $patient->gender === 'male' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-pink-50 text-pink-700 border-pink-200' }}">
                            {{ __('app.' . $patient->gender) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.email') }}</p>
                        <p class="text-sm text-gray-900">{{ $patient->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.date_of_birth') }}</p>
                        <p class="text-sm text-gray-900">{{ $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.age') }}</p>
                        <p class="text-sm text-gray-900">{{ $patient->date_of_birth ? $patient->date_of_birth->age . ' ' . __('app.years') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.blood_type') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $patient->blood_type ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.national_id') }}</p>
                        <p class="text-sm text-gray-900 font-mono" dir="ltr">{{ $patient->national_id ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.address') }}</p>
                        <p class="text-sm text-gray-900">{{ $patient->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Medical Info Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.medical_info') }}</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.allergies') }}</p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $patient->allergies ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ __('app.medical_history') }}</p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $patient->medical_history ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Insurance --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ showAssign: false }">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        {{ __('app.insurance') }}
                    </h3>
                    @if(auth()->user()->hasPermission('patients.edit') && $insuranceProviders->count())
                    <button @click="showAssign = !showAssign" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('app.assign_insurance') }}
                    </button>
                    @endif
                </div>

                {{-- Assign Insurance Form --}}
                <div x-show="showAssign" x-transition x-cloak class="px-6 py-5 bg-gray-50/50 border-b border-gray-100">
                    <form action="{{ route('dashboard.patients.insurance.store', $patient) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.insurance_provider') }} <span class="text-red-500">*</span></label>
                                <select name="insurance_provider_id" required class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('app.select_provider') }}</option>
                                    @foreach($insuranceProviders as $provider)
                                    <option value="{{ $provider->id }}">{{ $provider->name }} ({{ $provider->coverage_percentage }}%)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.policy_number') }}</label>
                                <input type="text" name="policy_number" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.member_id') }}</label>
                                <input type="text" name="member_id" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.expiry_date') }}</label>
                                <input type="date" name="expiry_date" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.notes') }}</label>
                            <input type="text" name="notes" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('app.assign') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Active Insurance --}}
                @if($patient->activeInsurance)
                <div class="px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold text-gray-900">{{ $patient->activeInsurance->provider->name }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-md border bg-emerald-50 text-emerald-700 border-emerald-200">{{ __('app.active') }}</span>
                                @if($patient->activeInsurance->isExpired())
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-md border bg-red-50 text-red-700 border-red-200">{{ __('app.expired') }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                <span>{{ __('app.coverage') }}: <strong class="text-indigo-600">{{ $patient->activeInsurance->provider->coverage_percentage }}%</strong></span>
                                @if($patient->activeInsurance->policy_number)
                                <span>{{ __('app.policy') }}: <strong>{{ $patient->activeInsurance->policy_number }}</strong></span>
                                @endif
                                @if($patient->activeInsurance->expiry_date)
                                <span>{{ __('app.expires') }}: <strong>{{ $patient->activeInsurance->expiry_date }}</strong></span>
                                @endif
                            </div>
                        </div>
                        @if(auth()->user()->hasPermission('patients.edit'))
                        <form method="POST" action="{{ route('dashboard.patients.insurance.remove', $patient->activeInsurance) }}" onsubmit="return confirm('{{ __('app.confirm_remove_insurance') }}')">
                            @csrf @method('PATCH')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="{{ __('app.remove') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @else
                <div class="px-6 py-8 text-center">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <p class="text-sm text-gray-400">{{ __('app.no_insurance_assigned') }}</p>
                </div>
                @endif
            </div>

            {{-- File Attachments --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ showUpload: false, category: 'other' }">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        {{ __('app.file_attachments') }}
                        <span class="text-xs font-normal text-gray-400">({{ $patient->files->count() }})</span>
                    </h3>
                    @if(auth()->user()->hasPermission('patients.edit'))
                    <button @click="showUpload = !showUpload" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('app.upload_files') }}
                    </button>
                    @endif
                </div>

                {{-- Upload Form --}}
                <div x-show="showUpload" x-transition x-cloak class="px-6 py-5 bg-gray-50/50 border-b border-gray-100">
                    <form action="{{ route('dashboard.patients.files.store', $patient) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.files') }} <span class="text-red-500">*</span></label>
                                <input type="file" name="files[]" multiple required class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.dicom,.dcm">
                                <p class="text-xs text-gray-400 mt-1">{{ __('app.max_file_size_10mb') }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.category') }} <span class="text-red-500">*</span></label>
                                <select name="category" x-model="category" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="lab_result">{{ __('app.lab_result') }}</option>
                                    <option value="radiology">{{ __('app.radiology') }}</option>
                                    <option value="prescription">{{ __('app.prescription') }}</option>
                                    <option value="report">{{ __('app.medical_report') }}</option>
                                    <option value="id_document">{{ __('app.id_document') }}</option>
                                    <option value="insurance">{{ __('app.insurance_doc') }}</option>
                                    <option value="other" selected>{{ __('app.other') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ __('app.notes') }}</label>
                            <input type="text" name="notes" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('app.file_notes_placeholder') }}">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                {{ __('app.upload') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Files List --}}
                @if($patient->files->count())
                <div class="divide-y divide-gray-100">
                    @foreach($patient->files as $file)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">
                        {{-- File Icon --}}
                        <div class="shrink-0">
                            @if($file->isImage())
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @elseif($file->isPdf())
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            @else
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            @endif
                        </div>

                        {{-- File Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $file->name }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                @php
                                    $categoryStyles = [
                                        'lab_result' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'radiology' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                        'prescription' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'report' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'id_document' => 'bg-gray-50 text-gray-600 border-gray-200',
                                        'insurance' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'other' => 'bg-gray-50 text-gray-600 border-gray-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $categoryStyles[$file->category] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                    {{ __('app.file_cat_' . $file->category) }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $file->formattedSize() }}</span>
                                <span class="text-xs text-gray-400">{{ $file->created_at->format('Y-m-d') }}</span>
                            </div>
                            @if($file->notes)
                            <p class="text-xs text-gray-500 mt-1">{{ $file->notes }}</p>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('dashboard.patients.files.download', $file) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="{{ __('app.download') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                            @if(auth()->user()->hasPermission('patients.edit'))
                            <form action="{{ route('dashboard.patients.files.destroy', $file) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete_file') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="{{ __('app.delete') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <p class="text-sm text-gray-400">{{ __('app.no_files_yet') }}</p>
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
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.doctor') }}</th>
                                <th class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($patient->appointments as $appointment)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $appointment->doctor->name ?? '-' }}</td>
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

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Actions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.actions') }}</h3>
                <div class="space-y-3">
                    <a href="{{ route('dashboard.patients.timeline', $patient) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('app.patient_timeline') }}
                    </a>
                    <a href="{{ route('dashboard.patients.edit', $patient) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        {{ __('app.edit_patient') }}
                    </a>
                </div>
            </div>

            {{-- Stats --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('app.statistics') }}</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.total_appointments') }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $patient->appointments->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.completed') }}</span>
                        <span class="text-sm font-bold text-emerald-600">{{ $patient->appointments->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.cancelled') }}</span>
                        <span class="text-sm font-bold text-red-600">{{ $patient->appointments->where('status', 'cancelled')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ __('app.registered_at') }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $patient->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
