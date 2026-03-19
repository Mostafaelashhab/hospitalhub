@extends('errors.layout')
@section('code', '500')
@section('accent', '#7c3aed')
@section('bg-color', '#faf5ff')
@section('blob-color', 'rgba(139,92,246,0.12)')
@section('blob2-color', 'rgba(168,85,247,0.08)')
@section('title', app()->getLocale()==='ar' ? 'حصل مشكلة عندنا' : 'Something broke on our end')
@section('message', app()->getLocale()==='ar' ? 'في خطأ في السيرفر بتاعنا. الفريق التقني اتبلّغ وبيشتغل على الحل.' : "There's a problem with our server. Our tech team has been notified and is working on a fix.")
@section('illustration')
<svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Server rack -->
    <rect x="130" y="50" width="140" height="200" rx="14" fill="#fff" stroke="#d8b4fe" stroke-width="2"/>
    <!-- Server 1 -->
    <rect x="145" y="70" width="110" height="35" rx="8" fill="#f3e8ff"/>
    <circle cx="165" cy="87" r="5" fill="#a855f7"/>
    <rect x="180" y="83" width="40" height="4" rx="2" fill="#d8b4fe"/>
    <rect x="180" y="91" width="25" height="4" rx="2" fill="#e9d5ff"/>
    <circle cx="240" cy="87" r="4" fill="#22c55e"/>
    <!-- Server 2 -->
    <rect x="145" y="115" width="110" height="35" rx="8" fill="#f3e8ff"/>
    <circle cx="165" cy="132" r="5" fill="#a855f7"/>
    <rect x="180" y="128" width="40" height="4" rx="2" fill="#d8b4fe"/>
    <rect x="180" y="136" width="25" height="4" rx="2" fill="#e9d5ff"/>
    <circle cx="240" cy="132" r="4" fill="#ef4444"/>
    <!-- Server 3 - broken -->
    <rect x="145" y="160" width="110" height="35" rx="8" fill="#fef2f2" stroke="#fca5a5" stroke-width="1" stroke-dasharray="4 3"/>
    <circle cx="165" cy="177" r="5" fill="#d4d4d8"/>
    <rect x="180" y="173" width="40" height="4" rx="2" fill="#e5e7eb"/>
    <rect x="180" y="181" width="25" height="4" rx="2" fill="#f3f4f6"/>
    <circle cx="240" cy="177" r="4" fill="#ef4444"/>
    <!-- X on broken server -->
    <g transform="translate(195,170)">
        <line x1="0" y1="0" x2="10" y2="10" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
        <line x1="10" y1="0" x2="0" y2="10" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
    </g>
    <!-- Wrench -->
    <g transform="translate(290,120) rotate(30)">
        <rect x="-4" y="0" width="8" height="40" rx="3" fill="#d8b4fe"/>
        <circle cx="0" cy="-8" r="12" fill="none" stroke="#d8b4fe" stroke-width="4"/>
    </g>
    <!-- Person fixing -->
    <circle cx="85" cy="150" r="20" fill="#e9d5ff"/>
    <path d="M60 220 C60 190 72 175 85 175 C98 175 110 190 110 220" fill="#e9d5ff"/>
    <line x1="105" y1="190" x2="135" y2="170" stroke="#d8b4fe" stroke-width="2.5" stroke-linecap="round"/>
    <!-- Sparks -->
    <circle cx="320" cy="70" r="5" fill="#fde68a" opacity="0.6"/>
    <circle cx="55" cy="100" r="4" fill="#e9d5ff" opacity="0.5"/>
    <path d="M300 180 l5-10 l5 10" stroke="#d8b4fe" stroke-width="2" stroke-linecap="round" fill="none" opacity="0.4"/>
</svg>
@endsection
