{{-- Plastic Surgery Diagram - Surgical Body Zones --}}
{{-- Requires parent Alpine.js scope with: selectedZones, toggleZone(zoneId), zoneNotes --}}

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 820 720" class="w-full h-auto max-w-3xl mx-auto">
    <defs>
        <linearGradient id="psSkinBase" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#f5d0b0" />
            <stop offset="50%" stop-color="#e8c4a0" />
            <stop offset="100%" stop-color="#ddb892" />
        </linearGradient>
        <linearGradient id="psSkinHighlight" x1="0.5" y1="0" x2="0.5" y2="1">
            <stop offset="0%" stop-color="#fff5eb" stop-opacity="0.3" />
            <stop offset="100%" stop-color="#fff5eb" stop-opacity="0" />
        </linearGradient>

        <filter id="psBodyShadow" x="-5%" y="-5%" width="110%" height="110%">
            <feDropShadow dx="2" dy="2" stdDeviation="3" flood-color="#a08060" flood-opacity="0.15" />
        </filter>
        <filter id="psInnerGlow" x="-10%" y="-10%" width="120%" height="120%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="2" result="blur" />
            <feFlood flood-color="#c49070" flood-opacity="0.12" result="color" />
            <feComposite in="color" in2="blur" operator="in" result="shadow" />
            <feMerge>
                <feMergeNode in="shadow" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>
        <filter id="psSelectGlow" x="-15%" y="-15%" width="130%" height="130%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="4" result="blur" />
            <feFlood flood-color="#818cf8" flood-opacity="0.35" result="color" />
            <feComposite in="color" in2="blur" operator="in" result="shadow" />
            <feMerge>
                <feMergeNode in="shadow" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>

        <pattern id="psSkinTexture" x="0" y="0" width="6" height="6" patternUnits="userSpaceOnUse">
            <rect width="6" height="6" fill="transparent" />
            <circle cx="1" cy="1" r="0.3" fill="#c49a78" opacity="0.08" />
            <circle cx="4" cy="3" r="0.25" fill="#c49a78" opacity="0.06" />
        </pattern>

        <style>
            .ps-label { font-family: system-ui, -apple-system, sans-serif; font-size: 9px; fill: #4b5563; pointer-events: none; text-anchor: middle; font-weight: 500; }
            .ps-title { font-family: system-ui, -apple-system, sans-serif; font-size: 15px; font-weight: 700; fill: #1f2937; text-anchor: middle; letter-spacing: 0.02em; }
            .ps-subtitle { font-family: system-ui, -apple-system, sans-serif; font-size: 12px; font-weight: 600; fill: #6b7280; text-anchor: middle; }
            .ps-zone { cursor: pointer; transition: all 0.25s cubic-bezier(0.4,0,0.2,1); transform-origin: center; }
            .ps-zone:hover { filter: brightness(1.08) drop-shadow(0 0 6px rgba(99,102,241,0.3)); transform: scale(1.012); }
            .ps-contour { fill: none; stroke: #c4996e; stroke-width: 0.5; opacity: 0.2; pointer-events: none; }
            .ps-marker { font-family: system-ui, sans-serif; font-size: 7px; fill: #9ca3af; pointer-events: none; }
        </style>
    </defs>

    {{-- Background --}}
    <rect width="820" height="720" fill="#fafaf9" rx="8" />

    {{-- Title --}}
    <text class="ps-title" x="410" y="28">Plastic Surgery - Surgical Zone Assessment</text>

    {{-- ==================== FACE DETAIL (Left Side) ==================== --}}
    <text class="ps-subtitle" x="200" y="52">Face Detail</text>

    {{-- Face outline --}}
    <g filter="url(#psBodyShadow)" opacity="0.06" class="pointer-events-none">
        <ellipse cx="200" cy="220" rx="120" ry="160" fill="#333" />
    </g>

    {{-- ===== SCALP / HAIR ===== --}}
    <path
        @click="toggleZone('scalp_hair')"
        :fill="selectedZones.includes('scalp_hair') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('scalp_hair') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M200,68 C155,68 120,90 115,130 L125,120 L140,108 L160,98 L200,92 L240,98 L260,108 L275,120 L285,130 C280,90 245,68 200,68 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('scalp_hair') ? 1 : 0.92"
    />
    <text class="ps-label" x="200" y="100">Scalp / Hair</text>

    {{-- ===== FOREHEAD / BROW ===== --}}
    <path
        @click="toggleZone('forehead_brow')"
        :fill="selectedZones.includes('forehead_brow') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('forehead_brow') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M115,130 C118,115 145,100 200,100 C255,100 282,115 285,130 L282,150 L275,160 L260,168 L200,170 L140,168 L125,160 L118,150 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('forehead_brow') ? 1 : 0.92"
    />
    <text class="ps-label" x="200" y="140">Forehead / Brow</text>

    {{-- ===== EYELIDS ===== --}}
    <ellipse
        @click="toggleZone('eyelids')"
        :fill="selectedZones.includes('eyelids') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('eyelids') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        cx="165" cy="188" rx="22" ry="12"
        class="ps-zone"
        :opacity="selectedZones.includes('eyelids') ? 1 : 0.92"
    />
    <ellipse
        @click="toggleZone('eyelids')"
        :fill="selectedZones.includes('eyelids') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('eyelids') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        cx="235" cy="188" rx="22" ry="12"
        class="ps-zone"
        :opacity="selectedZones.includes('eyelids') ? 1 : 0.92"
    />
    {{-- Eye details --}}
    <g class="pointer-events-none" opacity="0.2">
        <ellipse cx="165" cy="188" rx="12" ry="6" fill="none" stroke="#5a4a3a" stroke-width="0.6" />
        <ellipse cx="235" cy="188" rx="12" ry="6" fill="none" stroke="#5a4a3a" stroke-width="0.6" />
        <circle cx="165" cy="188" r="3" fill="#5a4a3a" opacity="0.3" />
        <circle cx="235" cy="188" r="3" fill="#5a4a3a" opacity="0.3" />
    </g>
    <text class="ps-label" x="200" y="182">Eyelids</text>

    {{-- ===== NOSE ===== --}}
    <path
        @click="toggleZone('nose')"
        :fill="selectedZones.includes('nose') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('nose') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M195,195 L193,210 L188,230 L182,242 L188,248 L200,252 L212,248 L218,242 L212,230 L207,210 L205,195 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('nose') ? 1 : 0.92"
    />
    <text class="ps-label" x="200" y="228">Nose</text>

    {{-- ===== EARS ===== --}}
    <path
        @click="toggleZone('ears')"
        :fill="selectedZones.includes('ears') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('ears') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M110,175 L105,180 L100,195 L98,210 L100,222 L105,228 L112,225 L115,210 L114,195 L112,180 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('ears') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('ears')"
        :fill="selectedZones.includes('ears') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('ears') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M290,175 L295,180 L300,195 L302,210 L300,222 L295,228 L288,225 L285,210 L286,195 L288,180 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('ears') ? 1 : 0.92"
    />
    <text class="ps-label" x="100" y="200">Ear</text>
    <text class="ps-label" x="300" y="200">Ear</text>

    {{-- ===== CHEEKS ===== --}}
    <path
        @click="toggleZone('cheeks')"
        :fill="selectedZones.includes('cheeks') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('cheeks') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M118,200 L140,200 L155,210 L160,230 L155,250 L140,260 L125,255 L115,240 L112,220 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('cheeks') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('cheeks')"
        :fill="selectedZones.includes('cheeks') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('cheeks') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M282,200 L260,200 L245,210 L240,230 L245,250 L260,260 L275,255 L285,240 L288,220 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('cheeks') ? 1 : 0.92"
    />
    <text class="ps-label" x="138" y="232">Cheek</text>
    <text class="ps-label" x="262" y="232">Cheek</text>

    {{-- ===== LIPS ===== --}}
    <path
        @click="toggleZone('lips')"
        :fill="selectedZones.includes('lips') ? '#818cf8' : '#e8a090'"
        :filter="selectedZones.includes('lips') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M178,262 L190,258 L200,260 L210,258 L222,262 L218,272 L210,278 L200,280 L190,278 L182,272 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('lips') ? 1 : 0.92"
    />
    <text class="ps-label" x="200" y="273">Lips</text>

    {{-- ===== CHIN / JAWLINE ===== --}}
    <path
        @click="toggleZone('chin_jaw')"
        :fill="selectedZones.includes('chin_jaw') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('chin_jaw') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M130,262 L140,262 L178,265 L180,280 L190,298 L200,305 L210,298 L220,280 L222,265 L260,262 L270,262 L275,270 L265,295 L248,315 L230,325 L200,330 L170,325 L152,315 L135,295 L125,270 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('chin_jaw') ? 1 : 0.92"
    />
    <text class="ps-label" x="200" y="315">Chin / Jawline</text>

    {{-- ===== NECK (face detail) ===== --}}
    <path
        @click="toggleZone('neck')"
        :fill="selectedZones.includes('neck') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('neck') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M170,328 L230,328 L235,340 L238,365 L235,385 L230,390 L170,390 L165,385 L162,365 L165,340 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('neck') ? 1 : 0.92"
    />
    <text class="ps-label" x="200" y="363">Neck</text>

    {{-- Skin texture overlay for face --}}
    <rect x="90" y="60" width="220" height="345" fill="url(#psSkinTexture)" opacity="0.5" class="pointer-events-none" />


    {{-- ==================== BODY VIEW (Right Side) ==================== --}}
    <text class="ps-subtitle" x="600" y="52">Body Zones</text>

    {{-- Body silhouette shadow --}}
    <g filter="url(#psBodyShadow)" opacity="0.06" class="pointer-events-none">
        <path d="M600,72 C575,72 565,82 563,100 C561,118 567,128 575,133 L570,140 C540,145 515,155 510,162 L485,210 L463,290 L455,320 L457,325 L467,322 L475,290 L493,230 L505,200 L515,175 L525,163 L520,230 L515,310 L520,340 L527,348 L523,430 L520,520 L517,570 L513,595 L510,610 L503,625 L510,632 L540,628 L547,610 L543,570 L550,510 L565,430 L575,348 L590,340 L600,340 L610,348 L625,430 L640,510 L647,570 L643,610 L650,628 L680,632 L687,625 L680,610 L677,595 L673,570 L670,520 L667,430 L663,348 L670,340 L675,310 L670,230 L665,163 L675,175 L685,200 L697,230 L715,290 L723,322 L733,325 L735,320 L727,290 L705,210 L680,162 L675,155 L650,145 L620,140 L615,133 C623,128 629,118 627,100 C625,82 615,72 600,72 Z" fill="#333" />
    </g>

    {{-- ===== BREAST / CHEST ===== --}}
    <path
        @click="toggleZone('breast_chest')"
        :fill="selectedZones.includes('breast_chest') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('breast_chest') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M530,152 C550,147 575,145 600,145 C625,145 650,147 670,152 L674,162 L676,190 L678,220 L673,232 C648,237 628,238 600,238 C572,238 552,237 527,232 L522,220 L524,190 L526,162 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('breast_chest') ? 1 : 0.92"
    />
    <g class="pointer-events-none" opacity="0.12">
        <ellipse cx="570" cy="190" rx="22" ry="18" fill="none" stroke="#8a6e52" stroke-width="0.7" />
        <ellipse cx="630" cy="190" rx="22" ry="18" fill="none" stroke="#8a6e52" stroke-width="0.7" />
    </g>
    <text class="ps-label" x="600" y="195">Breast / Chest</text>

    {{-- ===== UPPER ARMS ===== --}}
    <path
        @click="toggleZone('upper_arms')"
        :fill="selectedZones.includes('upper_arms') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('upper_arms') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M528,152 L515,156 C508,162 502,172 498,188 L490,222 L487,252 L492,254 L502,252 L508,222 L515,195 L522,172 L526,162 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('upper_arms') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('upper_arms')"
        :fill="selectedZones.includes('upper_arms') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('upper_arms') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M672,152 L685,156 C692,162 698,172 702,188 L710,222 L713,252 L708,254 L698,252 L692,222 L685,195 L678,172 L674,162 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('upper_arms') ? 1 : 0.92"
    />
    <text class="ps-label" x="496" y="205" transform="rotate(-12,496,205)">R. Arm</text>
    <text class="ps-label" x="704" y="205" transform="rotate(12,704,205)">L. Arm</text>

    {{-- ===== ABDOMEN / WAIST ===== --}}
    <path
        @click="toggleZone('abdomen_waist')"
        :fill="selectedZones.includes('abdomen_waist') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('abdomen_waist') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M527,234 C552,239 575,240 600,240 C625,240 648,239 673,234 L676,255 L678,295 L676,325 C666,333 638,337 600,337 C562,337 534,333 524,325 L522,295 L524,255 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('abdomen_waist') ? 1 : 0.92"
    />
    <g class="pointer-events-none" opacity="0.1">
        <ellipse cx="600" cy="290" rx="25" ry="4" fill="none" stroke="#8a6e52" stroke-width="0.6" />
        <line x1="600" y1="250" x2="600" y2="330" stroke="#8a6e52" stroke-width="0.3" />
    </g>
    <text class="ps-label" x="600" y="290">Abdomen / Waist</text>

    {{-- ===== BUTTOCKS ===== --}}
    <path
        @click="toggleZone('buttocks')"
        :fill="selectedZones.includes('buttocks') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('buttocks') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M540,335 C555,340 575,342 600,342 C625,342 645,340 660,335 L665,350 L662,375 L655,390 L635,395 L600,397 L565,395 L545,390 L538,375 L535,350 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('buttocks') ? 1 : 0.92"
    />
    <g class="pointer-events-none" opacity="0.1">
        <line x1="600" y1="342" x2="600" y2="395" stroke="#8a6e52" stroke-width="0.5" />
        <path d="M565,360 Q580,375 600,370 Q620,375 635,360" fill="none" stroke="#8a6e52" stroke-width="0.5" />
    </g>
    <text class="ps-label" x="600" y="372">Buttocks (BBL)</text>

    {{-- ===== THIGHS ===== --}}
    <path
        @click="toggleZone('thighs')"
        :fill="selectedZones.includes('thighs') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('thighs') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M540,395 L565,397 L595,398 L600,398 L600,400 L595,430 L588,470 L582,510 L575,530 L555,530 L550,515 L545,470 L538,430 L535,400 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('thighs') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('thighs')"
        :fill="selectedZones.includes('thighs') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('thighs') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M660,395 L635,397 L605,398 L600,398 L600,400 L605,430 L612,470 L618,510 L625,530 L645,530 L650,515 L655,470 L662,430 L665,400 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('thighs') ? 1 : 0.92"
    />
    <text class="ps-label" x="560" y="465">R. Thigh</text>
    <text class="ps-label" x="640" y="465">L. Thigh</text>

    {{-- ===== LOWER LEGS ===== --}}
    <path
        @click="toggleZone('lower_legs')"
        :fill="selectedZones.includes('lower_legs') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('lower_legs') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M555,532 L580,532 L583,550 L584,590 L583,630 L580,655 L578,668 L554,668 L552,655 L548,630 L546,590 L547,550 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('lower_legs') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('lower_legs')"
        :fill="selectedZones.includes('lower_legs') ? '#818cf8' : 'url(#psSkinBase)'"
        :filter="selectedZones.includes('lower_legs') ? 'url(#psSelectGlow)' : 'url(#psInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M620,532 L645,532 L648,550 L650,590 L648,630 L646,655 L644,668 L620,668 L618,655 L614,630 L612,590 L613,550 Z"
        class="ps-zone"
        :opacity="selectedZones.includes('lower_legs') ? 1 : 0.92"
    />
    <text class="ps-label" x="565" y="605">R. Leg</text>
    <text class="ps-label" x="632" y="605">L. Leg</text>

    {{-- Skin texture overlay for body --}}
    <rect x="450" y="60" width="300" height="620" fill="url(#psSkinTexture)" opacity="0.5" class="pointer-events-none" />

    {{-- Divider --}}
    <line x1="400" y1="42" x2="400" y2="710" stroke="#e5e7eb" stroke-width="1" stroke-dasharray="8 4" opacity="0.6" />

    {{-- ===== PROCEDURE LABELS ===== --}}
    <g class="pointer-events-none" opacity="0.5">
        {{-- Face side annotations --}}
        <text class="ps-marker" x="55" y="98" text-anchor="end">Hair Transplant</text>
        <line x1="58" y1="96" x2="115" y2="96" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="ps-marker" x="55" y="140" text-anchor="end">Brow Lift</text>
        <line x1="58" y1="138" x2="115" y2="138" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="ps-marker" x="55" y="188" text-anchor="end">Blepharoplasty</text>
        <line x1="58" y1="186" x2="110" y2="186" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="ps-marker" x="55" y="228" text-anchor="end">Rhinoplasty</text>
        <line x1="58" y1="226" x2="182" y2="226" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="ps-marker" x="55" y="270" text-anchor="end">Lip Surgery</text>
        <line x1="58" y1="268" x2="178" y2="268" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="ps-marker" x="55" y="315" text-anchor="end">Chin / Facelift</text>
        <line x1="58" y1="313" x2="152" y2="313" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="ps-marker" x="340" y="200" text-anchor="start">Otoplasty</text>
        <line x1="305" y1="198" x2="337" y2="198" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        {{-- Body side annotations --}}
        <text class="ps-marker" x="450" y="195" text-anchor="end">Augmentation / Reduction</text>
        <line x1="453" y1="193" x2="525" y2="193" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="ps-marker" x="450" y="290" text-anchor="end">Tummy Tuck / Lipo</text>
        <line x1="453" y1="288" x2="522" y2="288" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="ps-marker" x="450" y="372" text-anchor="end">BBL / Lift</text>
        <line x1="453" y1="370" x2="535" y2="370" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="ps-marker" x="748" y="205" text-anchor="start">Brachioplasty</text>
        <line x1="715" y1="203" x2="745" y2="203" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="ps-marker" x="748" y="465" text-anchor="start">Thigh Lift</text>
        <line x1="652" y1="463" x2="745" y2="463" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />
    </g>

    {{-- Legend --}}
    <g transform="translate(340, 705)" class="pointer-events-none">
        <rect x="0" y="0" width="8" height="8" rx="2" fill="url(#psSkinBase)" stroke="#c4996e" stroke-width="0.5" />
        <text x="12" y="8" font-family="system-ui, sans-serif" font-size="7" fill="#9ca3af">Normal</text>
        <rect x="50" y="0" width="8" height="8" rx="2" fill="#818cf8" />
        <text x="62" y="8" font-family="system-ui, sans-serif" font-size="7" fill="#9ca3af">Selected</text>
    </g>
</svg>
