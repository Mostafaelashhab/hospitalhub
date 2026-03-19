@extends('errors.layout')
@section('code', '419')
@section('accent', '#ea580c')
@section('bg-color', '#fff7ed')
@section('blob-color', 'rgba(249,115,22,0.12)')
@section('blob2-color', 'rgba(234,88,12,0.08)')
@section('title', app()->getLocale()==='ar' ? 'الجلسة خلصت' : 'Your session timed out')
@section('message', app()->getLocale()==='ar' ? 'عشان أمانك، الجلسة بتنتهي بعد فترة. حدّث الصفحة وسجل دخول تاني.' : "For your security, sessions expire after a while. Refresh the page and log in again.")
@section('illustration')
<svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Hourglass -->
    <path d="M140 60 L260 60" stroke="#fdba74" stroke-width="4" stroke-linecap="round"/>
    <path d="M140 240 L260 240" stroke="#fdba74" stroke-width="4" stroke-linecap="round"/>
    <path d="M155 60 L155 110 C155 130 175 150 200 150 C225 150 245 130 245 110 L245 60" fill="#fff7ed" stroke="#fb923c" stroke-width="2.5"/>
    <path d="M155 240 L155 190 C155 170 175 150 200 150 C225 150 245 170 245 190 L245 240" fill="#fff7ed" stroke="#fb923c" stroke-width="2.5"/>
    <!-- Sand top -->
    <path d="M170 80 L230 80 L210 120 C205 128 195 128 190 120 Z" fill="#fdba74" opacity="0.5"/>
    <!-- Sand falling -->
    <line x1="200" y1="135" x2="200" y2="160" stroke="#f97316" stroke-width="2" stroke-dasharray="3 5"/>
    <!-- Sand bottom -->
    <path d="M175 230 L225 230 L215 200 C210 190 190 190 185 200 Z" fill="#fdba74" opacity="0.7"/>
    <!-- Clock arrows around -->
    <g transform="translate(310,90)" opacity="0.4">
        <circle cx="0" cy="0" r="20" fill="none" stroke="#fdba74" stroke-width="2"/>
        <line x1="0" y1="0" x2="0" y2="-12" stroke="#f97316" stroke-width="2" stroke-linecap="round"/>
        <line x1="0" y1="0" x2="8" y2="4" stroke="#f97316" stroke-width="2" stroke-linecap="round"/>
    </g>
    <!-- Person waiting -->
    <circle cx="80" cy="170" r="20" fill="#fed7aa"/>
    <path d="M55 240 C55 210 67 195 80 195 C93 195 105 210 105 240" fill="#fed7aa"/>
    <!-- Zzz -->
    <text x="100" y="155" font-size="14" font-weight="800" fill="#fdba74" opacity="0.6" font-family="system-ui">z</text>
    <text x="112" y="145" font-size="12" font-weight="800" fill="#fdba74" opacity="0.4" font-family="system-ui">z</text>
    <text x="120" y="138" font-size="10" font-weight="800" fill="#fdba74" opacity="0.3" font-family="system-ui">z</text>
    <circle cx="330" cy="220" r="7" fill="#ffedd5"/>
    <circle cx="60" cy="90" r="5" fill="#fed7aa" opacity="0.5"/>
</svg>
@endsection
