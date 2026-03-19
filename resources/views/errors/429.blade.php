@extends('errors.layout')
@section('code', '429')
@section('accent', '#ca8a04')
@section('bg-color', '#fefce8')
@section('blob-color', 'rgba(234,179,8,0.12)')
@section('blob2-color', 'rgba(202,138,4,0.08)')
@section('title', app()->getLocale()==='ar' ? 'هدّي شوية' : 'Please slow down')
@section('message', app()->getLocale()==='ar' ? 'أنت بتضغط كتير وبسرعة. استنى ثواني وحاول تاني.' : "You're clicking too fast. Wait a few seconds and try again.")
@section('illustration')
<svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Speedometer -->
    <path d="M110 200 A90 90 0 0 1 290 200" fill="none" stroke="#fde68a" stroke-width="8" stroke-linecap="round"/>
    <path d="M130 200 A70 70 0 0 1 270 200" fill="none" stroke="#fef3c7" stroke-width="20" stroke-linecap="round"/>
    <!-- Needle (pointing to red/high) -->
    <line x1="200" y1="200" x2="255" y2="140" stroke="#eab308" stroke-width="4" stroke-linecap="round"/>
    <circle cx="200" cy="200" r="8" fill="#eab308"/>
    <circle cx="200" cy="200" r="4" fill="#fff"/>
    <!-- Speed marks -->
    <circle cx="130" cy="155" r="4" fill="#86efac" opacity="0.7"/>
    <circle cx="155" cy="125" r="4" fill="#fde68a" opacity="0.7"/>
    <circle cx="200" cy="115" r="4" fill="#fbbf24" opacity="0.7"/>
    <circle cx="245" cy="125" r="4" fill="#fb923c" opacity="0.7"/>
    <circle cx="270" cy="155" r="4" fill="#f87171" opacity="0.7"/>
    <!-- Lightning bolts -->
    <path d="M310 70 L305 90 L315 88 L308 110" stroke="#fbbf24" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
    <path d="M80 80 L77 95 L85 93 L80 108" stroke="#fcd34d" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" opacity="0.6"/>
    <!-- Warning -->
    <path d="M185 230 L200 208 L215 230 Z" fill="#fef3c7" stroke="#eab308" stroke-width="2"/>
    <text x="200" y="227" text-anchor="middle" font-size="12" font-weight="900" fill="#eab308" font-family="system-ui">!</text>
    <circle cx="340" cy="200" r="8" fill="#fef3c7"/>
    <circle cx="60" cy="180" r="6" fill="#fde68a" opacity="0.4"/>
</svg>
@endsection
