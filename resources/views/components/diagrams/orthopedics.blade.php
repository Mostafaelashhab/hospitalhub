{{-- Orthopedics Skeleton Diagram - Realistic Bone & Joint Assessment --}}
{{-- Requires parent Alpine.js scope with: selectedZones, toggleZone(zoneId), zoneNotes --}}

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 520 820" class="w-full h-auto max-w-lg mx-auto">
    <defs>
        {{-- Bone gradient - ivory/cream realistic bone color --}}
        <linearGradient id="boneGrad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#f5f0e8" />
            <stop offset="40%" stop-color="#ede5d5" />
            <stop offset="100%" stop-color="#ddd5c2" />
        </linearGradient>
        <linearGradient id="boneGradV" x1="0.5" y1="0" x2="0.5" y2="1">
            <stop offset="0%" stop-color="#f0ead8" />
            <stop offset="100%" stop-color="#d8ceb8" />
        </linearGradient>
        {{-- Joint gradient --}}
        <linearGradient id="jointGrad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#e8e0d0" />
            <stop offset="100%" stop-color="#cfc5b0" />
        </linearGradient>
        {{-- X-ray dark background gradient --}}
        <radialGradient id="xrayBg" cx="0.5" cy="0.45" r="0.55">
            <stop offset="0%" stop-color="#1a1e2e" />
            <stop offset="100%" stop-color="#0d1017" />
        </radialGradient>
        {{-- Bone glow for x-ray style --}}
        <filter id="boneGlow" x="-15%" y="-15%" width="130%" height="130%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="1.5" result="blur" />
            <feFlood flood-color="#c8d8e8" flood-opacity="0.15" result="color" />
            <feComposite in="color" in2="blur" operator="in" result="glow" />
            <feMerge>
                <feMergeNode in="glow" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>
        <filter id="selectedGlow" x="-20%" y="-20%" width="140%" height="140%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur" />
            <feFlood flood-color="#818cf8" flood-opacity="0.5" result="color" />
            <feComposite in="color" in2="blur" operator="in" result="glow" />
            <feMerge>
                <feMergeNode in="glow" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>
        {{-- Subtle bone texture --}}
        <pattern id="boneTexture" x="0" y="0" width="4" height="4" patternUnits="userSpaceOnUse">
            <rect width="4" height="4" fill="transparent" />
            <circle cx="1" cy="1" r="0.3" fill="#b8a890" opacity="0.08" />
            <circle cx="3" cy="3" r="0.2" fill="#b8a890" opacity="0.06" />
        </pattern>

        <style>
            .bone-label { font-family: system-ui, -apple-system, sans-serif; font-size: 8px; fill: #94a3b8; pointer-events: none; text-anchor: middle; font-weight: 500; letter-spacing: 0.03em; }
            .bone-label-light { font-family: system-ui, -apple-system, sans-serif; font-size: 7.5px; fill: #64748b; pointer-events: none; text-anchor: middle; }
            .orth-title { font-family: system-ui, -apple-system, sans-serif; font-size: 14px; font-weight: 700; fill: #cbd5e1; text-anchor: middle; letter-spacing: 0.04em; }
            .bone-zone { cursor: pointer; transition: all 0.25s cubic-bezier(0.4,0,0.2,1); transform-origin: center; }
            .bone-zone:hover { filter: brightness(1.2) drop-shadow(0 0 6px rgba(148,163,184,0.4)); transform: scale(1.015); }
            .bone-detail { fill: none; stroke: #8a9ab0; stroke-width: 0.4; opacity: 0.3; pointer-events: none; }
            .bone-outline { fill: none; stroke: #6b7d92; stroke-width: 0.6; opacity: 0.2; pointer-events: none; }
            .conn-bone { fill: none; stroke: #5a6878; stroke-width: 1.8; stroke-linecap: round; opacity: 0.25; pointer-events: none; }
        </style>
    </defs>

    {{-- Dark X-ray background --}}
    <rect width="520" height="820" fill="url(#xrayBg)" rx="8" />

    {{-- Title --}}
    <text class="orth-title" x="260" y="24">Orthopedic Assessment - Skeletal System</text>

    {{-- ==================== DECORATIVE BONE CONNECTIONS ==================== --}}
    <g class="pointer-events-none">
        {{-- Sternum --}}
        <path d="M260,170 L260,245" class="conn-bone" stroke-width="2.5" />
        {{-- Clavicles --}}
        <path d="M190,162 Q225,158 260,165" class="conn-bone" stroke-width="2" />
        <path d="M330,162 Q295,158 260,165" class="conn-bone" stroke-width="2" />

        {{-- Rib cage - left side --}}
        <path d="M248,172 Q220,176 198,170" class="conn-bone" stroke-width="1.2" />
        <path d="M248,182 Q218,188 196,180" class="conn-bone" stroke-width="1.2" />
        <path d="M248,192 Q216,200 194,190" class="conn-bone" stroke-width="1.2" />
        <path d="M248,202 Q218,210 196,200" class="conn-bone" stroke-width="1.2" />
        <path d="M248,212 Q220,218 200,212" class="conn-bone" stroke-width="1.2" />
        <path d="M250,222 Q225,230 210,225" class="conn-bone" stroke-width="1" />
        <path d="M252,232 Q232,240 218,235" class="conn-bone" stroke-width="0.8" />
        {{-- Rib cage - right side --}}
        <path d="M272,172 Q300,176 322,170" class="conn-bone" stroke-width="1.2" />
        <path d="M272,182 Q302,188 324,180" class="conn-bone" stroke-width="1.2" />
        <path d="M272,192 Q304,200 326,190" class="conn-bone" stroke-width="1.2" />
        <path d="M272,202 Q302,210 324,200" class="conn-bone" stroke-width="1.2" />
        <path d="M272,212 Q300,218 320,212" class="conn-bone" stroke-width="1.2" />
        <path d="M270,222 Q295,230 310,225" class="conn-bone" stroke-width="1" />
        <path d="M268,232 Q288,240 302,235" class="conn-bone" stroke-width="0.8" />

        {{-- Humerus bones --}}
        <path d="M180,178 Q172,220 162,270" class="conn-bone" stroke-width="2.2" />
        <path d="M340,178 Q348,220 358,270" class="conn-bone" stroke-width="2.2" />

        {{-- Radius/Ulna --}}
        <path d="M157,290 Q148,330 138,385" class="conn-bone" stroke-width="1.5" />
        <path d="M162,290 Q155,330 148,385" class="conn-bone" stroke-width="1.5" />
        <path d="M363,290 Q372,330 382,385" class="conn-bone" stroke-width="1.5" />
        <path d="M358,290 Q365,330 372,385" class="conn-bone" stroke-width="1.5" />

        {{-- Femur bones --}}
        <path d="M228,375 Q222,420 215,500" class="conn-bone" stroke-width="2.5" />
        <path d="M292,375 Q298,420 305,500" class="conn-bone" stroke-width="2.5" />

        {{-- Tibia/Fibula --}}
        <path d="M212,530 Q208,580 205,650" class="conn-bone" stroke-width="2" />
        <path d="M218,530 Q215,580 213,650" class="conn-bone" stroke-width="1.2" />
        <path d="M308,530 Q312,580 315,650" class="conn-bone" stroke-width="2" />
        <path d="M302,530 Q305,580 307,650" class="conn-bone" stroke-width="1.2" />
    </g>

    {{-- ==================== SKULL ==================== --}}
    <g @click="toggleZone('skull')" class="cursor-pointer bone-zone">
        {{-- Cranium --}}
        <path
            :fill="selectedZones.includes('skull') ? '#818cf8' : 'url(#boneGrad)'"
            :filter="selectedZones.includes('skull') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="1"
            d="M260,38 C230,38 210,52 207,72 C204,92 208,105 215,112 L218,118 Q220,125 225,130 L235,135 Q248,140 260,140 Q272,140 285,135 L295,130 Q300,125 302,118 L305,112 C312,105 316,92 313,72 C310,52 290,38 260,38 Z"
            :opacity="selectedZones.includes('skull') ? 1 : 0.9"
        />
        {{-- Skull details --}}
        <g class="pointer-events-none" opacity="0.2">
            {{-- Eye sockets --}}
            <ellipse cx="245" cy="92" rx="12" ry="9" fill="none" stroke="#6b7d92" stroke-width="0.8" />
            <ellipse cx="275" cy="92" rx="12" ry="9" fill="none" stroke="#6b7d92" stroke-width="0.8" />
            {{-- Nasal opening --}}
            <path d="M255,102 L258,115 L260,118 L262,115 L265,102" fill="none" stroke="#6b7d92" stroke-width="0.7" />
            {{-- Zygomatic arches --}}
            <path d="M232,95 Q220,100 215,108" fill="none" stroke="#6b7d92" stroke-width="0.5" />
            <path d="M288,95 Q300,100 305,108" fill="none" stroke="#6b7d92" stroke-width="0.5" />
            {{-- Jaw --}}
            <path d="M230,125 Q240,138 260,142 Q280,138 290,125" fill="none" stroke="#6b7d92" stroke-width="0.8" />
            {{-- Teeth line --}}
            <path d="M242,128 L278,128" fill="none" stroke="#6b7d92" stroke-width="0.4" />
            {{-- Coronal suture --}}
            <path d="M215,65 Q238,58 260,55 Q282,58 305,65" fill="none" stroke="#6b7d92" stroke-width="0.3" stroke-dasharray="2 2" />
            {{-- Temporal lines --}}
            <path d="M212,75 Q210,90 215,108" fill="none" stroke="#6b7d92" stroke-width="0.3" />
            <path d="M308,75 Q310,90 305,108" fill="none" stroke="#6b7d92" stroke-width="0.3" />
        </g>
        <text class="bone-label" x="260" y="77">Skull</text>
    </g>

    {{-- ==================== CERVICAL SPINE ==================== --}}
    <g @click="toggleZone('cervical_spine')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('cervical_spine') ? '#818cf8' : 'url(#boneGradV)'"
            :filter="selectedZones.includes('cervical_spine') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M250,142 L270,142 Q274,144 274,148 L274,160 Q274,163 270,164 L250,164 Q246,163 246,160 L246,148 Q246,144 250,142 Z"
            :opacity="selectedZones.includes('cervical_spine') ? 1 : 0.9"
        />
        {{-- Vertebrae segments C1-C7 --}}
        <g class="pointer-events-none" opacity="0.2">
            <line x1="249" y1="146" x2="271" y2="146" stroke="#6b7d92" stroke-width="0.4" />
            <line x1="249" y1="149" x2="271" y2="149" stroke="#6b7d92" stroke-width="0.4" />
            <line x1="249" y1="152" x2="271" y2="152" stroke="#6b7d92" stroke-width="0.4" />
            <line x1="249" y1="155" x2="271" y2="155" stroke="#6b7d92" stroke-width="0.4" />
            <line x1="249" y1="158" x2="271" y2="158" stroke="#6b7d92" stroke-width="0.4" />
            <line x1="249" y1="161" x2="271" y2="161" stroke="#6b7d92" stroke-width="0.4" />
            {{-- Spinous processes --}}
            <line x1="246" y1="148" x2="242" y2="148" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="246" y1="153" x2="242" y2="153" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="246" y1="158" x2="241" y2="158" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="274" y1="148" x2="278" y2="148" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="274" y1="153" x2="278" y2="153" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="274" y1="158" x2="279" y2="158" stroke="#6b7d92" stroke-width="0.5" />
        </g>
        <text class="bone-label" x="260" y="156">C-Spine</text>
    </g>

    {{-- ==================== THORACIC SPINE ==================== --}}
    <g @click="toggleZone('thoracic_spine')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('thoracic_spine') ? '#818cf8' : 'url(#boneGradV)'"
            :filter="selectedZones.includes('thoracic_spine') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M248,166 L272,166 Q276,168 276,172 L278,238 Q278,242 274,243 L246,243 Q242,242 242,238 L244,172 Q244,168 248,166 Z"
            :opacity="selectedZones.includes('thoracic_spine') ? 1 : 0.9"
        />
        {{-- T1-T12 vertebrae hints --}}
        <g class="pointer-events-none" opacity="0.2">
            @for($i = 0; $i < 12; $i++)
                <line x1="247" y1="{{ 171 + $i * 6 }}" x2="273" y2="{{ 171 + $i * 6 }}" stroke="#6b7d92" stroke-width="0.4" />
                <line x1="244" y1="{{ 173 + $i * 6 }}" x2="239" y2="{{ 173 + $i * 6 }}" stroke="#6b7d92" stroke-width="0.4" />
                <line x1="276" y1="{{ 173 + $i * 6 }}" x2="281" y2="{{ 173 + $i * 6 }}" stroke="#6b7d92" stroke-width="0.4" />
            @endfor
        </g>
        <text class="bone-label" x="260" y="210">T-Spine</text>
    </g>

    {{-- ==================== LUMBAR SPINE ==================== --}}
    <g @click="toggleZone('lumbar_spine')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('lumbar_spine') ? '#818cf8' : 'url(#boneGradV)'"
            :filter="selectedZones.includes('lumbar_spine') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M245,245 L275,245 Q280,247 280,252 L282,302 Q282,306 278,308 L242,308 Q238,306 238,302 L240,252 Q240,247 245,245 Z"
            :opacity="selectedZones.includes('lumbar_spine') ? 1 : 0.9"
        />
        {{-- L1-L5 vertebrae --}}
        <g class="pointer-events-none" opacity="0.2">
            @for($i = 0; $i < 5; $i++)
                <line x1="244" y1="{{ 253 + $i * 11 }}" x2="276" y2="{{ 253 + $i * 11 }}" stroke="#6b7d92" stroke-width="0.5" />
                <line x1="240" y1="{{ 256 + $i * 11 }}" x2="234" y2="{{ 256 + $i * 11 }}" stroke="#6b7d92" stroke-width="0.5" />
                <line x1="280" y1="{{ 256 + $i * 11 }}" x2="286" y2="{{ 256 + $i * 11 }}" stroke="#6b7d92" stroke-width="0.5" />
            @endfor
        </g>
        <text class="bone-label" x="260" y="280">L-Spine</text>
    </g>

    {{-- ==================== PELVIS ==================== --}}
    <g @click="toggleZone('pelvis')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('pelvis') ? '#818cf8' : 'url(#boneGrad)'"
            :filter="selectedZones.includes('pelvis') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="1"
            d="M200,310 Q208,305 230,308 L260,310 L290,308 Q312,305 320,310 L328,325 Q332,340 325,355 L315,368 Q305,375 295,372 L285,365 Q272,378 260,380 Q248,378 235,365 L225,372 Q215,375 205,368 L195,355 Q188,340 192,325 Z"
            :opacity="selectedZones.includes('pelvis') ? 1 : 0.9"
        />
        {{-- Pelvis detail: iliac crests, sacral foramina, obturator foramen --}}
        <g class="pointer-events-none" opacity="0.2">
            {{-- Sacrum --}}
            <path d="M250,310 L270,310 L268,335 Q260,345 252,335 Z" fill="none" stroke="#6b7d92" stroke-width="0.6" />
            {{-- Iliac crests --}}
            <path d="M200,312 Q215,308 230,312" fill="none" stroke="#6b7d92" stroke-width="0.6" />
            <path d="M290,312 Q305,308 320,312" fill="none" stroke="#6b7d92" stroke-width="0.6" />
            {{-- Obturator foramina --}}
            <ellipse cx="232" cy="355" rx="12" ry="10" fill="none" stroke="#6b7d92" stroke-width="0.5" />
            <ellipse cx="288" cy="355" rx="12" ry="10" fill="none" stroke="#6b7d92" stroke-width="0.5" />
            {{-- Pubic symphysis --}}
            <line x1="255" y1="370" x2="265" y2="370" stroke="#6b7d92" stroke-width="0.5" />
        </g>
        <text class="bone-label" x="260" y="342">Pelvis</text>
    </g>

    {{-- ==================== RIGHT SHOULDER ==================== --}}
    <g @click="toggleZone('right_shoulder')" class="cursor-pointer bone-zone">
        {{-- Scapula hint + glenohumeral joint --}}
        <path
            :fill="selectedZones.includes('right_shoulder') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('right_shoulder') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M188,148 Q178,148 172,155 Q168,162 170,172 Q172,180 180,182 Q186,184 192,180 Q198,175 198,166 Q198,155 192,148 Z"
            :opacity="selectedZones.includes('right_shoulder') ? 1 : 0.9"
        />
        {{-- Acromion hint --}}
        <path d="M185,150 Q178,148 175,152" fill="none" stroke="#6b7d92" stroke-width="0.4" opacity="0.25" class="pointer-events-none" />
        <text class="bone-label" x="182" y="168">R.Shld</text>
    </g>

    {{-- ==================== LEFT SHOULDER ==================== --}}
    <g @click="toggleZone('left_shoulder')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('left_shoulder') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('left_shoulder') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M332,148 Q342,148 348,155 Q352,162 350,172 Q348,180 340,182 Q334,184 328,180 Q322,175 322,166 Q322,155 328,148 Z"
            :opacity="selectedZones.includes('left_shoulder') ? 1 : 0.9"
        />
        <path d="M335,150 Q342,148 345,152" fill="none" stroke="#6b7d92" stroke-width="0.4" opacity="0.25" class="pointer-events-none" />
        <text class="bone-label" x="338" y="168">L.Shld</text>
    </g>

    {{-- ==================== RIGHT ELBOW ==================== --}}
    <g @click="toggleZone('right_elbow')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('right_elbow') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('right_elbow') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M155,265 Q148,265 145,272 Q142,280 145,288 Q148,294 156,294 Q164,294 168,288 Q172,280 168,272 Q165,265 158,265 Z"
            :opacity="selectedZones.includes('right_elbow') ? 1 : 0.9"
        />
        {{-- Olecranon hint --}}
        <path d="M150,268 L148,263" fill="none" stroke="#6b7d92" stroke-width="0.4" opacity="0.3" class="pointer-events-none" />
        <text class="bone-label" x="156" y="282">R.Elb</text>
    </g>

    {{-- ==================== LEFT ELBOW ==================== --}}
    <g @click="toggleZone('left_elbow')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('left_elbow') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('left_elbow') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M355,265 Q348,265 345,272 Q342,280 345,288 Q348,294 356,294 Q364,294 368,288 Q372,280 368,272 Q365,265 358,265 Z"
            :opacity="selectedZones.includes('left_elbow') ? 1 : 0.9"
        />
        <path d="M362,268 L364,263" fill="none" stroke="#6b7d92" stroke-width="0.4" opacity="0.3" class="pointer-events-none" />
        <text class="bone-label" x="356" y="282">L.Elb</text>
    </g>

    {{-- ==================== RIGHT WRIST ==================== --}}
    <g @click="toggleZone('right_wrist')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('right_wrist') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('right_wrist') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M130,382 Q125,382 122,387 Q120,392 122,398 L126,402 Q130,405 136,405 Q142,405 146,402 L150,398 Q152,392 150,387 Q147,382 142,382 Z"
            :opacity="selectedZones.includes('right_wrist') ? 1 : 0.9"
        />
        {{-- Carpal bones hint --}}
        <g class="pointer-events-none" opacity="0.15">
            <circle cx="130" cy="392" r="3" fill="none" stroke="#6b7d92" stroke-width="0.4" />
            <circle cx="136" cy="390" r="3" fill="none" stroke="#6b7d92" stroke-width="0.4" />
            <circle cx="142" cy="392" r="3" fill="none" stroke="#6b7d92" stroke-width="0.4" />
            <circle cx="133" cy="398" r="2.5" fill="none" stroke="#6b7d92" stroke-width="0.4" />
            <circle cx="139" cy="398" r="2.5" fill="none" stroke="#6b7d92" stroke-width="0.4" />
        </g>
        <text class="bone-label" x="136" y="395">R.Wrst</text>
    </g>

    {{-- ==================== LEFT WRIST ==================== --}}
    <g @click="toggleZone('left_wrist')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('left_wrist') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('left_wrist') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M370,382 Q365,382 362,387 Q360,392 362,398 L366,402 Q370,405 376,405 Q382,405 386,402 L390,398 Q392,392 390,387 Q387,382 382,382 Z"
            :opacity="selectedZones.includes('left_wrist') ? 1 : 0.9"
        />
        <g class="pointer-events-none" opacity="0.15">
            <circle cx="370" cy="392" r="3" fill="none" stroke="#6b7d92" stroke-width="0.4" />
            <circle cx="376" cy="390" r="3" fill="none" stroke="#6b7d92" stroke-width="0.4" />
            <circle cx="382" cy="392" r="3" fill="none" stroke="#6b7d92" stroke-width="0.4" />
            <circle cx="373" cy="398" r="2.5" fill="none" stroke="#6b7d92" stroke-width="0.4" />
            <circle cx="379" cy="398" r="2.5" fill="none" stroke="#6b7d92" stroke-width="0.4" />
        </g>
        <text class="bone-label" x="376" y="395">L.Wrst</text>
    </g>

    {{-- ==================== RIGHT HAND ==================== --}}
    <g @click="toggleZone('right_hand')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('right_hand') ? '#818cf8' : 'url(#boneGrad)'"
            :filter="selectedZones.includes('right_hand') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M122,407 L148,407 L150,415 L152,428 L150,438 L146,440 L144,430 L142,438 L138,442 L135,432 L133,440 L129,442 L126,432 L123,438 L119,440 L115,432 L112,420 L114,412 Z"
            :opacity="selectedZones.includes('right_hand') ? 1 : 0.9"
        />
        {{-- Metacarpal/phalanx hints --}}
        <g class="pointer-events-none" opacity="0.15">
            <line x1="125" y1="410" x2="120" y2="432" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="130" y1="410" x2="128" y2="435" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="135" y1="410" x2="136" y2="435" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="140" y1="410" x2="143" y2="432" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="146" y1="410" x2="150" y2="430" stroke="#6b7d92" stroke-width="0.5" />
        </g>
        <text class="bone-label" x="134" y="424">R.Hand</text>
    </g>

    {{-- ==================== LEFT HAND ==================== --}}
    <g @click="toggleZone('left_hand')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('left_hand') ? '#818cf8' : 'url(#boneGrad)'"
            :filter="selectedZones.includes('left_hand') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M362,407 L388,407 L396,412 L398,420 L395,432 L391,440 L387,438 L384,432 L381,442 L377,440 L375,432 L372,442 L368,438 L366,430 L364,440 L360,438 L358,428 L360,415 Z"
            :opacity="selectedZones.includes('left_hand') ? 1 : 0.9"
        />
        <g class="pointer-events-none" opacity="0.15">
            <line x1="370" y1="410" x2="368" y2="432" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="375" y1="410" x2="374" y2="435" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="380" y1="410" x2="380" y2="435" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="385" y1="410" x2="386" y2="432" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="390" y1="412" x2="393" y2="430" stroke="#6b7d92" stroke-width="0.5" />
        </g>
        <text class="bone-label" x="378" y="424">L.Hand</text>
    </g>

    {{-- ==================== RIGHT HIP ==================== --}}
    <g @click="toggleZone('right_hip')" class="cursor-pointer bone-zone">
        <circle
            :fill="selectedZones.includes('right_hip') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('right_hip') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            cx="228" cy="372" r="16"
            :opacity="selectedZones.includes('right_hip') ? 1 : 0.9"
        />
        {{-- Femoral head in acetabulum --}}
        <circle cx="228" cy="372" r="8" fill="none" stroke="#6b7d92" stroke-width="0.5" opacity="0.2" class="pointer-events-none" />
        <text class="bone-label" x="228" y="375">R.Hip</text>
    </g>

    {{-- ==================== LEFT HIP ==================== --}}
    <g @click="toggleZone('left_hip')" class="cursor-pointer bone-zone">
        <circle
            :fill="selectedZones.includes('left_hip') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('left_hip') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            cx="292" cy="372" r="16"
            :opacity="selectedZones.includes('left_hip') ? 1 : 0.9"
        />
        <circle cx="292" cy="372" r="8" fill="none" stroke="#6b7d92" stroke-width="0.5" opacity="0.2" class="pointer-events-none" />
        <text class="bone-label" x="292" y="375">L.Hip</text>
    </g>

    {{-- ==================== RIGHT KNEE ==================== --}}
    <g @click="toggleZone('right_knee')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('right_knee') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('right_knee') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M198,498 Q194,498 192,504 Q190,512 192,520 Q194,526 200,528 L220,528 Q226,526 228,520 Q230,512 228,504 Q226,498 222,498 Z"
            :opacity="selectedZones.includes('right_knee') ? 1 : 0.9"
        />
        {{-- Patella --}}
        <ellipse cx="210" cy="510" rx="8" ry="9" fill="none" stroke="#6b7d92" stroke-width="0.5" opacity="0.25" class="pointer-events-none" />
        {{-- Condyle hints --}}
        <path d="M200,520 Q210,525 220,520" fill="none" stroke="#6b7d92" stroke-width="0.3" opacity="0.2" class="pointer-events-none" />
        <text class="bone-label" x="210" y="516">R.Knee</text>
    </g>

    {{-- ==================== LEFT KNEE ==================== --}}
    <g @click="toggleZone('left_knee')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('left_knee') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('left_knee') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M298,498 Q294,498 292,504 Q290,512 292,520 Q294,526 300,528 L320,528 Q326,526 328,520 Q330,512 328,504 Q326,498 322,498 Z"
            :opacity="selectedZones.includes('left_knee') ? 1 : 0.9"
        />
        <ellipse cx="310" cy="510" rx="8" ry="9" fill="none" stroke="#6b7d92" stroke-width="0.5" opacity="0.25" class="pointer-events-none" />
        <path d="M300,520 Q310,525 320,520" fill="none" stroke="#6b7d92" stroke-width="0.3" opacity="0.2" class="pointer-events-none" />
        <text class="bone-label" x="310" y="516">L.Knee</text>
    </g>

    {{-- ==================== RIGHT ANKLE ==================== --}}
    <g @click="toggleZone('right_ankle')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('right_ankle') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('right_ankle') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M196,644 Q190,644 188,650 Q186,658 188,664 Q192,670 200,670 L218,670 Q224,668 226,662 Q228,656 226,650 Q222,644 218,644 Z"
            :opacity="selectedZones.includes('right_ankle') ? 1 : 0.9"
        />
        {{-- Malleoli hints --}}
        <circle cx="194" cy="652" r="3" fill="none" stroke="#6b7d92" stroke-width="0.4" opacity="0.2" class="pointer-events-none" />
        <circle cx="220" cy="652" r="3" fill="none" stroke="#6b7d92" stroke-width="0.4" opacity="0.2" class="pointer-events-none" />
        <text class="bone-label" x="207" y="660">R.Ankle</text>
    </g>

    {{-- ==================== LEFT ANKLE ==================== --}}
    <g @click="toggleZone('left_ankle')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('left_ankle') ? '#818cf8' : 'url(#jointGrad)'"
            :filter="selectedZones.includes('left_ankle') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M296,644 Q290,644 288,650 Q286,658 288,664 Q292,670 300,670 L318,670 Q324,668 326,662 Q328,656 326,650 Q322,644 318,644 Z"
            :opacity="selectedZones.includes('left_ankle') ? 1 : 0.9"
        />
        <circle cx="294" cy="652" r="3" fill="none" stroke="#6b7d92" stroke-width="0.4" opacity="0.2" class="pointer-events-none" />
        <circle cx="320" cy="652" r="3" fill="none" stroke="#6b7d92" stroke-width="0.4" opacity="0.2" class="pointer-events-none" />
        <text class="bone-label" x="307" y="660">L.Ankle</text>
    </g>

    {{-- ==================== RIGHT FOOT ==================== --}}
    <g @click="toggleZone('right_foot')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('right_foot') ? '#818cf8' : 'url(#boneGrad)'"
            :filter="selectedZones.includes('right_foot') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M190,672 L224,672 L226,680 L228,690 L224,698 L220,702 L212,705 L204,706 L196,705 L185,702 L178,696 L176,690 L178,682 L184,675 Z"
            :opacity="selectedZones.includes('right_foot') ? 1 : 0.9"
        />
        {{-- Tarsal/metatarsal hints --}}
        <g class="pointer-events-none" opacity="0.15">
            <line x1="195" y1="680" x2="190" y2="700" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="200" y1="678" x2="198" y2="702" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="206" y1="676" x2="206" y2="704" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="212" y1="676" x2="214" y2="702" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="218" y1="678" x2="220" y2="698" stroke="#6b7d92" stroke-width="0.5" />
            {{-- Calcaneus --}}
            <path d="M188,678 Q185,685 186,692" fill="none" stroke="#6b7d92" stroke-width="0.5" />
        </g>
        <text class="bone-label" x="202" y="693">R.Foot</text>
    </g>

    {{-- ==================== LEFT FOOT ==================== --}}
    <g @click="toggleZone('left_foot')" class="cursor-pointer bone-zone">
        <path
            :fill="selectedZones.includes('left_foot') ? '#818cf8' : 'url(#boneGrad)'"
            :filter="selectedZones.includes('left_foot') ? 'url(#selectedGlow)' : 'url(#boneGlow)'"
            stroke="#8a9ab0" stroke-width="0.8"
            d="M290,672 L324,672 L330,675 L336,682 L338,690 L336,696 L332,702 L324,705 L316,706 L308,705 L300,702 L296,698 L292,690 L290,680 Z"
            :opacity="selectedZones.includes('left_foot') ? 1 : 0.9"
        />
        <g class="pointer-events-none" opacity="0.15">
            <line x1="298" y1="678" x2="296" y2="698" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="304" y1="676" x2="302" y2="702" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="310" y1="676" x2="310" y2="704" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="316" y1="676" x2="318" y2="702" stroke="#6b7d92" stroke-width="0.5" />
            <line x1="322" y1="678" x2="326" y2="698" stroke="#6b7d92" stroke-width="0.5" />
            <path d="M328,678 Q332,685 330,692" fill="none" stroke="#6b7d92" stroke-width="0.5" />
        </g>
        <text class="bone-label" x="314" y="693">L.Foot</text>
    </g>

    {{-- Bone texture overlay --}}
    <rect width="520" height="820" fill="url(#boneTexture)" opacity="0.3" class="pointer-events-none" rx="8" />

    {{-- Legend --}}
    <g transform="translate(180, 790)" class="pointer-events-none">
        <rect x="0" y="0" width="10" height="10" rx="2" fill="url(#boneGrad)" stroke="#8a9ab0" stroke-width="0.5" />
        <text x="14" y="9" font-family="system-ui, sans-serif" font-size="8" fill="#64748b">Normal</text>
        <rect x="60" y="0" width="10" height="10" rx="2" fill="#818cf8" />
        <text x="74" y="9" font-family="system-ui, sans-serif" font-size="8" fill="#64748b">Selected</text>
        <circle cx="140" cy="5" r="4" fill="url(#jointGrad)" stroke="#8a9ab0" stroke-width="0.5" />
        <text x="148" y="9" font-family="system-ui, sans-serif" font-size="8" fill="#64748b">Joint</text>
    </g>
</svg>
