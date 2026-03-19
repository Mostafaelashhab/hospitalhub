@extends('errors.layout')
@section('code', '403')
@section('accent', '#dc2626')
@section('bg-color', '#fef2f2')
@section('blob-color', 'rgba(239,68,68,0.1)')
@section('blob2-color', 'rgba(249,115,22,0.08)')
@section('title', app()->getLocale()==='ar' ? 'ممنوع الدخول هنا' : "You can't enter here")
@section('message', app()->getLocale()==='ar' ? 'الصفحة دي محتاجة صلاحيات مش عندك. لو فاكر إن ده غلط، كلّم الأدمن وهيساعدك.' : "You need special permissions to view this page. If you believe this is a mistake, ask your admin for help.")
@section('illustration')
<svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Guard/Shield -->
    <path d="M200 40 L280 80 L280 160 C280 220 240 260 200 280 C160 260 120 220 120 160 L120 80 Z" fill="#fff" stroke="#fca5a5" stroke-width="2"/>
    <path d="M200 60 L260 90 L260 160 C260 210 230 240 200 258 C170 240 140 210 140 160 L140 90 Z" fill="#fee2e2"/>
    <!-- Lock -->
    <rect x="178" y="145" width="44" height="35" rx="6" fill="#ef4444"/>
    <path d="M185 145 L185 130 C185 118 192 110 200 110 C208 110 215 118 215 130 L215 145" fill="none" stroke="#ef4444" stroke-width="4" stroke-linecap="round"/>
    <circle cx="200" cy="160" r="4" fill="#fff"/>
    <line x1="200" y1="163" x2="200" y2="170" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
    <!-- Person -->
    <circle cx="90" cy="180" r="18" fill="#fecaca"/>
    <path d="M65 240 C65 215 77 200 90 200 C103 200 115 215 115 240" fill="#fecaca"/>
    <line x1="90" y1="200" x2="130" y2="170" stroke="#fca5a5" stroke-width="2" stroke-dasharray="6 4"/>
    <!-- X marks -->
    <g transform="translate(310,70)" opacity="0.4">
        <line x1="0" y1="0" x2="16" y2="16" stroke="#f87171" stroke-width="3" stroke-linecap="round"/>
        <line x1="16" y1="0" x2="0" y2="16" stroke="#f87171" stroke-width="3" stroke-linecap="round"/>
    </g>
    <g transform="translate(70,80)" opacity="0.3">
        <line x1="0" y1="0" x2="12" y2="12" stroke="#f87171" stroke-width="2.5" stroke-linecap="round"/>
        <line x1="12" y1="0" x2="0" y2="12" stroke="#f87171" stroke-width="2.5" stroke-linecap="round"/>
    </g>
    <circle cx="330" cy="220" r="8" fill="#fee2e2"/>
    <circle cx="50" cy="130" r="6" fill="#fecaca" opacity="0.5"/>
</svg>
@endsection
