<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ app()->getLocale()==='ar' ? 'الدخول بالواتساب' : 'WhatsApp Login' }} - {{ __('app.app_name') }}</title>
    @include('partials.meta')
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|cairo:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(app()->getLocale() === 'ar')
    <style>body { font-family: 'Cairo', sans-serif !important; }</style>
    @endif
</head>
<body class="font-sans antialiased bg-gray-950 text-white min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <span class="text-xl font-extrabold">{{ __('app.app_name') }}</span>
                </a>
            </div>

            {{-- Card --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8">
                {{-- WhatsApp icon --}}
                <div class="w-14 h-14 bg-emerald-600/15 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-7 h-7 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>

                <h2 class="text-xl font-bold text-center mb-1">{{ app()->getLocale()==='ar' ? 'الدخول بالواتساب' : 'Login with WhatsApp' }}</h2>
                <p class="text-sm text-gray-400 text-center mb-6">{{ app()->getLocale()==='ar' ? 'هيوصلك رمز تحقق على الواتساب' : "We'll send you a verification code on WhatsApp" }}</p>

                <form method="POST" action="{{ route('otp.login.send') }}" x-data="whatsappOtp()" x-init="check()" @submit="if(online) loading = true; else $event.preventDefault();">
                    @csrf

                    {{-- Service offline banner --}}
                    <div x-show="!checking && !online" x-cloak class="mb-5 p-3 bg-red-600/10 border border-red-600/30 rounded-xl text-red-400 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        <span>@if(app()->getLocale()==='ar') خدمة الواتساب غير متاحة حالياً، حاول لاحقاً @else WhatsApp service is currently unavailable, try again later @endif</span>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ app()->getLocale()==='ar' ? 'رقم الهاتف' : 'Phone Number' }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <input type="text" name="phone" value="{{ old('phone') }}" required autofocus dir="ltr"
                                   placeholder="01xxxxxxxxx"
                                   :disabled="!online && !checking"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-xl ps-12 pe-4 py-3 text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition disabled:opacity-50">
                        </div>
                        @error('phone') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" :disabled="loading || !online"
                            class="w-full py-3.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg x-show="loading || checking" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span x-show="checking">@if(app()->getLocale()==='ar') جاري التحقق من الخدمة... @else Checking service... @endif</span>
                        <span x-show="!checking && !loading && online">{{ app()->getLocale()==='ar' ? 'إرسال رمز التحقق' : 'Send Verification Code' }}</span>
                        <span x-show="!checking && !online">@if(app()->getLocale()==='ar') الخدمة غير متاحة @else Service Unavailable @endif</span>
                        <span x-show="loading && !checking">{{ app()->getLocale()==='ar' ? 'جاري الإرسال...' : 'Sending...' }}</span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition">
                        {{ app()->getLocale()==='ar' ? 'الدخول بالإيميل والباسورد' : 'Login with email & password' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
<script>
function whatsappOtp() {
    return {
        online: false,
        checking: true,
        loading: false,
        async check() {
            try {
                const res = await fetch('{{ route("whatsapp.health") }}');
                const data = await res.json();
                this.online = data.online;
            } catch (e) {
                this.online = false;
            }
            this.checking = false;
        }
    }
}
</script>
</body>
</html>
