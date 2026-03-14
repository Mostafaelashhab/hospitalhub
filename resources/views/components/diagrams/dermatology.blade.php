{{-- Dermatology Diagram - Realistic Front and Back Body Views --}}
{{-- Requires parent Alpine.js scope with: selectedZones, toggleZone(zoneId), zoneNotes --}}

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 820 700" class="w-full h-auto max-w-3xl mx-auto">
    <defs>
        {{-- Skin tone gradient --}}
        <linearGradient id="skinBase" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#f5d0b0" />
            <stop offset="50%" stop-color="#e8c4a0" />
            <stop offset="100%" stop-color="#ddb892" />
        </linearGradient>
        <linearGradient id="skinShadowL" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#d4a880" stop-opacity="0.4" />
            <stop offset="100%" stop-color="#d4a880" stop-opacity="0" />
        </linearGradient>
        <linearGradient id="skinShadowR" x1="1" y1="0" x2="0" y2="0">
            <stop offset="0%" stop-color="#d4a880" stop-opacity="0.4" />
            <stop offset="100%" stop-color="#d4a880" stop-opacity="0" />
        </linearGradient>
        <linearGradient id="skinHighlight" x1="0.5" y1="0" x2="0.5" y2="1">
            <stop offset="0%" stop-color="#fff5eb" stop-opacity="0.3" />
            <stop offset="100%" stop-color="#fff5eb" stop-opacity="0" />
        </linearGradient>

        {{-- Body contour shadow filter --}}
        <filter id="bodyShadow" x="-5%" y="-5%" width="110%" height="110%">
            <feDropShadow dx="2" dy="2" stdDeviation="3" flood-color="#a08060" flood-opacity="0.15" />
        </filter>
        <filter id="innerGlow" x="-10%" y="-10%" width="120%" height="120%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="2" result="blur" />
            <feFlood flood-color="#c49070" flood-opacity="0.12" result="color" />
            <feComposite in="color" in2="blur" operator="in" result="shadow" />
            <feMerge>
                <feMergeNode in="shadow" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>
        <filter id="selectGlow" x="-15%" y="-15%" width="130%" height="130%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="4" result="blur" />
            <feFlood flood-color="#818cf8" flood-opacity="0.35" result="color" />
            <feComposite in="color" in2="blur" operator="in" result="shadow" />
            <feMerge>
                <feMergeNode in="shadow" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>

        {{-- Skin texture pattern --}}
        <pattern id="skinTexture" x="0" y="0" width="6" height="6" patternUnits="userSpaceOnUse">
            <rect width="6" height="6" fill="transparent" />
            <circle cx="1" cy="1" r="0.3" fill="#c49a78" opacity="0.08" />
            <circle cx="4" cy="3" r="0.25" fill="#c49a78" opacity="0.06" />
            <circle cx="2" cy="5" r="0.2" fill="#c49a78" opacity="0.05" />
        </pattern>

        {{-- Zone hover/selected styles --}}
        <style>
            .derm-label { font-family: system-ui, -apple-system, sans-serif; font-size: 9px; fill: #4b5563; pointer-events: none; text-anchor: middle; font-weight: 500; }
            .derm-title { font-family: system-ui, -apple-system, sans-serif; font-size: 15px; font-weight: 700; fill: #1f2937; text-anchor: middle; letter-spacing: 0.02em; }
            .derm-subtitle { font-family: system-ui, -apple-system, sans-serif; font-size: 12px; font-weight: 600; fill: #6b7280; text-anchor: middle; }
            .derm-zone { cursor: pointer; transition: all 0.25s cubic-bezier(0.4,0,0.2,1); transform-origin: center; }
            .derm-zone:hover { filter: brightness(1.08) drop-shadow(0 0 6px rgba(99,102,241,0.3)); transform: scale(1.012); }
            .derm-contour { fill: none; stroke: #c4996e; stroke-width: 0.5; opacity: 0.2; pointer-events: none; }
            .derm-separator { stroke: #d1d5db; stroke-width: 0.5; stroke-dasharray: 2 2; opacity: 0.4; pointer-events: none; }
        </style>
    </defs>

    {{-- Background --}}
    <rect width="820" height="700" fill="#fafaf9" rx="8" />

    {{-- Title --}}
    <text class="derm-title" x="410" y="28">Dermatology - Skin Zone Assessment</text>

    {{-- Divider --}}
    <line x1="410" y1="42" x2="410" y2="690" stroke="#e5e7eb" stroke-width="1" stroke-dasharray="8 4" opacity="0.6" />

    {{-- ==================== FRONT VIEW (Left Side) ==================== --}}
    <text class="derm-subtitle" x="205" y="52">Anterior (Front)</text>

    {{-- Front body silhouette outline for context --}}
    <g filter="url(#bodyShadow)" opacity="0.08" class="pointer-events-none">
        <path d="M205,72 C180,72 170,82 168,100 C166,118 172,128 180,133 L175,140 C145,145 120,155 115,162 L90,210 L68,290 L60,320 L62,325 L72,322 L80,290 L98,230 L110,200 L120,175 L130,163 L125,230 L120,310 L125,340 L132,348 L128,430 L125,520 L122,570 L118,595 L115,610 L108,625 L115,632 L145,628 L152,610 L148,570 L155,510 L170,430 L180,348 L195,340 L205,340 L215,348 L230,430 L245,510 L252,570 L248,610 L255,628 L285,632 L292,625 L285,610 L282,595 L278,570 L275,520 L272,430 L268,348 L275,340 L280,310 L275,230 L270,163 L280,175 L290,200 L302,230 L320,290 L328,322 L338,325 L340,320 L332,290 L310,210 L285,162 L280,155 L255,145 L225,140 L220,133 C228,128 234,118 232,100 C230,82 220,72 205,72 Z" fill="#333" />
    </g>

    {{-- ===== FRONT HEAD/FACE ===== --}}
    <path
        @click="toggleZone('head_face')"
        :fill="selectedZones.includes('head_face') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('head_face') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M205,72 C183,72 171,84 169,100 C167,116 173,127 180,132 C186,136 195,138 205,138 C215,138 224,136 230,132 C237,127 243,116 241,100 C239,84 227,72 205,72 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('head_face') ? 1 : 0.92"
    />
    {{-- Face details --}}
    <g class="pointer-events-none" opacity="0.15">
        <ellipse cx="192" cy="102" rx="8" ry="4" fill="none" stroke="#8a6e52" stroke-width="0.6" />
        <ellipse cx="218" cy="102" rx="8" ry="4" fill="none" stroke="#8a6e52" stroke-width="0.6" />
        <path d="M200,112 L204,120 L198,120 Z" fill="none" stroke="#8a6e52" stroke-width="0.5" />
        <path d="M195,128 Q205,133 215,128" fill="none" stroke="#8a6e52" stroke-width="0.5" />
    </g>
    <text class="derm-label" x="205" y="107">Head / Face</text>

    {{-- Hair suggestion --}}
    <path d="M172,92 C172,72 185,64 205,64 C225,64 238,72 238,92" fill="none" stroke="#8a7460" stroke-width="0.8" opacity="0.2" class="pointer-events-none" />

    {{-- ===== FRONT NECK ===== --}}
    <path
        @click="toggleZone('neck')"
        :fill="selectedZones.includes('neck') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('neck') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M192,137 L218,137 L222,140 C222,148 220,155 218,158 L192,158 C190,155 188,148 188,140 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('neck') ? 1 : 0.92"
    />
    <text class="derm-label" x="205" y="151">Neck</text>

    {{-- ===== FRONT CHEST ===== --}}
    <path
        @click="toggleZone('chest')"
        :fill="selectedZones.includes('chest') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('chest') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M140,162 C155,157 180,155 205,155 C230,155 255,157 270,162 L275,170 L278,200 L280,230 L275,240 C250,245 230,246 205,246 C180,246 160,245 135,240 L130,230 L132,200 L135,170 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('chest') ? 1 : 0.92"
    />
    {{-- Chest contour hints --}}
    <g class="pointer-events-none" opacity="0.1">
        <path d="M165,185 Q175,195 190,192" fill="none" stroke="#8a6e52" stroke-width="0.8" />
        <path d="M245,185 Q235,195 220,192" fill="none" stroke="#8a6e52" stroke-width="0.8" />
        <line x1="205" y1="165" x2="205" y2="240" stroke="#8a6e52" stroke-width="0.3" />
    </g>
    <text class="derm-label" x="205" y="202">Chest</text>

    {{-- ===== FRONT ABDOMEN ===== --}}
    <path
        @click="toggleZone('abdomen')"
        :fill="selectedZones.includes('abdomen') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('abdomen') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M135,242 C160,247 180,248 205,248 C230,248 250,247 275,242 L278,260 L280,300 L278,330 C268,338 240,342 205,342 C170,342 142,338 132,330 L130,300 L132,260 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('abdomen') ? 1 : 0.92"
    />
    {{-- Abdominal contour --}}
    <g class="pointer-events-none" opacity="0.08">
        <ellipse cx="205" cy="295" rx="28" ry="4" fill="none" stroke="#8a6e52" stroke-width="0.6" />
        <line x1="205" y1="255" x2="205" y2="335" stroke="#8a6e52" stroke-width="0.3" />
    </g>
    <text class="derm-label" x="205" y="292">Abdomen</text>

    {{-- ===== FRONT RIGHT ARM (upper) ===== --}}
    <path
        @click="toggleZone('right_arm')"
        :fill="selectedZones.includes('right_arm') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_arm') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M138,162 L125,165 C118,170 112,180 108,195 L100,230 L97,260 L102,262 L112,260 L118,230 L125,200 L132,180 L135,170 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_arm') ? 1 : 0.92"
    />
    {{-- Deltoid/bicep contour --}}
    <path d="M130,172 Q122,185 118,200" class="derm-contour pointer-events-none" />
    <text class="derm-label" x="112" y="215" transform="rotate(-10,112,215)">R. Arm</text>

    {{-- ===== FRONT RIGHT FOREARM ===== --}}
    <path
        @click="toggleZone('right_forearm')"
        :fill="selectedZones.includes('right_forearm') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_forearm') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M97,262 L92,280 L82,320 L76,355 L74,370 L78,372 L92,370 L96,355 L102,320 L108,280 L112,262 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_forearm') ? 1 : 0.92"
    />
    <text class="derm-label" x="90" y="320" transform="rotate(-6,90,320)">R. Forearm</text>

    {{-- ===== FRONT RIGHT HAND ===== --}}
    <path
        @click="toggleZone('right_hand')"
        :fill="selectedZones.includes('right_hand') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_hand') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M74,372 L70,380 L62,395 L58,408 L60,412 L66,408 L68,398 L72,392 L70,405 L68,418 L70,422 L76,418 L78,405 L80,392 L80,408 L79,420 L81,424 L87,420 L88,408 L88,395 L90,405 L90,418 L92,420 L97,416 L96,400 L94,388 L92,378 L92,372 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_hand') ? 1 : 0.92"
    />
    <text class="derm-label" x="72" y="385">R. Hand</text>

    {{-- ===== FRONT LEFT ARM (upper) ===== --}}
    <path
        @click="toggleZone('left_arm')"
        :fill="selectedZones.includes('left_arm') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_arm') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M272,162 L285,165 C292,170 298,180 302,195 L310,230 L313,260 L308,262 L298,260 L292,230 L285,200 L278,180 L275,170 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_arm') ? 1 : 0.92"
    />
    <path d="M280,172 Q288,185 292,200" class="derm-contour pointer-events-none" />
    <text class="derm-label" x="298" y="215" transform="rotate(10,298,215)">L. Arm</text>

    {{-- ===== FRONT LEFT FOREARM ===== --}}
    <path
        @click="toggleZone('left_forearm')"
        :fill="selectedZones.includes('left_forearm') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_forearm') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M313,262 L318,280 L328,320 L334,355 L336,370 L332,372 L318,370 L314,355 L308,320 L302,280 L298,262 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_forearm') ? 1 : 0.92"
    />
    <text class="derm-label" x="320" y="320" transform="rotate(6,320,320)">L. Forearm</text>

    {{-- ===== FRONT LEFT HAND ===== --}}
    <path
        @click="toggleZone('left_hand')"
        :fill="selectedZones.includes('left_hand') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_hand') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M336,372 L340,380 L348,395 L352,408 L350,412 L344,408 L342,398 L338,392 L340,405 L342,418 L340,422 L334,418 L332,405 L330,392 L330,408 L331,420 L329,424 L323,420 L322,408 L322,395 L320,405 L320,418 L318,420 L313,416 L314,400 L316,388 L318,378 L318,372 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_hand') ? 1 : 0.92"
    />
    <text class="derm-label" x="338" y="385">L. Hand</text>

    {{-- ===== FRONT RIGHT THIGH ===== --}}
    <path
        @click="toggleZone('right_thigh')"
        :fill="selectedZones.includes('right_thigh') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_thigh') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M132,332 C142,340 160,344 178,344 L205,344 L205,348 L200,380 L195,420 L188,460 L182,490 L175,500 L155,500 L150,490 L148,460 L142,420 L135,380 L130,348 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_thigh') ? 1 : 0.92"
    />
    <text class="derm-label" x="172" y="425">R. Thigh</text>

    {{-- ===== FRONT LEFT THIGH ===== --}}
    <path
        @click="toggleZone('left_thigh')"
        :fill="selectedZones.includes('left_thigh') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_thigh') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M278,332 C268,340 250,344 232,344 L205,344 L205,348 L210,380 L215,420 L222,460 L228,490 L235,500 L255,500 L260,490 L262,460 L268,420 L275,380 L280,348 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_thigh') ? 1 : 0.92"
    />
    <text class="derm-label" x="238" y="425">L. Thigh</text>

    {{-- ===== FRONT RIGHT LEG (lower) ===== --}}
    <path
        @click="toggleZone('right_leg')"
        :fill="selectedZones.includes('right_leg') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_leg') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M155,502 L182,502 L185,520 L186,560 L185,600 L182,630 L180,645 L154,645 L152,630 L148,600 L146,560 L147,520 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_leg') ? 1 : 0.92"
    />
    {{-- Shin contour --}}
    <path d="M168,510 L166,580 L164,630" class="derm-contour pointer-events-none" />
    <text class="derm-label" x="167" y="575">R. Leg</text>

    {{-- ===== FRONT LEFT LEG (lower) ===== --}}
    <path
        @click="toggleZone('left_leg')"
        :fill="selectedZones.includes('left_leg') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_leg') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M228,502 L255,502 L258,520 L260,560 L258,600 L256,630 L254,645 L228,645 L226,630 L222,600 L220,560 L221,520 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_leg') ? 1 : 0.92"
    />
    <path d="M242,510 L240,580 L238,630" class="derm-contour pointer-events-none" />
    <text class="derm-label" x="241" y="575">L. Leg</text>

    {{-- ===== FRONT RIGHT FOOT ===== --}}
    <path
        @click="toggleZone('right_foot')"
        :fill="selectedZones.includes('right_foot') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_foot') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M154,647 L180,647 L182,655 L183,662 L178,668 L148,670 L142,666 L143,658 L148,650 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_foot') ? 1 : 0.92"
    />
    <text class="derm-label" x="163" y="662">R. Foot</text>

    {{-- ===== FRONT LEFT FOOT ===== --}}
    <path
        @click="toggleZone('left_foot')"
        :fill="selectedZones.includes('left_foot') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_foot') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M228,647 L254,647 L260,650 L265,658 L266,666 L260,670 L230,668 L225,662 L226,655 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_foot') ? 1 : 0.92"
    />
    <text class="derm-label" x="246" y="662">L. Foot</text>

    {{-- Skin texture overlay for front --}}
    <rect x="55" y="60" width="300" height="620" fill="url(#skinTexture)" opacity="0.5" class="pointer-events-none" />


    {{-- ==================== BACK VIEW (Right Side) ==================== --}}
    <text class="derm-subtitle" x="615" y="52">Posterior (Back)</text>

    {{-- Back body silhouette shadow --}}
    <g filter="url(#bodyShadow)" opacity="0.08" class="pointer-events-none">
        <path d="M615,72 C590,72 580,82 578,100 C576,118 582,128 590,133 L585,140 C555,145 530,155 525,162 L500,210 L478,290 L470,320 L472,325 L482,322 L490,290 L508,230 L520,200 L530,175 L540,163 L535,230 L530,310 L535,340 L542,348 L538,430 L535,520 L532,570 L528,595 L525,610 L518,625 L525,632 L555,628 L562,610 L558,570 L565,510 L580,430 L590,348 L605,340 L615,340 L625,348 L640,430 L655,510 L662,570 L658,610 L665,628 L695,632 L702,625 L695,610 L692,595 L688,570 L685,520 L682,430 L678,348 L685,340 L690,310 L685,230 L680,163 L690,175 L700,200 L712,230 L730,290 L738,322 L748,325 L750,320 L742,290 L720,210 L695,162 L690,155 L665,145 L635,140 L630,133 C638,128 644,118 642,100 C640,82 630,72 615,72 Z" fill="#333" />
    </g>

    {{-- ===== BACK HEAD ===== --}}
    <path
        @click="toggleZone('head_face')"
        :fill="selectedZones.includes('head_face') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('head_face') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M615,72 C593,72 581,84 579,100 C577,116 583,127 590,132 C596,136 605,138 615,138 C625,138 634,136 640,132 C647,127 653,116 651,100 C649,84 637,72 615,72 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('head_face') ? 1 : 0.92"
    />
    {{-- Back of head detail --}}
    <path d="M600,95 Q615,88 630,95" fill="none" stroke="#8a6e52" stroke-width="0.5" opacity="0.15" class="pointer-events-none" />
    <text class="derm-label" x="615" y="107">Head (Post.)</text>

    {{-- ===== BACK NECK ===== --}}
    <path
        @click="toggleZone('neck')"
        :fill="selectedZones.includes('neck') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('neck') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="0.8"
        d="M602,137 L628,137 L632,140 C632,148 630,155 628,158 L602,158 C600,155 598,148 598,140 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('neck') ? 1 : 0.92"
    />
    {{-- C7 vertebra prominence hint --}}
    <circle cx="615" cy="155" r="2" fill="none" stroke="#8a6e52" stroke-width="0.5" opacity="0.2" class="pointer-events-none" />
    <text class="derm-label" x="615" y="151">Neck</text>

    {{-- ===== UPPER BACK ===== --}}
    <path
        @click="toggleZone('upper_back')"
        :fill="selectedZones.includes('upper_back') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('upper_back') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M550,162 C565,157 590,155 615,155 C640,155 665,157 680,162 L685,170 L688,200 L690,230 L685,240 C660,245 640,246 615,246 C590,246 570,245 545,240 L540,230 L542,200 L545,170 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('upper_back') ? 1 : 0.92"
    />
    {{-- Scapula and spine contour --}}
    <g class="pointer-events-none" opacity="0.12">
        <path d="M570,175 Q560,195 565,220 L580,225 L585,195 Z" fill="none" stroke="#8a6e52" stroke-width="0.7" />
        <path d="M660,175 Q670,195 665,220 L650,225 L645,195 Z" fill="none" stroke="#8a6e52" stroke-width="0.7" />
        <line x1="615" y1="160" x2="615" y2="240" stroke="#8a6e52" stroke-width="0.5" />
        {{-- Vertebrae hints --}}
        <line x1="610" y1="170" x2="620" y2="170" stroke="#8a6e52" stroke-width="0.4" />
        <line x1="610" y1="182" x2="620" y2="182" stroke="#8a6e52" stroke-width="0.4" />
        <line x1="610" y1="194" x2="620" y2="194" stroke="#8a6e52" stroke-width="0.4" />
        <line x1="610" y1="206" x2="620" y2="206" stroke="#8a6e52" stroke-width="0.4" />
        <line x1="610" y1="218" x2="620" y2="218" stroke="#8a6e52" stroke-width="0.4" />
        <line x1="610" y1="230" x2="620" y2="230" stroke="#8a6e52" stroke-width="0.4" />
    </g>
    <text class="derm-label" x="615" y="202">Upper Back</text>

    {{-- ===== LOWER BACK ===== --}}
    <path
        @click="toggleZone('lower_back')"
        :fill="selectedZones.includes('lower_back') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('lower_back') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M545,242 C570,247 590,248 615,248 C640,248 660,247 685,242 L688,260 L690,300 L688,330 C678,338 650,342 615,342 C580,342 552,338 542,330 L540,300 L542,260 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('lower_back') ? 1 : 0.92"
    />
    {{-- Lumbar spine and dimples --}}
    <g class="pointer-events-none" opacity="0.12">
        <line x1="615" y1="248" x2="615" y2="335" stroke="#8a6e52" stroke-width="0.5" />
        <line x1="610" y1="260" x2="620" y2="260" stroke="#8a6e52" stroke-width="0.5" />
        <line x1="610" y1="275" x2="620" y2="275" stroke="#8a6e52" stroke-width="0.5" />
        <line x1="610" y1="290" x2="620" y2="290" stroke="#8a6e52" stroke-width="0.5" />
        <line x1="610" y1="305" x2="620" y2="305" stroke="#8a6e52" stroke-width="0.5" />
        <line x1="610" y1="320" x2="620" y2="320" stroke="#8a6e52" stroke-width="0.5" />
        <circle cx="600" cy="330" r="2" fill="none" stroke="#8a6e52" stroke-width="0.5" />
        <circle cx="630" cy="330" r="2" fill="none" stroke="#8a6e52" stroke-width="0.5" />
    </g>
    <text class="derm-label" x="615" y="292">Lower Back</text>

    {{-- ===== BACK RIGHT ARM ===== --}}
    <path
        @click="toggleZone('right_arm')"
        :fill="selectedZones.includes('right_arm') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_arm') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M548,162 L535,165 C528,170 522,180 518,195 L510,230 L507,260 L512,262 L522,260 L528,230 L535,200 L542,180 L545,170 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_arm') ? 1 : 0.92"
    />
    <text class="derm-label" x="522" y="215" transform="rotate(-10,522,215)">R. Arm</text>

    {{-- ===== BACK RIGHT FOREARM ===== --}}
    <path
        @click="toggleZone('right_forearm')"
        :fill="selectedZones.includes('right_forearm') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_forearm') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M507,262 L502,280 L492,320 L486,355 L484,370 L488,372 L502,370 L506,355 L512,320 L518,280 L522,262 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_forearm') ? 1 : 0.92"
    />
    <text class="derm-label" x="500" y="320" transform="rotate(-6,500,320)">R. Forearm</text>

    {{-- ===== BACK RIGHT HAND ===== --}}
    <path
        @click="toggleZone('right_hand')"
        :fill="selectedZones.includes('right_hand') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_hand') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M484,372 L480,380 L472,395 L468,408 L470,412 L476,408 L478,398 L482,392 L480,405 L478,418 L480,422 L486,418 L488,405 L490,392 L490,408 L489,420 L491,424 L497,420 L498,408 L498,395 L500,405 L500,418 L502,420 L507,416 L506,400 L504,388 L502,378 L502,372 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_hand') ? 1 : 0.92"
    />
    <text class="derm-label" x="482" y="385">R. Hand</text>

    {{-- ===== BACK LEFT ARM ===== --}}
    <path
        @click="toggleZone('left_arm')"
        :fill="selectedZones.includes('left_arm') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_arm') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M682,162 L695,165 C702,170 708,180 712,195 L720,230 L723,260 L718,262 L708,260 L702,230 L695,200 L688,180 L685,170 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_arm') ? 1 : 0.92"
    />
    <text class="derm-label" x="708" y="215" transform="rotate(10,708,215)">L. Arm</text>

    {{-- ===== BACK LEFT FOREARM ===== --}}
    <path
        @click="toggleZone('left_forearm')"
        :fill="selectedZones.includes('left_forearm') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_forearm') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M723,262 L728,280 L738,320 L744,355 L746,370 L742,372 L728,370 L724,355 L718,320 L712,280 L708,262 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_forearm') ? 1 : 0.92"
    />
    <text class="derm-label" x="730" y="320" transform="rotate(6,730,320)">L. Forearm</text>

    {{-- ===== BACK LEFT HAND ===== --}}
    <path
        @click="toggleZone('left_hand')"
        :fill="selectedZones.includes('left_hand') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_hand') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M746,372 L750,380 L758,395 L762,408 L760,412 L754,408 L752,398 L748,392 L750,405 L752,418 L750,422 L744,418 L742,405 L740,392 L740,408 L741,420 L739,424 L733,420 L732,408 L732,395 L730,405 L730,418 L728,420 L723,416 L724,400 L726,388 L728,378 L728,372 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_hand') ? 1 : 0.92"
    />
    <text class="derm-label" x="748" y="385">L. Hand</text>

    {{-- ===== BACK RIGHT THIGH ===== --}}
    <path
        @click="toggleZone('right_thigh')"
        :fill="selectedZones.includes('right_thigh') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_thigh') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M542,332 C552,340 570,344 588,344 L615,344 L615,348 L610,380 L605,420 L598,460 L592,490 L585,500 L565,500 L560,490 L558,460 L552,420 L545,380 L540,348 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_thigh') ? 1 : 0.92"
    />
    <text class="derm-label" x="582" y="425">R. Thigh</text>

    {{-- ===== BACK LEFT THIGH ===== --}}
    <path
        @click="toggleZone('left_thigh')"
        :fill="selectedZones.includes('left_thigh') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_thigh') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M688,332 C678,340 660,344 642,344 L615,344 L615,348 L620,380 L625,420 L632,460 L638,490 L645,500 L665,500 L670,490 L672,460 L678,420 L685,380 L690,348 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_thigh') ? 1 : 0.92"
    />
    <text class="derm-label" x="648" y="425">L. Thigh</text>

    {{-- ===== BACK RIGHT LEG ===== --}}
    <path
        @click="toggleZone('right_leg')"
        :fill="selectedZones.includes('right_leg') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_leg') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M565,502 L592,502 L595,520 L596,560 L595,600 L592,630 L590,645 L564,645 L562,630 L558,600 L556,560 L557,520 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_leg') ? 1 : 0.92"
    />
    {{-- Calf contour --}}
    <path d="M575,510 Q582,540 580,570 L578,620" class="derm-contour pointer-events-none" />
    <text class="derm-label" x="577" y="575">R. Leg</text>

    {{-- ===== BACK LEFT LEG ===== --}}
    <path
        @click="toggleZone('left_leg')"
        :fill="selectedZones.includes('left_leg') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_leg') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M638,502 L665,502 L668,520 L670,560 L668,600 L666,630 L664,645 L638,645 L636,630 L632,600 L630,560 L631,520 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_leg') ? 1 : 0.92"
    />
    <path d="M648,510 Q655,540 653,570 L651,620" class="derm-contour pointer-events-none" />
    <text class="derm-label" x="651" y="575">L. Leg</text>

    {{-- ===== BACK RIGHT FOOT ===== --}}
    <path
        @click="toggleZone('right_foot')"
        :fill="selectedZones.includes('right_foot') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('right_foot') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M564,647 L590,647 L592,655 L593,662 L588,668 L558,670 L552,666 L553,658 L558,650 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('right_foot') ? 1 : 0.92"
    />
    <text class="derm-label" x="573" y="662">R. Foot</text>

    {{-- ===== BACK LEFT FOOT ===== --}}
    <path
        @click="toggleZone('left_foot')"
        :fill="selectedZones.includes('left_foot') ? '#818cf8' : 'url(#skinBase)'"
        :filter="selectedZones.includes('left_foot') ? 'url(#selectGlow)' : 'url(#innerGlow)'"
        stroke="#c4996e" stroke-width="1"
        d="M638,647 L664,647 L670,650 L675,658 L676,666 L670,670 L640,668 L635,662 L636,655 Z"
        class="cursor-pointer derm-zone"
        :opacity="selectedZones.includes('left_foot') ? 1 : 0.92"
    />
    <text class="derm-label" x="656" y="662">L. Foot</text>

    {{-- Skin texture overlay for back --}}
    <rect x="465" y="60" width="300" height="620" fill="url(#skinTexture)" opacity="0.5" class="pointer-events-none" />

    {{-- Legend --}}
    <g transform="translate(340, 685)" class="pointer-events-none">
        <rect x="0" y="0" width="8" height="8" rx="2" fill="url(#skinBase)" stroke="#c4996e" stroke-width="0.5" />
        <text x="12" y="8" font-family="system-ui, sans-serif" font-size="7" fill="#9ca3af">Normal</text>
        <rect x="50" y="0" width="8" height="8" rx="2" fill="#818cf8" />
        <text x="62" y="8" font-family="system-ui, sans-serif" font-size="7" fill="#9ca3af">Selected</text>
    </g>
</svg>
