{{-- Dental Chart - Interactive SVG Diagram --}}
{{-- Expects parent Alpine.js scope with: selectedZones (array), toggleZone(id) method, zoneNotes (object) --}}

<div class="w-full max-w-4xl mx-auto">
    <svg viewBox="0 0 800 600" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
        <defs>
            {{-- Tooth enamel gradient --}}
            <linearGradient id="enamelGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#fefefe"/>
                <stop offset="30%" stop-color="#f5f0e8"/>
                <stop offset="100%" stop-color="#e8e0d0"/>
            </linearGradient>

            {{-- Root gradient (dentin/cementum) --}}
            <linearGradient id="rootGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#e8ddd0"/>
                <stop offset="100%" stop-color="#d4c8b8"/>
            </linearGradient>

            {{-- Selected tooth gradient --}}
            <linearGradient id="selectedGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#818cf8"/>
                <stop offset="100%" stop-color="#6366f1"/>
            </linearGradient>

            {{-- Gum gradient upper --}}
            <linearGradient id="gumUpperGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#f0a8a0"/>
                <stop offset="60%" stop-color="#e89890"/>
                <stop offset="100%" stop-color="#d88880"/>
            </linearGradient>

            {{-- Gum gradient lower --}}
            <linearGradient id="gumLowerGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#d88880"/>
                <stop offset="40%" stop-color="#e89890"/>
                <stop offset="100%" stop-color="#f0a8a0"/>
            </linearGradient>

            {{-- Drop shadow filter --}}
            <filter id="toothShadow" x="-10%" y="-10%" width="120%" height="130%">
                <feDropShadow dx="0" dy="1" stdDeviation="1.2" flood-color="#8a7a6a" flood-opacity="0.2"/>
            </filter>

            {{-- Selected glow filter --}}
            <filter id="toothGlow" x="-20%" y="-20%" width="140%" height="140%">
                <feDropShadow dx="0" dy="0" stdDeviation="3" flood-color="#6366f1" flood-opacity="0.5"/>
            </filter>

            {{-- Fissure pattern for molars --}}
            <filter id="fissureTexture" x="0" y="0" width="100%" height="100%">
                <feTurbulence type="fractalNoise" baseFrequency="0.05" numOctaves="2" seed="3" result="noise"/>
                <feColorMatrix in="noise" type="saturate" values="0" result="gray"/>
                <feBlend in="SourceGraphic" in2="gray" mode="multiply" result="blend"/>
                <feComposite in="blend" in2="SourceGraphic" operator="in"/>
            </filter>
        </defs>

        {{-- Background --}}
        <rect width="800" height="600" fill="transparent"/>

        {{-- ==================== UPPER JAW ==================== --}}
        <text x="400" y="22" text-anchor="middle" class="text-sm" fill="#64748b" font-family="sans-serif" font-size="13" font-weight="600">
            {{ __('Upper Jaw (Maxilla)') }}
        </text>

        {{-- Upper gum line / alveolar ridge --}}
        <path d="M 85,235 Q 85,55 400,42 Q 715,55 715,235"
              fill="url(#gumUpperGrad)" stroke="#c87878" stroke-width="0.8" opacity="0.35"/>

        {{-- Upper arch guide --}}
        <path d="M 95,210 Q 95,60 400,48 Q 705,60 705,210"
              fill="none" stroke="#e8d8d0" stroke-width="0.8" stroke-dasharray="3,3"/>

        {{-- Upper teeth: 1 (upper-right 3rd molar) to 16 (upper-left 3rd molar) --}}
        @php
            $upperTeeth = [
                1  => ['x' => 104, 'y' => 175, 'r' => -30],
                2  => ['x' => 130, 'y' => 148, 'r' => -25],
                3  => ['x' => 158, 'y' => 124, 'r' => -20],
                4  => ['x' => 190, 'y' => 104, 'r' => -15],
                5  => ['x' => 224, 'y' => 88,  'r' => -10],
                6  => ['x' => 260, 'y' => 76,  'r' => -6],
                7  => ['x' => 298, 'y' => 66,  'r' => -3],
                8  => ['x' => 338, 'y' => 62,  'r' => 0],
                9  => ['x' => 378, 'y' => 62,  'r' => 0],
                10 => ['x' => 418, 'y' => 62,  'r' => 0],
                11 => ['x' => 458, 'y' => 66,  'r' => 3],
                12 => ['x' => 496, 'y' => 76,  'r' => 6],
                13 => ['x' => 532, 'y' => 88,  'r' => 10],
                14 => ['x' => 566, 'y' => 104, 'r' => 15],
                15 => ['x' => 598, 'y' => 124, 'r' => 20],
                16 => ['x' => 626, 'y' => 148, 'r' => 25],
            ];

            $lowerTeeth = [
                17 => ['x' => 626, 'y' => 340, 'r' => -25],
                18 => ['x' => 598, 'y' => 364, 'r' => -20],
                19 => ['x' => 566, 'y' => 384, 'r' => -15],
                20 => ['x' => 532, 'y' => 400, 'r' => -10],
                21 => ['x' => 496, 'y' => 412, 'r' => -6],
                22 => ['x' => 458, 'y' => 420, 'r' => -3],
                23 => ['x' => 418, 'y' => 424, 'r' => 0],
                24 => ['x' => 378, 'y' => 426, 'r' => 0],
                25 => ['x' => 338, 'y' => 426, 'r' => 0],
                26 => ['x' => 298, 'y' => 424, 'r' => 0],
                27 => ['x' => 260, 'y' => 420, 'r' => 3],
                28 => ['x' => 224, 'y' => 412, 'r' => 6],
                29 => ['x' => 190, 'y' => 400, 'r' => 10],
                30 => ['x' => 158, 'y' => 384, 'r' => 15],
                31 => ['x' => 130, 'y' => 364, 'r' => 20],
                32 => ['x' => 104, 'y' => 340, 'r' => 25],
            ];

            $allTeeth = $upperTeeth + $lowerTeeth;
            $molars = [1,2,3,14,15,16,17,18,19,30,31,32];
            $premolars = [4,5,12,13,20,21,28,29];
            $canines = [6,11,22,27];
            $incisors = [7,8,9,10,23,24,25,26];
        @endphp

        {{-- Render upper teeth --}}
        @foreach($upperTeeth as $num => $pos)
            @php
                $zoneId = 'tooth_' . $num;
                $isMolar = in_array($num, $molars);
                $isPremolar = in_array($num, $premolars);
                $isCanine = in_array($num, $canines);
                $isIncisor = in_array($num, $incisors);

                $w = $isMolar ? 38 : ($isPremolar ? 32 : ($isCanine ? 26 : 24));
                $h = $isMolar ? 38 : ($isPremolar ? 36 : ($isCanine ? 40 : 34));
                $cx = $pos['x'] + $w / 2;
                $cy = $pos['y'] + $h / 2;
            @endphp
            <g
                @click="toggleZone('{{ $zoneId }}')"
                :filter="selectedZones.includes('{{ $zoneId }}') ? 'url(#toothGlow)' : 'url(#toothShadow)'"
                class="cursor-pointer transition-all duration-150"
                style="transform-origin: {{ $cx }}px {{ $cy }}px;"
            >
                {{-- Roots (drawn first, behind crown) --}}
                @if($isMolar)
                    {{-- Molar: 3 curved roots --}}
                    <path d="M{{ $pos['x'] + $w * 0.2 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w * 0.15 }} {{ $pos['y'] + $h + 8 }}
                              {{ $pos['x'] + $w * 0.12 }} {{ $pos['y'] + $h + 18 }}
                             Q{{ $pos['x'] + $w * 0.1 }} {{ $pos['y'] + $h + 24 }}
                              {{ $pos['x'] + $w * 0.18 }} {{ $pos['y'] + $h + 22 }}
                             Q{{ $pos['x'] + $w * 0.22 }} {{ $pos['y'] + $h + 14 }}
                              {{ $pos['x'] + $w * 0.25 }} {{ $pos['y'] + $h }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                    <path d="M{{ $pos['x'] + $w * 0.42 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w * 0.48 }} {{ $pos['y'] + $h + 10 }}
                              {{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] + $h + 22 }}
                             Q{{ $pos['x'] + $w * 0.52 }} {{ $pos['y'] + $h + 26 }}
                              {{ $pos['x'] + $w * 0.54 }} {{ $pos['y'] + $h + 22 }}
                             Q{{ $pos['x'] + $w * 0.56 }} {{ $pos['y'] + $h + 10 }}
                              {{ $pos['x'] + $w * 0.58 }} {{ $pos['y'] + $h }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                    <path d="M{{ $pos['x'] + $w * 0.75 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w * 0.82 }} {{ $pos['y'] + $h + 8 }}
                              {{ $pos['x'] + $w * 0.86 }} {{ $pos['y'] + $h + 18 }}
                             Q{{ $pos['x'] + $w * 0.88 }} {{ $pos['y'] + $h + 22 }}
                              {{ $pos['x'] + $w * 0.82 }} {{ $pos['y'] + $h + 20 }}
                             Q{{ $pos['x'] + $w * 0.78 }} {{ $pos['y'] + $h + 12 }}
                              {{ $pos['x'] + $w * 0.8 }} {{ $pos['y'] + $h }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                @elseif($isPremolar)
                    {{-- Premolar: 2 roots --}}
                    <path d="M{{ $pos['x'] + $w * 0.25 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w * 0.2 }} {{ $pos['y'] + $h + 8 }}
                              {{ $pos['x'] + $w * 0.18 }} {{ $pos['y'] + $h + 18 }}
                             Q{{ $pos['x'] + $w * 0.2 }} {{ $pos['y'] + $h + 22 }}
                              {{ $pos['x'] + $w * 0.28 }} {{ $pos['y'] + $h + 16 }}
                             Q{{ $pos['x'] + $w * 0.32 }} {{ $pos['y'] + $h + 8 }}
                              {{ $pos['x'] + $w * 0.38 }} {{ $pos['y'] + $h }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                    <path d="M{{ $pos['x'] + $w * 0.62 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w * 0.68 }} {{ $pos['y'] + $h + 8 }}
                              {{ $pos['x'] + $w * 0.72 }} {{ $pos['y'] + $h + 18 }}
                             Q{{ $pos['x'] + $w * 0.74 }} {{ $pos['y'] + $h + 20 }}
                              {{ $pos['x'] + $w * 0.78 }} {{ $pos['y'] + $h + 16 }}
                             Q{{ $pos['x'] + $w * 0.76 }} {{ $pos['y'] + $h + 8 }}
                              {{ $pos['x'] + $w * 0.75 }} {{ $pos['y'] + $h }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                @elseif($isCanine)
                    {{-- Canine: 1 long tapered root --}}
                    <path d="M{{ $pos['x'] + $w * 0.3 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w * 0.35 }} {{ $pos['y'] + $h + 12 }}
                              {{ $pos['x'] + $w * 0.45 }} {{ $pos['y'] + $h + 26 }}
                             Q{{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] + $h + 30 }}
                              {{ $pos['x'] + $w * 0.55 }} {{ $pos['y'] + $h + 26 }}
                             Q{{ $pos['x'] + $w * 0.65 }} {{ $pos['y'] + $h + 12 }}
                              {{ $pos['x'] + $w * 0.7 }} {{ $pos['y'] + $h }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                @else
                    {{-- Incisor: 1 shorter conical root --}}
                    <path d="M{{ $pos['x'] + $w * 0.3 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w * 0.38 }} {{ $pos['y'] + $h + 10 }}
                              {{ $pos['x'] + $w * 0.46 }} {{ $pos['y'] + $h + 20 }}
                             Q{{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] + $h + 22 }}
                              {{ $pos['x'] + $w * 0.54 }} {{ $pos['y'] + $h + 20 }}
                             Q{{ $pos['x'] + $w * 0.62 }} {{ $pos['y'] + $h + 10 }}
                              {{ $pos['x'] + $w * 0.7 }} {{ $pos['y'] + $h }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                @endif

                {{-- Crown shape varies by tooth type --}}
                @if($isMolar)
                    {{-- Molar: wide rounded rectangle with bumpy occlusal surface --}}
                    <path d="M{{ $pos['x'] + 3 }} {{ $pos['y'] + $h }}
                             L{{ $pos['x'] + 3 }} {{ $pos['y'] + 8 }}
                             Q{{ $pos['x'] + 3 }} {{ $pos['y'] }} {{ $pos['x'] + 10 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w * 0.3 }} {{ $pos['y'] - 2 }} {{ $pos['x'] + $w * 0.4 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] - 3 }} {{ $pos['x'] + $w * 0.6 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w * 0.7 }} {{ $pos['y'] - 2 }} {{ $pos['x'] + $w - 10 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w - 3 }} {{ $pos['y'] }} {{ $pos['x'] + $w - 3 }} {{ $pos['y'] + 8 }}
                             L{{ $pos['x'] + $w - 3 }} {{ $pos['y'] + $h }}
                             Z"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? 'url(#selectedGrad)' : 'url(#enamelGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#4f46e5' : '#c4b8a8'"
                          stroke-width="1.2"/>
                    {{-- Molar fissure pattern (cross + pits) --}}
                    <line x1="{{ $pos['x'] + 5 }}" y1="{{ $pos['y'] + $h * 0.4 }}"
                          x2="{{ $pos['x'] + $w - 5 }}" y2="{{ $pos['y'] + $h * 0.4 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc60' : '#c4b8a860'"
                          stroke-width="0.8"/>
                    <line x1="{{ $pos['x'] + $w * 0.35 }}" y1="{{ $pos['y'] + 4 }}"
                          x2="{{ $pos['x'] + $w * 0.35 }}" y2="{{ $pos['y'] + $h * 0.4 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc60' : '#c4b8a860'"
                          stroke-width="0.7"/>
                    <line x1="{{ $pos['x'] + $w * 0.65 }}" y1="{{ $pos['y'] + 4 }}"
                          x2="{{ $pos['x'] + $w * 0.65 }}" y2="{{ $pos['y'] + $h * 0.4 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc60' : '#c4b8a860'"
                          stroke-width="0.7"/>
                    {{-- Cusp dots --}}
                    <circle cx="{{ $pos['x'] + $w * 0.2 }}" cy="{{ $pos['y'] + $h * 0.22 }}" r="1.5"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc40' : '#d8d0c440'" stroke="none"/>
                    <circle cx="{{ $pos['x'] + $w * 0.5 }}" cy="{{ $pos['y'] + $h * 0.18 }}" r="1.5"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc40' : '#d8d0c440'" stroke="none"/>
                    <circle cx="{{ $pos['x'] + $w * 0.8 }}" cy="{{ $pos['y'] + $h * 0.22 }}" r="1.5"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc40' : '#d8d0c440'" stroke="none"/>
                    <circle cx="{{ $pos['x'] + $w * 0.3 }}" cy="{{ $pos['y'] + $h * 0.55 }}" r="1.3"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc40' : '#d8d0c440'" stroke="none"/>
                    <circle cx="{{ $pos['x'] + $w * 0.7 }}" cy="{{ $pos['y'] + $h * 0.55 }}" r="1.3"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc40' : '#d8d0c440'" stroke="none"/>
                @elseif($isPremolar)
                    {{-- Premolar: slightly narrower with 2 cusps --}}
                    <path d="M{{ $pos['x'] + 3 }} {{ $pos['y'] + $h }}
                             L{{ $pos['x'] + 3 }} {{ $pos['y'] + 7 }}
                             Q{{ $pos['x'] + 3 }} {{ $pos['y'] }} {{ $pos['x'] + 8 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w * 0.35 }} {{ $pos['y'] - 3 }} {{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] + 1 }}
                             Q{{ $pos['x'] + $w * 0.65 }} {{ $pos['y'] - 3 }} {{ $pos['x'] + $w - 8 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w - 3 }} {{ $pos['y'] }} {{ $pos['x'] + $w - 3 }} {{ $pos['y'] + 7 }}
                             L{{ $pos['x'] + $w - 3 }} {{ $pos['y'] + $h }}
                             Z"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? 'url(#selectedGrad)' : 'url(#enamelGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#4f46e5' : '#c4b8a8'"
                          stroke-width="1.2"/>
                    {{-- Premolar fissure (central groove) --}}
                    <line x1="{{ $pos['x'] + $w * 0.5 }}" y1="{{ $pos['y'] + 3 }}"
                          x2="{{ $pos['x'] + $w * 0.5 }}" y2="{{ $pos['y'] + $h * 0.5 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc50' : '#c4b8a850'"
                          stroke-width="0.7"/>
                    <line x1="{{ $pos['x'] + 5 }}" y1="{{ $pos['y'] + $h * 0.4 }}"
                          x2="{{ $pos['x'] + $w - 5 }}" y2="{{ $pos['y'] + $h * 0.4 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc50' : '#c4b8a850'"
                          stroke-width="0.7"/>
                    {{-- 2 cusp highlights --}}
                    <circle cx="{{ $pos['x'] + $w * 0.3 }}" cy="{{ $pos['y'] + $h * 0.2 }}" r="1.3"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc30' : '#d8d0c430'" stroke="none"/>
                    <circle cx="{{ $pos['x'] + $w * 0.7 }}" cy="{{ $pos['y'] + $h * 0.2 }}" r="1.3"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc30' : '#d8d0c430'" stroke="none"/>
                @elseif($isCanine)
                    {{-- Canine: pointed/conical crown --}}
                    <path d="M{{ $pos['x'] + 2 }} {{ $pos['y'] + $h }}
                             L{{ $pos['x'] + 2 }} {{ $pos['y'] + 10 }}
                             Q{{ $pos['x'] + 2 }} {{ $pos['y'] + 4 }} {{ $pos['x'] + 6 }} {{ $pos['y'] + 4 }}
                             Q{{ $pos['x'] + $w * 0.3 }} {{ $pos['y'] + 2 }} {{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] - 4 }}
                             Q{{ $pos['x'] + $w * 0.7 }} {{ $pos['y'] + 2 }} {{ $pos['x'] + $w - 6 }} {{ $pos['y'] + 4 }}
                             Q{{ $pos['x'] + $w - 2 }} {{ $pos['y'] + 4 }} {{ $pos['x'] + $w - 2 }} {{ $pos['y'] + 10 }}
                             L{{ $pos['x'] + $w - 2 }} {{ $pos['y'] + $h }}
                             Z"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? 'url(#selectedGrad)' : 'url(#enamelGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#4f46e5' : '#c4b8a8'"
                          stroke-width="1.2"/>
                    {{-- Canine ridges --}}
                    <line x1="{{ $pos['x'] + $w * 0.5 }}" y1="{{ $pos['y'] }}"
                          x2="{{ $pos['x'] + $w * 0.5 }}" y2="{{ $pos['y'] + $h * 0.6 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc30' : '#c4b8a830'"
                          stroke-width="0.6"/>
                @else
                    {{-- Incisor: flat/chisel-shaped, wider at incisal edge --}}
                    <path d="M{{ $pos['x'] + 3 }} {{ $pos['y'] + $h }}
                             L{{ $pos['x'] + 4 }} {{ $pos['y'] + 8 }}
                             Q{{ $pos['x'] + 4 }} {{ $pos['y'] + 2 }} {{ $pos['x'] + 7 }} {{ $pos['y'] + 1 }}
                             L{{ $pos['x'] + $w - 7 }} {{ $pos['y'] + 1 }}
                             Q{{ $pos['x'] + $w - 4 }} {{ $pos['y'] + 2 }} {{ $pos['x'] + $w - 4 }} {{ $pos['y'] + 8 }}
                             L{{ $pos['x'] + $w - 3 }} {{ $pos['y'] + $h }}
                             Z"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? 'url(#selectedGrad)' : 'url(#enamelGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#4f46e5' : '#c4b8a8'"
                          stroke-width="1.2"/>
                    {{-- Incisal edge line --}}
                    <line x1="{{ $pos['x'] + 5 }}" y1="{{ $pos['y'] + 4 }}"
                          x2="{{ $pos['x'] + $w - 5 }}" y2="{{ $pos['y'] + 4 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc40' : '#c4b8a840'"
                          stroke-width="0.6"/>
                    {{-- Mamelons hint (for incisors) --}}
                    <circle cx="{{ $pos['x'] + $w * 0.3 }}" cy="{{ $pos['y'] + 3 }}" r="0.8"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc20' : '#c4b8a820'" stroke="none"/>
                    <circle cx="{{ $pos['x'] + $w * 0.5 }}" cy="{{ $pos['y'] + 2.5 }}" r="0.8"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc20' : '#c4b8a820'" stroke="none"/>
                    <circle cx="{{ $pos['x'] + $w * 0.7 }}" cy="{{ $pos['y'] + 3 }}" r="0.8"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc20' : '#c4b8a820'" stroke="none"/>
                @endif

                {{-- Enamel highlight (specular reflection) --}}
                <rect x="{{ $pos['x'] + 5 }}" y="{{ $pos['y'] + 3 }}" width="{{ max($w * 0.3, 6) }}" height="{{ $h * 0.15 }}"
                      rx="2" ry="2" fill="white" opacity="0.15"/>

                {{-- CEJ line (cementoenamel junction) at base of crown --}}
                <line x1="{{ $pos['x'] + 4 }}" y1="{{ $pos['y'] + $h - 1 }}"
                      x2="{{ $pos['x'] + $w - 4 }}" y2="{{ $pos['y'] + $h - 1 }}"
                      :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : '#d4c8b8'"
                      stroke-width="0.6" stroke-dasharray="1.5,1"/>

                {{-- Tooth number --}}
                <text
                    x="{{ $cx }}"
                    y="{{ $pos['y'] + $h * 0.68 }}"
                    text-anchor="middle"
                    dominant-baseline="middle"
                    font-family="sans-serif"
                    font-size="{{ $isMolar ? 11 : ($isPremolar ? 10 : 9) }}"
                    font-weight="600"
                    :fill="selectedZones.includes('{{ $zoneId }}') ? '#ffffff' : '#5a5040'"
                    class="pointer-events-none select-none"
                >{{ $num }}</text>

                {{-- Note indicator dot --}}
                <template x-if="zoneNotes['{{ $zoneId }}'] && zoneNotes['{{ $zoneId }}'].length > 0">
                    <circle
                        cx="{{ $pos['x'] + $w - 4 }}"
                        cy="{{ $pos['y'] + 5 }}"
                        r="3"
                        fill="#f59e0b"
                        stroke="#ffffff"
                        stroke-width="1"
                    />
                </template>

                {{-- Tooltip title --}}
                <title x-text="'Tooth {{ $num }}' + (zoneNotes['{{ $zoneId }}'] ? ': ' + zoneNotes['{{ $zoneId }}'] : '')"></title>

                {{-- Hover overlay --}}
                <rect
                    x="{{ $pos['x'] }}"
                    y="{{ $pos['y'] }}"
                    width="{{ $w }}"
                    height="{{ $h }}"
                    rx="6"
                    ry="6"
                    fill="white"
                    opacity="0"
                    class="hover:opacity-10 transition-opacity duration-150"
                />
            </g>
        @endforeach

        {{-- ==================== CENTER DIVIDER ==================== --}}
        <line x1="400" y1="32" x2="400" y2="570" stroke="#e2e8f0" stroke-width="0.8" stroke-dasharray="4,4" opacity="0.5"/>

        {{-- ==================== LOWER JAW ==================== --}}
        <text x="400" y="575" text-anchor="middle" fill="#64748b" font-family="sans-serif" font-size="13" font-weight="600">
            {{ __('Lower Jaw (Mandible)') }}
        </text>

        {{-- Lower gum line --}}
        <path d="M 85,345 Q 85,525 400,540 Q 715,525 715,345"
              fill="url(#gumLowerGrad)" stroke="#c87878" stroke-width="0.8" opacity="0.35"/>

        {{-- Lower arch guide --}}
        <path d="M 95,370 Q 95,510 400,522 Q 705,510 705,370"
              fill="none" stroke="#e8d8d0" stroke-width="0.8" stroke-dasharray="3,3"/>

        {{-- Render lower teeth --}}
        @foreach($lowerTeeth as $num => $pos)
            @php
                $zoneId = 'tooth_' . $num;
                $isMolar = in_array($num, $molars);
                $isPremolar = in_array($num, $premolars);
                $isCanine = in_array($num, $canines);
                $isIncisor = in_array($num, $incisors);

                $w = $isMolar ? 38 : ($isPremolar ? 32 : ($isCanine ? 26 : 24));
                $h = $isMolar ? 38 : ($isPremolar ? 36 : ($isCanine ? 40 : 34));
                $cx = $pos['x'] + $w / 2;
                $cy = $pos['y'] + $h / 2;
            @endphp
            <g
                @click="toggleZone('{{ $zoneId }}')"
                :filter="selectedZones.includes('{{ $zoneId }}') ? 'url(#toothGlow)' : 'url(#toothShadow)'"
                class="cursor-pointer transition-all duration-150"
                style="transform-origin: {{ $cx }}px {{ $cy }}px;"
            >
                {{-- Roots (extend upward for lower teeth) --}}
                @if($isMolar)
                    <path d="M{{ $pos['x'] + $w * 0.2 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w * 0.15 }} {{ $pos['y'] - 8 }}
                              {{ $pos['x'] + $w * 0.12 }} {{ $pos['y'] - 18 }}
                             Q{{ $pos['x'] + $w * 0.1 }} {{ $pos['y'] - 22 }}
                              {{ $pos['x'] + $w * 0.18 }} {{ $pos['y'] - 20 }}
                             Q{{ $pos['x'] + $w * 0.22 }} {{ $pos['y'] - 12 }}
                              {{ $pos['x'] + $w * 0.25 }} {{ $pos['y'] }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                    <path d="M{{ $pos['x'] + $w * 0.42 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w * 0.48 }} {{ $pos['y'] - 10 }}
                              {{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] - 22 }}
                             Q{{ $pos['x'] + $w * 0.52 }} {{ $pos['y'] - 26 }}
                              {{ $pos['x'] + $w * 0.54 }} {{ $pos['y'] - 22 }}
                             Q{{ $pos['x'] + $w * 0.56 }} {{ $pos['y'] - 10 }}
                              {{ $pos['x'] + $w * 0.58 }} {{ $pos['y'] }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                    <path d="M{{ $pos['x'] + $w * 0.75 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w * 0.82 }} {{ $pos['y'] - 8 }}
                              {{ $pos['x'] + $w * 0.86 }} {{ $pos['y'] - 18 }}
                             Q{{ $pos['x'] + $w * 0.88 }} {{ $pos['y'] - 22 }}
                              {{ $pos['x'] + $w * 0.82 }} {{ $pos['y'] - 20 }}
                             Q{{ $pos['x'] + $w * 0.78 }} {{ $pos['y'] - 12 }}
                              {{ $pos['x'] + $w * 0.8 }} {{ $pos['y'] }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                @elseif($isPremolar)
                    <path d="M{{ $pos['x'] + $w * 0.25 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w * 0.2 }} {{ $pos['y'] - 8 }}
                              {{ $pos['x'] + $w * 0.18 }} {{ $pos['y'] - 18 }}
                             Q{{ $pos['x'] + $w * 0.2 }} {{ $pos['y'] - 20 }}
                              {{ $pos['x'] + $w * 0.28 }} {{ $pos['y'] - 14 }}
                             Q{{ $pos['x'] + $w * 0.32 }} {{ $pos['y'] - 8 }}
                              {{ $pos['x'] + $w * 0.38 }} {{ $pos['y'] }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                    <path d="M{{ $pos['x'] + $w * 0.62 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w * 0.68 }} {{ $pos['y'] - 8 }}
                              {{ $pos['x'] + $w * 0.72 }} {{ $pos['y'] - 18 }}
                             Q{{ $pos['x'] + $w * 0.74 }} {{ $pos['y'] - 20 }}
                              {{ $pos['x'] + $w * 0.78 }} {{ $pos['y'] - 14 }}
                             Q{{ $pos['x'] + $w * 0.76 }} {{ $pos['y'] - 8 }}
                              {{ $pos['x'] + $w * 0.75 }} {{ $pos['y'] }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                @elseif($isCanine)
                    <path d="M{{ $pos['x'] + $w * 0.3 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w * 0.35 }} {{ $pos['y'] - 12 }}
                              {{ $pos['x'] + $w * 0.45 }} {{ $pos['y'] - 26 }}
                             Q{{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] - 30 }}
                              {{ $pos['x'] + $w * 0.55 }} {{ $pos['y'] - 26 }}
                             Q{{ $pos['x'] + $w * 0.65 }} {{ $pos['y'] - 12 }}
                              {{ $pos['x'] + $w * 0.7 }} {{ $pos['y'] }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                @else
                    <path d="M{{ $pos['x'] + $w * 0.3 }} {{ $pos['y'] }}
                             Q{{ $pos['x'] + $w * 0.38 }} {{ $pos['y'] - 10 }}
                              {{ $pos['x'] + $w * 0.46 }} {{ $pos['y'] - 20 }}
                             Q{{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] - 22 }}
                              {{ $pos['x'] + $w * 0.54 }} {{ $pos['y'] - 20 }}
                             Q{{ $pos['x'] + $w * 0.62 }} {{ $pos['y'] - 10 }}
                              {{ $pos['x'] + $w * 0.7 }} {{ $pos['y'] }}"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : 'url(#rootGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#818cf8' : '#c4b8a8'" stroke-width="0.5"/>
                @endif

                {{-- Crown (lower teeth: occlusal surface at bottom) --}}
                @if($isMolar)
                    <path d="M{{ $pos['x'] + 3 }} {{ $pos['y'] }}
                             L{{ $pos['x'] + 3 }} {{ $pos['y'] + $h - 8 }}
                             Q{{ $pos['x'] + 3 }} {{ $pos['y'] + $h }} {{ $pos['x'] + 10 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w * 0.3 }} {{ $pos['y'] + $h + 2 }} {{ $pos['x'] + $w * 0.4 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] + $h + 3 }} {{ $pos['x'] + $w * 0.6 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w * 0.7 }} {{ $pos['y'] + $h + 2 }} {{ $pos['x'] + $w - 10 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w - 3 }} {{ $pos['y'] + $h }} {{ $pos['x'] + $w - 3 }} {{ $pos['y'] + $h - 8 }}
                             L{{ $pos['x'] + $w - 3 }} {{ $pos['y'] }}
                             Z"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? 'url(#selectedGrad)' : 'url(#enamelGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#4f46e5' : '#c4b8a8'"
                          stroke-width="1.2"/>
                    {{-- Fissure pattern --}}
                    <line x1="{{ $pos['x'] + 5 }}" y1="{{ $pos['y'] + $h * 0.6 }}"
                          x2="{{ $pos['x'] + $w - 5 }}" y2="{{ $pos['y'] + $h * 0.6 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc60' : '#c4b8a860'"
                          stroke-width="0.8"/>
                    <line x1="{{ $pos['x'] + $w * 0.35 }}" y1="{{ $pos['y'] + $h * 0.6 }}"
                          x2="{{ $pos['x'] + $w * 0.35 }}" y2="{{ $pos['y'] + $h - 4 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc60' : '#c4b8a860'"
                          stroke-width="0.7"/>
                    <line x1="{{ $pos['x'] + $w * 0.65 }}" y1="{{ $pos['y'] + $h * 0.6 }}"
                          x2="{{ $pos['x'] + $w * 0.65 }}" y2="{{ $pos['y'] + $h - 4 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc60' : '#c4b8a860'"
                          stroke-width="0.7"/>
                    {{-- Cusp hints --}}
                    <circle cx="{{ $pos['x'] + $w * 0.2 }}" cy="{{ $pos['y'] + $h * 0.78 }}" r="1.5"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc40' : '#d8d0c440'" stroke="none"/>
                    <circle cx="{{ $pos['x'] + $w * 0.5 }}" cy="{{ $pos['y'] + $h * 0.82 }}" r="1.5"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc40' : '#d8d0c440'" stroke="none"/>
                    <circle cx="{{ $pos['x'] + $w * 0.8 }}" cy="{{ $pos['y'] + $h * 0.78 }}" r="1.5"
                            :fill="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc40' : '#d8d0c440'" stroke="none"/>
                @elseif($isPremolar)
                    <path d="M{{ $pos['x'] + 3 }} {{ $pos['y'] }}
                             L{{ $pos['x'] + 3 }} {{ $pos['y'] + $h - 7 }}
                             Q{{ $pos['x'] + 3 }} {{ $pos['y'] + $h }} {{ $pos['x'] + 8 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w * 0.35 }} {{ $pos['y'] + $h + 3 }} {{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] + $h - 1 }}
                             Q{{ $pos['x'] + $w * 0.65 }} {{ $pos['y'] + $h + 3 }} {{ $pos['x'] + $w - 8 }} {{ $pos['y'] + $h }}
                             Q{{ $pos['x'] + $w - 3 }} {{ $pos['y'] + $h }} {{ $pos['x'] + $w - 3 }} {{ $pos['y'] + $h - 7 }}
                             L{{ $pos['x'] + $w - 3 }} {{ $pos['y'] }}
                             Z"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? 'url(#selectedGrad)' : 'url(#enamelGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#4f46e5' : '#c4b8a8'"
                          stroke-width="1.2"/>
                    <line x1="{{ $pos['x'] + $w * 0.5 }}" y1="{{ $pos['y'] + $h * 0.5 }}"
                          x2="{{ $pos['x'] + $w * 0.5 }}" y2="{{ $pos['y'] + $h - 3 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc50' : '#c4b8a850'"
                          stroke-width="0.7"/>
                    <line x1="{{ $pos['x'] + 5 }}" y1="{{ $pos['y'] + $h * 0.6 }}"
                          x2="{{ $pos['x'] + $w - 5 }}" y2="{{ $pos['y'] + $h * 0.6 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc50' : '#c4b8a850'"
                          stroke-width="0.7"/>
                @elseif($isCanine)
                    <path d="M{{ $pos['x'] + 2 }} {{ $pos['y'] }}
                             L{{ $pos['x'] + 2 }} {{ $pos['y'] + $h - 10 }}
                             Q{{ $pos['x'] + 2 }} {{ $pos['y'] + $h - 4 }} {{ $pos['x'] + 6 }} {{ $pos['y'] + $h - 4 }}
                             Q{{ $pos['x'] + $w * 0.3 }} {{ $pos['y'] + $h - 2 }} {{ $pos['x'] + $w * 0.5 }} {{ $pos['y'] + $h + 4 }}
                             Q{{ $pos['x'] + $w * 0.7 }} {{ $pos['y'] + $h - 2 }} {{ $pos['x'] + $w - 6 }} {{ $pos['y'] + $h - 4 }}
                             Q{{ $pos['x'] + $w - 2 }} {{ $pos['y'] + $h - 4 }} {{ $pos['x'] + $w - 2 }} {{ $pos['y'] + $h - 10 }}
                             L{{ $pos['x'] + $w - 2 }} {{ $pos['y'] }}
                             Z"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? 'url(#selectedGrad)' : 'url(#enamelGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#4f46e5' : '#c4b8a8'"
                          stroke-width="1.2"/>
                    <line x1="{{ $pos['x'] + $w * 0.5 }}" y1="{{ $pos['y'] + $h * 0.4 }}"
                          x2="{{ $pos['x'] + $w * 0.5 }}" y2="{{ $pos['y'] + $h }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc30' : '#c4b8a830'"
                          stroke-width="0.6"/>
                @else
                    <path d="M{{ $pos['x'] + 3 }} {{ $pos['y'] }}
                             L{{ $pos['x'] + 4 }} {{ $pos['y'] + $h - 8 }}
                             Q{{ $pos['x'] + 4 }} {{ $pos['y'] + $h - 2 }} {{ $pos['x'] + 7 }} {{ $pos['y'] + $h - 1 }}
                             L{{ $pos['x'] + $w - 7 }} {{ $pos['y'] + $h - 1 }}
                             Q{{ $pos['x'] + $w - 4 }} {{ $pos['y'] + $h - 2 }} {{ $pos['x'] + $w - 4 }} {{ $pos['y'] + $h - 8 }}
                             L{{ $pos['x'] + $w - 3 }} {{ $pos['y'] }}
                             Z"
                          :fill="selectedZones.includes('{{ $zoneId }}') ? 'url(#selectedGrad)' : 'url(#enamelGrad)'"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#4f46e5' : '#c4b8a8'"
                          stroke-width="1.2"/>
                    {{-- Incisal edge --}}
                    <line x1="{{ $pos['x'] + 5 }}" y1="{{ $pos['y'] + $h - 4 }}"
                          x2="{{ $pos['x'] + $w - 5 }}" y2="{{ $pos['y'] + $h - 4 }}"
                          :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc40' : '#c4b8a840'"
                          stroke-width="0.6"/>
                @endif

                {{-- Enamel highlight --}}
                <rect x="{{ $pos['x'] + 5 }}" y="{{ $pos['y'] + $h * 0.6 }}" width="{{ max($w * 0.3, 6) }}" height="{{ $h * 0.12 }}"
                      rx="2" ry="2" fill="white" opacity="0.12"/>

                {{-- CEJ line --}}
                <line x1="{{ $pos['x'] + 4 }}" y1="{{ $pos['y'] + 1 }}"
                      x2="{{ $pos['x'] + $w - 4 }}" y2="{{ $pos['y'] + 1 }}"
                      :stroke="selectedZones.includes('{{ $zoneId }}') ? '#a5b4fc' : '#d4c8b8'"
                      stroke-width="0.6" stroke-dasharray="1.5,1"/>

                {{-- Tooth number --}}
                <text
                    x="{{ $cx }}"
                    y="{{ $pos['y'] + $h * 0.38 }}"
                    text-anchor="middle"
                    dominant-baseline="middle"
                    font-family="sans-serif"
                    font-size="{{ $isMolar ? 11 : ($isPremolar ? 10 : 9) }}"
                    font-weight="600"
                    :fill="selectedZones.includes('{{ $zoneId }}') ? '#ffffff' : '#5a5040'"
                    class="pointer-events-none select-none"
                >{{ $num }}</text>

                {{-- Note indicator dot --}}
                <template x-if="zoneNotes['{{ $zoneId }}'] && zoneNotes['{{ $zoneId }}'].length > 0">
                    <circle
                        cx="{{ $pos['x'] + $w - 4 }}"
                        cy="{{ $pos['y'] + $h - 5 }}"
                        r="3"
                        fill="#f59e0b"
                        stroke="#ffffff"
                        stroke-width="1"
                    />
                </template>

                <title x-text="'Tooth {{ $num }}' + (zoneNotes['{{ $zoneId }}'] ? ': ' + zoneNotes['{{ $zoneId }}'] : '')"></title>

                {{-- Hover overlay --}}
                <rect
                    x="{{ $pos['x'] }}"
                    y="{{ $pos['y'] }}"
                    width="{{ $w }}"
                    height="{{ $h }}"
                    rx="6"
                    ry="6"
                    fill="white"
                    opacity="0"
                    class="hover:opacity-10 transition-opacity duration-150"
                />
            </g>
        @endforeach

        {{-- Quadrant labels --}}
        <text x="65" y="140" fill="#94a3b8" font-family="sans-serif" font-size="10" font-weight="500" transform="rotate(-90, 65, 140)">
            {{ __('Right') }}
        </text>
        <text x="745" y="140" fill="#94a3b8" font-family="sans-serif" font-size="10" font-weight="500" transform="rotate(90, 745, 140)">
            {{ __('Left') }}
        </text>

        {{-- Tooth type legend --}}
        <g transform="translate(30, 585)" font-family="sans-serif" font-size="8" fill="#94a3b8">
            <rect x="0" y="-6" width="8" height="8" rx="1" fill="url(#enamelGrad)" stroke="#c4b8a8" stroke-width="0.5"/>
            <text x="12" y="0">Molars (1-3, 14-16, 17-19, 30-32)</text>
            <rect x="220" y="-6" width="8" height="8" rx="1" fill="url(#enamelGrad)" stroke="#c4b8a8" stroke-width="0.5"/>
            <text x="232" y="0">Premolars (4-5, 12-13, 20-21, 28-29)</text>
            <rect x="460" y="-6" width="6" height="8" rx="1" fill="url(#enamelGrad)" stroke="#c4b8a8" stroke-width="0.5"/>
            <text x="470" y="0">Canines (6, 11, 22, 27)</text>
            <rect x="600" y="-6" width="6" height="8" rx="1" fill="url(#enamelGrad)" stroke="#c4b8a8" stroke-width="0.5"/>
            <text x="610" y="0">Incisors (7-10, 23-26)</text>
        </g>
    </svg>
</div>
