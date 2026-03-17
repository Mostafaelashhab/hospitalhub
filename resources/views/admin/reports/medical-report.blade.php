<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('app.medical_report') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'DejaVu Sans', Arial" : "Arial, 'DejaVu Sans'" }}, sans-serif;
            font-size: 12px;
            color: #1a1a2e;
            background: #ffffff;
            direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
        }

        .page {
            width: 100%;
            padding: 20px 30px;
        }

        /* ── Header ── */
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #1a5276;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }};
            width: 40%;
        }

        .clinic-logo {
            max-height: 60px;
            max-width: 160px;
            margin-bottom: 6px;
        }

        .clinic-name {
            font-size: 18px;
            font-weight: bold;
            color: #1a5276;
            line-height: 1.3;
        }

        .clinic-sub {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
            line-height: 1.5;
        }

        .report-title-box {
            background: #1a5276;
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 6px;
            text-align: center;
            display: inline-block;
        }

        .report-title-box .report-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .report-title-box .report-date {
            font-size: 10px;
            margin-top: 3px;
            opacity: 0.85;
        }

        /* ── Info Grid ── */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 16px;
            border-collapse: separate;
            border-spacing: 8px 0;
        }

        .info-box {
            display: table-cell;
            width: 50%;
            border: 1px solid #aed6f1;
            border-radius: 6px;
            padding: 10px 14px;
            background: #eaf4fc;
            vertical-align: top;
        }

        .info-box-title {
            font-size: 11px;
            font-weight: bold;
            color: #1a5276;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 1px solid #aed6f1;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #333;
            font-size: 11px;
            width: 45%;
            padding-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 6px;
        }

        .info-value {
            display: table-cell;
            color: #555;
            font-size: 11px;
        }

        /* ── Section blocks ── */
        .section {
            margin-bottom: 14px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #ffffff;
            background: #1a5276;
            padding: 5px 12px;
            border-radius: 4px 4px 0 0;
        }

        .section-body {
            border: 1px solid #aed6f1;
            border-top: none;
            border-radius: 0 0 4px 4px;
            padding: 10px 14px;
            background: #fafcff;
            min-height: 36px;
            font-size: 12px;
            color: #2c3e50;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px dashed #c0d8ea;
            margin: 12px 0;
        }

        /* ── Footer ── */
        .footer {
            border-top: 2px solid #1a5276;
            margin-top: 24px;
            padding-top: 10px;
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            font-size: 10px;
            color: #777;
            vertical-align: bottom;
        }

        .footer-right {
            display: table-cell;
            text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }};
            font-size: 10px;
            color: #777;
            vertical-align: bottom;
        }

        .footer-clinic {
            font-weight: bold;
            color: #1a5276;
            font-size: 11px;
        }

        .badge-allergies {
            display: inline-block;
            background: #fadbd8;
            color: #922b21;
            border: 1px solid #f1948a;
            border-radius: 3px;
            padding: 1px 6px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-left">
            @if($clinic->logo)
                <img src="{{ public_path('storage/' . $clinic->logo) }}" class="clinic-logo" alt="logo">
            @endif
            <div class="clinic-name">
                {{ app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en }}
                @if($clinic->name_ar && $clinic->name_en && app()->getLocale() !== 'ar')
                    <br><span style="font-size:13px;color:#555;">{{ $clinic->name_ar }}</span>
                @elseif($clinic->name_ar && $clinic->name_en && app()->getLocale() === 'ar')
                    <br><span style="font-size:13px;color:#555;">{{ $clinic->name_en }}</span>
                @endif
            </div>
            <div class="clinic-sub">
                @if($clinic->phone) {{ __('app.phone') }}: {{ $clinic->phone }} &nbsp;|&nbsp; @endif
                @if($clinic->email) {{ $clinic->email }} @endif
                @if($clinic->address_en || $clinic->address_ar)
                    <br>{{ app()->getLocale() === 'ar' ? $clinic->address_ar : $clinic->address_en }}
                @endif
            </div>
        </div>
        <div class="header-right">
            <div class="report-title-box">
                <div class="report-title">{{ __('app.medical_report') }}</div>
                <div class="report-date">{{ now()->format('d / m / Y') }}</div>
            </div>
        </div>
    </div>

    {{-- ── PATIENT & DOCTOR INFO ── --}}
    <div class="info-grid">
        {{-- Patient Info --}}
        <div class="info-box">
            <div class="info-title info-box-title">{{ __('app.patient_info') }}</div>

            <div class="info-row">
                <span class="info-label">{{ __('app.name') }}:</span>
                <span class="info-value">{{ $patient->name }}</span>
            </div>

            @if($patient->date_of_birth)
            <div class="info-row">
                <span class="info-label">{{ __('app.age') }}:</span>
                <span class="info-value">{{ $patient->date_of_birth->age }} {{ __('app.years') }}</span>
            </div>
            @endif

            @if($patient->gender)
            <div class="info-row">
                <span class="info-label">{{ __('app.gender') }}:</span>
                <span class="info-value">{{ ucfirst($patient->gender) }}</span>
            </div>
            @endif

            @if($patient->blood_type)
            <div class="info-row">
                <span class="info-label">{{ __('app.blood_type') }}:</span>
                <span class="info-value">{{ $patient->blood_type }}</span>
            </div>
            @endif

            @if($patient->phone)
            <div class="info-row">
                <span class="info-label">{{ __('app.phone') }}:</span>
                <span class="info-value">{{ $patient->phone }}</span>
            </div>
            @endif

            @if($patient->allergies)
            <div class="info-row">
                <span class="info-label">{{ __('app.allergies') }}:</span>
                <span class="info-value">
                    <span class="badge-allergies">{{ $patient->allergies }}</span>
                </span>
            </div>
            @endif
        </div>

        {{-- Doctor Info --}}
        <div class="info-box">
            <div class="info-title info-box-title">{{ __('app.doctor_info') }}</div>

            @if($doctor)
            <div class="info-row">
                <span class="info-label">{{ __('app.doctor') }}:</span>
                <span class="info-value">{{ $doctor->name }}</span>
            </div>

            @if($doctor->specialty)
            <div class="info-row">
                <span class="info-label">{{ __('app.specialty') }}:</span>
                <span class="info-value">
                    {{ app()->getLocale() === 'ar' && isset($doctor->specialty->name_ar) ? $doctor->specialty->name_ar : $doctor->specialty->name_en ?? $doctor->specialty->name ?? '' }}
                </span>
            </div>
            @endif
            @endif

            <div class="info-row">
                <span class="info-label">{{ __('app.appointment_date') }}:</span>
                <span class="info-value">
                    {{ $diagnosis->appointment->appointment_date->format('d / m / Y') }}
                </span>
            </div>

            @if($diagnosis->appointment->appointment_time)
            <div class="info-row">
                <span class="info-label">{{ __('app.appointment_time') }}:</span>
                <span class="info-value">{{ $diagnosis->appointment->appointment_time->format('H:i') }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- ── REPORT BODY ── --}}

    @if($diagnosis->complaint)
    <div class="section">
        <div class="section-title">{{ __('app.chief_complaint') }}</div>
        <div class="section-body">{{ $diagnosis->complaint }}</div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">{{ __('app.diagnoses') }}</div>
        <div class="section-body">{{ $diagnosis->diagnosis ?: '—' }}</div>
    </div>

    @if($diagnosis->prescription)
    <div class="section">
        <div class="section-title">{{ __('app.treatment_plan') }}</div>
        <div class="section-body">{{ $diagnosis->prescription }}</div>
    </div>
    @endif

    @if($diagnosis->lab_tests)
    <div class="section">
        <div class="section-title">{{ __('app.lab_tests_ordered') }}</div>
        <div class="section-body">{{ $diagnosis->lab_tests }}</div>
    </div>
    @endif

    @if($diagnosis->radiology)
    <div class="section">
        <div class="section-title">{{ __('app.radiology_ordered') }}</div>
        <div class="section-body">{{ $diagnosis->radiology }}</div>
    </div>
    @endif

    @if($diagnosis->notes)
    <div class="section">
        <div class="section-title">{{ __('app.notes') }}</div>
        <div class="section-body">{{ $diagnosis->notes }}</div>
    </div>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <div class="footer-left">
            <span class="footer-clinic">
                {{ app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en }}
            </span><br>
            {{ __('app.computer_generated') }}
        </div>
        <div class="footer-right">
            {{ __('app.report_generated') }}: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

</div>
</body>
</html>
