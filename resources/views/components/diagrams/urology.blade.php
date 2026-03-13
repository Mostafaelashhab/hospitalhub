{{-- Urology — Urinary System Anatomical Diagram --}}
{{-- Expects parent Alpine.js scope with: selectedZones, toggleZone(zoneId), zoneNotes --}}

<svg viewBox="0 0 480 620" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto max-w-lg mx-auto">
    <defs>
        <style>
            .uro-zone { cursor: pointer; transition: fill 0.2s ease, opacity 0.2s ease, stroke 0.2s ease; }
            .uro-zone:hover { opacity: 0.85; filter: url(#uro-hover-glow); }
            .uro-label { font-family: system-ui, -apple-system, sans-serif; font-size: 10px; fill: #374151; pointer-events: none; font-weight: 500; }
            .uro-label-line { stroke: #9CA3AF; stroke-width: 0.75; fill: none; stroke-dasharray: 2,2; pointer-events: none; }
            .uro-detail { pointer-events: none; }
        </style>

        {{-- Kidney cortex gradient --}}
        <radialGradient id="uro-kidney-grad" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#E8A8A0"/>
            <stop offset="50%" stop-color="#C47870"/>
            <stop offset="100%" stop-color="#A85850"/>
        </radialGradient>

        {{-- Kidney medulla gradient --}}
        <radialGradient id="uro-medulla-grad" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="#D08878"/>
            <stop offset="100%" stop-color="#B06858"/>
        </radialGradient>

        {{-- Renal pelvis gradient --}}
        <radialGradient id="uro-pelvis-grad" cx="50%" cy="50%" r="60%">
            <stop offset="0%" stop-color="#F0D0A0"/>
            <stop offset="100%" stop-color="#D4B080"/>
        </radialGradient>

        {{-- Adrenal gradient --}}
        <radialGradient id="uro-adrenal-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#F5E0A0"/>
            <stop offset="60%" stop-color="#E0C870"/>
            <stop offset="100%" stop-color="#C8A850"/>
        </radialGradient>

        {{-- Bladder gradient --}}
        <radialGradient id="uro-bladder-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#C8D8F0"/>
            <stop offset="50%" stop-color="#A0B8D8"/>
            <stop offset="100%" stop-color="#7890B0"/>
        </radialGradient>

        {{-- Prostate gradient --}}
        <radialGradient id="uro-prostate-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#D8C8E8"/>
            <stop offset="100%" stop-color="#A890C0"/>
        </radialGradient>

        {{-- Ureter gradient --}}
        <linearGradient id="uro-ureter-grad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#D09080"/>
            <stop offset="100%" stop-color="#E0A898"/>
        </linearGradient>

        {{-- Shadow filter --}}
        <filter id="uro-shadow" x="-5%" y="-5%" width="115%" height="115%">
            <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#00000020"/>
        </filter>

        {{-- Hover glow --}}
        <filter id="uro-hover-glow" x="-10%" y="-10%" width="120%" height="120%">
            <feDropShadow dx="0" dy="0" stdDeviation="4" flood-color="#818cf860"/>
        </filter>

        {{-- Kidney cross-section clip paths --}}
        <clipPath id="uro-right-kidney-clip">
            <path d="M130 90 Q108 115 106 155 Q108 195 130 220 Q150 235 162 215 Q152 155 162 95 Q150 80 130 90 Z"/>
        </clipPath>
        <clipPath id="uro-left-kidney-clip">
            <path d="M350 90 Q372 115 374 155 Q372 195 350 220 Q330 235 318 215 Q328 155 318 95 Q330 80 350 90 Z"/>
        </clipPath>
    </defs>

    {{-- ==================== BACKGROUND ANATOMY ==================== --}}

    {{-- Spine --}}
    <g class="uro-detail" opacity="0.12">
        <rect x="230" y="40" width="20" height="500" rx="5" fill="#D8D0C0" stroke="#B8A890" stroke-width="0.5"/>
        {{-- Vertebrae hints --}}
        <line x1="225" y1="80" x2="255" y2="80" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="225" y1="110" x2="255" y2="110" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="225" y1="140" x2="255" y2="140" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="225" y1="170" x2="255" y2="170" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="225" y1="200" x2="255" y2="200" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="225" y1="230" x2="255" y2="230" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="225" y1="260" x2="255" y2="260" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="225" y1="290" x2="255" y2="290" stroke="#B8A890" stroke-width="0.5"/>
    </g>

    {{-- Rib cage hints --}}
    <g class="uro-detail" opacity="0.08">
        <path d="M230 65 Q180 70 140 90 Q115 105 110 125" fill="none" stroke="#B8A890" stroke-width="2"/>
        <path d="M250 65 Q300 70 340 90 Q365 105 370 125" fill="none" stroke="#B8A890" stroke-width="2"/>
        <path d="M230 85 Q185 90 150 108 Q125 120 118 140" fill="none" stroke="#B8A890" stroke-width="2"/>
        <path d="M250 85 Q295 90 330 108 Q355 120 362 140" fill="none" stroke="#B8A890" stroke-width="2"/>
        <path d="M230 105 Q190 110 158 125 Q135 138 128 155" fill="none" stroke="#B8A890" stroke-width="2"/>
        <path d="M250 105 Q290 110 322 125 Q345 138 352 155" fill="none" stroke="#B8A890" stroke-width="2"/>
    </g>

    {{-- Pelvic bone hint --}}
    <g class="uro-detail" opacity="0.1">
        <path d="M140 370 Q150 340 190 320 Q220 310 240 315 Q260 310 290 320 Q330 340 340 370 Q345 410 330 440 Q310 460 280 465 L200 465 Q170 460 150 440 Q135 410 140 370 Z"
              fill="none" stroke="#B8A890" stroke-width="2"/>
    </g>

    {{-- Abdominal aorta and IVC --}}
    <g class="uro-detail" opacity="0.12">
        <path d="M236 50 L236 420" stroke="#C04040" stroke-width="3"/>
        <path d="M244 50 L244 420" stroke="#4060A0" stroke-width="3"/>
    </g>

    {{-- ==================== RIGHT ADRENAL ==================== --}}
    <path
        d="M135 72 Q130 55 145 48 Q158 44 168 48 Q175 55 172 68 Q168 78 158 82 Q145 84 138 78 Z"
        class="uro-zone"
        :fill="selectedZones.includes('right_adrenal') ? '#818CF8' : 'url(#uro-adrenal-grad)'"
        stroke="#A09050" stroke-width="1.2"
        @click="toggleZone('right_adrenal')"
        filter="url(#uro-shadow)"
    />
    {{-- Adrenal texture --}}
    <g class="uro-detail" opacity="0.2">
        <path d="M142 58 Q150 55 160 58" fill="none" stroke="#907830" stroke-width="0.5"/>
        <path d="M140 65 Q150 62 165 65" fill="none" stroke="#907830" stroke-width="0.5"/>
        <path d="M142 72 Q152 69 162 72" fill="none" stroke="#907830" stroke-width="0.5"/>
    </g>
    <line x1="135" y1="62" x2="80" y2="52" class="uro-label-line"/>
    <text x="30" y="48" class="uro-label">R. Adrenal</text>

    {{-- ==================== LEFT ADRENAL ==================== --}}
    <path
        d="M345 72 Q350 55 335 48 Q322 44 312 48 Q305 55 308 68 Q312 78 322 82 Q335 84 342 78 Z"
        class="uro-zone"
        :fill="selectedZones.includes('left_adrenal') ? '#818CF8' : 'url(#uro-adrenal-grad)'"
        stroke="#A09050" stroke-width="1.2"
        @click="toggleZone('left_adrenal')"
        filter="url(#uro-shadow)"
    />
    <g class="uro-detail" opacity="0.2">
        <path d="M318 58 Q328 55 338 58" fill="none" stroke="#907830" stroke-width="0.5"/>
        <path d="M315 65 Q328 62 340 65" fill="none" stroke="#907830" stroke-width="0.5"/>
        <path d="M318 72 Q328 69 338 72" fill="none" stroke="#907830" stroke-width="0.5"/>
    </g>
    <line x1="345" y1="62" x2="400" y2="52" class="uro-label-line"/>
    <text x="405" y="48" class="uro-label">L. Adrenal</text>

    {{-- ==================== RIGHT KIDNEY ==================== --}}
    {{-- Outer capsule --}}
    <path
        d="M130 90 Q108 115 106 155 Q108 195 130 220 Q150 235 162 215 Q152 155 162 95 Q150 80 130 90 Z"
        class="uro-zone"
        :fill="selectedZones.includes('right_kidney') ? '#818CF8' : 'url(#uro-kidney-grad)'"
        stroke="#805048" stroke-width="1.5"
        @click="toggleZone('right_kidney')"
        filter="url(#uro-shadow)"
    />
    {{-- Internal kidney structure (cross-section detail) --}}
    <g class="uro-detail" clip-path="url(#uro-right-kidney-clip)">
        {{-- Medullary pyramids --}}
        <g opacity="0.35">
            <path d="M125 110 L140 140 L115 140 Z" fill="#B06858"/>
            <path d="M120 145 L138 175 L110 175 Z" fill="#B06858"/>
            <path d="M125 180 L142 205 L115 205 Z" fill="#B06858"/>
        </g>
        {{-- Renal pelvis --}}
        <path d="M148 130 Q158 145 158 155 Q158 165 148 180 Q142 170 140 155 Q142 140 148 130 Z"
              fill="#F0D0A0" opacity="0.5" stroke="#C4A070" stroke-width="0.5"/>
        {{-- Cortex-medulla boundary --}}
        <path d="M118 100 Q112 130 112 155 Q112 180 118 210" fill="none" stroke="#A06858" stroke-width="0.5" opacity="0.3"/>
        {{-- Renal columns --}}
        <path d="M120 140 L130 140" fill="none" stroke="#C47870" stroke-width="2" opacity="0.3"/>
        <path d="M115 175 L128 175" fill="none" stroke="#C47870" stroke-width="2" opacity="0.3"/>
    </g>
    {{-- Hilum notch --}}
    <path d="M158 140 Q165 155 158 170" fill="none" stroke="#805048" stroke-width="1" class="uro-detail" opacity="0.5"/>
    {{-- Renal artery and vein --}}
    <g class="uro-detail" opacity="0.3">
        <path d="M162 148 Q185 145 220 142 L236 142" stroke="#C04040" stroke-width="1.5" fill="none"/>
        <path d="M162 162 Q185 165 220 168 L244 168" stroke="#4060A0" stroke-width="1.5" fill="none"/>
    </g>
    <line x1="112" y1="155" x2="55" y2="155" class="uro-label-line"/>
    <text x="10" y="152" class="uro-label">R. Kidney</text>

    {{-- ==================== LEFT KIDNEY ==================== --}}
    <path
        d="M350 90 Q372 115 374 155 Q372 195 350 220 Q330 235 318 215 Q328 155 318 95 Q330 80 350 90 Z"
        class="uro-zone"
        :fill="selectedZones.includes('left_kidney') ? '#818CF8' : 'url(#uro-kidney-grad)'"
        stroke="#805048" stroke-width="1.5"
        @click="toggleZone('left_kidney')"
        filter="url(#uro-shadow)"
    />
    <g class="uro-detail" clip-path="url(#uro-left-kidney-clip)">
        <g opacity="0.35">
            <path d="M355 110 L340 140 L365 140 Z" fill="#B06858"/>
            <path d="M360 145 L342 175 L370 175 Z" fill="#B06858"/>
            <path d="M355 180 L338 205 L365 205 Z" fill="#B06858"/>
        </g>
        <path d="M332 130 Q322 145 322 155 Q322 165 332 180 Q338 170 340 155 Q338 140 332 130 Z"
              fill="#F0D0A0" opacity="0.5" stroke="#C4A070" stroke-width="0.5"/>
        <path d="M362 100 Q368 130 368 155 Q368 180 362 210" fill="none" stroke="#A06858" stroke-width="0.5" opacity="0.3"/>
        <path d="M360 140 L350 140" fill="none" stroke="#C47870" stroke-width="2" opacity="0.3"/>
        <path d="M365 175 L352 175" fill="none" stroke="#C47870" stroke-width="2" opacity="0.3"/>
    </g>
    <path d="M322 140 Q315 155 322 170" fill="none" stroke="#805048" stroke-width="1" class="uro-detail" opacity="0.5"/>
    <g class="uro-detail" opacity="0.3">
        <path d="M318 148 Q295 145 260 142 L244 142" stroke="#C04040" stroke-width="1.5" fill="none"/>
        <path d="M318 162 Q295 165 260 168 L236 168" stroke="#4060A0" stroke-width="1.5" fill="none"/>
    </g>
    <line x1="368" y1="155" x2="420" y2="155" class="uro-label-line"/>
    <text x="425" y="152" class="uro-label">L. Kidney</text>

    {{-- ==================== RIGHT URETER ==================== --}}
    <path
        d="M155 220 Q160 260 168 300 Q175 340 185 370 Q192 390 198 405"
        class="uro-zone"
        :stroke="selectedZones.includes('right_ureter') ? '#818CF8' : '#D09080'"
        stroke-width="5" fill="none" stroke-linecap="round"
        @click="toggleZone('right_ureter')"
    />
    {{-- Ureter lumen --}}
    <path d="M155 220 Q160 260 168 300 Q175 340 185 370 Q192 390 198 405"
          fill="none" stroke="#F0D0C8" stroke-width="1.5" class="uro-detail" opacity="0.5" stroke-linecap="round"/>
    {{-- Peristalsis narrowings --}}
    <g class="uro-detail" opacity="0.15">
        <ellipse cx="162" cy="265" rx="4" ry="2" fill="none" stroke="#805048" stroke-width="0.5"/>
        <ellipse cx="172" cy="320" rx="4" ry="2" fill="none" stroke="#805048" stroke-width="0.5"/>
        <ellipse cx="182" cy="375" rx="4" ry="2" fill="none" stroke="#805048" stroke-width="0.5"/>
    </g>
    <line x1="158" y1="285" x2="80" y2="285" class="uro-label-line"/>
    <text x="25" y="282" class="uro-label">R. Ureter</text>

    {{-- ==================== LEFT URETER ==================== --}}
    <path
        d="M325 220 Q320 260 312 300 Q305 340 295 370 Q288 390 282 405"
        class="uro-zone"
        :stroke="selectedZones.includes('left_ureter') ? '#818CF8' : '#D09080'"
        stroke-width="5" fill="none" stroke-linecap="round"
        @click="toggleZone('left_ureter')"
    />
    <path d="M325 220 Q320 260 312 300 Q305 340 295 370 Q288 390 282 405"
          fill="none" stroke="#F0D0C8" stroke-width="1.5" class="uro-detail" opacity="0.5" stroke-linecap="round"/>
    <g class="uro-detail" opacity="0.15">
        <ellipse cx="318" cy="265" rx="4" ry="2" fill="none" stroke="#805048" stroke-width="0.5"/>
        <ellipse cx="308" cy="320" rx="4" ry="2" fill="none" stroke="#805048" stroke-width="0.5"/>
        <ellipse cx="298" cy="375" rx="4" ry="2" fill="none" stroke="#805048" stroke-width="0.5"/>
    </g>
    <line x1="320" y1="285" x2="400" y2="285" class="uro-label-line"/>
    <text x="405" y="282" class="uro-label">L. Ureter</text>

    {{-- ==================== BLADDER ==================== --}}
    <path
        d="M178 400 Q170 415 168 435 Q166 460 178 478 Q192 495 210 500 Q225 503 240 503 Q255 503 270 500 Q288 495 302 478 Q314 460 312 435 Q310 415 302 400 Q285 388 240 385 Q195 388 178 400 Z"
        class="uro-zone"
        :fill="selectedZones.includes('bladder') ? '#818CF8' : 'url(#uro-bladder-grad)'"
        stroke="#607090" stroke-width="1.5"
        @click="toggleZone('bladder')"
        filter="url(#uro-shadow)"
    />
    {{-- Bladder wall thickness detail --}}
    <path d="M188 408 Q182 420 180 440 Q178 458 188 472 Q200 485 218 490 Q230 492 240 492 Q250 492 262 490 Q280 485 292 472 Q302 458 300 440 Q298 420 292 408 Q278 398 240 396 Q202 398 188 408 Z"
          fill="#D0E0F0" opacity="0.3" stroke="none" class="uro-detail"/>
    {{-- Rugae folds --}}
    <g class="uro-detail" opacity="0.15">
        <path d="M200 420 Q220 415 260 418 Q280 420 290 425" fill="none" stroke="#506080" stroke-width="0.8"/>
        <path d="M195 438 Q220 432 255 434 Q280 436 295 440" fill="none" stroke="#506080" stroke-width="0.8"/>
        <path d="M198 455 Q220 450 258 452 Q282 454 296 458" fill="none" stroke="#506080" stroke-width="0.8"/>
        <path d="M202 470 Q222 465 258 467 Q280 469 292 472" fill="none" stroke="#506080" stroke-width="0.8"/>
    </g>
    {{-- Trigone area --}}
    <path d="M200 405 L240 480 L280 405" fill="none" stroke="#708090" stroke-width="0.5" class="uro-detail" opacity="0.15"/>
    {{-- Ureteral orifices --}}
    <circle cx="205" cy="410" r="2.5" fill="#506080" opacity="0.3" class="uro-detail"/>
    <circle cx="275" cy="410" r="2.5" fill="#506080" opacity="0.3" class="uro-detail"/>
    <text x="240" y="450" class="uro-label" text-anchor="middle" font-size="11">Bladder</text>

    {{-- ==================== PROSTATE ==================== --}}
    <path
        d="M218 505 Q210 510 208 520 Q206 532 215 540 Q228 548 240 550 Q252 548 265 540 Q274 532 272 520 Q270 510 262 505 Q252 502 240 501 Q228 502 218 505 Z"
        class="uro-zone"
        :fill="selectedZones.includes('prostate') ? '#818CF8' : 'url(#uro-prostate-grad)'"
        stroke="#806898" stroke-width="1.2"
        @click="toggleZone('prostate')"
        filter="url(#uro-shadow)"
    />
    {{-- Prostate internal zones --}}
    <g class="uro-detail" opacity="0.2">
        {{-- Central zone --}}
        <ellipse cx="240" cy="520" rx="10" ry="8" fill="none" stroke="#604878" stroke-width="0.5"/>
        {{-- Peripheral zone --}}
        <path d="M220 515 Q215 530 225 540 Q235 545 240 545 Q245 545 255 540 Q265 530 260 515"
              fill="none" stroke="#604878" stroke-width="0.5"/>
        {{-- Urethra through prostate --}}
        <line x1="240" y1="502" x2="240" y2="550" stroke="#604878" stroke-width="1"/>
    </g>
    <line x1="272" y1="525" x2="340" y2="530" class="uro-label-line"/>
    <text x="345" y="534" class="uro-label">Prostate</text>

    {{-- ==================== URETHRA ==================== --}}
    <path
        d="M240 550 L240 590"
        class="uro-zone"
        :stroke="selectedZones.includes('urethra') ? '#818CF8' : '#D09080'"
        stroke-width="6" fill="none" stroke-linecap="round"
        @click="toggleZone('urethra')"
    />
    {{-- Urethra lumen --}}
    <path d="M240 550 L240 590" fill="none" stroke="#F0D0C8" stroke-width="1.5" class="uro-detail" opacity="0.5" stroke-linecap="round"/>
    <line x1="246" y1="572" x2="310" y2="578" class="uro-label-line"/>
    <text x="315" y="582" class="uro-label">Urethra</text>

    {{-- Title --}}
    <text x="240" y="612" font-family="system-ui, -apple-system, sans-serif" font-size="13" font-weight="600" fill="#1F2937" text-anchor="middle">
        Urology — Urinary System
    </text>
</svg>
