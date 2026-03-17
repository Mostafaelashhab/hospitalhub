{{-- Colorectal Surgery — Large Intestine & Anorectal Anatomical Diagram --}}
{{-- Expects parent Alpine.js scope with: selectedZones, toggleZone(zoneId), zoneNotes --}}

<svg viewBox="0 0 520 700" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto max-w-lg mx-auto">
    <defs>
        <style>
            .cr-zone { cursor: pointer; transition: fill 0.25s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease, stroke 0.25s ease, transform 0.25s ease, filter 0.25s ease; transform-origin: center; }
            .cr-zone:hover { opacity: 0.88; filter: url(#cr-hover-glow); transform: scale(1.012); }
            .cr-label { font-family: system-ui, -apple-system, sans-serif; font-size: 9.5px; fill: #374151; pointer-events: none; font-weight: 500; }
            .cr-label-line { stroke: #9CA3AF; stroke-width: 0.75; fill: none; stroke-dasharray: 2,2; pointer-events: none; }
            .cr-detail { pointer-events: none; }
            .cr-title { font-family: system-ui, -apple-system, sans-serif; font-size: 13px; font-weight: 600; fill: #1F2937; }
        </style>

        {{-- Colon wall gradient --}}
        <linearGradient id="cr-colon-grad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#E8A8A0"/>
            <stop offset="40%" stop-color="#D4908A"/>
            <stop offset="100%" stop-color="#C07870"/>
        </linearGradient>

        {{-- Transverse colon gradient --}}
        <linearGradient id="cr-trans-grad" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#D89890"/>
            <stop offset="50%" stop-color="#CC8880"/>
            <stop offset="100%" stop-color="#D89890"/>
        </linearGradient>

        {{-- Cecum gradient --}}
        <radialGradient id="cr-cecum-grad" cx="45%" cy="40%" r="60%">
            <stop offset="0%" stop-color="#E8B8A8"/>
            <stop offset="50%" stop-color="#D4A090"/>
            <stop offset="100%" stop-color="#C08878"/>
        </radialGradient>

        {{-- Appendix gradient --}}
        <linearGradient id="cr-appendix-grad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#E0A898"/>
            <stop offset="100%" stop-color="#C88070"/>
        </linearGradient>

        {{-- Sigmoid gradient --}}
        <radialGradient id="cr-sigmoid-grad" cx="50%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#D8A098"/>
            <stop offset="100%" stop-color="#C08070"/>
        </radialGradient>

        {{-- Rectum gradient --}}
        <radialGradient id="cr-rectum-grad" cx="50%" cy="35%" r="60%">
            <stop offset="0%" stop-color="#D09088"/>
            <stop offset="50%" stop-color="#B87068"/>
            <stop offset="100%" stop-color="#A05850"/>
        </radialGradient>

        {{-- Anal canal gradient --}}
        <radialGradient id="cr-anal-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#C08078"/>
            <stop offset="50%" stop-color="#A86058"/>
            <stop offset="100%" stop-color="#904848"/>
        </radialGradient>

        {{-- Internal hemorrhoid gradient --}}
        <radialGradient id="cr-ihemorrhoid-grad" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="#D05060"/>
            <stop offset="100%" stop-color="#A03040"/>
        </radialGradient>

        {{-- External hemorrhoid gradient --}}
        <radialGradient id="cr-ehemorrhoid-grad" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="#B84858"/>
            <stop offset="100%" stop-color="#903040"/>
        </radialGradient>

        {{-- Perianal gradient --}}
        <radialGradient id="cr-perianal-grad" cx="50%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#F0D0C0"/>
            <stop offset="100%" stop-color="#D4B0A0"/>
        </radialGradient>

        {{-- Pilonidal gradient --}}
        <radialGradient id="cr-pilonidal-grad" cx="50%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#E0C8B8"/>
            <stop offset="100%" stop-color="#C0A898"/>
        </radialGradient>

        {{-- Hepatic flexure gradient --}}
        <radialGradient id="cr-hflex-grad" cx="50%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#D8A098"/>
            <stop offset="100%" stop-color="#C08878"/>
        </radialGradient>

        {{-- Splenic flexure gradient --}}
        <radialGradient id="cr-sflex-grad" cx="50%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#D8A098"/>
            <stop offset="100%" stop-color="#C08878"/>
        </radialGradient>

        {{-- Shadow filter --}}
        <filter id="cr-shadow" x="-5%" y="-5%" width="115%" height="115%">
            <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#00000020"/>
        </filter>

        {{-- Hover glow --}}
        <filter id="cr-hover-glow" x="-10%" y="-10%" width="120%" height="120%">
            <feDropShadow dx="0" dy="0" stdDeviation="4" flood-color="#818cf860"/>
        </filter>

        {{-- Blood vessel filter --}}
        <filter id="cr-vessel-glow" x="-20%" y="-20%" width="140%" height="140%">
            <feGaussianBlur stdDeviation="1" result="blur"/>
            <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
        </filter>
    </defs>

    {{-- ==================== BACKGROUND ANATOMY ==================== --}}

    {{-- Spine reference --}}
    <g class="cr-detail" opacity="0.08">
        <rect x="248" y="30" width="18" height="520" rx="4" fill="#D8D0C0" stroke="#B8A890" stroke-width="0.5"/>
        @for($i = 0; $i < 16; $i++)
            <line x1="244" y1="{{ 50 + $i * 32 }}" x2="270" y2="{{ 50 + $i * 32 }}" stroke="#B8A890" stroke-width="0.5"/>
        @endfor
    </g>

    {{-- Pelvic bone hint --}}
    <g class="cr-detail" opacity="0.07">
        <path d="M140 420 Q155 380 200 360 Q235 350 258 355 Q280 350 315 360 Q360 380 375 420 Q380 470 365 510 Q345 540 310 550 L205 550 Q170 540 150 510 Q135 470 140 420 Z"
              fill="none" stroke="#B8A890" stroke-width="2.5"/>
    </g>

    {{-- Liver hint (top right) --}}
    <g class="cr-detail" opacity="0.06">
        <path d="M280 55 Q340 50 400 65 Q430 75 440 100 Q445 130 430 145 Q400 155 350 150 Q300 140 280 120 Z"
              fill="#8B5E3C" stroke="none"/>
    </g>

    {{-- Spleen hint (top left) --}}
    <g class="cr-detail" opacity="0.06">
        <ellipse cx="95" cy="105" rx="35" ry="25" fill="#7B4B8A" stroke="none"/>
    </g>

    {{-- Small intestine background --}}
    <g class="cr-detail" opacity="0.06">
        <path d="M180 250 Q200 240 230 250 Q260 260 250 280 Q240 300 210 295 Q190 290 195 270 Q200 255 220 260 Q240 265 235 280"
              fill="none" stroke="#C08888" stroke-width="8" stroke-linecap="round"/>
        <path d="M230 275 Q260 280 270 300 Q275 320 255 330 Q235 335 225 315 Q220 300 240 295 Q260 300 258 320"
              fill="none" stroke="#C08888" stroke-width="8" stroke-linecap="round"/>
        <path d="M220 320 Q200 330 195 350 Q192 370 210 378 Q230 382 245 370 Q255 358 240 345 Q225 338 215 350"
              fill="none" stroke="#C08888" stroke-width="8" stroke-linecap="round"/>
    </g>

    {{-- Mesenteric blood vessels --}}
    <g class="cr-detail" opacity="0.1">
        {{-- Superior mesenteric artery --}}
        <path d="M258 120 Q255 180 250 220 Q240 280 230 320" stroke="#C04040" stroke-width="1.5" fill="none"/>
        <path d="M250 220 Q220 240 190 260" stroke="#C04040" stroke-width="1" fill="none"/>
        <path d="M250 220 Q280 240 310 250" stroke="#C04040" stroke-width="1" fill="none"/>
        {{-- Inferior mesenteric artery --}}
        <path d="M255 300 Q240 350 230 400 Q225 440 240 470" stroke="#C04040" stroke-width="1.2" fill="none"/>
        <path d="M235 380 Q200 400 170 390" stroke="#C04040" stroke-width="0.8" fill="none"/>
        <path d="M230 420 Q200 440 180 450" stroke="#C04040" stroke-width="0.8" fill="none"/>
        {{-- Superior mesenteric vein --}}
        <path d="M262 120 Q265 180 268 220 Q275 280 280 320" stroke="#4060A0" stroke-width="1.5" fill="none"/>
    </g>

    {{-- ==================== ASCENDING COLON ==================== --}}
    <path
        d="M380 310 Q390 290 392 260 Q394 230 390 200 Q386 175 380 160 Q372 148 362 160 Q356 175 354 200 Q352 230 354 260 Q356 290 362 310 Z"
        class="cr-zone"
        :fill="selectedZones.includes('ascending_colon') ? '#818CF8' : 'url(#cr-colon-grad)'"
        stroke="#A06858" stroke-width="1.3"
        @click="toggleZone('ascending_colon')"
        filter="url(#cr-shadow)"
    />
    {{-- Haustra markings --}}
    <g class="cr-detail" opacity="0.2">
        <path d="M358 175 Q371 170 385 175" fill="none" stroke="#906050" stroke-width="0.8"/>
        <path d="M356 200 Q371 195 389 200" fill="none" stroke="#906050" stroke-width="0.8"/>
        <path d="M355 225 Q371 220 390 225" fill="none" stroke="#906050" stroke-width="0.8"/>
        <path d="M355 250 Q371 245 391 250" fill="none" stroke="#906050" stroke-width="0.8"/>
        <path d="M357 275 Q371 270 389 275" fill="none" stroke="#906050" stroke-width="0.8"/>
    </g>
    {{-- Taeniae coli hints --}}
    <g class="cr-detail" opacity="0.12">
        <path d="M371 155 L371 315" stroke="#A07060" stroke-width="0.6" stroke-dasharray="3,3"/>
    </g>
    <line x1="394" y1="230" x2="460" y2="220" class="cr-label-line"/>
    <text x="462" y="224" class="cr-label">Ascending<tspan x="462" dy="12">Colon</tspan></text>

    {{-- ==================== HEPATIC FLEXURE ==================== --}}
    <path
        d="M362 148 Q365 130 375 118 Q388 105 405 108 Q418 112 422 128 Q424 142 418 155 Q410 165 398 162 Q385 158 380 150 Z"
        class="cr-zone"
        :fill="selectedZones.includes('hepatic_flexure') ? '#818CF8' : 'url(#cr-hflex-grad)'"
        stroke="#A06858" stroke-width="1.3"
        @click="toggleZone('hepatic_flexure')"
        filter="url(#cr-shadow)"
    />
    <g class="cr-detail" opacity="0.15">
        <path d="M375 125 Q395 118 415 125" fill="none" stroke="#906050" stroke-width="0.7"/>
        <path d="M378 140 Q398 134 418 140" fill="none" stroke="#906050" stroke-width="0.7"/>
    </g>
    <line x1="420" y1="125" x2="470" y2="105" class="cr-label-line"/>
    <text x="472" y="102" class="cr-label">Hepatic<tspan x="472" dy="12">Flexure</tspan></text>

    {{-- ==================== TRANSVERSE COLON ==================== --}}
    <path
        d="M398 100 Q370 85 330 78 Q280 72 240 74 Q200 76 165 82 Q135 90 118 102 Q110 115 120 128 Q132 138 160 132 Q200 124 240 120 Q280 118 320 120 Q358 124 385 132 Q398 138 405 128 Q412 115 405 105 Z"
        class="cr-zone"
        :fill="selectedZones.includes('transverse_colon') ? '#818CF8' : 'url(#cr-trans-grad)'"
        stroke="#A06858" stroke-width="1.3"
        @click="toggleZone('transverse_colon')"
        filter="url(#cr-shadow)"
    />
    {{-- Haustra on transverse --}}
    <g class="cr-detail" opacity="0.18">
        <path d="M150 95 Q150 110 150 125" fill="none" stroke="#906050" stroke-width="0.7"/>
        <path d="M180 90 Q180 108 180 122" fill="none" stroke="#906050" stroke-width="0.7"/>
        <path d="M210 87 Q210 105 210 120" fill="none" stroke="#906050" stroke-width="0.7"/>
        <path d="M240 86 Q240 103 240 119" fill="none" stroke="#906050" stroke-width="0.7"/>
        <path d="M270 86 Q270 103 270 119" fill="none" stroke="#906050" stroke-width="0.7"/>
        <path d="M300 87 Q300 105 300 120" fill="none" stroke="#906050" stroke-width="0.7"/>
        <path d="M330 89 Q330 107 330 122" fill="none" stroke="#906050" stroke-width="0.7"/>
        <path d="M360 92 Q360 110 360 126" fill="none" stroke="#906050" stroke-width="0.7"/>
    </g>
    {{-- Omental attachment hint --}}
    <g class="cr-detail" opacity="0.05">
        <path d="M150 130 Q200 160 260 165 Q320 160 370 130" fill="#E8D0B0" stroke="none"/>
    </g>
    <text x="258" y="60" class="cr-label" text-anchor="middle" font-size="10">Transverse Colon</text>

    {{-- ==================== SPLENIC FLEXURE ==================== --}}
    <path
        d="M118 102 Q108 90 100 108 Q92 128 98 145 Q105 158 118 160 Q130 158 138 148 Q142 138 135 128 Q128 118 120 115 Z"
        class="cr-zone"
        :fill="selectedZones.includes('splenic_flexure') ? '#818CF8' : 'url(#cr-sflex-grad)'"
        stroke="#A06858" stroke-width="1.3"
        @click="toggleZone('splenic_flexure')"
        filter="url(#cr-shadow)"
    />
    <g class="cr-detail" opacity="0.15">
        <path d="M102 118 Q115 112 130 118" fill="none" stroke="#906050" stroke-width="0.7"/>
        <path d="M100 135 Q115 130 132 135" fill="none" stroke="#906050" stroke-width="0.7"/>
    </g>
    <line x1="98" y1="130" x2="40" y2="115" class="cr-label-line"/>
    <text x="5" y="112" class="cr-label">Splenic<tspan x="5" dy="12">Flexure</tspan></text>

    {{-- ==================== DESCENDING COLON ==================== --}}
    <path
        d="M135 160 Q143 175 145 200 Q147 230 145 260 Q143 290 140 320 Q137 345 132 360 Q124 370 115 358 Q110 340 112 310 Q114 280 116 250 Q118 220 118 190 Q118 170 122 160 Z"
        class="cr-zone"
        :fill="selectedZones.includes('descending_colon') ? '#818CF8' : 'url(#cr-colon-grad)'"
        stroke="#A06858" stroke-width="1.3"
        @click="toggleZone('descending_colon')"
        filter="url(#cr-shadow)"
    />
    {{-- Haustra markings --}}
    <g class="cr-detail" opacity="0.2">
        <path d="M117 185 Q130 180 143 185" fill="none" stroke="#906050" stroke-width="0.8"/>
        <path d="M116 210 Q130 205 145 210" fill="none" stroke="#906050" stroke-width="0.8"/>
        <path d="M115 235 Q129 230 144 235" fill="none" stroke="#906050" stroke-width="0.8"/>
        <path d="M114 260 Q128 255 143 260" fill="none" stroke="#906050" stroke-width="0.8"/>
        <path d="M113 285 Q127 280 142 285" fill="none" stroke="#906050" stroke-width="0.8"/>
        <path d="M112 310 Q126 305 140 310" fill="none" stroke="#906050" stroke-width="0.8"/>
        <path d="M112 335 Q124 330 137 335" fill="none" stroke="#906050" stroke-width="0.8"/>
    </g>
    <g class="cr-detail" opacity="0.12">
        <path d="M128 158 L128 365" stroke="#A07060" stroke-width="0.6" stroke-dasharray="3,3"/>
    </g>
    <line x1="112" y1="260" x2="42" y2="250" class="cr-label-line"/>
    <text x="5" y="247" class="cr-label">Descending<tspan x="5" dy="12">Colon</tspan></text>

    {{-- ==================== CECUM ==================== --}}
    <path
        d="M355 310 Q345 325 340 345 Q338 368 345 388 Q355 405 375 408 Q395 405 405 388 Q412 368 410 345 Q406 325 395 310 Z"
        class="cr-zone"
        :fill="selectedZones.includes('cecum') ? '#818CF8' : 'url(#cr-cecum-grad)'"
        stroke="#A06858" stroke-width="1.3"
        @click="toggleZone('cecum')"
        filter="url(#cr-shadow)"
    />
    {{-- Ileocecal valve hint --}}
    <g class="cr-detail" opacity="0.25">
        <ellipse cx="358" cy="318" rx="8" ry="5" fill="none" stroke="#906050" stroke-width="1"/>
        <path d="M352 318 L364 318" fill="none" stroke="#906050" stroke-width="0.5"/>
    </g>
    {{-- Cecal folds --}}
    <g class="cr-detail" opacity="0.15">
        <path d="M345 340 Q375 335 405 340" fill="none" stroke="#906050" stroke-width="0.7"/>
        <path d="M342 360 Q375 355 408 360" fill="none" stroke="#906050" stroke-width="0.7"/>
        <path d="M345 380 Q375 375 405 380" fill="none" stroke="#906050" stroke-width="0.7"/>
    </g>
    <line x1="412" y1="365" x2="465" y2="370" class="cr-label-line"/>
    <text x="467" y="367" class="cr-label">Cecum</text>

    {{-- ==================== APPENDIX ==================== --}}
    <path
        d="M370 408 Q365 420 355 432 Q342 445 330 452 Q320 456 315 450 Q312 442 318 432 Q328 418 340 408 Q350 400 358 402"
        class="cr-zone"
        :stroke="selectedZones.includes('appendix') ? '#818CF8' : '#C08070'"
        :fill="selectedZones.includes('appendix') ? '#818CF820' : 'url(#cr-appendix-grad)'"
        stroke-width="1.3"
        @click="toggleZone('appendix')"
        filter="url(#cr-shadow)"
    />
    {{-- Appendix tip --}}
    <circle cx="315" cy="450" r="3" class="cr-detail" fill="#C08070" opacity="0.3"/>
    <line x1="315" y1="452" x2="290" y2="472" class="cr-label-line"/>
    <text x="268" y="480" class="cr-label">Appendix</text>

    {{-- ==================== SIGMOID COLON ==================== --}}
    <path
        d="M130 365 Q140 380 155 395 Q175 412 195 420 Q220 428 240 425 Q258 420 268 408 Q275 395 270 380 Q262 368 248 365 Q232 362 220 370 Q210 380 215 395 Q222 410 240 418 Q255 422 265 430 Q272 440 268 455 Q262 468 252 478"
        class="cr-zone"
        :stroke="selectedZones.includes('sigmoid_colon') ? '#818CF8' : '#C08070'"
        stroke-width="18" fill="none" stroke-linecap="round" stroke-linejoin="round"
        @click="toggleZone('sigmoid_colon')"
    />
    {{-- Sigmoid lumen --}}
    <path d="M130 365 Q140 380 155 395 Q175 412 195 420 Q220 428 240 425 Q258 420 268 408 Q275 395 270 380 Q262 368 248 365 Q232 362 220 370 Q210 380 215 395 Q222 410 240 418 Q255 422 265 430 Q272 440 268 455 Q262 468 252 478"
          fill="none" stroke="#F0D0C8" stroke-width="6" class="cr-detail" opacity="0.35" stroke-linecap="round"/>
    {{-- Haustra hints on sigmoid --}}
    <g class="cr-detail" opacity="0.12">
        <circle cx="148" cy="388" r="2" fill="#906050"/>
        <circle cx="175" cy="410" r="2" fill="#906050"/>
        <circle cx="210" cy="425" r="2" fill="#906050"/>
        <circle cx="248" cy="418" r="2" fill="#906050"/>
        <circle cx="270" cy="395" r="2" fill="#906050"/>
        <circle cx="255" cy="370" r="2" fill="#906050"/>
        <circle cx="225" cy="375" r="2" fill="#906050"/>
        <circle cx="218" cy="398" r="2" fill="#906050"/>
        <circle cx="245" cy="425" r="2" fill="#906050"/>
        <circle cx="268" cy="448" r="2" fill="#906050"/>
    </g>
    <line x1="145" y1="395" x2="55" y2="395" class="cr-label-line"/>
    <text x="5" y="392" class="cr-label">Sigmoid<tspan x="5" dy="12">Colon</tspan></text>

    {{-- ==================== RECTUM ==================== --}}
    <path
        d="M252 478 Q248 490 245 505 Q242 525 240 545 Q238 560 240 572 Q243 580 258 580 Q272 578 275 568 Q278 555 276 540 Q274 520 270 505 Q266 490 262 480 Z"
        class="cr-zone"
        :fill="selectedZones.includes('rectum') ? '#818CF8' : 'url(#cr-rectum-grad)'"
        stroke="#905858" stroke-width="1.3"
        @click="toggleZone('rectum')"
        filter="url(#cr-shadow)"
    />
    {{-- Rectal valves (Houston's valves) --}}
    <g class="cr-detail" opacity="0.25">
        <path d="M248 498 Q258 494 268 498" fill="none" stroke="#805050" stroke-width="1"/>
        <path d="M245 520 Q255 516 272 520" fill="none" stroke="#805050" stroke-width="1"/>
        <path d="M242 542 Q255 538 274 542" fill="none" stroke="#805050" stroke-width="1"/>
    </g>
    {{-- Rectal wall layers hint --}}
    <g class="cr-detail" opacity="0.1">
        <path d="M250 485 Q246 510 244 540 Q242 560 245 575" fill="none" stroke="#704040" stroke-width="2"/>
        <path d="M264 485 Q268 510 270 540 Q272 560 268 575" fill="none" stroke="#704040" stroke-width="2"/>
    </g>
    <line x1="278" y1="530" x2="350" y2="525" class="cr-label-line"/>
    <text x="355" y="528" class="cr-label">Rectum</text>

    {{-- ==================== ANAL CANAL ==================== --}}
    <path
        d="M240 578 Q235 588 233 600 Q232 612 235 622 Q240 630 258 630 Q275 628 280 620 Q283 610 282 598 Q280 588 275 580 Z"
        class="cr-zone"
        :fill="selectedZones.includes('anal_canal') ? '#818CF8' : 'url(#cr-anal-grad)'"
        stroke="#805050" stroke-width="1.3"
        @click="toggleZone('anal_canal')"
        filter="url(#cr-shadow)"
    />
    {{-- Dentate line --}}
    <g class="cr-detail" opacity="0.3">
        <path d="M236 608 Q245 605 258 605 Q270 605 278 608" fill="none" stroke="#E8C040" stroke-width="1.2" stroke-dasharray="2,1.5"/>
    </g>
    {{-- Internal sphincter --}}
    <g class="cr-detail" opacity="0.2">
        <path d="M237 595 Q238 605 240 618 Q242 625 248 628" fill="none" stroke="#704848" stroke-width="2.5"/>
        <path d="M278 595 Q276 605 274 618 Q272 625 266 628" fill="none" stroke="#704848" stroke-width="2.5"/>
    </g>
    {{-- External sphincter --}}
    <g class="cr-detail" opacity="0.15">
        <path d="M230 592 Q228 608 232 624 Q236 632 248 635" fill="none" stroke="#604040" stroke-width="3"/>
        <path d="M284 592 Q286 608 282 624 Q278 632 266 635" fill="none" stroke="#604040" stroke-width="3"/>
    </g>
    <line x1="284" y1="610" x2="350" y2="615" class="cr-label-line"/>
    <text x="355" y="612" class="cr-label">Anal Canal</text>
    {{-- Dentate line label --}}
    <line x1="278" y1="606" x2="330" y2="600" class="cr-label-line"/>
    <text x="332" y="597" class="cr-label" font-size="8" fill="#B0903A">Dentate Line</text>

    {{-- ==================== INTERNAL HEMORRHOIDS ==================== --}}
    <g @click="toggleZone('internal_hemorrhoids')">
        <ellipse
            cx="240" cy="598"
            rx="6" ry="5"
            class="cr-zone"
            :fill="selectedZones.includes('internal_hemorrhoids') ? '#818CF8' : 'url(#cr-ihemorrhoid-grad)'"
            stroke="#803040" stroke-width="1"
        />
        <ellipse
            cx="253" cy="595"
            rx="5" ry="4.5"
            class="cr-zone"
            :fill="selectedZones.includes('internal_hemorrhoids') ? '#818CF8' : 'url(#cr-ihemorrhoid-grad)'"
            stroke="#803040" stroke-width="1"
        />
        <ellipse
            cx="265" cy="598"
            rx="5.5" ry="5"
            class="cr-zone"
            :fill="selectedZones.includes('internal_hemorrhoids') ? '#818CF8' : 'url(#cr-ihemorrhoid-grad)'"
            stroke="#803040" stroke-width="1"
        />
    </g>
    <line x1="233" y1="594" x2="165" y2="585" class="cr-label-line"/>
    <text x="100" y="582" class="cr-label">Internal<tspan x="100" dy="12">Hemorrhoids</tspan></text>

    {{-- ==================== EXTERNAL HEMORRHOIDS ==================== --}}
    <g @click="toggleZone('external_hemorrhoids')">
        <ellipse
            cx="237" cy="628"
            rx="6" ry="4.5"
            class="cr-zone"
            :fill="selectedZones.includes('external_hemorrhoids') ? '#818CF8' : 'url(#cr-ehemorrhoid-grad)'"
            stroke="#703040" stroke-width="1"
        />
        <ellipse
            cx="252" cy="631"
            rx="5.5" ry="4"
            class="cr-zone"
            :fill="selectedZones.includes('external_hemorrhoids') ? '#818CF8' : 'url(#cr-ehemorrhoid-grad)'"
            stroke="#703040" stroke-width="1"
        />
        <ellipse
            cx="267" cy="628"
            rx="5.5" ry="4.5"
            class="cr-zone"
            :fill="selectedZones.includes('external_hemorrhoids') ? '#818CF8' : 'url(#cr-ehemorrhoid-grad)'"
            stroke="#703040" stroke-width="1"
        />
    </g>
    <line x1="270" y1="632" x2="350" y2="645" class="cr-label-line"/>
    <text x="355" y="642" class="cr-label">External<tspan x="355" dy="12">Hemorrhoids</tspan></text>

    {{-- ==================== PERIANAL REGION ==================== --}}
    <path
        d="M220 632 Q215 645 215 655 Q218 668 240 672 Q258 672 275 668 Q282 658 282 648 Q280 638 275 632 Q260 640 252 642 Q240 642 228 638 Z"
        class="cr-zone"
        :fill="selectedZones.includes('perianal') ? '#818CF8' : 'url(#cr-perianal-grad)'"
        stroke="#B09888" stroke-width="1"
        @click="toggleZone('perianal')"
        opacity="0.8"
    />
    <line x1="218" y1="658" x2="150" y2="665" class="cr-label-line"/>
    <text x="95" y="662" class="cr-label">Perianal<tspan x="95" dy="12">Region</tspan></text>

    {{-- ==================== PILONIDAL REGION ==================== --}}
    <path
        d="M242 670 Q235 678 235 688 Q238 698 252 700 Q266 698 268 688 Q268 678 262 670 Z"
        class="cr-zone"
        :fill="selectedZones.includes('pilonidal') ? '#818CF8' : 'url(#cr-pilonidal-grad)'"
        stroke="#B0A090" stroke-width="1"
        @click="toggleZone('pilonidal')"
        opacity="0.8"
    />
    {{-- Natal cleft hint --}}
    <g class="cr-detail" opacity="0.15">
        <path d="M252 672 L252 698" stroke="#907060" stroke-width="0.5"/>
    </g>

    {{-- ==================== ILEOCECAL CONNECTION ==================== --}}
    <g class="cr-detail" opacity="0.2">
        <path d="M320 310 Q335 308 350 312" stroke="#C08888" stroke-width="5" fill="none" stroke-linecap="round"/>
        <text x="305" y="305" class="cr-label" font-size="8" fill="#9CA3AF">Terminal Ileum</text>
    </g>

    {{-- ==================== DECORATIVE ELEMENTS ==================== --}}

    {{-- Lymph node hints along colon --}}
    <g class="cr-detail" opacity="0.08">
        <circle cx="400" cy="180" r="4" fill="#80A080"/>
        <circle cx="395" cy="240" r="3.5" fill="#80A080"/>
        <circle cx="150" cy="215" r="3.5" fill="#80A080"/>
        <circle cx="148" cy="280" r="4" fill="#80A080"/>
        <circle cx="200" cy="430" r="3.5" fill="#80A080"/>
        <circle cx="258" cy="460" r="3" fill="#80A080"/>
    </g>

    {{-- ==================== ZOOMED INSET: ANORECTAL DETAIL ==================== --}}
    <g transform="translate(380, 440)">
        <rect x="0" y="0" width="120" height="95" rx="6" fill="white" stroke="#E5E7EB" stroke-width="1" filter="url(#cr-shadow)" opacity="0.95"/>
        <text x="60" y="14" class="cr-label" text-anchor="middle" font-size="8" font-weight="600" fill="#6B7280">Anorectal Detail</text>
        <line x1="10" y1="18" x2="110" y2="18" stroke="#E5E7EB" stroke-width="0.5"/>

        {{-- Mini rectum --}}
        <rect x="42" y="22" width="36" height="28" rx="4" fill="#D09088" stroke="#A06058" stroke-width="0.5" opacity="0.6"/>
        <text x="60" y="40" class="cr-label" text-anchor="middle" font-size="6" fill="#704040">Rectum</text>

        {{-- Dentate line --}}
        <line x1="38" y1="50" x2="82" y2="50" stroke="#E8C040" stroke-width="1" stroke-dasharray="2,1"/>
        <text x="95" y="53" class="cr-label" font-size="5.5" fill="#B0903A">DL</text>

        {{-- Internal hemorrhoids above DL --}}
        <circle cx="48" cy="45" r="3.5" fill="#D05060" opacity="0.7"/>
        <circle cx="60" cy="43" r="3" fill="#D05060" opacity="0.7"/>
        <circle cx="72" cy="45" r="3.5" fill="#D05060" opacity="0.7"/>
        <text x="18" y="47" class="cr-label" font-size="5" fill="#A03040">IH</text>

        {{-- Anal canal below DL --}}
        <rect x="44" y="52" width="32" height="16" rx="3" fill="#B07068" stroke="#905050" stroke-width="0.5" opacity="0.6"/>
        <text x="60" y="63" class="cr-label" text-anchor="middle" font-size="5.5" fill="#FFFFFF">Anal Canal</text>

        {{-- External hemorrhoids --}}
        <circle cx="46" cy="72" r="3" fill="#B84858" opacity="0.7"/>
        <circle cx="60" cy="74" r="3.5" fill="#B84858" opacity="0.7"/>
        <circle cx="74" cy="72" r="3" fill="#B84858" opacity="0.7"/>
        <text x="18" y="75" class="cr-label" font-size="5" fill="#903040">EH</text>

        {{-- Sphincters --}}
        <path d="M40 52 Q38 60 40 68" fill="none" stroke="#604040" stroke-width="1.5" opacity="0.4"/>
        <path d="M80 52 Q82 60 80 68" fill="none" stroke="#604040" stroke-width="1.5" opacity="0.4"/>
        <text x="30" y="63" class="cr-label" font-size="4.5" fill="#604040">S</text>
        <text x="84" y="63" class="cr-label" font-size="4.5" fill="#604040">S</text>

        {{-- Legend --}}
        <g transform="translate(10, 80)">
            <circle cx="3" cy="0" r="2" fill="#D05060" opacity="0.7"/>
            <text x="8" y="3" class="cr-label" font-size="5" fill="#6B7280">IH = Internal Hemorrhoids</text>
        </g>
        <g transform="translate(10, 88)">
            <circle cx="3" cy="0" r="2" fill="#B84858" opacity="0.7"/>
            <text x="8" y="3" class="cr-label" font-size="5" fill="#6B7280">EH = External Hemorrhoids</text>
        </g>
    </g>

    {{-- Title --}}
    <text x="258" y="24" class="cr-title" text-anchor="middle">
        Colorectal Surgery — Large Intestine &amp; Anorectal
    </text>
</svg>
