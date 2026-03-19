@extends('errors.layout')
@section('code', '503')
@section('accent', '#4f46e5')
@section('bg-color', '#eef2ff')
@section('blob-color', 'rgba(99,102,241,0.12)')
@section('blob2-color', 'rgba(79,70,229,0.08)')
@section('title', app()->getLocale()==='ar' ? 'النظام في صيانة' : "We're updating things")
@section('message', app()->getLocale()==='ar' ? 'بنحدّث النظام عشان يبقى أحسن. هنرجع في أقرب وقت.' : "We're making things better. We'll be back very soon.")
@section('illustration')
<svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Big gear -->
    <g transform="translate(200,140)">
        <circle cx="0" cy="0" r="55" fill="#e0e7ff" stroke="#c7d2fe" stroke-width="2"/>
        <circle cx="0" cy="0" r="35" fill="#eef2ff" stroke="#a5b4fc" stroke-width="2"/>
        <circle cx="0" cy="0" r="15" fill="#fff" stroke="#818cf8" stroke-width="2.5"/>
        <!-- Gear teeth -->
        <rect x="-8" y="-62" width="16" height="14" rx="4" fill="#c7d2fe"/>
        <rect x="-8" y="48" width="16" height="14" rx="4" fill="#c7d2fe"/>
        <rect x="48" y="-8" width="14" height="16" rx="4" fill="#c7d2fe"/>
        <rect x="-62" y="-8" width="14" height="16" rx="4" fill="#c7d2fe"/>
        <rect x="32" y="-42" width="14" height="16" rx="4" fill="#c7d2fe" transform="rotate(45 39 -34)"/>
        <rect x="-46" y="26" width="14" height="16" rx="4" fill="#c7d2fe" transform="rotate(45 -39 34)"/>
        <rect x="26" y="32" width="14" height="16" rx="4" fill="#c7d2fe" transform="rotate(-45 33 40)"/>
        <rect x="-42" y="-46" width="14" height="16" rx="4" fill="#c7d2fe" transform="rotate(-45 -35 -38)"/>
    </g>
    <!-- Small gear -->
    <g transform="translate(300,90)">
        <circle cx="0" cy="0" r="28" fill="#e0e7ff" stroke="#c7d2fe" stroke-width="1.5"/>
        <circle cx="0" cy="0" r="18" fill="#eef2ff" stroke="#a5b4fc" stroke-width="1.5"/>
        <circle cx="0" cy="0" r="8" fill="#fff" stroke="#818cf8" stroke-width="2"/>
        <rect x="-5" y="-33" width="10" height="10" rx="3" fill="#c7d2fe"/>
        <rect x="-5" y="23" width="10" height="10" rx="3" fill="#c7d2fe"/>
        <rect x="23" y="-5" width="10" height="10" rx="3" fill="#c7d2fe"/>
        <rect x="-33" y="-5" width="10" height="10" rx="3" fill="#c7d2fe"/>
    </g>
    <!-- Person -->
    <circle cx="85" cy="145" r="22" fill="#c7d2fe"/>
    <path d="M60 225 C60 195 72 180 85 180 C98 180 110 195 110 225" fill="#c7d2fe"/>
    <!-- Wrench in hand -->
    <line x1="105" y1="195" x2="140" y2="170" stroke="#a5b4fc" stroke-width="3" stroke-linecap="round"/>
    <circle cx="143" cy="167" r="6" fill="none" stroke="#a5b4fc" stroke-width="2.5"/>
    <!-- Progress bar -->
    <rect x="110" y="235" width="180" height="12" rx="6" fill="#e0e7ff"/>
    <rect x="110" y="235" width="110" height="12" rx="6" fill="#818cf8"/>
    <text x="200" y="265" text-anchor="middle" font-size="11" font-weight="700" fill="#a5b4fc" font-family="system-ui">{{ app()->getLocale()==='ar' ? 'جاري التحديث...' : 'Updating...' }}</text>
    <!-- Stars -->
    <circle cx="340" cy="200" r="5" fill="#e0e7ff"/>
    <circle cx="55" cy="85" r="4" fill="#c7d2fe" opacity="0.5"/>
    <circle cx="350" cy="250" r="6" fill="#eef2ff"/>
</svg>
@endsection
