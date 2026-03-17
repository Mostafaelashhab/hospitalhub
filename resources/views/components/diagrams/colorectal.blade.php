{{-- Colorectal Surgery — Lower GI Anatomical Diagram --}}
{{-- Expects parent Alpine.js scope with: selectedZones, toggleZone(zoneId), zoneNotes --}}

<svg viewBox="0 0 500 650" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto max-w-lg mx-auto">
    <defs>
        <style>
            .cr-zone { cursor: pointer; transition: fill 0.25s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease, stroke 0.25s ease, transform 0.25s ease, filter 0.25s ease; transform-origin: center; }
            .cr-zone:hover { opacity: 0.88; filter: url(#cr-hover-glow); transform: scale(1.015); }
            .cr-label { font-family: system-ui, -apple-system, sans-serif; font-size: 10px; fill: #374151; pointer-events: none; font-weight: 500; }
            .cr-label-line { stroke: #9CA3AF; stroke-width: 0.75; fill: none; stroke-dasharray: 2,2; pointer-events: none; }
            .cr-detail { pointer-events: none; }
        </style>

        {{-- Colon wall gradient --}}
        <radialGradient id="cr-colon-grad" cx="50%" cy="40%" r="60%">
            <stop offset="0%" stop-color="#E8B0A0"/>
            <stop offset="50%" stop-color="#D09080"/>
            <stop offset="100%" stop-color="#B87060"/>
        </radialGradient>

        {{-- Cecum gradient --}}
        <radialGradient id="cr-cecum-grad" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#D8A898"/>
            <stop offset="50%" stop-color="#C89080"/>
            <stop offset="100%" stop-color="#B07868"/>
        </radialGradient>

        {{-- Appendix gradient --}}
        <radialGradient id="cr-appendix-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#E0A090"/>
            <stop offset="100%" stop-color="#C07060"/>
        </radialGradient>

        {{-- Rectum gradient --}}
        <radialGradient id="cr-rectum-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#D8A0A0"/>
            <stop offset="50%" stop-color="#C08080"/>
            <stop offset="100%" stop-color="#A06868"/>
        </radialGradient>

        {{-- Anal canal gradient --}}
        <radialGradient id="cr-anal-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#C89898"/>
            <stop offset="50%" stop-color="#A87878"/>
            <stop offset="100%" stop-color="#906060"/>
        </radialGradient>

        {{-- Sigmoid gradient --}}
        <radialGradient id="cr-sigmoid-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#E0B8A8"/>
            <stop offset="50%" stop-color="#C89888"/>
            <stop offset="100%" stop-color="#B08070"/>
        </radialGradient>

        {{-- Hemorrhoid zone gradient --}}
        <radialGradient id="cr-hemorrhoid-grad" cx="50%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#D06060"/>
            <stop offset="50%" stop-color="#B04848"/>
            <stop offset="100%" stop-color="#903838"/>
        </radialGradient>

        {{-- Perianal gradient --}}
        <radialGradient id="cr-perianal-grad" cx="50%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#E8C8B8"/>
            <stop offset="100%" stop-color="#C8A898"/>
        </radialGradient>

        {{-- Shadow filter --}}
        <filter id="cr-shadow" x="-5%" y="-5%" width="115%" height="115%">
            <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#00000020"/>
        </filter>

        {{-- Hover glow --}}
        <filter id="cr-hover-glow" x="-10%" y="-10%" width="120%" height="120%">
            <feDropShadow dx="0" dy="0" stdDeviation="4" flood-color="#818cf860"/>
        </filter>
    </defs>

    {{-- ==================== BACKGROUND ANATOMY ==================== --}}

    {{-- Spine hint --}}
    <g class="cr-detail" opacity="0.1">
        <rect x="240" y="30" width="18" height="420" rx="4" fill="#D8D0C0" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="236" y1="70" x2="262" y2="70" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="236" y1="100" x2="262" y2="100" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="236" y1="130" x2="262" y2="130" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="236" y1="160" x2="262" y2="160" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="236" y1="190" x2="262" y2="190" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="236" y1="220" x2="262" y2="220" stroke="#B8A890" stroke-width="0.5"/>
        <line x1="236" y1="250" x2="262" y2="250" stroke="#B8A890" stroke-width="0.5"/>
    </g>

    {{-- Pelvic bone hint --}}
    <g class="cr-detail" opacity="0.08">
        <path d="M150 380 Q160 350 200 330 Q230 320 250 325 Q270 320 300 330 Q340 350 350 380 Q355 420 340 450 Q320 470 290 475 L210 475 Q180 470 160 450 Q145 420 150 380 Z"
              fill="none" stroke="#B8A890" stroke-width="2"/>
    </g>

    {{-- ==================== ASCENDING COLON ==================== --}}
    <path
        d="M365 310 Q370 280 372 250 Q374 220 372 190 Q370 160 365 135 Q360 120 350 110 Q340 105 330 108"
        class="cr-zone"
        :stroke="selectedZones.includes('ascending_colon') ? '#818CF8' : '#B87060'"
        :fill="selectedZones.includes('ascending_colon') ? '#818CF820' : 'none'"
        stroke-width="22" stroke-linecap="round" stroke-linejoin="round"
        @click="toggleZone('ascending_colon')"
        filter="url(#cr-shadow)"
    />
    {{-- Haustra markings --}}
    <g class="cr-detail" opacity="0.2">
        <line x1="355" y1="145" x2="380" y2="145" stroke="#805040" stroke-width="0.8"/>
        <line x1="355" y1="175" x2="382" y2="175" stroke="#805040" stroke-width="0.8"/>
        <line x1="355" y1="205" x2="383" y2="205" stroke="#805040" stroke-width="0.8"/>
        <line x1="355" y1="235" x2="382" y2="235" stroke="#805040" stroke-width="0.8"/>
        <line x1="355" y1="265" x2="380" y2="265" stroke="#805040" stroke-width="0.8"/>
        <line x1="355" y1="295" x2="378" y2="295" stroke="#805040" stroke-width="0.8"/>
    </g>
    <line x1="385" y1="220" x2="440" y2="220" class="cr-label-line"/>
    <text x="442" y="217" class="cr-label">Ascending</text>
    <text x="442" y="229" class="cr-label">Colon</text>

    {{-- ==================== TRANSVERSE COLON ==================== --}}
    <path
        d="M330 108 Q300 90 250 85 Q200 82 170 90 Q145 100 135 115"
        class="cr-zone"
        :stroke="selectedZones.includes('transverse_colon') ? '#818CF8' : '#B87060'"
        :fill="selectedZones.includes('transverse_colon') ? '#818CF820' : 'none'"
        stroke-width="22" stroke-linecap="round" stroke-linejoin="round"
        @click="toggleZone('transverse_colon')"
        filter="url(#cr-shadow)"
    />
    {{-- Haustra markings --}}
    <g class="cr-detail" opacity="0.2">
        <line x1="170" y1="82" x2="170" y2="105" stroke="#805040" stroke-width="0.8"/>
        <line x1="200" y1="78" x2="200" y2="100" stroke="#805040" stroke-width="0.8"/>
        <line x1="230" y1="76" x2="230" y2="98" stroke="#805040" stroke-width="0.8"/>
        <line x1="260" y1="77" x2="260" y2="99" stroke="#805040" stroke-width="0.8"/>
        <line x1="290" y1="80" x2="290" y2="102" stroke="#805040" stroke-width="0.8"/>
    </g>
    <line x1="250" y1="72" x2="250" y2="45" class="cr-label-line"/>
    <text x="250" y="40" class="cr-label" text-anchor="middle">Transverse Colon</text>

    {{-- ==================== DESCENDING COLON ==================== --}}
    <path
        d="M135 115 Q130 135 128 160 Q126 190 128 220 Q130 250 132 280 Q134 300 136 320"
        class="cr-zone"
        :stroke="selectedZones.includes('descending_colon') ? '#818CF8' : '#B87060'"
        :fill="selectedZones.includes('descending_colon') ? '#818CF820' : 'none'"
        stroke-width="22" stroke-linecap="round" stroke-linejoin="round"
        @click="toggleZone('descending_colon')"
        filter="url(#cr-shadow)"
    />
    {{-- Haustra markings --}}
    <g class="cr-detail" opacity="0.2">
        <line x1="118" y1="145" x2="143" y2="145" stroke="#805040" stroke-width="0.8"/>
        <line x1="116" y1="175" x2="142" y2="175" stroke="#805040" stroke-width="0.8"/>
        <line x1="116" y1="205" x2="142" y2="205" stroke="#805040" stroke-width="0.8"/>
        <line x1="117" y1="235" x2="143" y2="235" stroke="#805040" stroke-width="0.8"/>
        <line x1="118" y1="265" x2="144" y2="265" stroke="#805040" stroke-width="0.8"/>
        <line x1="120" y1="295" x2="146" y2="295" stroke="#805040" stroke-width="0.8"/>
    </g>
    <line x1="115" y1="220" x2="55" y2="220" class="cr-label-line"/>
    <text x="10" y="217" class="cr-label">Descending</text>
    <text x="10" y="229" class="cr-label">Colon</text>

    {{-- ==================== HEPATIC FLEXURE ==================== --}}
    <path
        d="M350 110 Q365 100 370 108 Q375 118 372 130"
        class="cr-zone"
        :stroke="selectedZones.includes('hepatic_flexure') ? '#818CF8' : '#B08070'"
        :fill="selectedZones.includes('hepatic_flexure') ? '#818CF820' : 'none'"
        stroke-width="20" stroke-linecap="round"
        @click="toggleZone('hepatic_flexure')"
    />
    <line x1="385" y1="110" x2="430" y2="100" class="cr-label-line"/>
    <text x="432" y="97" class="cr-label">Hepatic</text>
    <text x="432" y="109" class="cr-label">Flexure</text>

    {{-- ==================== SPLENIC FLEXURE ==================== --}}
    <path
        d="M145 100 Q130 95 128 105 Q125 118 128 135"
        class="cr-zone"
        :stroke="selectedZones.includes('splenic_flexure') ? '#818CF8' : '#B08070'"
        :fill="selectedZones.includes('splenic_flexure') ? '#818CF820' : 'none'"
        stroke-width="20" stroke-linecap="round"
        @click="toggleZone('splenic_flexure')"
    />
    <line x1="115" y1="100" x2="55" y2="90" class="cr-label-line"/>
    <text x="10" y="87" class="cr-label">Splenic</text>
    <text x="10" y="99" class="cr-label">Flexure</text>

    {{-- ==================== CECUM ==================== --}}
    <path
        d="M350 315 Q355 335 365 350 Q375 365 378 380 Q380 400 370 415 Q355 425 340 420 Q325 415 320 400 Q315 385 320 365 Q325 345 335 325 Q340 318 350 315 Z"
        class="cr-zone"
        :fill="selectedZones.includes('cecum') ? '#818CF8' : 'url(#cr-cecum-grad)'"
        stroke="#A07060" stroke-width="1.5"
        @click="toggleZone('cecum')"
        filter="url(#cr-shadow)"
    />
    {{-- Ileocecal valve hint --}}
    <g class="cr-detail" opacity="0.25">
        <path d="M340 330 Q348 338 352 345" fill="none" stroke="#805040" stroke-width="1.5"/>
        <circle cx="345" cy="337" r="3" fill="#C09080" stroke="#805040" stroke-width="0.5"/>
    </g>
    <line x1="380" y1="380" x2="430" y2="380" class="cr-label-line"/>
    <text x="432" y="377" class="cr-label">Cecum</text>

    {{-- ==================== APPENDIX ==================== --}}
    <path
        d="M345 420 Q340 435 330 445 Q318 455 310 462 Q300 468 295 470"
        class="cr-zone"
        :stroke="selectedZones.includes('appendix') ? '#818CF8' : '#C07060'"
        stroke-width="7" fill="none" stroke-linecap="round"
        @click="toggleZone('appendix')"
    />
    <line x1="295" y1="470" x2="330" y2="490" class="cr-label-line"/>
    <text x="332" y="494" class="cr-label">Appendix</text>

    {{-- ==================== SIGMOID COLON ==================== --}}
    <path
        d="M136 320 Q140 345 155 365 Q175 385 200 395 Q225 400 240 390 Q255 378 258 360 Q260 345 255 335 Q248 325 240 330 Q232 340 235 358 Q240 378 250 400 Q258 418 255 438"
        class="cr-zone"
        :stroke="selectedZones.includes('sigmoid_colon') ? '#818CF8' : '#B08070'"
        :fill="selectedZones.includes('sigmoid_colon') ? '#818CF820' : 'none'"
        stroke-width="18" stroke-linecap="round" stroke-linejoin="round"
        @click="toggleZone('sigmoid_colon')"
        filter="url(#cr-shadow)"
    />
    {{-- Haustra markings --}}
    <g class="cr-detail" opacity="0.15">
        <line x1="148" y1="350" x2="165" y2="340" stroke="#805040" stroke-width="0.8"/>
        <line x1="180" y1="380" x2="195" y2="368" stroke="#805040" stroke-width="0.8"/>
        <line x1="218" y1="393" x2="228" y2="380" stroke="#805040" stroke-width="0.8"/>
    </g>
    <line x1="145" y1="365" x2="55" y2="365" class="cr-label-line"/>
    <text x="10" y="362" class="cr-label">Sigmoid</text>
    <text x="10" y="374" class="cr-label">Colon</text>

    {{-- ==================== RECTUM ==================== --}}
    <path
        d="M245 438 Q242 455 240 475 Q238 495 240 510 Q242 525 248 540 Q252 548 255 555"
        class="cr-zone"
        :stroke="selectedZones.includes('rectum') ? '#818CF8' : '#A06868'"
        :fill="selectedZones.includes('rectum') ? '#818CF820' : 'none'"
        stroke-width="24" stroke-linecap="round"
        @click="toggleZone('rectum')"
        filter="url(#cr-shadow)"
    />
    {{-- Rectal valves (Houston's valves) --}}
    <g class="cr-detail" opacity="0.2">
        <path d="M232 465 Q245 460 258 465" fill="none" stroke="#805050" stroke-width="1"/>
        <path d="M230 490 Q245 485 260 490" fill="none" stroke="#805050" stroke-width="1"/>
        <path d="M232 515 Q248 510 262 515" fill="none" stroke="#805050" stroke-width="1"/>
    </g>
    <line x1="268" y1="490" x2="340" y2="490" class="cr-label-line"/>
    <text x="345" y="487" class="cr-label">Rectum</text>

    {{-- ==================== ANAL CANAL ==================== --}}
    <path
        d="M248 555 Q246 565 245 575 Q244 585 245 595"
        class="cr-zone"
        :stroke="selectedZones.includes('anal_canal') ? '#818CF8' : '#906060'"
        :fill="selectedZones.includes('anal_canal') ? '#818CF820' : 'none'"
        stroke-width="20" stroke-linecap="round"
        @click="toggleZone('anal_canal')"
        filter="url(#cr-shadow)"
    />
    {{-- Dentate line --}}
    <g class="cr-detail" opacity="0.3">
        <line x1="234" y1="578" x2="258" y2="578" stroke="#704040" stroke-width="1" stroke-dasharray="2,2"/>
    </g>
    {{-- Internal sphincter --}}
    <g class="cr-detail" opacity="0.15">
        <path d="M234 560 Q230 575 234 590" fill="none" stroke="#705050" stroke-width="2"/>
        <path d="M258 560 Q262 575 258 590" fill="none" stroke="#705050" stroke-width="2"/>
    </g>
    <line x1="268" y1="575" x2="340" y2="575" class="cr-label-line"/>
    <text x="345" y="572" class="cr-label">Anal Canal</text>

    {{-- ==================== HEMORRHOID ZONE (INTERNAL) ==================== --}}
    <g @click="toggleZone('internal_hemorrhoids')" class="cr-zone" style="cursor:pointer">
        <circle
            cx="232" cy="562"
            r="5"
            :fill="selectedZones.includes('internal_hemorrhoids') ? '#818CF8' : '#D06060'"
            stroke="#903838" stroke-width="1"
        />
        <circle
            cx="260" cy="562"
            r="5"
            :fill="selectedZones.includes('internal_hemorrhoids') ? '#818CF8' : '#D06060'"
            stroke="#903838" stroke-width="1"
        />
        <circle
            cx="246" cy="555"
            r="4.5"
            :fill="selectedZones.includes('internal_hemorrhoids') ? '#818CF8' : '#D06060'"
            stroke="#903838" stroke-width="1"
        />
    </g>
    <line x1="225" y1="558" x2="155" y2="545" class="cr-label-line"/>
    <text x="95" y="540" class="cr-label">Internal</text>
    <text x="95" y="552" class="cr-label">Hemorrhoids</text>

    {{-- ==================== HEMORRHOID ZONE (EXTERNAL) ==================== --}}
    <g @click="toggleZone('external_hemorrhoids')" class="cr-zone" style="cursor:pointer">
        <circle
            cx="230" cy="598"
            r="5.5"
            :fill="selectedZones.includes('external_hemorrhoids') ? '#818CF8' : '#B04848'"
            stroke="#803030" stroke-width="1"
        />
        <circle
            cx="262" cy="598"
            r="5.5"
            :fill="selectedZones.includes('external_hemorrhoids') ? '#818CF8' : '#B04848'"
            stroke="#803030" stroke-width="1"
        />
        <circle
            cx="246" cy="604"
            r="5"
            :fill="selectedZones.includes('external_hemorrhoids') ? '#818CF8' : '#B04848'"
            stroke="#803030" stroke-width="1"
        />
    </g>
    <line x1="225" y1="600" x2="155" y2="610" class="cr-label-line"/>
    <text x="80" y="607" class="cr-label">External</text>
    <text x="80" y="619" class="cr-label">Hemorrhoids</text>

    {{-- ==================== PERIANAL AREA ==================== --}}
    <ellipse
        cx="246" cy="600"
        rx="30" ry="18"
        class="cr-zone"
        :fill="selectedZones.includes('perianal') ? '#818CF820' : '#E8C8B815'"
        :stroke="selectedZones.includes('perianal') ? '#818CF8' : '#C8A898'"
        stroke-width="1.5" stroke-dasharray="4,3"
        @click="toggleZone('perianal')"
    />
    <line x1="278" y1="600" x2="340" y2="610" class="cr-label-line"/>
    <text x="345" y="607" class="cr-label">Perianal</text>
    <text x="345" y="619" class="cr-label">Area</text>

    {{-- ==================== PILONIDAL REGION ==================== --}}
    <ellipse
        cx="248" cy="430"
        rx="15" ry="10"
        class="cr-zone"
        :fill="selectedZones.includes('pilonidal') ? '#818CF8' : '#D8C0B0'"
        :stroke="selectedZones.includes('pilonidal') ? '#818CF8' : '#A89080'"
        stroke-width="1.2"
        @click="toggleZone('pilonidal')"
        opacity="0.6"
    />
    <line x1="265" y1="428" x2="340" y2="420" class="cr-label-line"/>
    <text x="345" y="417" class="cr-label">Pilonidal</text>
    <text x="345" y="429" class="cr-label">Region</text>

    {{-- Title --}}
    <text x="250" y="645" font-family="system-ui, -apple-system, sans-serif" font-size="13" font-weight="600" fill="#1F2937" text-anchor="middle">
        Colorectal Surgery — Lower GI System
    </text>
</svg>
