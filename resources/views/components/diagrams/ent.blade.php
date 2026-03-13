{{-- ENT (Ear, Nose, Throat) Anatomical Diagram - Sagittal Cross-Section --}}
{{-- Expects parent Alpine.js scope with: selectedZones, toggleZone(zoneId), zoneNotes --}}

<svg viewBox="0 0 680 560" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto max-w-2xl mx-auto">
    <defs>
        {{-- Tissue/mucosal gradients --}}
        <linearGradient id="skinTone" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#f2d5b8" />
            <stop offset="100%" stop-color="#e0ba98" />
        </linearGradient>
        <linearGradient id="mucosaGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#f4a0a0" />
            <stop offset="100%" stop-color="#d88080" />
        </linearGradient>
        <linearGradient id="cartilageGrad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#e8ddd0" />
            <stop offset="100%" stop-color="#c8b8a0" />
        </linearGradient>
        <linearGradient id="boneGradEnt" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#f0e8d8" />
            <stop offset="100%" stop-color="#d0c4b0" />
        </linearGradient>
        <linearGradient id="sinusGrad" x1="0.5" y1="0" x2="0.5" y2="1">
            <stop offset="0%" stop-color="#b8d4f0" />
            <stop offset="100%" stop-color="#90b8e0" />
        </linearGradient>
        <linearGradient id="airwayGrad" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#1a1a2e" />
            <stop offset="50%" stop-color="#16213e" />
            <stop offset="100%" stop-color="#1a1a2e" />
        </linearGradient>
        <radialGradient id="cochleaGrad" cx="0.5" cy="0.5" r="0.5">
            <stop offset="0%" stop-color="#f0d8c0" />
            <stop offset="100%" stop-color="#c8a888" />
        </radialGradient>
        <linearGradient id="earCanalGrad" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#e8c8a8" />
            <stop offset="100%" stop-color="#2a1a10" />
        </linearGradient>
        <linearGradient id="eustachianGrad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#f0c0c0" />
            <stop offset="100%" stop-color="#d8a0a0" />
        </linearGradient>

        {{-- Filters --}}
        <filter id="tissueShadow" x="-5%" y="-5%" width="110%" height="110%">
            <feDropShadow dx="1" dy="1" stdDeviation="2" flood-color="#8a6050" flood-opacity="0.15" />
        </filter>
        <filter id="entSelectGlow" x="-15%" y="-15%" width="130%" height="130%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur" />
            <feFlood flood-color="#818cf8" flood-opacity="0.4" result="color" />
            <feComposite in="color" in2="blur" operator="in" result="glow" />
            <feMerge>
                <feMergeNode in="glow" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>
        <filter id="organGlow" x="-10%" y="-10%" width="120%" height="120%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="1.5" result="blur" />
            <feFlood flood-color="#a08070" flood-opacity="0.1" result="color" />
            <feComposite in="color" in2="blur" operator="in" result="glow" />
            <feMerge>
                <feMergeNode in="glow" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>

        {{-- Tissue texture --}}
        <pattern id="tissueTexture" x="0" y="0" width="5" height="5" patternUnits="userSpaceOnUse">
            <rect width="5" height="5" fill="transparent" />
            <circle cx="1.5" cy="2" r="0.3" fill="#c09080" opacity="0.06" />
            <circle cx="4" cy="4" r="0.2" fill="#c09080" opacity="0.04" />
        </pattern>

        <style>
            .ent-zone { cursor: pointer; transition: all 0.2s ease; }
            .ent-zone:hover { filter: brightness(1.08); }
            .ent-label { font-family: system-ui, -apple-system, sans-serif; font-size: 9px; fill: #374151; pointer-events: none; font-weight: 500; }
            .ent-label-sm { font-family: system-ui, -apple-system, sans-serif; font-size: 7.5px; fill: #6b7280; pointer-events: none; }
            .ent-title { font-family: system-ui, -apple-system, sans-serif; font-size: 15px; font-weight: 700; fill: #1f2937; text-anchor: middle; }
            .ent-detail { fill: none; stroke: #a08878; stroke-width: 0.4; opacity: 0.3; pointer-events: none; }
            .ent-leader { stroke: #9ca3af; stroke-width: 0.6; fill: none; pointer-events: none; }
            .ent-leader-dot { fill: #9ca3af; pointer-events: none; }
        </style>
    </defs>

    {{-- Background --}}
    <rect width="680" height="560" fill="#faf8f5" rx="8" />

    {{-- Title --}}
    <text class="ent-title" x="340" y="26">ENT - Ear, Nose &amp; Throat Anatomy</text>
    <text x="340" y="40" font-family="system-ui, sans-serif" font-size="9" fill="#9ca3af" text-anchor="middle">Sagittal cross-section view</text>

    {{-- ==================== HEAD PROFILE OUTLINE ==================== --}}
    {{-- Realistic sagittal head profile (cut-away) --}}
    <g class="pointer-events-none">
        {{-- Outer skin/skull outline --}}
        <path d="M180,60 C130,60 95,95 85,140 C78,175 80,200 82,220
                 Q84,240 90,260 L95,275 Q100,290 108,300
                 L115,310 Q125,322 135,328 L140,332
                 Q145,340 150,350 L155,370 Q158,385 158,400
                 L158,420 Q156,440 152,455 L148,470
                 Q145,480 145,490 L148,500
                 C210,510 260,510 320,500
                 L322,490 Q322,480 319,470
                 L315,455 Q311,440 310,420
                 L310,400 Q310,385 312,370
                 L318,332 Q325,322 330,310
                 L335,298 Q340,285 342,270
                 Q348,245 348,220
                 Q350,190 345,160
                 C338,110 310,75 270,62
                 Q250,56 230,56 Q205,56 180,60 Z"
              fill="url(#skinTone)" stroke="#c4996e" stroke-width="1.2" filter="url(#tissueShadow)" />

        {{-- Inner skull bone outline (cross-section) --}}
        <path d="M190,72 C145,78 110,108 102,148
                 C96,178 98,205 100,225
                 Q102,248 110,268 L118,288
                 Q125,305 135,318
                 L140,325 Q142,330 142,340
                 L144,355 Q148,375 148,395
                 L148,410 Q146,430 142,445
                 L280,445 Q276,430 275,410
                 L275,395 Q275,375 278,355
                 L280,340 Q280,330 282,325
                 L288,318 Q298,305 305,288
                 L312,268 Q320,248 322,225
                 Q325,205 323,178
                 C318,138 298,98 260,78
                 Q240,68 220,66 Q205,65 190,72 Z"
              fill="none" stroke="#c4a880" stroke-width="0.8" opacity="0.35" />

        {{-- Brain area hint (above nasal/sinus) --}}
        <path d="M195,82 Q210,76 240,76 Q268,76 285,82 Q305,95 312,120 Q316,145 312,170
                 L308,190 Q300,210 288,220
                 L135,220 Q120,210 112,190
                 L108,170 Q105,145 110,120 Q118,95 140,82 Z"
              fill="#f0e0d0" opacity="0.15" stroke="#c4a880" stroke-width="0.3" />
        <path d="M160,100 Q180,92 210,90 Q240,90 260,100" fill="none" stroke="#c4a880" stroke-width="0.3" opacity="0.2" />
        <path d="M140,120 Q180,108 220,106 Q260,108 280,120" fill="none" stroke="#c4a880" stroke-width="0.3" opacity="0.15" />

        {{-- Hair --}}
        <path d="M175,62 C130,62 92,98 84,145 L88,140 C95,95 132,64 178,60 Q210,54 245,56 Q280,58 305,72 C330,88 345,115 348,155 L350,150 C348,110 330,82 302,66 Q275,54 245,52 Q210,50 175,62 Z"
              fill="#6b5540" opacity="0.3" />

        {{-- Eye socket area (reference) --}}
        <ellipse cx="155" cy="195" rx="20" ry="12" fill="#f0e0d0" stroke="#c4a880" stroke-width="0.5" opacity="0.3" />
    </g>

    {{-- ==================== AIRWAY PASSAGE (background) ==================== --}}
    <g class="pointer-events-none" opacity="0.4">
        <path d="M195,225 L195,250 Q195,265 200,280 L205,310 Q208,330 210,350 L212,380 Q214,400 215,420 L218,445"
              fill="none" stroke="#1a1a2e" stroke-width="12" stroke-linecap="round" opacity="0.15" />
    </g>

    {{-- ==================== FRONTAL SINUS ==================== --}}
    <g @click="toggleZone('sinuses')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('sinuses') ? '#818cf8' : 'url(#sinusGrad)'"
            :filter="selectedZones.includes('sinuses') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#7090b0" stroke-width="0.8"
            d="M145,145 Q150,130 165,125 Q180,122 195,128 Q200,132 198,145 Q196,155 190,162 L160,165 Q148,162 145,155 Z"
            :opacity="selectedZones.includes('sinuses') ? 1 : 0.8"
        />
        {{-- Sinus cavity lines --}}
        <g class="pointer-events-none" opacity="0.2">
            <path d="M155,138 Q170,133 185,138" fill="none" stroke="#5080a0" stroke-width="0.4" />
            <path d="M152,148 Q170,143 190,148" fill="none" stroke="#5080a0" stroke-width="0.4" />
        </g>
    </g>
    {{-- Frontal sinus label --}}
    <line x1="170" y1="135" x2="82" y2="105" class="ent-leader" />
    <circle cx="82" cy="105" r="1.5" class="ent-leader-dot" />
    <text x="78" y="101" class="ent-label" text-anchor="end">Frontal Sinus</text>

    {{-- ==================== ETHMOID SINUS ==================== --}}
    <g @click="toggleZone('sinuses')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('sinuses') ? '#818cf8' : 'url(#sinusGrad)'"
            :filter="selectedZones.includes('sinuses') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#7090b0" stroke-width="0.6"
            d="M170,168 Q178,165 188,168 Q192,172 190,180 Q186,188 178,190 Q170,188 167,182 Q165,175 170,168 Z"
            :opacity="selectedZones.includes('sinuses') ? 0.9 : 0.7"
        />
    </g>
    <line x1="178" y1="178" x2="82" y2="155" class="ent-leader" />
    <circle cx="82" cy="155" r="1.5" class="ent-leader-dot" />
    <text x="78" y="151" class="ent-label" text-anchor="end">Ethmoid Sinus</text>

    {{-- ==================== SPHENOID SINUS ==================== --}}
    <g @click="toggleZone('sinuses')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('sinuses') ? '#818cf8' : 'url(#sinusGrad)'"
            :filter="selectedZones.includes('sinuses') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#7090b0" stroke-width="0.6"
            d="M215,175 Q225,170 238,175 Q245,182 242,192 Q238,200 228,202 Q218,200 213,192 Q210,182 215,175 Z"
            :opacity="selectedZones.includes('sinuses') ? 0.9 : 0.7"
        />
        {{-- Sella turcica hint --}}
        <path d="M220,175 Q228,168 238,175" fill="none" stroke="#5080a0" stroke-width="0.3" opacity="0.2" class="pointer-events-none" />
    </g>
    <line x1="228" y1="188" x2="350" y2="118" class="ent-leader" />
    <circle cx="350" cy="118" r="1.5" class="ent-leader-dot" />
    <text x="354" y="115" class="ent-label">Sphenoid Sinus</text>

    {{-- ==================== MAXILLARY SINUS ==================== --}}
    <g @click="toggleZone('sinuses')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('sinuses') ? '#818cf8' : 'url(#sinusGrad)'"
            :filter="selectedZones.includes('sinuses') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#7090b0" stroke-width="0.6"
            d="M130,225 Q138,215 152,218 Q160,222 162,235 Q160,248 150,252 Q138,250 132,242 Q128,235 130,225 Z"
            :opacity="selectedZones.includes('sinuses') ? 0.9 : 0.7"
        />
    </g>
    <line x1="145" y1="235" x2="82" y2="235" class="ent-leader" />
    <circle cx="82" cy="235" r="1.5" class="ent-leader-dot" />
    <text x="78" y="231" class="ent-label" text-anchor="end">Maxillary Sinus</text>

    {{-- ==================== NASAL CAVITY ==================== --}}
    <g @click="toggleZone('nasal_cavity')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('nasal_cavity') ? '#818cf8' : '#f5c8b0'"
            :filter="selectedZones.includes('nasal_cavity') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#c09080" stroke-width="0.8"
            d="M158,168 Q165,165 172,168 L175,180 L178,195 L180,215 L182,240 L178,260 Q172,270 165,272 L155,270 Q148,265 146,255 L148,235 L150,215 L152,195 L155,180 Z"
            :opacity="selectedZones.includes('nasal_cavity') ? 1 : 0.85"
        />
        {{-- Turbinate structures inside nasal cavity --}}
        <g class="pointer-events-none" opacity="0.3">
            {{-- Superior turbinate --}}
            <path d="M155,195 Q165,190 172,195 Q175,200 170,205 Q162,208 155,205 Z" fill="#e0a090" stroke="#c09080" stroke-width="0.4" />
            {{-- Middle turbinate --}}
            <path d="M152,215 Q164,208 175,215 Q178,222 172,228 Q162,232 152,228 Z" fill="#e0a090" stroke="#c09080" stroke-width="0.4" />
            {{-- Inferior turbinate --}}
            <path d="M150,240 Q163,232 178,240 Q180,248 174,254 Q162,258 150,254 Z" fill="#e0a090" stroke="#c09080" stroke-width="0.4" />
        </g>
    </g>
    <line x1="165" y1="220" x2="82" y2="190" class="ent-leader" />
    <circle cx="82" cy="190" r="1.5" class="ent-leader-dot" />
    <text x="78" y="186" class="ent-label" text-anchor="end">Nasal Cavity</text>

    {{-- ==================== SEPTUM ==================== --}}
    <g @click="toggleZone('nasal_cavity')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('nasal_cavity') ? '#818cf8' : 'url(#cartilageGrad)'"
            stroke="#b0a090" stroke-width="0.6"
            d="M160,168 L162,270 L155,272 L153,168 Z"
            :opacity="selectedZones.includes('nasal_cavity') ? 0.8 : 0.6"
        />
    </g>
    {{-- Septum label with leader --}}
    <line x1="157" y1="250" x2="82" y2="268" class="ent-leader" />
    <circle cx="82" cy="268" r="1.5" class="ent-leader-dot" />
    <text x="78" y="264" class="ent-label" text-anchor="end">Septum</text>

    {{-- Turbinates label --}}
    <line x1="172" y1="222" x2="82" y2="215" class="ent-leader" />
    <circle cx="82" cy="215" r="1.5" class="ent-leader-dot" />
    <text x="78" y="211" class="ent-label-sm" text-anchor="end">Turbinates</text>

    {{-- ==================== NOSE TIP (external) ==================== --}}
    <g class="pointer-events-none">
        <path d="M145,255 Q135,268 128,285 Q125,295 130,300 L138,302 Q142,298 145,290 Q148,278 150,268"
              fill="url(#skinTone)" stroke="#c4996e" stroke-width="0.8" />
        {{-- Nostril --}}
        <ellipse cx="138" cy="296" rx="6" ry="4" fill="#8a6050" opacity="0.3" />
    </g>

    {{-- ==================== ADENOIDS ==================== --}}
    <g @click="toggleZone('adenoids')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('adenoids') ? '#818cf8' : '#dbb8d0'"
            :filter="selectedZones.includes('adenoids') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#b090a0" stroke-width="0.8"
            d="M200,230 Q210,222 222,228 Q228,238 224,248 Q218,255 208,255 Q198,252 195,242 Q194,234 200,230 Z"
            :opacity="selectedZones.includes('adenoids') ? 1 : 0.8"
        />
        {{-- Lumpy texture --}}
        <g class="pointer-events-none" opacity="0.15">
            <circle cx="208" cy="238" r="4" fill="none" stroke="#906878" stroke-width="0.5" />
            <circle cx="215" cy="244" r="3" fill="none" stroke="#906878" stroke-width="0.5" />
        </g>
    </g>
    <line x1="210" y1="240" x2="350" y2="148" class="ent-leader" />
    <circle cx="350" cy="148" r="1.5" class="ent-leader-dot" />
    <text x="354" y="145" class="ent-label">Adenoids</text>

    {{-- ==================== SOFT PALATE / UVULA ==================== --}}
    <g class="pointer-events-none">
        <path d="M155,278 Q170,275 190,278 Q200,282 205,290 L200,302 Q195,310 188,312 Q182,310 180,305"
              fill="#e8a8a0" stroke="#c08878" stroke-width="0.5" opacity="0.5" />
        {{-- Hard palate --}}
        <path d="M140,275 L155,278 Q148,272 140,270 Z" fill="url(#boneGradEnt)" stroke="#c4a880" stroke-width="0.3" opacity="0.4" />
    </g>

    {{-- ==================== TONSILS ==================== --}}
    <g @click="toggleZone('tonsils')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('tonsils') ? '#818cf8' : '#f0b0b0'"
            :filter="selectedZones.includes('tonsils') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#c08878" stroke-width="0.8"
            d="M192,295 Q200,288 210,292 Q215,300 212,312 Q208,320 200,322 Q192,320 188,312 Q186,302 192,295 Z"
            :opacity="selectedZones.includes('tonsils') ? 1 : 0.85"
        />
        {{-- Tonsillar crypts --}}
        <g class="pointer-events-none" opacity="0.15">
            <circle cx="200" cy="304" r="2" fill="none" stroke="#905050" stroke-width="0.5" />
            <circle cx="204" cy="310" r="1.5" fill="none" stroke="#905050" stroke-width="0.5" />
            <circle cx="196" cy="312" r="1.5" fill="none" stroke="#905050" stroke-width="0.5" />
        </g>
    </g>
    <line x1="200" y1="308" x2="350" y2="178" class="ent-leader" />
    <circle cx="350" cy="178" r="1.5" class="ent-leader-dot" />
    <text x="354" y="175" class="ent-label">Palatine Tonsils</text>

    {{-- ==================== PHARYNX ==================== --}}
    <g @click="toggleZone('pharynx')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('pharynx') ? '#818cf8' : '#f8d098'"
            :filter="selectedZones.includes('pharynx') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#c0a070" stroke-width="0.8"
            d="M195,260 Q205,255 218,260 L222,275 L225,300 L228,325 Q228,340 225,350 L218,358 Q210,362 202,358 L195,350 Q192,340 192,325 L195,300 L195,275 Z"
            :opacity="selectedZones.includes('pharynx') ? 1 : 0.8"
        />
        {{-- Pharyngeal wall folds --}}
        <g class="pointer-events-none" opacity="0.12">
            <path d="M198,280 Q210,276 220,280" fill="none" stroke="#a08060" stroke-width="0.5" />
            <path d="M197,300 Q210,296 222,300" fill="none" stroke="#a08060" stroke-width="0.5" />
            <path d="M196,320 Q210,316 224,320" fill="none" stroke="#a08060" stroke-width="0.5" />
            <path d="M198,340 Q210,336 222,340" fill="none" stroke="#a08060" stroke-width="0.5" />
        </g>
    </g>
    <line x1="210" y1="310" x2="350" y2="258" class="ent-leader" />
    <circle cx="350" cy="258" r="1.5" class="ent-leader-dot" />
    <text x="354" y="255" class="ent-label">Pharynx</text>

    {{-- Nasopharynx / Oropharynx / Hypopharynx sub-labels --}}
    <text x="354" y="268" class="ent-label-sm" fill="#9ca3af">(Naso / Oro / Hypo)</text>

    {{-- ==================== EPIGLOTTIS ==================== --}}
    <g @click="toggleZone('epiglottis')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('epiglottis') ? '#818cf8' : '#f0c8a0'"
            :filter="selectedZones.includes('epiglottis') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#c0a078" stroke-width="0.8"
            d="M200,358 Q205,348 212,350 Q218,352 220,358 Q218,372 210,380 Q202,372 200,358 Z"
            :opacity="selectedZones.includes('epiglottis') ? 1 : 0.85"
        />
    </g>
    <line x1="210" y1="368" x2="350" y2="290" class="ent-leader" />
    <circle cx="350" cy="290" r="1.5" class="ent-leader-dot" />
    <text x="354" y="287" class="ent-label">Epiglottis</text>

    {{-- ==================== VOCAL CORDS ==================== --}}
    <g @click="toggleZone('vocal_cords')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('vocal_cords') ? '#818cf8' : '#f0d0c0'"
            :filter="selectedZones.includes('vocal_cords') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#c0a090" stroke-width="0.6"
            d="M202,395 Q207,390 210,390 Q213,390 218,395 L220,400 Q218,405 210,408 Q202,405 200,400 Z"
            :opacity="selectedZones.includes('vocal_cords') ? 1 : 0.85"
        />
        {{-- Vocal fold lines --}}
        <line x1="204" y1="397" x2="216" y2="397" stroke="#a08070" stroke-width="0.4" opacity="0.3" class="pointer-events-none" />
        <line x1="205" y1="401" x2="215" y2="401" stroke="#a08070" stroke-width="0.4" opacity="0.3" class="pointer-events-none" />
    </g>
    <line x1="210" y1="400" x2="350" y2="345" class="ent-leader" />
    <circle cx="350" cy="345" r="1.5" class="ent-leader-dot" />
    <text x="354" y="342" class="ent-label">Vocal Cords</text>

    {{-- ==================== LARYNX ==================== --}}
    <g @click="toggleZone('larynx')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('larynx') ? '#818cf8' : '#c8e8c0'"
            :filter="selectedZones.includes('larynx') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#80a878" stroke-width="0.8"
            d="M195,380 Q198,370 210,368 Q222,370 225,380 L228,400 Q230,415 228,430 L222,445 Q215,450 210,450 Q205,450 198,445 L192,430 Q190,415 192,400 Z"
            :opacity="selectedZones.includes('larynx') ? 1 : 0.8"
        />
        {{-- Thyroid cartilage (Adam's apple) hint --}}
        <path d="M197,382 L210,375 L223,382" fill="none" stroke="#60885c" stroke-width="0.5" opacity="0.3" class="pointer-events-none" />
        {{-- Cricoid cartilage --}}
        <path d="M198,430 Q210,436 222,430" fill="none" stroke="#60885c" stroke-width="0.5" opacity="0.3" class="pointer-events-none" />
        {{-- Tracheal ring hints --}}
        <g class="pointer-events-none" opacity="0.15">
            <path d="M196,440 Q210,445 224,440" fill="none" stroke="#60885c" stroke-width="0.5" />
            <path d="M198,448 Q210,452 222,448" fill="none" stroke="#60885c" stroke-width="0.5" />
        </g>
    </g>
    <line x1="210" y1="420" x2="350" y2="385" class="ent-leader" />
    <circle cx="350" cy="385" r="1.5" class="ent-leader-dot" />
    <text x="354" y="382" class="ent-label">Larynx</text>
    <text x="354" y="394" class="ent-label-sm" fill="#9ca3af">(Thyroid &amp; Cricoid cartilage)</text>

    {{-- Trachea continuation (decorative) --}}
    <g class="pointer-events-none" opacity="0.4">
        <path d="M198,450 L198,500 Q198,510 210,510 Q222,510 222,500 L222,450" fill="none" stroke="#80a878" stroke-width="1" />
        <line x1="200" y1="460" x2="220" y2="460" stroke="#80a878" stroke-width="0.5" />
        <line x1="200" y1="470" x2="220" y2="470" stroke="#80a878" stroke-width="0.5" />
        <line x1="200" y1="480" x2="220" y2="480" stroke="#80a878" stroke-width="0.5" />
        <line x1="200" y1="490" x2="220" y2="490" stroke="#80a878" stroke-width="0.5" />
    </g>

    {{-- Esophagus (behind trachea, decorative) --}}
    <g class="pointer-events-none" opacity="0.25">
        <path d="M224,450 Q228,460 228,475 L228,510" fill="none" stroke="#c08878" stroke-width="2" />
    </g>

    {{-- ==================== EAR SECTION ==================== --}}
    {{-- The ear is positioned to the right/posterior of the head profile --}}

    {{-- ==================== OUTER EAR (PINNA) ==================== --}}
    <g @click="toggleZone('outer_ear')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('outer_ear') ? '#818cf8' : '#f0c8b0'"
            :filter="selectedZones.includes('outer_ear') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#c0a088" stroke-width="1"
            d="M340,185 Q335,170 340,155 Q348,140 360,138 Q375,136 385,148 Q392,160 390,178 Q388,195 380,210 Q372,222 362,228 Q352,230 345,225 Q338,218 338,208 Q340,198 345,195 Q348,200 350,195 Q345,190 340,185 Z"
            :opacity="selectedZones.includes('outer_ear') ? 1 : 0.9"
        />
        {{-- Pinna detail: helix, antihelix, tragus, lobule --}}
        <g class="pointer-events-none" opacity="0.2">
            {{-- Helix --}}
            <path d="M342,180 Q338,165 342,152 Q350,140 362,140 Q378,140 386,155 Q392,168 388,185 Q384,200 376,215 Q368,225 358,228" fill="none" stroke="#906858" stroke-width="0.8" />
            {{-- Antihelix --}}
            <path d="M348,190 Q346,175 350,162 Q358,150 368,152 Q376,156 378,168 Q378,182 372,198 Q366,210 358,218" fill="none" stroke="#906858" stroke-width="0.6" />
            {{-- Tragus --}}
            <path d="M342,192 Q338,195 340,200 Q342,204 346,202" fill="none" stroke="#906858" stroke-width="0.6" />
            {{-- Lobule --}}
            <path d="M352,225 Q348,232 352,238 Q358,240 362,235 Q362,228 358,225" fill="none" stroke="#906858" stroke-width="0.5" />
        </g>
    </g>
    {{-- Outer ear label --}}
    <line x1="365" y1="180" x2="440" y2="145" class="ent-leader" />
    <circle cx="440" cy="145" r="1.5" class="ent-leader-dot" />
    <text x="444" y="142" class="ent-label">Outer Ear (Pinna)</text>

    {{-- ==================== EAR CANAL ==================== --}}
    <g @click="toggleZone('ear_canal')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('ear_canal') ? '#818cf8' : 'url(#earCanalGrad)'"
            :filter="selectedZones.includes('ear_canal') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#a08870" stroke-width="0.8"
            d="M335,195 L308,205 Q300,208 295,212 L292,218 Q290,222 292,225 L295,228 Q300,232 308,235 L335,240 L338,220 Z"
            :opacity="selectedZones.includes('ear_canal') ? 1 : 0.8"
        />
        {{-- Canal wall texture --}}
        <g class="pointer-events-none" opacity="0.15">
            <path d="M330,200 Q318,208 308,212" fill="none" stroke="#806040" stroke-width="0.4" />
            <path d="M330,235 Q318,232 308,228" fill="none" stroke="#806040" stroke-width="0.4" />
        </g>
    </g>
    <line x1="315" y1="215" x2="440" y2="175" class="ent-leader" />
    <circle cx="440" cy="175" r="1.5" class="ent-leader-dot" />
    <text x="444" y="172" class="ent-label">Ear Canal</text>

    {{-- ==================== TYMPANIC MEMBRANE (EARDRUM) ==================== --}}
    <g @click="toggleZone('tympanic_membrane')" class="cursor-pointer ent-zone">
        <ellipse
            :fill="selectedZones.includes('tympanic_membrane') ? '#818cf8' : '#e8d8c8'"
            :filter="selectedZones.includes('tympanic_membrane') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#a09080" stroke-width="0.8"
            cx="290" cy="220" rx="3" ry="14"
            :opacity="selectedZones.includes('tympanic_membrane') ? 1 : 0.85"
            transform="rotate(-5, 290, 220)"
        />
        {{-- Light reflex cone --}}
        <path d="M289,218 L287,228 L291,225 Z" fill="#f8f0e0" opacity="0.3" class="pointer-events-none" />
    </g>
    <line x1="290" y1="220" x2="440" y2="205" class="ent-leader" />
    <circle cx="440" cy="205" r="1.5" class="ent-leader-dot" />
    <text x="444" y="202" class="ent-label">Tympanic Membrane</text>
    <text x="444" y="213" class="ent-label-sm">(Eardrum)</text>

    {{-- ==================== MIDDLE EAR (OSSICLES) ==================== --}}
    <g @click="toggleZone('middle_ear')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('middle_ear') ? '#818cf8' : '#f0e0c8'"
            :filter="selectedZones.includes('middle_ear') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#b0a088" stroke-width="0.8"
            d="M268,208 Q275,205 285,208 L288,212 L288,228 L285,232 Q275,235 268,232 L265,228 L265,212 Z"
            :opacity="selectedZones.includes('middle_ear') ? 1 : 0.85"
        />
        {{-- Ossicles: malleus, incus, stapes --}}
        <g class="pointer-events-none" opacity="0.3">
            {{-- Malleus (hammer) --}}
            <line x1="284" y1="212" x2="280" y2="225" stroke="#907858" stroke-width="1" stroke-linecap="round" />
            {{-- Incus (anvil) --}}
            <path d="M280,214 L275,212 L273,218 L278,220" fill="none" stroke="#907858" stroke-width="0.8" />
            {{-- Stapes (stirrup) --}}
            <path d="M272,218 Q268,216 268,220 Q268,224 272,222" fill="none" stroke="#907858" stroke-width="0.8" />
            <line x1="268" y1="220" x2="266" y2="220" stroke="#907858" stroke-width="0.6" />
        </g>
    </g>
    <line x1="275" y1="220" x2="440" y2="238" class="ent-leader" />
    <circle cx="440" cy="238" r="1.5" class="ent-leader-dot" />
    <text x="444" y="235" class="ent-label">Middle Ear</text>
    <text x="444" y="246" class="ent-label-sm">(Ossicles: Malleus, Incus, Stapes)</text>

    {{-- ==================== INNER EAR (COCHLEA / VESTIBULAR) ==================== --}}
    <g @click="toggleZone('inner_ear')" class="cursor-pointer ent-zone">
        {{-- Cochlea (snail shape) --}}
        <path
            :fill="selectedZones.includes('inner_ear') ? '#818cf8' : 'url(#cochleaGrad)'"
            :filter="selectedZones.includes('inner_ear') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#a08868" stroke-width="0.8"
            d="M252,212 Q245,208 238,212 Q232,218 232,228 Q232,238 240,244 Q248,248 256,244 Q262,238 262,230 Q262,222 256,218 Q252,215 248,218 Q244,222 246,228 Q248,232 252,230"
            :opacity="selectedZones.includes('inner_ear') ? 1 : 0.85"
        />
        {{-- Vestibular system (semicircular canals) --}}
        <g>
            <path
                :stroke="selectedZones.includes('inner_ear') ? '#818cf8' : '#c0a888'"
                stroke-width="2.5" fill="none" stroke-linecap="round"
                d="M252,210 Q252,195 242,190 Q232,188 228,198"
                :opacity="selectedZones.includes('inner_ear') ? 0.8 : 0.5"
            />
            <path
                :stroke="selectedZones.includes('inner_ear') ? '#818cf8' : '#c0a888'"
                stroke-width="2.5" fill="none" stroke-linecap="round"
                d="M250,208 Q262,198 258,188 Q252,180 242,185"
                :opacity="selectedZones.includes('inner_ear') ? 0.8 : 0.5"
            />
            <path
                :stroke="selectedZones.includes('inner_ear') ? '#818cf8' : '#c0a888'"
                stroke-width="2.5" fill="none" stroke-linecap="round"
                d="M248,210 Q238,200 228,205 Q225,212 230,218"
                :opacity="selectedZones.includes('inner_ear') ? 0.8 : 0.5"
            />
        </g>
    </g>
    <line x1="245" y1="230" x2="440" y2="272" class="ent-leader" />
    <circle cx="440" cy="272" r="1.5" class="ent-leader-dot" />
    <text x="444" y="269" class="ent-label">Inner Ear</text>
    <text x="444" y="280" class="ent-label-sm">(Cochlea &amp; Vestibular System)</text>

    {{-- ==================== EUSTACHIAN TUBE ==================== --}}
    <g @click="toggleZone('eustachian_tube')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('eustachian_tube') ? '#818cf8' : 'url(#eustachianGrad)'"
            :filter="selectedZones.includes('eustachian_tube') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#b09090" stroke-width="0.8"
            d="M265,232 Q258,240 248,252 Q238,265 228,275 Q220,282 215,285 L212,280 Q218,275 228,268 Q238,258 248,245 Q258,234 262,228 Z"
            :opacity="selectedZones.includes('eustachian_tube') ? 1 : 0.75"
        />
        {{-- Tube lumen --}}
        <path d="M263,230 Q248,250 228,272" fill="none" stroke="#d0a0a0" stroke-width="0.5" opacity="0.3" class="pointer-events-none" />
    </g>
    <line x1="245" y1="255" x2="440" y2="310" class="ent-leader" />
    <circle cx="440" cy="310" r="1.5" class="ent-leader-dot" />
    <text x="444" y="307" class="ent-label">Eustachian Tube</text>
    <text x="444" y="318" class="ent-label-sm">(connects middle ear to pharynx)</text>

    {{-- ==================== THROAT (general) ==================== --}}
    <g @click="toggleZone('throat')" class="cursor-pointer ent-zone">
        <path
            :fill="selectedZones.includes('throat') ? '#818cf8' : '#f8c8c8'"
            :filter="selectedZones.includes('throat') ? 'url(#entSelectGlow)' : 'url(#organGlow)'"
            stroke="#c09090" stroke-width="0.8"
            d="M188,350 Q195,342 210,340 Q225,342 232,350 L235,370 L235,392 Q232,402 225,405 L210,408 L195,405 Q188,402 185,392 L185,370 Z"
            :opacity="selectedZones.includes('throat') ? 1 : 0.7"
        />
    </g>
    <line x1="210" y1="375" x2="350" y2="418" class="ent-leader" />
    <circle cx="350" cy="418" r="1.5" class="ent-leader-dot" />
    <text x="354" y="415" class="ent-label">Throat (General)</text>

    {{-- Tissue texture overlay --}}
    <rect width="680" height="560" fill="url(#tissueTexture)" opacity="0.4" class="pointer-events-none" rx="8" />

    {{-- Legend --}}
    <g transform="translate(420, 440)" class="pointer-events-none">
        <text x="0" y="0" font-family="system-ui, sans-serif" font-size="9" font-weight="600" fill="#4b5563">Regions:</text>
        <rect x="0" y="8" width="8" height="8" rx="1.5" fill="url(#sinusGrad)" stroke="#7090b0" stroke-width="0.5" />
        <text x="12" y="15" font-family="system-ui, sans-serif" font-size="7.5" fill="#6b7280">Sinuses</text>
        <rect x="0" y="22" width="8" height="8" rx="1.5" fill="#f5c8b0" stroke="#c09080" stroke-width="0.5" />
        <text x="12" y="29" font-family="system-ui, sans-serif" font-size="7.5" fill="#6b7280">Nasal Cavity</text>
        <rect x="0" y="36" width="8" height="8" rx="1.5" fill="#f0e0c8" stroke="#b0a088" stroke-width="0.5" />
        <text x="12" y="43" font-family="system-ui, sans-serif" font-size="7.5" fill="#6b7280">Ear Structures</text>
        <rect x="0" y="50" width="8" height="8" rx="1.5" fill="#f8d098" stroke="#c0a070" stroke-width="0.5" />
        <text x="12" y="57" font-family="system-ui, sans-serif" font-size="7.5" fill="#6b7280">Pharynx / Throat</text>
        <rect x="0" y="64" width="8" height="8" rx="1.5" fill="#c8e8c0" stroke="#80a878" stroke-width="0.5" />
        <text x="12" y="71" font-family="system-ui, sans-serif" font-size="7.5" fill="#6b7280">Larynx / Airway</text>
        <rect x="0" y="82" width="8" height="8" rx="1.5" fill="#818cf8" />
        <text x="12" y="89" font-family="system-ui, sans-serif" font-size="7.5" fill="#6b7280">Selected Zone</text>
    </g>
</svg>
