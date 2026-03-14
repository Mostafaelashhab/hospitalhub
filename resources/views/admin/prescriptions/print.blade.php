<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('app.prescription') }} #{{ $prescription->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1a1a1a; font-size: 14px; line-height: 1.5; direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}; }
        .page { max-width: 800px; margin: 0 auto; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
        .clinic-info h1 { font-size: 22px; color: #4f46e5; margin-bottom: 4px; }
        .clinic-info p { font-size: 12px; color: #666; }
        .rx-symbol { font-size: 36px; font-weight: bold; color: #4f46e5; font-family: serif; }
        .patient-info { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; background: #f8f9fa; border-radius: 8px; padding: 16px; margin-bottom: 24px; }
        .patient-info .field { }
        .patient-info .label { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
        .patient-info .value { font-size: 14px; font-weight: 600; color: #333; }
        .drugs-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .drugs-table th { background: #4f46e5; color: white; padding: 10px 14px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }}; }
        .drugs-table td { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; font-size: 13px; }
        .drugs-table tr:last-child td { border-bottom: none; }
        .drug-name { font-weight: 600; color: #1a1a1a; }
        .drug-detail { color: #666; font-size: 12px; }
        .notes-section { background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 14px; margin-bottom: 24px; }
        .notes-section h3 { font-size: 12px; color: #92400e; text-transform: uppercase; margin-bottom: 6px; }
        .notes-section p { font-size: 13px; color: #78350f; }
        .footer { border-top: 2px solid #e5e7eb; padding-top: 20px; display: flex; justify-content: space-between; align-items: flex-end; }
        .signature { text-align: center; }
        .signature .line { width: 200px; border-bottom: 1px solid #333; margin-bottom: 6px; }
        .signature p { font-size: 12px; color: #666; }
        .date-info { font-size: 12px; color: #888; }
        @media print {
            body { padding: 0; }
            .page { padding: 20px; max-width: 100%; }
            .no-print { display: none !important; }
        }
        .print-btn { position: fixed; top: 20px; {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 20px; background: #4f46e5; color: white; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; z-index: 100; }
        .print-btn:hover { background: #4338ca; }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">{{ __('app.print') }}</button>

    <div class="page">
        {{-- Header --}}
        <div class="header">
            <div class="clinic-info">
                <h1>{{ $clinic->name }}</h1>
                <p>{{ $clinic->address ?? '' }}</p>
                <p>{{ $clinic->phone ?? '' }}</p>
            </div>
            <div class="rx-symbol">&#8478;</div>
        </div>

        {{-- Patient Info --}}
        <div class="patient-info">
            <div class="field">
                <div class="label">{{ __('app.patient') }}</div>
                <div class="value">{{ $prescription->patient->name ?? '-' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('app.date') }}</div>
                <div class="value">{{ $prescription->created_at->format('Y-m-d') }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('app.phone') }}</div>
                <div class="value" dir="ltr">{{ $prescription->patient->phone ?? '-' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('app.doctor') }}</div>
                <div class="value">{{ $prescription->doctor->name ?? '-' }}</div>
            </div>
        </div>

        {{-- Drugs Table --}}
        <table class="drugs-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('app.drug_name') }}</th>
                    <th>{{ __('app.dosage') }}</th>
                    <th>{{ __('app.frequency') }}</th>
                    <th>{{ __('app.duration') }}</th>
                    <th>{{ __('app.instructions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescription->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="drug-name">{{ $item->drug_name }}</td>
                    <td>{{ $item->dosage ?: '-' }}</td>
                    <td>{{ $item->frequency ? __('app.' . $item->frequency) : '-' }}</td>
                    <td>{{ $item->duration ?: '-' }}</td>
                    <td class="drug-detail">{{ $item->instructions ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Notes --}}
        @if($prescription->notes)
        <div class="notes-section">
            <h3>{{ __('app.notes') }}</h3>
            <p>{{ $prescription->notes }}</p>
        </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            <div class="date-info">
                <p>{{ __('app.date') }}: {{ $prescription->created_at->format('Y-m-d h:i A') }}</p>
                <p>{{ __('app.prescription') }} #{{ $prescription->id }}</p>
            </div>
            <div class="signature">
                <div class="line"></div>
                <p>{{ $prescription->doctor->name ?? '' }}</p>
                <p>{{ __('app.doctor_signature') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
