<x-dashboard-layout>
    <x-slot name="title">{{ __('app.ai_radiology') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.ai_radiology') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'patients'])
    </x-slot>

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.patients.show', $patient) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center">
            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('app.ai_radiology') }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $patient->name }} &mdash; {{ __('app.ai_powered_analysis') }}</p>
        </div>
        @if(!$isConfigured)
            <span class="ms-auto inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-700 text-xs font-semibold rounded-lg border border-red-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                {{ __('app.api_not_configured') }}
            </span>
        @endif
    </div>

    {{-- Messages --}}
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div x-data="{
        analysisType: 'file',
        analyzing: false,
        imagePreview: null,
        result: {{ json_encode(session('ai_result')) ?: 'null' }},
        previewFile(event) {
            const file = event.target.files[0];
            if (file) {
                this.imagePreview = URL.createObjectURL(file);
            }
        }
    }" class="space-y-6">

        {{-- Analysis Form --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 p-5 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('app.analyze_xray') }}</h3>
            </div>

            <form method="POST" action="{{ route('dashboard.ai-radiology.analyze', $patient) }}" enctype="multipart/form-data" @submit="analyzing = true">
                @csrf
                <div class="p-5 space-y-5">

                    {{-- Source Type Tabs --}}
                    <div class="flex gap-2 bg-gray-50 p-1 rounded-xl">
                        <button type="button" @click="analysisType = 'file'"
                                :class="analysisType === 'file' ? 'bg-white shadow-sm text-violet-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 py-2.5 px-4 rounded-lg text-sm transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            {{ __('app.upload_file') }}
                        </button>
                        <button type="button" @click="analysisType = 'url'"
                                :class="analysisType === 'url' ? 'bg-white shadow-sm text-violet-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 py-2.5 px-4 rounded-lg text-sm transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            {{ __('app.image_url') }}
                        </button>
                        @if($radiologyFiles->count() > 0)
                        <button type="button" @click="analysisType = 'existing'"
                                :class="analysisType === 'existing' ? 'bg-white shadow-sm text-violet-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 py-2.5 px-4 rounded-lg text-sm transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            {{ __('app.existing_files') }}
                        </button>
                        @endif
                    </div>

                    <input type="hidden" name="analysis_type" :value="analysisType">

                    {{-- Upload File --}}
                    <div x-show="analysisType === 'file'" x-transition>
                        <label class="block">
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-violet-300 hover:bg-violet-50/30 transition-all cursor-pointer"
                                 :class="imagePreview && 'border-violet-300 bg-violet-50/30'">
                                <template x-if="!imagePreview">
                                    <div>
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-sm font-medium text-gray-600">{{ __('app.drop_xray_here') }}</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — {{ __('app.max') }} 10MB</p>
                                    </div>
                                </template>
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="max-h-64 mx-auto rounded-lg shadow-sm">
                                </template>
                                <input type="file" name="image_file" accept="image/*" class="hidden" @change="previewFile($event)">
                            </div>
                        </label>
                    </div>

                    {{-- URL Input --}}
                    <div x-show="analysisType === 'url'" x-transition>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.image_url') }}</label>
                        <input type="url" name="image_url" placeholder="https://example.com/xray.jpg"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:bg-white transition-all"
                               dir="ltr">
                    </div>

                    {{-- Existing Files --}}
                    <div x-show="analysisType === 'existing'" x-transition>
                        @if($radiologyFiles->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach($radiologyFiles as $file)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="existing_file_id" value="{{ $file->id }}" class="peer hidden">
                                <div class="border-2 border-gray-200 rounded-xl overflow-hidden transition-all peer-checked:border-violet-500 peer-checked:ring-2 peer-checked:ring-violet-500/20 group-hover:border-gray-300">
                                    @if($file->isImage())
                                        <img src="{{ Storage::url($file->file_path) }}" class="w-full h-28 object-cover" loading="lazy">
                                    @else
                                        <div class="w-full h-28 bg-gray-100 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                    @endif
                                    <div class="p-2">
                                        <p class="text-xs font-medium text-gray-700 truncate">{{ $file->name }}</p>
                                        <p class="text-[10px] text-gray-400">{{ $file->created_at->format('Y-m-d') }}</p>
                                    </div>
                                </div>
                                <div class="absolute top-2 {{ app()->getLocale() === 'ar' ? 'left-2' : 'right-2' }} w-5 h-5 bg-violet-600 rounded-full hidden peer-checked:flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Analysis Options --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.analysis_prompt') }}</label>
                            <textarea name="message" rows="3" placeholder="{{ __('app.analysis_prompt_placeholder') }}"
                                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:bg-white transition-all resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.report_language') }}</label>
                            <select name="language" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:bg-white transition-all">
                                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                                <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>العربية</option>
                            </select>

                            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                                <p class="text-xs text-amber-700 font-medium flex items-center gap-1.5">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ __('app.ai_disclaimer') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" :disabled="analyzing || !{{ $isConfigured ? 'true' : 'false' }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-violet-600 hover:bg-violet-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl shadow-sm shadow-violet-500/20 transition-all">
                        <template x-if="!analyzing">
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ __('app.start_analysis') }}
                            </span>
                        </template>
                        <template x-if="analyzing">
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                {{ __('app.analyzing') }}...
                            </span>
                        </template>
                    </button>
                </div>
            </form>
        </div>

        {{-- Results Section --}}
        @if(session('ai_result'))
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" id="results">
            <div class="border-b border-gray-100 p-5 flex items-center gap-3 bg-emerald-50">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-emerald-900">{{ __('app.analysis_result') }}</h3>
                <span class="ms-auto text-xs text-emerald-600 bg-emerald-100 px-2 py-1 rounded-lg font-medium">AI Report</span>
            </div>

            <div class="p-5 space-y-5">
                @php
                    $aiData = session('ai_result');
                    $result = $aiData['result'] ?? [];
                    $analysis = $result['analysis'] ?? [];
                    $risk = $result['risk_assessment'] ?? [];
                    $recommendations = $result['recommendations'] ?? $aiData['recommendations'] ?? [];
                @endphp

                {{-- Image Quality --}}
                @if(isset($result['image_quality']))
                <div class="bg-gray-50 rounded-xl p-4 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gray-200 flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">{{ __('app.image_quality') }}</p>
                        <p class="text-sm text-gray-800">{{ $result['image_quality'] }}</p>
                    </div>
                </div>
                @endif

                {{-- Risk Assessment Gauges --}}
                @if(!empty($risk))
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $gauges = [
                            ['key' => 'overall_health_risk_percentage', 'label' => __('app.health_risk'), 'color' => 'red'],
                            ['key' => 'urgency_percentage', 'label' => __('app.urgency'), 'color' => 'orange'],
                            ['key' => 'severity_percentage', 'label' => __('app.severity'), 'color' => 'amber'],
                            ['key' => 'complication_risk_percentage', 'label' => __('app.complication_risk'), 'color' => 'rose'],
                        ];
                    @endphp
                    @foreach($gauges as $gauge)
                        @if(isset($risk[$gauge['key']]))
                        @php $val = $risk[$gauge['key']]; @endphp
                        <div class="bg-white border border-gray-100 rounded-xl p-4 text-center">
                            <div class="relative w-16 h-16 mx-auto mb-2">
                                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 36 36">
                                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                          fill="none" stroke="#E5E7EB" stroke-width="3"/>
                                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                          fill="none"
                                          stroke="{{ $val >= 70 ? '#EF4444' : ($val >= 40 ? '#F59E0B' : '#10B981') }}"
                                          stroke-width="3" stroke-dasharray="{{ $val }}, 100"/>
                                </svg>
                                <span class="absolute inset-0 flex items-center justify-center text-sm font-bold {{ $val >= 70 ? 'text-red-600' : ($val >= 40 ? 'text-amber-600' : 'text-emerald-600') }}">{{ $val }}%</span>
                            </div>
                            <p class="text-xs font-medium text-gray-600">{{ $gauge['label'] }}</p>
                        </div>
                        @endif
                    @endforeach
                </div>
                @if(isset($risk['assessment_explanation']))
                <div class="bg-violet-50 rounded-xl p-4">
                    <p class="text-sm text-violet-800 leading-relaxed">{{ $risk['assessment_explanation'] }}</p>
                </div>
                @endif
                @endif

                {{-- Analysis Findings --}}
                @if(!empty($analysis))
                <div class="space-y-4">
                    @if(isset($analysis['findings']))
                    <div class="bg-amber-50 rounded-xl p-5">
                        <h4 class="text-sm font-semibold text-amber-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            {{ __('app.findings') }}
                        </h4>
                        <p class="text-sm text-amber-800 leading-relaxed">{{ $analysis['findings'] }}</p>
                    </div>
                    @endif

                    @if(isset($analysis['potential_abnormalities']))
                    <div class="bg-red-50 rounded-xl p-5">
                        <h4 class="text-sm font-semibold text-red-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ __('app.potential_abnormalities') }}
                        </h4>
                        <p class="text-sm text-red-800 leading-relaxed">{{ $analysis['potential_abnormalities'] }}</p>
                    </div>
                    @endif

                    @if(isset($analysis['observations']))
                    <div class="bg-blue-50 rounded-xl p-5">
                        <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ __('app.observations') }}
                        </h4>
                        <p class="text-sm text-blue-800 leading-relaxed">{{ $analysis['observations'] }}</p>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Recommendations --}}
                @if(!empty($recommendations))
                <div class="bg-emerald-50 rounded-xl p-5">
                    <h4 class="text-sm font-semibold text-emerald-900 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('app.recommendations') }}
                    </h4>
                    @if(is_array($recommendations))
                    <ul class="space-y-2">
                        @foreach($recommendations as $rec)
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span class="text-sm text-emerald-800">{{ is_string($rec) ? $rec : json_encode($rec) }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-sm text-emerald-800 leading-relaxed">{{ $recommendations }}</p>
                    @endif
                </div>
                @endif

                {{-- Fallback: if API returns flat message/report --}}
                @if(isset($aiData['message']) && $aiData['status'] === 'success' && empty($analysis))
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($aiData['message'])) !!}
                    </div>
                </div>
                @endif

                {{-- Raw JSON (collapsible for debugging) --}}
                <details class="bg-gray-50 rounded-xl overflow-hidden">
                    <summary class="px-5 py-3 text-xs font-semibold text-gray-500 cursor-pointer hover:text-gray-700 transition-colors flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        {{ __('app.raw_response') }}
                    </summary>
                    <div class="px-5 pb-4">
                        <pre class="text-xs text-gray-600 overflow-x-auto whitespace-pre-wrap bg-white p-4 rounded-lg border border-gray-200 max-h-96" dir="ltr">{{ json_encode($aiData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </details>

                {{-- Disclaimer --}}
                <div class="flex items-start gap-2 p-4 bg-red-50 border border-red-100 rounded-xl">
                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-xs text-red-700 font-medium leading-relaxed">{{ __('app.ai_medical_disclaimer') }}</p>
                </div>
            </div>
        </div>

        {{-- Auto scroll to results --}}
        <script>document.getElementById('results')?.scrollIntoView({ behavior: 'smooth', block: 'start' });</script>
        @endif

        {{-- Setup Instructions (when not configured) --}}
        @if(!$isConfigured)
        <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ __('app.setup_instructions') }}
            </h3>
            <div class="space-y-3 text-sm text-gray-600">
                <p>{{ __('app.ai_setup_step1') }}</p>
                <div class="bg-white rounded-lg p-3 border border-gray-200 font-mono text-xs" dir="ltr">
                    <span class="text-gray-400"># .env</span><br>
                    <span class="text-emerald-600">RAPIDAPI_KEY</span>=<span class="text-amber-600">your_api_key_here</span>
                </div>
                <p>{{ __('app.ai_setup_step2') }}</p>
            </div>
        </div>
        @endif
    </div>
</x-dashboard-layout>
