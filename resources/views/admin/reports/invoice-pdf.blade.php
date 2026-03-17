<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('app.invoice') }} #{{ $invoice->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'DejaVu Sans', Arial" : "Arial, 'DejaVu Sans'" }}, sans-serif;
            font-size: 12px;
            color: #1a1a2e;
            background: #fff;
            direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
        }

        .page { padding: 24px 30px; }

        /* Header */
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #117a65;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }
        .header-left  { display: table-cell; vertical-align: middle; width: 60%; }
        .header-right { display: table-cell; vertical-align: middle; text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}; width: 40%; }

        .clinic-logo { max-height: 58px; max-width: 160px; margin-bottom: 5px; }
        .clinic-name { font-size: 17px; font-weight: bold; color: #117a65; }
        .clinic-sub  { font-size: 10px; color: #555; margin-top: 3px; line-height: 1.5; }

        .invoice-title-box {
            background: #117a65;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            text-align: center;
            display: inline-block;
        }
        .invoice-title-box .inv-title { font-size: 16px; font-weight: bold; }
        .invoice-title-box .inv-meta  { font-size: 10px; margin-top: 3px; opacity: 0.85; }

        /* Info row */
        .info-grid { display: table; width: 100%; margin-bottom: 16px; border-collapse: separate; border-spacing: 8px 0; }
        .info-box  { display: table-cell; width: 50%; border: 1px solid #a2d9ce; border-radius: 6px; padding: 10px 14px; background: #e8f8f5; vertical-align: top; }
        .info-box-title { font-size: 11px; font-weight: bold; color: #117a65; text-transform: uppercase; border-bottom: 1px solid #a2d9ce; padding-bottom: 5px; margin-bottom: 8px; }
        .info-row   { display: table; width: 100%; margin-bottom: 4px; }
        .info-label { display: table-cell; font-weight: bold; color: #333; font-size: 11px; width: 45%; padding-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 6px; }
        .info-value { display: table-cell; color: #555; font-size: 11px; }

        /* Items table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .items-table th {
            background: #117a65;
            color: #fff;
            padding: 7px 10px;
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
            font-size: 11px;
        }
        .items-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #d5f0eb;
            font-size: 11px;
            color: #2c3e50;
        }
        .items-table tr:nth-child(even) td { background: #f2fbf8; }
        .text-right { text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}; }

        /* Totals */
        .totals-wrap { display: table; width: 100%; }
        .totals-spacer { display: table-cell; width: 55%; }
        .totals-box {
            display: table-cell;
            width: 45%;
            border: 1px solid #a2d9ce;
            border-radius: 6px;
            padding: 10px 14px;
            background: #e8f8f5;
        }
        .total-row { display: table; width: 100%; margin-bottom: 4px; }
        .total-label { display: table-cell; font-size: 11px; color: #555; }
        .total-value { display: table-cell; text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}; font-size: 11px; color: #333; }
        .total-grand  { font-size: 14px; font-weight: bold; color: #117a65; }
        .divider-green { border: none; border-top: 1px solid #a2d9ce; margin: 6px 0; }

        /* Status badge */
        .badge { display: inline-block; border-radius: 4px; padding: 2px 8px; font-size: 10px; font-weight: bold; }
        .badge-paid     { background: #d5f0eb; color: #0e6655; border: 1px solid #a2d9ce; }
        .badge-unpaid   { background: #fadbd8; color: #922b21; border: 1px solid #f1948a; }
        .badge-partial  { background: #fef9e7; color: #7d6608; border: 1px solid #f7dc6f; }
        .badge-refunded { background: #eaf2ff; color: #1a5276; border: 1px solid #aed6f1; }

        /* Footer */
        .footer { border-top: 2px solid #117a65; margin-top: 24px; padding-top: 10px; display: table; width: 100%; }
        .footer-left  { display: table-cell; font-size: 10px; color: #777; vertical-align: bottom; }
        .footer-right { display: table-cell; text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}; font-size: 10px; color: #777; vertical-align: bottom; }
        .footer-clinic { font-weight: bold; color: #117a65; font-size: 11px; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-left">
            @if($clinic->logo)
                <img src="{{ public_path('storage/' . $clinic->logo) }}" class="clinic-logo" alt="logo">
            @endif
            <div class="clinic-name">
                {{ app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en }}
            </div>
            <div class="clinic-sub">
                @if($clinic->phone) {{ __('app.phone') }}: {{ $clinic->phone }} &nbsp;|&nbsp; @endif
                @if($clinic->email) {{ $clinic->email }} @endif
                @if($clinic->address_en || $clinic->address_ar)
                    <br>{{ app()->getLocale() === 'ar' ? $clinic->address_ar : $clinic->address_en }}
                @endif
                @if($clinic->tax_number)
                    <br>{{ __('app.tax_number') ?? 'Tax #' }}: {{ $clinic->tax_number }}
                @endif
            </div>
        </div>
        <div class="header-right">
            <div class="invoice-title-box">
                <div class="inv-title">{{ __('app.invoice') }}</div>
                <div class="inv-meta"># {{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="inv-meta">{{ now()->format('d / m / Y') }}</div>
            </div>
        </div>
    </div>

    {{-- Patient & Appointment Info --}}
    <div class="info-grid">
        <div class="info-box">
            <div class="info-box-title">{{ __('app.patient_info') }}</div>
            <div class="info-row">
                <span class="info-label">{{ __('app.name') }}:</span>
                <span class="info-value">{{ $invoice->patient->name }}</span>
            </div>
            @if($invoice->patient->phone)
            <div class="info-row">
                <span class="info-label">{{ __('app.phone') }}:</span>
                <span class="info-value">{{ $invoice->patient->phone }}</span>
            </div>
            @endif
            @if($invoice->insuranceProvider)
            <div class="info-row">
                <span class="info-label">{{ __('app.insurance') }}:</span>
                <span class="info-value">{{ $invoice->insuranceProvider->name }}</span>
            </div>
            @endif
        </div>
        <div class="info-box">
            <div class="info-box-title">{{ __('app.invoice') }}</div>
            <div class="info-row">
                <span class="info-label">{{ __('app.status') }}:</span>
                <span class="info-value">
                    <span class="badge badge-{{ $invoice->status }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </span>
            </div>
            @if($invoice->payment_method)
            <div class="info-row">
                <span class="info-label">{{ __('app.payment_method') }}:</span>
                <span class="info-value">{{ ucfirst(str_replace('_', ' ', $invoice->payment_method)) }}</span>
            </div>
            @endif
            @if($invoice->appointment)
            <div class="info-row">
                <span class="info-label">{{ __('app.appointment_date') }}:</span>
                <span class="info-value">{{ $invoice->appointment->appointment_date->format('d / m / Y') }}</span>
            </div>
            @if($invoice->appointment->doctor)
            <div class="info-row">
                <span class="info-label">{{ __('app.doctor') }}:</span>
                <span class="info-value">{{ $invoice->appointment->doctor->name }}</span>
            </div>
            @endif
            @endif
        </div>
    </div>

    {{-- Items Table --}}
    @if($invoice->items && $invoice->items->count() > 0)
    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('app.description') ?? 'Description' }}</th>
                <th class="text-right">{{ __('app.quantity') ?? 'Qty' }}</th>
                <th class="text-right">{{ __('app.price') }}</th>
                <th class="text-right">{{ __('app.total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->description }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->price, 2) }}</td>
                <td class="text-right">{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Totals --}}
    <div class="totals-wrap">
        <div class="totals-spacer"></div>
        <div class="totals-box">
            <div class="total-row">
                <span class="total-label">{{ __('app.subtotal') ?? 'Subtotal' }}</span>
                <span class="total-value">{{ number_format($invoice->amount, 2) }}</span>
            </div>
            @if($invoice->discount > 0)
            <div class="total-row">
                <span class="total-label">{{ __('app.discount') }}</span>
                <span class="total-value">({{ number_format($invoice->discount, 2) }})</span>
            </div>
            @endif
            @if($invoice->insurance_coverage > 0)
            <div class="total-row">
                <span class="total-label">{{ __('app.insurance_coverage') ?? 'Insurance Coverage' }}</span>
                <span class="total-value">({{ number_format($invoice->insurance_coverage, 2) }})</span>
            </div>
            @endif
            <hr class="divider-green">
            <div class="total-row">
                <span class="total-label total-grand">{{ __('app.total') }}</span>
                <span class="total-value total-grand">{{ number_format($invoice->total, 2) }}</span>
            </div>
            @if($invoice->insurance_coverage > 0)
            <div class="total-row">
                <span class="total-label" style="font-size:10px;color:#888;">{{ __('app.patient_share') ?? 'Patient Share' }}</span>
                <span class="total-value" style="font-size:10px;color:#888;">{{ number_format($invoice->patient_share, 2) }}</span>
            </div>
            @endif
        </div>
    </div>

    @if($invoice->notes)
    <div style="margin-top:16px; padding: 10px 14px; border: 1px solid #a2d9ce; border-radius:6px; background:#f2fbf8;">
        <strong style="font-size:11px; color:#117a65;">{{ __('app.notes') }}:</strong>
        <p style="margin-top:4px; font-size:11px; color:#555;">{{ $invoice->notes }}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-left">
            <span class="footer-clinic">{{ app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en }}</span><br>
            {{ __('app.computer_generated') }}
        </div>
        <div class="footer-right">
            {{ __('app.report_generated') }}: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

</div>
</body>
</html>
