{{-- Cosmetic Dermatology Diagram - Face Injection Zones & Skin Treatment Areas --}}
{{-- Requires parent Alpine.js scope with: selectedZones, toggleZone(zoneId), zoneNotes --}}

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 820 720" class="w-full h-auto max-w-3xl mx-auto">
    <defs>
        <linearGradient id="cdSkinBase" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#f5d0b0" />
            <stop offset="50%" stop-color="#e8c4a0" />
            <stop offset="100%" stop-color="#ddb892" />
        </linearGradient>
        <linearGradient id="cdSkinHighlight" x1="0.5" y1="0" x2="0.5" y2="1">
            <stop offset="0%" stop-color="#fff5eb" stop-opacity="0.3" />
            <stop offset="100%" stop-color="#fff5eb" stop-opacity="0" />
        </linearGradient>
        <radialGradient id="cdGlow" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="#e0c8f0" stop-opacity="0.15" />
            <stop offset="100%" stop-color="#e0c8f0" stop-opacity="0" />
        </radialGradient>

        <filter id="cdBodyShadow" x="-5%" y="-5%" width="110%" height="110%">
            <feDropShadow dx="2" dy="2" stdDeviation="3" flood-color="#a08060" flood-opacity="0.15" />
        </filter>
        <filter id="cdInnerGlow" x="-10%" y="-10%" width="120%" height="120%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="2" result="blur" />
            <feFlood flood-color="#c49070" flood-opacity="0.12" result="color" />
            <feComposite in="color" in2="blur" operator="in" result="shadow" />
            <feMerge>
                <feMergeNode in="shadow" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>
        <filter id="cdSelectGlow" x="-15%" y="-15%" width="130%" height="130%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="4" result="blur" />
            <feFlood flood-color="#818cf8" flood-opacity="0.35" result="color" />
            <feComposite in="color" in2="blur" operator="in" result="shadow" />
            <feMerge>
                <feMergeNode in="shadow" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>

        <pattern id="cdSkinTexture" x="0" y="0" width="6" height="6" patternUnits="userSpaceOnUse">
            <rect width="6" height="6" fill="transparent" />
            <circle cx="1" cy="1" r="0.3" fill="#c49a78" opacity="0.08" />
            <circle cx="4" cy="3" r="0.25" fill="#c49a78" opacity="0.06" />
        </pattern>

        <style>
            .cd-label { font-family: system-ui, -apple-system, sans-serif; font-size: 8.5px; fill: #4b5563; pointer-events: none; text-anchor: middle; font-weight: 500; }
            .cd-title { font-family: system-ui, -apple-system, sans-serif; font-size: 15px; font-weight: 700; fill: #1f2937; text-anchor: middle; letter-spacing: 0.02em; }
            .cd-subtitle { font-family: system-ui, -apple-system, sans-serif; font-size: 12px; font-weight: 600; fill: #6b7280; text-anchor: middle; }
            .cd-zone { cursor: pointer; transition: all 0.25s cubic-bezier(0.4,0,0.2,1); transform-origin: center; }
            .cd-zone:hover { filter: brightness(1.08) drop-shadow(0 0 6px rgba(99,102,241,0.3)); transform: scale(1.012); }
            .cd-marker { font-family: system-ui, sans-serif; font-size: 7px; fill: #9ca3af; pointer-events: none; }
            .cd-inject { font-family: system-ui, sans-serif; font-size: 6.5px; fill: #a78bfa; pointer-events: none; font-weight: 600; }
        </style>
    </defs>

    {{-- Background --}}
    <rect width="820" height="720" fill="#fafaf9" rx="8" />

    {{-- Title --}}
    <text class="cd-title" x="410" y="28">Cosmetic Dermatology - Treatment Zone Assessment</text>

    {{-- Divider --}}
    <line x1="400" y1="42" x2="400" y2="710" stroke="#e5e7eb" stroke-width="1" stroke-dasharray="8 4" opacity="0.6" />

    {{-- ==================== FACE ZONES (Left Side) ==================== --}}
    <text class="cd-subtitle" x="200" y="52">Face - Injection & Treatment Zones</text>

    {{-- Face outline shadow --}}
    <g filter="url(#cdBodyShadow)" opacity="0.06" class="pointer-events-none">
        <ellipse cx="200" cy="250" rx="130" ry="170" fill="#333" />
    </g>

    {{-- ===== FOREHEAD ===== --}}
    <path
        @click="toggleZone('forehead')"
        :fill="selectedZones.includes('forehead') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('forehead') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M200,78 C150,78 118,100 112,135 L115,148 L125,158 L200,162 L275,158 L285,148 L288,135 C282,100 250,78 200,78 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('forehead') ? 1 : 0.92"
    />
    <text class="cd-label" x="200" y="125">Forehead</text>
    <text class="cd-inject" x="200" y="136">Botox</text>

    {{-- ===== GLABELLA (between brows) ===== --}}
    <ellipse
        @click="toggleZone('glabella')"
        :fill="selectedZones.includes('glabella') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('glabella') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        cx="200" cy="170" rx="18" ry="10"
        class="cd-zone"
        :opacity="selectedZones.includes('glabella') ? 1 : 0.92"
    />
    <text class="cd-label" x="200" y="173">Glabella</text>

    {{-- ===== CROW'S FEET (around eyes) ===== --}}
    <path
        @click="toggleZone('crows_feet')"
        :fill="selectedZones.includes('crows_feet') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('crows_feet') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M118,180 L128,175 L138,182 L135,195 L125,200 L115,195 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('crows_feet') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('crows_feet')"
        :fill="selectedZones.includes('crows_feet') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('crows_feet') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M282,180 L272,175 L262,182 L265,195 L275,200 L285,195 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('crows_feet') ? 1 : 0.92"
    />
    <text class="cd-label" x="112" y="175" text-anchor="end">Crow's</text>
    <text class="cd-label" x="112" y="184" text-anchor="end">Feet</text>
    <text class="cd-label" x="288" y="175" text-anchor="start">Crow's</text>
    <text class="cd-label" x="288" y="184" text-anchor="start">Feet</text>

    {{-- ===== UNDER EYES / TEAR TROUGH ===== --}}
    <path
        @click="toggleZone('under_eyes')"
        :fill="selectedZones.includes('under_eyes') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('under_eyes') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M148,198 L175,200 L178,208 L170,216 L148,214 L140,208 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('under_eyes') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('under_eyes')"
        :fill="selectedZones.includes('under_eyes') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('under_eyes') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M252,198 L225,200 L222,208 L230,216 L252,214 L260,208 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('under_eyes') ? 1 : 0.92"
    />
    {{-- Eye details --}}
    <g class="pointer-events-none" opacity="0.2">
        <ellipse cx="162" cy="192" rx="16" ry="8" fill="none" stroke="#5a4a3a" stroke-width="0.6" />
        <ellipse cx="238" cy="192" rx="16" ry="8" fill="none" stroke="#5a4a3a" stroke-width="0.6" />
        <circle cx="162" cy="192" r="4" fill="#5a4a3a" opacity="0.3" />
        <circle cx="238" cy="192" r="4" fill="#5a4a3a" opacity="0.3" />
    </g>
    <text class="cd-label" x="155" y="212">Under Eye</text>
    <text class="cd-label" x="245" y="212">Under Eye</text>

    {{-- ===== TEMPLES ===== --}}
    <ellipse
        @click="toggleZone('temples')"
        :fill="selectedZones.includes('temples') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('temples') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        cx="120" cy="162" rx="12" ry="16"
        class="cd-zone"
        :opacity="selectedZones.includes('temples') ? 1 : 0.92"
    />
    <ellipse
        @click="toggleZone('temples')"
        :fill="selectedZones.includes('temples') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('temples') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        cx="280" cy="162" rx="12" ry="16"
        class="cd-zone"
        :opacity="selectedZones.includes('temples') ? 1 : 0.92"
    />
    <text class="cd-label" x="102" y="155" text-anchor="end">Temple</text>
    <text class="cd-label" x="298" y="155" text-anchor="start">Temple</text>

    {{-- ===== NOSE ===== --}}
    <path
        @click="toggleZone('nose')"
        :fill="selectedZones.includes('nose') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('nose') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M195,205 L192,225 L186,245 L180,258 L188,264 L200,268 L212,264 L220,258 L214,245 L208,225 L205,205 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('nose') ? 1 : 0.92"
    />
    <text class="cd-label" x="200" y="245">Nose</text>

    {{-- ===== NASOLABIAL FOLDS ===== --}}
    <path
        @click="toggleZone('nasolabial')"
        :fill="selectedZones.includes('nasolabial') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('nasolabial') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M172,255 L180,258 L178,278 L172,290 L165,288 L163,270 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('nasolabial') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('nasolabial')"
        :fill="selectedZones.includes('nasolabial') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('nasolabial') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M228,255 L220,258 L222,278 L228,290 L235,288 L237,270 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('nasolabial') ? 1 : 0.92"
    />
    <text class="cd-label" x="152" y="275" text-anchor="end">NL Fold</text>
    <text class="cd-label" x="248" y="275" text-anchor="start">NL Fold</text>

    {{-- ===== CHEEKS (Filler) ===== --}}
    <path
        @click="toggleZone('cheeks')"
        :fill="selectedZones.includes('cheeks') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('cheeks') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M120,215 L145,218 L158,230 L158,252 L148,262 L130,260 L115,248 L110,230 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('cheeks') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('cheeks')"
        :fill="selectedZones.includes('cheeks') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('cheeks') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M280,215 L255,218 L242,230 L242,252 L252,262 L270,260 L285,248 L290,230 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('cheeks') ? 1 : 0.92"
    />
    <text class="cd-label" x="136" y="242">Cheek</text>
    <text class="cd-inject" x="136" y="252">Filler</text>
    <text class="cd-label" x="264" y="242">Cheek</text>
    <text class="cd-inject" x="264" y="252">Filler</text>

    {{-- ===== LIPS ===== --}}
    <path
        @click="toggleZone('lips')"
        :fill="selectedZones.includes('lips') ? '#818cf8' : '#e8a090'"
        :filter="selectedZones.includes('lips') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M175,290 L188,284 L200,287 L212,284 L225,290 L222,302 L212,310 L200,313 L188,310 L178,302 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('lips') ? 1 : 0.92"
    />
    <text class="cd-label" x="200" y="300">Lips</text>
    <text class="cd-inject" x="200" y="310">Filler</text>

    {{-- ===== MARIONETTE LINES ===== --}}
    <path
        @click="toggleZone('marionette')"
        :fill="selectedZones.includes('marionette') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('marionette') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M170,302 L178,305 L175,322 L168,335 L160,332 L162,315 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('marionette') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('marionette')"
        :fill="selectedZones.includes('marionette') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('marionette') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M230,302 L222,305 L225,322 L232,335 L240,332 L238,315 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('marionette') ? 1 : 0.92"
    />
    <text class="cd-label" x="142" y="325" text-anchor="end">Marionette</text>
    <text class="cd-label" x="258" y="325" text-anchor="start">Marionette</text>

    {{-- ===== JAWLINE ===== --}}
    <path
        @click="toggleZone('jawline')"
        :fill="selectedZones.includes('jawline') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('jawline') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M125,268 L148,266 L160,290 L168,335 L162,348 L150,355 L200,360 L250,355 L238,348 L232,335 L240,290 L252,266 L275,268 L282,280 L270,320 L255,348 L238,365 L200,370 L162,365 L145,348 L130,320 L118,280 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('jawline') ? 1 : 0.92"
    />
    <text class="cd-label" x="200" y="355">Jawline</text>
    <text class="cd-inject" x="200" y="365">Filler / HIFU</text>

    {{-- ===== CHIN ===== --}}
    <path
        @click="toggleZone('chin')"
        :fill="selectedZones.includes('chin') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('chin') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M180,335 L190,320 L200,318 L210,320 L220,335 L215,350 L200,356 L185,350 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('chin') ? 1 : 0.92"
    />
    <text class="cd-label" x="200" y="340">Chin</text>

    {{-- ===== NECK / DECOLLETAGE ===== --}}
    <path
        @click="toggleZone('neck_decolletage')"
        :fill="selectedZones.includes('neck_decolletage') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('neck_decolletage') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M155,372 L245,372 L255,380 L265,410 L275,450 L280,480 L278,485 L122,485 L120,480 L125,450 L135,410 L145,380 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('neck_decolletage') ? 1 : 0.92"
    />
    <g class="pointer-events-none" opacity="0.1">
        <line x1="165" y1="380" x2="235" y2="380" stroke="#8a6e52" stroke-width="0.5" />
        <path d="M140,420 Q200,430 260,420" fill="none" stroke="#8a6e52" stroke-width="0.4" />
        <path d="M135,445 Q200,455 265,445" fill="none" stroke="#8a6e52" stroke-width="0.4" />
    </g>
    <text class="cd-label" x="200" y="405">Neck</text>
    <text class="cd-label" x="200" y="460">Décolletage</text>
    <text class="cd-inject" x="200" y="470">Profhilo / HIFU</text>

    {{-- Skin texture overlay --}}
    <rect x="100" y="65" width="200" height="430" fill="url(#cdSkinTexture)" opacity="0.5" class="pointer-events-none" />


    {{-- ==================== BODY TREATMENT ZONES (Right Side) ==================== --}}
    <text class="cd-subtitle" x="610" y="52">Body - Skin Treatment Zones</text>

    {{-- Body silhouette shadow --}}
    <g filter="url(#cdBodyShadow)" opacity="0.06" class="pointer-events-none">
        <path d="M610,80 C585,80 575,90 573,108 C571,126 577,136 585,141 L580,148 C550,153 525,163 520,170 L495,218 L473,298 L465,328 L467,333 L477,330 L485,298 L503,238 L515,208 L525,183 L535,171 L530,238 L525,318 L530,348 L537,356 L533,438 L530,528 L527,578 L523,603 L520,618 L513,633 L520,640 L550,636 L557,618 L553,578 L560,518 L575,438 L585,356 L600,348 L610,348 L620,356 L635,438 L650,518 L657,578 L653,618 L660,636 L690,640 L697,633 L690,618 L687,603 L683,578 L680,528 L677,438 L673,356 L680,348 L685,318 L680,238 L675,171 L685,183 L695,208 L707,238 L725,298 L733,330 L743,333 L745,328 L737,298 L715,218 L690,170 L685,163 L660,153 L630,148 L625,141 C633,136 639,126 637,108 C635,90 625,80 610,80 Z" fill="#333" />
    </g>

    {{-- ===== FACE/SCALP (body view) ===== --}}
    <path
        @click="toggleZone('face_scalp')"
        :fill="selectedZones.includes('face_scalp') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('face_scalp') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M610,78 C590,78 578,90 575,106 C573,122 579,133 585,138 C592,142 601,144 610,144 C619,144 628,142 635,138 C641,133 647,122 645,106 C643,90 630,78 610,78 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('face_scalp') ? 1 : 0.92"
    />
    <g class="pointer-events-none" opacity="0.15">
        <ellipse cx="600" cy="110" rx="6" ry="3" fill="none" stroke="#5a4a3a" stroke-width="0.5" />
        <ellipse cx="620" cy="110" rx="6" ry="3" fill="none" stroke="#5a4a3a" stroke-width="0.5" />
        <path d="M605,125 Q610,128 615,125" fill="none" stroke="#5a4a3a" stroke-width="0.4" />
    </g>
    <text class="cd-label" x="610" y="115">Face / Scalp</text>

    {{-- ===== NECK (body view) ===== --}}
    <path
        @click="toggleZone('neck_body')"
        :fill="selectedZones.includes('neck_body') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('neck_body') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M597,143 L623,143 L627,148 L628,162 L625,168 L595,168 L592,162 L593,148 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('neck_body') ? 1 : 0.92"
    />
    <text class="cd-label" x="610" y="159">Neck</text>

    {{-- ===== CHEST / DÉCOLLETAGE (body view) ===== --}}
    <path
        @click="toggleZone('chest_decolletage')"
        :fill="selectedZones.includes('chest_decolletage') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('chest_decolletage') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M545,170 C565,165 588,163 610,163 C632,163 655,165 675,170 L678,180 L680,210 L682,240 L677,248 C652,253 635,254 610,254 C585,254 568,253 543,248 L538,240 L540,210 L542,180 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('chest_decolletage') ? 1 : 0.92"
    />
    <text class="cd-label" x="610" y="212">Chest / Décolletage</text>
    <text class="cd-inject" x="610" y="223">Laser / Pigmentation</text>

    {{-- ===== ARMS (body view) ===== --}}
    <path
        @click="toggleZone('arms')"
        :fill="selectedZones.includes('arms') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('arms') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M543,170 L528,175 C520,182 514,192 510,208 L502,240 L498,270 L492,300 L486,330 L484,345 L488,347 L502,345 L506,330 L512,300 L518,270 L524,240 L530,210 L536,185 L540,175 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('arms') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('arms')"
        :fill="selectedZones.includes('arms') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('arms') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M677,170 L692,175 C700,182 706,192 710,208 L718,240 L722,270 L728,300 L734,330 L736,345 L732,347 L718,345 L714,330 L708,300 L702,270 L696,240 L690,210 L684,185 L680,175 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('arms') ? 1 : 0.92"
    />
    <text class="cd-label" x="502" y="270" transform="rotate(-10,502,270)">R. Arm</text>
    <text class="cd-inject" x="502" y="280" transform="rotate(-10,502,280)">Laser</text>
    <text class="cd-label" x="718" y="270" transform="rotate(10,718,270)">L. Arm</text>

    {{-- ===== ABDOMEN (body view) ===== --}}
    <path
        @click="toggleZone('abdomen')"
        :fill="selectedZones.includes('abdomen') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('abdomen') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M543,250 C568,255 588,256 610,256 C632,256 652,255 677,250 L680,270 L682,310 L680,340 C670,348 642,352 610,352 C578,352 550,348 540,340 L538,310 L540,270 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('abdomen') ? 1 : 0.92"
    />
    <text class="cd-label" x="610" y="300">Abdomen</text>
    <text class="cd-inject" x="610" y="311">Stretch Marks / Laser</text>

    {{-- ===== LEGS (body view) ===== --}}
    <path
        @click="toggleZone('legs')"
        :fill="selectedZones.includes('legs') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('legs') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M540,342 C550,350 572,354 590,354 L610,354 L610,358 L605,390 L598,430 L590,480 L584,530 L578,570 L572,600 L568,620 L540,620 L538,600 L535,570 L530,480 L528,430 L527,390 L526,358 L526,342 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('legs') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('legs')"
        :fill="selectedZones.includes('legs') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('legs') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M680,342 C670,350 648,354 630,354 L610,354 L610,358 L615,390 L622,430 L630,480 L636,530 L642,570 L648,600 L652,620 L680,620 L682,600 L685,570 L690,480 L692,430 L693,390 L694,358 L694,342 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('legs') ? 1 : 0.92"
    />
    <text class="cd-label" x="558" y="490">R. Leg</text>
    <text class="cd-inject" x="558" y="500">Laser / Veins</text>
    <text class="cd-label" x="662" y="490">L. Leg</text>

    {{-- ===== HANDS (body view) ===== --}}
    <path
        @click="toggleZone('hands')"
        :fill="selectedZones.includes('hands') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('hands') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M484,348 L480,358 L472,375 L468,388 L470,392 L476,388 L478,378 L482,372 L480,385 L478,398 L480,402 L486,398 L488,385 L490,372 L490,388 L489,400 L491,404 L497,400 L498,388 L498,375 L500,385 L500,398 L502,400 L507,396 L506,380 L504,368 L502,358 L502,348 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('hands') ? 1 : 0.92"
    />
    <path
        @click="toggleZone('hands')"
        :fill="selectedZones.includes('hands') ? '#818cf8' : 'url(#cdSkinBase)'"
        :filter="selectedZones.includes('hands') ? 'url(#cdSelectGlow)' : 'url(#cdInnerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M736,348 L740,358 L748,375 L752,388 L750,392 L744,388 L742,378 L738,372 L740,385 L742,398 L740,402 L734,398 L732,385 L730,372 L730,388 L731,400 L729,404 L723,400 L722,388 L722,375 L720,385 L720,398 L718,400 L713,396 L714,380 L716,368 L718,358 L718,348 Z"
        class="cd-zone"
        :opacity="selectedZones.includes('hands') ? 1 : 0.92"
    />
    <text class="cd-label" x="478" y="365">R. Hand</text>
    <text class="cd-label" x="742" y="365">L. Hand</text>

    {{-- Skin texture overlay for body --}}
    <rect x="465" y="70" width="290" height="570" fill="url(#cdSkinTexture)" opacity="0.5" class="pointer-events-none" />

    {{-- ===== TREATMENT ANNOTATIONS ===== --}}
    <g class="pointer-events-none" opacity="0.45">
        <text class="cd-marker" x="50" y="125" text-anchor="end">Botox / Threads</text>
        <line x1="53" y1="123" x2="112" y2="123" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="cd-marker" x="50" y="192" text-anchor="end">Botox</text>
        <line x1="53" y1="190" x2="115" y2="190" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="cd-marker" x="50" y="240" text-anchor="end">Filler / Sculptra</text>
        <line x1="53" y1="238" x2="110" y2="238" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="cd-marker" x="50" y="298" text-anchor="end">Lip Filler</text>
        <line x1="53" y1="296" x2="175" y2="296" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="cd-marker" x="50" y="355" text-anchor="end">Jawline Filler / HIFU</text>
        <line x1="53" y1="353" x2="118" y2="353" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="cd-marker" x="50" y="440" text-anchor="end">Profhilo / Meso</text>
        <line x1="53" y1="438" x2="120" y2="438" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />

        <text class="cd-marker" x="350" y="208" text-anchor="end">Under Eye Filler</text>
        <line x1="253" y1="206" x2="347" y2="206" stroke="#d1d5db" stroke-width="0.5" stroke-dasharray="2 2" />
    </g>

    {{-- Legend --}}
    <g transform="translate(340, 705)" class="pointer-events-none">
        <rect x="0" y="0" width="8" height="8" rx="2" fill="url(#cdSkinBase)" stroke="#c4996e" stroke-width="0.5" />
        <text x="12" y="8" font-family="system-ui, sans-serif" font-size="7" fill="#9ca3af">Normal</text>
        <rect x="50" y="0" width="8" height="8" rx="2" fill="#818cf8" />
        <text x="62" y="8" font-family="system-ui, sans-serif" font-size="7" fill="#9ca3af">Selected</text>
    </g>
</svg>
