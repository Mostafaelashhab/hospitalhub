@php $isEdit = isset($offer); @endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Title EN --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.title') }} (English) <span class="text-red-500">*</span></label>
        <input type="text" name="title_en" value="{{ old('title_en', $isEdit ? $offer->title_en : '') }}" required
               class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. 20% Off on First Visit">
        @error('title_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Title AR --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.title') }} ({{ __('app.arabic') }}) <span class="text-red-500">*</span></label>
        <input type="text" name="title_ar" value="{{ old('title_ar', $isEdit ? $offer->title_ar : '') }}" required dir="rtl"
               class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="مثلا: خصم 20% على اول زيارة">
        @error('title_ar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Description EN --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.description') }} (English)</label>
        <textarea name="description_en" rows="3" class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm resize-none">{{ old('description_en', $isEdit ? $offer->description_en : '') }}</textarea>
        @error('description_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Description AR --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.description') }} ({{ __('app.arabic') }})</label>
        <textarea name="description_ar" rows="3" dir="rtl" class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm resize-none">{{ old('description_ar', $isEdit ? $offer->description_ar : '') }}</textarea>
        @error('description_ar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Type --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.type') }} <span class="text-red-500">*</span></label>
        <select name="type" x-model="type" class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="discount" {{ old('type', $isEdit ? $offer->type : '') === 'discount' ? 'selected' : '' }}>{{ __('app.discount') }}</option>
            <option value="drug_offer" {{ old('type', $isEdit ? $offer->type : '') === 'drug_offer' ? 'selected' : '' }}>{{ __('app.drug_offer') }}</option>
            <option value="promotion" {{ old('type', $isEdit ? $offer->type : '') === 'promotion' ? 'selected' : '' }}>{{ __('app.promotion') }}</option>
        </select>
    </div>

    {{-- Image --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.image') }}</label>
        <input type="file" name="image" accept="image/*" class="w-full text-sm border border-gray-200 rounded-xl file:mr-3 file:ml-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        @if($isEdit && $offer->image)
        <p class="text-xs text-gray-400 mt-1">{{ __('app.current_image_exists') }}</p>
        @endif
        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Discount Percentage --}}
    <div x-show="type === 'discount' || type === 'drug_offer'" x-transition>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.discount_percentage') }}</label>
        <div class="relative">
            <input type="number" name="discount_percentage" step="0.01" min="0" max="100"
                   value="{{ old('discount_percentage', $isEdit ? $offer->discount_percentage : '') }}"
                   class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm {{ app()->getLocale() === 'ar' ? 'pl-10' : 'pr-10' }}">
            <span class="absolute {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }} top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">%</span>
        </div>
    </div>

    {{-- Discount Amount --}}
    <div x-show="type === 'discount' || type === 'drug_offer'" x-transition>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.discount_amount') }}</label>
        <div class="relative">
            <input type="number" name="discount_amount" step="0.01" min="0"
                   value="{{ old('discount_amount', $isEdit ? $offer->discount_amount : '') }}"
                   class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm {{ app()->getLocale() === 'ar' ? 'pl-14' : 'pr-14' }}">
            <span class="absolute {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }} top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold">{{ __('app.egp') }}</span>
        </div>
    </div>

    {{-- Start Date --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.start_date') }} <span class="text-red-500">*</span></label>
        <input type="date" name="start_date" value="{{ old('start_date', $isEdit ? $offer->start_date->format('Y-m-d') : '') }}" required
               class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- End Date --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('app.end_date') }} <span class="text-red-500">*</span></label>
        <input type="date" name="end_date" value="{{ old('end_date', $isEdit ? $offer->end_date->format('Y-m-d') : '') }}" required
               class="w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Target Clinics --}}
<div class="mt-6" x-data="{ forAll: {{ old('for_all_clinics', $isEdit ? ($offer->for_all_clinics ? 'true' : 'false') : 'true') }} }">
    <label class="block text-sm font-semibold text-gray-700 mb-3">{{ __('app.target_clinics') }}</label>
    <div class="flex items-center gap-4 mb-4">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="for_all_clinics" value="1" x-model="forAll" class="text-indigo-600 focus:ring-indigo-500" {{ old('for_all_clinics', $isEdit ? $offer->for_all_clinics : true) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">{{ __('app.all_clinics') }}</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="for_all_clinics" value="0" x-model="forAll" class="text-indigo-600 focus:ring-indigo-500" {{ old('for_all_clinics', $isEdit ? $offer->for_all_clinics : true) ? '' : 'checked' }}>
            <span class="text-sm text-gray-700">{{ __('app.specific_clinics') }}</span>
        </label>
    </div>

    <div x-show="forAll == '0' || forAll === false" x-transition class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto p-3 bg-gray-50 rounded-xl border border-gray-200">
        @foreach($clinics as $clinic)
        <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-white cursor-pointer transition-colors">
            <input type="checkbox" name="clinic_ids[]" value="{{ $clinic->id }}" class="text-indigo-600 rounded focus:ring-indigo-500"
                   {{ $isEdit && $offer->clinics->contains($clinic->id) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700 truncate">{{ app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en }}</span>
        </label>
        @endforeach
    </div>
</div>
