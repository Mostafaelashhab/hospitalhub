@extends('errors.layout')
@section('code', '404')
@section('accent', '#d97706')
@section('bg-color', '#fffbeb')
@section('blob-color', 'rgba(245,158,11,0.12)')
@section('blob2-color', 'rgba(234,179,8,0.08)')
@section('title', app()->getLocale()==='ar' ? 'الصفحة دي مش موجودة' : "We can't find this page")
@section('message', app()->getLocale()==='ar' ? 'يمكن اللينك قديم أو الصفحة اتنقلت. جرب ترجع للصفحة الرئيسية.' : "The link might be old or the page has been moved. Try going back to the homepage.")
@section('illustration')
<svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Map / Compass -->
    <rect x="80" y="60" width="180" height="200" rx="16" fill="#fff" stroke="#fde68a" stroke-width="2"/>
    <!-- Map lines -->
    <path d="M100 100 L160 130 L220 90 L240 140" stroke="#fcd34d" stroke-width="2" stroke-linecap="round" stroke-dasharray="6 4"/>
    <path d="M100 160 L140 140 L200 180 L240 150" stroke="#fde68a" stroke-width="2" stroke-linecap="round" stroke-dasharray="6 4"/>
    <path d="M100 200 L150 190 L200 220 L240 195" stroke="#fef3c7" stroke-width="2" stroke-linecap="round"/>
    <!-- Pin with ? -->
    <g transform="translate(170,80)">
        <path d="M0 0 C0-28 -20-40 0-55 C20-40 0-28 0 0z" fill="#f59e0b"/>
        <circle cx="0" cy="-35" r="12" fill="#fff"/>
        <text x="0" y="-30" text-anchor="middle" font-size="16" font-weight="900" fill="#f59e0b" font-family="system-ui">?</text>
    </g>
    <!-- Person looking at map -->
    <circle cx="310" cy="140" r="22" fill="#fde68a"/>
    <path d="M285 210 C285 180 297 165 310 165 C323 165 335 180 335 210" fill="#fde68a"/>
    <!-- Hand pointing -->
    <line x1="290" y1="180" x2="260" y2="160" stroke="#fbbf24" stroke-width="3" stroke-linecap="round"/>
    <!-- Question marks -->
    <text x="320" y="110" font-size="22" font-weight="900" fill="#fcd34d" opacity="0.5" font-family="system-ui">?</text>
    <text x="50" y="150" font-size="18" font-weight="900" fill="#fde68a" opacity="0.4" font-family="system-ui">?</text>
    <!-- Decorative -->
    <circle cx="60" cy="80" r="8" fill="#fef3c7"/>
    <circle cx="350" cy="250" r="10" fill="#fde68a" opacity="0.4"/>
    <path d="M50 240 l8-14 l8 14z" fill="#fde68a" opacity="0.3"/>
</svg>
@endsection
