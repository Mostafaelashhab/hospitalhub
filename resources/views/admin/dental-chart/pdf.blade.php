<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('app.dental_chart') }} - {{ $patient->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; padding: 20px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #6366f1; padding-bottom: 15px; margin-bottom: 20px; }
        .header-left { }
        .header-right { text-align: right; }
        .clinic-name { font-size: 18px; font-weight: 800; color: #6366f1; }
        .patient-name { font-size: 16px; font-weight: 700; color: #111827; margin-top: 4px; }
        .meta { font-size: 10px; color: #6b7280; margin-top: 2px; }
        h2 { font-size: 14px; font-weight: 700; color: #111827; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #f3f4f6; padding: 6px 8px; text-align: left; font-size: 10px; font-weight: 700; color: #374151; border: 1px solid #e5e7eb; }
        td { padding: 5px 8px; border: 1px solid #e5e7eb; font-size: 10px; }
        .status-healthy { color: #059669; }
        .status-cavity { color: #dc2626; font-weight: 700; }
        .status-filling { color: #2563eb; }
        .status-crown { color: #7c3aed; }
        .status-extraction { color: #dc2626; }
        .status-implant { color: #0891b2; }
        .status-root_canal { color: #ea580c; }
        .status-bridge { color: #4f46e5; }
        .status-veneer { color: #db2777; }
        .status-missing { color: #6b7280; font-style: italic; }
        .quadrant-title { font-size: 11px; font-weight: 700; color: #6366f1; padding: 8px 0 4px; }
        .summary-box { display: inline-block; padding: 4px 10px; margin: 2px 4px; border-radius: 4px; font-size: 10px; font-weight: 600; }
        .footer { margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 10px; text-align: center; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>
    {{-- Header --}}
    <table style="border: none; margin-bottom: 15px;">
        <tr style="border: none;">
            <td style="border: none; width: 50%; vertical-align: top;">
                <div class="clinic-name">{{ $clinic->name_en }}</div>
                @if($clinic->name_ar && $clinic->name_ar !== $clinic->name_en)
                <div style="font-size: 14px; color: #6366f1;">{{ $clinic->name_ar }}</div>
                @endif
                @if($clinic->phone)
                <div class="meta">{{ $clinic->phone }}</div>
                @endif
            </td>
            <td style="border: none; width: 50%; text-align: right; vertical-align: top;">
                <div class="patient-name">{{ $patient->name }}</div>
                <div class="meta">{{ $patient->phone ?? '' }}</div>
                <div class="meta">{{ __('app.date') }}: {{ $chart ? $chart->created_at->format('d/m/Y') : now()->format('d/m/Y') }}</div>
                @if($chart && $chart->doctor)
                <div class="meta">{{ __('app.doctor') }}: {{ $chart->doctor->name }}</div>
                @endif
            </td>
        </tr>
    </table>

    <div style="border-bottom: 2px solid #6366f1; margin-bottom: 15px;"></div>

    <h2 style="text-align: center; font-size: 16px; margin-bottom: 15px;">{{ __('app.dental_chart') }}</h2>

    {{-- Summary --}}
    @php
        $statusCounts = collect($toothData)->groupBy('status')->map->count();
        $statusLabels = [
            'healthy' => ['label' => 'Healthy', 'bg' => '#ecfdf5', 'color' => '#059669'],
            'cavity' => ['label' => 'Cavity', 'bg' => '#fef2f2', 'color' => '#dc2626'],
            'filling' => ['label' => 'Filling', 'bg' => '#eff6ff', 'color' => '#2563eb'],
            'crown' => ['label' => 'Crown', 'bg' => '#f5f3ff', 'color' => '#7c3aed'],
            'extraction' => ['label' => 'Extraction', 'bg' => '#fef2f2', 'color' => '#dc2626'],
            'implant' => ['label' => 'Implant', 'bg' => '#ecfeff', 'color' => '#0891b2'],
            'root_canal' => ['label' => 'Root Canal', 'bg' => '#fff7ed', 'color' => '#ea580c'],
            'bridge' => ['label' => 'Bridge', 'bg' => '#eef2ff', 'color' => '#4f46e5'],
            'veneer' => ['label' => 'Veneer', 'bg' => '#fdf2f8', 'color' => '#db2777'],
            'missing' => ['label' => 'Missing', 'bg' => '#f9fafb', 'color' => '#6b7280'],
        ];
    @endphp

    <div style="margin-bottom: 15px; text-align: center;">
        @foreach($statusCounts as $status => $count)
        @if($count > 0)
        <span class="summary-box" style="background: {{ $statusLabels[$status]['bg'] ?? '#f9fafb' }}; color: {{ $statusLabels[$status]['color'] ?? '#6b7280' }};">
            {{ $statusLabels[$status]['label'] ?? $status }}: {{ $count }}
        </span>
        @endif
        @endforeach
    </div>

    {{-- Upper Jaw --}}
    <div class="quadrant-title">Upper Jaw ({{ app()->getLocale() === 'ar' ? 'الفك العلوي' : 'Maxilla' }})</div>
    <table>
        <thead>
            <tr>
                <th style="width: 60px;">Tooth #</th>
                <th style="width: 100px;">Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28'] as $t)
            @if($toothData[$t]['status'] !== 'healthy' || $toothData[$t]['notes'])
            <tr>
                <td style="font-weight: 700; text-align: center;">{{ $t }}</td>
                <td class="status-{{ $toothData[$t]['status'] }}">{{ ucfirst(str_replace('_', ' ', $toothData[$t]['status'])) }}</td>
                <td>{{ $toothData[$t]['notes'] ?: '-' }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    {{-- Lower Jaw --}}
    <div class="quadrant-title">Lower Jaw ({{ app()->getLocale() === 'ar' ? 'الفك السفلي' : 'Mandible' }})</div>
    <table>
        <thead>
            <tr>
                <th style="width: 60px;">Tooth #</th>
                <th style="width: 100px;">Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38'] as $t)
            @if($toothData[$t]['status'] !== 'healthy' || $toothData[$t]['notes'])
            <tr>
                <td style="font-weight: 700; text-align: center;">{{ $t }}</td>
                <td class="status-{{ $toothData[$t]['status'] }}">{{ ucfirst(str_replace('_', ' ', $toothData[$t]['status'])) }}</td>
                <td>{{ $toothData[$t]['notes'] ?: '-' }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    @if($chart && $chart->notes)
    <div style="margin-top: 10px;">
        <strong>{{ __('app.notes') }}:</strong>
        <p style="margin-top: 4px; color: #374151;">{{ $chart->notes }}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        {{ $clinic->name_en }} &mdash; {{ __('app.dental_chart') }} &mdash; {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
