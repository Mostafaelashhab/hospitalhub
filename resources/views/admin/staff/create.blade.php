<x-dashboard-layout>
    <x-slot name="title">{{ __('app.add_staff') }}</x-slot>
    <x-slot name="pageTitle">{{ __('app.add_staff') }}</x-slot>

    <x-slot name="sidebar">
        @include('partials.dashboard-sidebar', ['active' => 'staff'])
    </x-slot>

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('dashboard.staff.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('app.back') }}
        </a>
    </div>

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('app.add_staff') }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ __('app.add_staff_desc') }}</p>
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
        <form method="POST" action="{{ route('dashboard.staff.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.email') }} <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required dir="ltr"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" dir="ltr"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.role') }} <span class="text-red-500">*</span></label>
                    <select name="role" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">{{ __('app.select_role') }}</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->slug }}" {{ old('role') === $role->slug ? 'selected' : '' }}>{{ $role->localizedName() }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.password') }} <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
            </div>

            {{-- Branch Access --}}
            @if($branches->count() > 1)
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('app.branch_access') }}</label>
                <p class="text-xs text-gray-400 mb-3">{{ __('app.branch_access_desc') }}</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach($branches as $branch)
                    <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition-all hover:border-indigo-200 hover:bg-indigo-50/30 has-[:checked]:border-indigo-300 has-[:checked]:bg-indigo-50">
                        <input type="checkbox" name="branch_ids[]" value="{{ $branch->id }}" {{ in_array($branch->id, old('branch_ids', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-sm font-medium text-gray-800">{{ $branch->name }}</span>
                            @if($branch->is_main)
                            <span class="text-[10px] font-bold text-indigo-600 bg-indigo-100 px-1.5 py-0.5 rounded {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">{{ __('app.main_branch') }}</span>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-2">{{ __('app.branch_access_hint') }}</p>
            </div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('app.save') }}
                </button>
                <a href="{{ route('dashboard.staff.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all">
                    {{ __('app.cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-dashboard-layout>
