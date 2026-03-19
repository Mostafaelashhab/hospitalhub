<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ app()->getLocale()==='ar' ? 'رمز التحقق' : 'Verification Code' }} - {{ __('app.app_name') }}</title>
    @include('partials.meta')
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|cairo:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(app()->getLocale() === 'ar')
    <style>body { font-family: 'Cairo', sans-serif !important; }</style>
    @endif
    <style>
        .otp-input{width:48px;height:56px;text-align:center;font-size:24px;font-weight:800;border-radius:12px;border:2px solid #374151;background:#1f2937;color:#fff;outline:none;transition:all .2s}
        .otp-input:focus{border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,0.15)}
    </style>
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
                <div class="w-14 h-14 bg-emerald-600/15 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>

                <h2 class="text-xl font-bold text-center mb-1">{{ app()->getLocale()==='ar' ? 'أدخل رمز التحقق' : 'Enter verification code' }}</h2>
                <p class="text-sm text-gray-400 text-center mb-2">{{ app()->getLocale()==='ar' ? 'تم إرسال رمز مكون من 6 أرقام على الواتساب' : 'A 6-digit code was sent to your WhatsApp' }}</p>
                <p class="text-sm text-emerald-400 text-center font-bold mb-6" dir="ltr">{{ $phone }}</p>

                <form method="POST" action="{{ route('otp.verify') }}" id="otpForm" x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    <input type="hidden" name="phone" value="{{ $phone }}">
                    <input type="hidden" name="purpose" value="{{ $purpose }}">
                    <input type="hidden" name="code" id="otpCode">

                    {{-- OTP Inputs --}}
                    <div class="flex justify-center gap-3 mb-6" dir="ltr" id="otpBox">
                        <input type="text" maxlength="1" inputmode="numeric" class="otp-input" data-i="0" autofocus>
                        <input type="text" maxlength="1" inputmode="numeric" class="otp-input" data-i="1">
                        <input type="text" maxlength="1" inputmode="numeric" class="otp-input" data-i="2">
                        <input type="text" maxlength="1" inputmode="numeric" class="otp-input" data-i="3">
                        <input type="text" maxlength="1" inputmode="numeric" class="otp-input" data-i="4">
                        <input type="text" maxlength="1" inputmode="numeric" class="otp-input" data-i="5">
                    </div>

                    @error('code') <p class="text-red-400 text-xs text-center mb-4">{{ $message }}</p> @enderror

                    <button type="submit" id="otpSubmit" disabled
                            :disabled="loading"
                            class="w-full py-3.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 disabled:opacity-50 flex items-center justify-center gap-2">
                        <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span x-show="!loading">{{ app()->getLocale()==='ar' ? 'تأكيد' : 'Verify' }}</span>
                        <span x-show="loading">{{ app()->getLocale()==='ar' ? 'جاري التحقق...' : 'Verifying...' }}</span>
                    </button>
                </form>

                {{-- Resend --}}
                <div class="mt-6 text-center" x-data="{ timer: {{ (int) $cooldown }}, canResend: {{ (int) $cooldown <= 0 ? 'true' : 'false' }} }"
                     x-init="if(timer > 0) { let i = setInterval(() => { timer--; if(timer <= 0) { canResend = true; clearInterval(i); } }, 1000); }">
                    <template x-if="!canResend">
                        <p class="text-sm text-gray-500">
                            {{ app()->getLocale()==='ar' ? 'إعادة الإرسال بعد' : 'Resend in' }}
                            <span class="text-white font-bold" x-text="timer"></span>
                            {{ app()->getLocale()==='ar' ? 'ثانية' : 's' }}
                        </p>
                    </template>
                    <template x-if="canResend">
                        <form method="POST" action="{{ $purpose === 'login' ? route('otp.login.send') : route('otp.resend') }}">
                            @csrf
                            <input type="hidden" name="phone" value="{{ $phone }}">
                            <input type="hidden" name="purpose" value="{{ $purpose }}">
                            <button type="submit" class="text-sm text-emerald-400 hover:text-emerald-300 font-semibold transition">
                                {{ app()->getLocale()==='ar' ? 'إعادة إرسال الرمز' : 'Resend code' }}
                            </button>
                        </form>
                    </template>
                </div>

                <div class="mt-4 text-center">
                    @php
                        $backUrl = match($purpose) {
                            'login' => route('otp.login'),
                            'register' => route('register.clinic'),
                            default => route('login'),
                        };
                    @endphp
                    <a href="{{ $backUrl }}" class="text-sm text-gray-400 hover:text-white transition">
                        {{ app()->getLocale()==='ar' ? 'تغيير الرقم' : 'Change number' }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function(){
        var inputs = document.querySelectorAll('.otp-input');
        var codeField = document.getElementById('otpCode');
        var submitBtn = document.getElementById('otpSubmit');

        function getCode(){
            var code = '';
            inputs.forEach(function(inp){ code += inp.value; });
            return code;
        }

        function syncCode(){
            var code = getCode();
            codeField.value = code;
            submitBtn.disabled = code.length < 6;
            // Auto-submit when all 6 digits entered
            if(code.length === 6){
                submitBtn.click();
            }
        }

        inputs.forEach(function(inp, i){
            // Handle input
            inp.addEventListener('input', function(e){
                var val = this.value.replace(/\D/g, '');
                this.value = val.slice(-1);
                if(this.value && i < 5){
                    inputs[i+1].focus();
                }
                syncCode();
            });

            // Handle backspace
            inp.addEventListener('keydown', function(e){
                if(e.key === 'Backspace' && !this.value && i > 0){
                    inputs[i-1].focus();
                    inputs[i-1].value = '';
                    syncCode();
                }
            });

            // Handle paste
            inp.addEventListener('paste', function(e){
                e.preventDefault();
                var text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                for(var j = 0; j < text.length && j < 6; j++){
                    inputs[j].value = text[j];
                }
                if(text.length > 0){
                    inputs[Math.min(text.length, 5)].focus();
                }
                syncCode();
            });

            // Select all on focus
            inp.addEventListener('focus', function(){
                this.select();
            });
        });
    })();
    </script>
</body>
</html>
