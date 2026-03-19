<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') — {{ config('app.name') }}</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800,900|cairo:400,600,700,800,900&display=swap" rel="stylesheet"/>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:{{ app()->getLocale()==='ar'?"'Cairo'":"'Inter'" }},system-ui,sans-serif;min-height:100vh;background:#fff}
        .page{min-height:100vh;display:grid;grid-template-columns:1fr 1fr}
        .side-img{background:@yield('bg-color','#eef2ff');display:flex;align-items:center;justify-content:center;padding:40px;position:relative;overflow:hidden}
        .side-img::before{content:'';position:absolute;width:300px;height:300px;border-radius:50%;background:@yield('blob-color','rgba(99,102,241,0.1)');top:-50px;right:-50px}
        .side-img::after{content:'';position:absolute;width:200px;height:200px;border-radius:50%;background:@yield('blob2-color','rgba(168,85,247,0.08)');bottom:-30px;left:-30px}
        .side-img svg{position:relative;z-index:1;width:100%;max-width:380px;height:auto}
        .side-text{display:flex;align-items:center;justify-content:center;padding:60px 50px}
        .content{max-width:400px}
        .logo{display:flex;align-items:center;gap:8px;margin-bottom:48px}
        .logo-icon{width:36px;height:36px;background:linear-gradient(135deg,#6366f1,#a855f7);border-radius:10px;display:flex;align-items:center;justify-content:center}
        .logo span{font-size:16px;font-weight:800;color:#0f172a}
        .code{font-size:14px;font-weight:800;color:@yield('accent','#6366f1');text-transform:uppercase;letter-spacing:2px;margin-bottom:12px}
        h1{font-size:32px;font-weight:900;color:#0f172a;line-height:1.2;margin-bottom:12px}
        p{font-size:15px;color:#64748b;line-height:1.8;margin-bottom:36px}
        .btns{display:flex;gap:12px;flex-wrap:wrap}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:12px;font-size:14px;font-weight:700;text-decoration:none;transition:all .2s}
        .btn-primary{background:#0f172a;color:#fff}
        .btn-primary:hover{background:#1e293b;transform:translateY(-1px)}
        .btn-ghost{background:#f1f5f9;color:#475569}
        .btn-ghost:hover{background:#e2e8f0;transform:translateY(-1px)}
        .help{margin-top:40px;padding-top:24px;border-top:1px solid #f1f5f9;font-size:13px;color:#94a3b8}
        .help a{color:@yield('accent','#6366f1');text-decoration:none;font-weight:600}
        @media(max-width:768px){
            .page{grid-template-columns:1fr}
            .side-img{min-height:280px;padding:30px}
            .side-img svg{max-width:260px}
            .side-text{padding:40px 24px}
            h1{font-size:26px}
        }
    </style>
</head>
<body>
<div class="page">
    <div class="side-img">
        @yield('illustration')
    </div>
    <div class="side-text">
        <div class="content">
            <div class="logo">
                <div class="logo-icon">
                    <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span>{{ config('app.name') }}</span>
            </div>
            <div class="code">{{ app()->getLocale()==='ar' ? 'خطأ' : 'Error' }} @yield('code')</div>
            <h1>@yield('title')</h1>
            <p>@yield('message')</p>
            <div class="btns">
                <a href="/" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ app()->getLocale()==='ar'?'الصفحة الرئيسية':'Go Home' }}
                </a>
                <a href="javascript:history.back()" class="btn btn-ghost">
                    {{ app()->getLocale()==='ar'?'رجوع':'Go Back' }}
                </a>
            </div>
            <div class="help">
                {{ app()->getLocale()==='ar' ? 'محتاج مساعدة؟' : 'Need help?' }}
                <a href="tel:01550047838">01550047838</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
