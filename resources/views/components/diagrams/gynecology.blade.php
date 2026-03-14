{{-- Gynecology — Female Reproductive System Anatomical Diagram --}}
{{-- Expects parent Alpine.js scope with: selectedZones, toggleZone(zoneId), zoneNotes --}}

<svg viewBox="0 0 500 520" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto max-w-lg mx-auto">
    <defs>
        <style>
            .gyn-zone { cursor: pointer; transition: fill 0.25s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease, stroke 0.25s ease, transform 0.25s ease, filter 0.25s ease; transform-origin: center; }
            .gyn-zone:hover { opacity: 0.88; filter: url(#gyn-hover-glow); transform: scale(1.015); }
            .gyn-label { font-family: system-ui, -apple-system, sans-serif; font-size: 10px; fill: #374151; pointer-events: none; font-weight: 500; }
            .gyn-label-line { stroke: #9CA3AF; stroke-width: 0.75; fill: none; stroke-dasharray: 2,2; pointer-events: none; }
            .gyn-detail { pointer-events: none; }
        </style>

        {{-- Tissue gradient for uterus myometrium --}}
        <radialGradient id="gyn-uterus-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#F9C4C4"/>
            <stop offset="60%" stop-color="#E8918E"/>
            <stop offset="100%" stop-color="#D4706C"/>
        </radialGradient>

        {{-- Endometrium gradient --}}
        <radialGradient id="gyn-endo-grad" cx="50%" cy="45%" r="50%">
            <stop offset="0%" stop-color="#E85D5D"/>
            <stop offset="100%" stop-color="#C0393A"/>
        </radialGradient>

        {{-- Ovary gradient --}}
        <radialGradient id="gyn-ovary-grad" cx="40%" cy="35%" r="60%">
            <stop offset="0%" stop-color="#FDDEDE"/>
            <stop offset="50%" stop-color="#F0B0AE"/>
            <stop offset="100%" stop-color="#D98E8C"/>
        </radialGradient>

        {{-- Cervix gradient --}}
        <radialGradient id="gyn-cervix-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#F5D0B0"/>
            <stop offset="100%" stop-color="#D4A574"/>
        </radialGradient>

        {{-- Vaginal canal gradient --}}
        <linearGradient id="gyn-vagina-grad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#EAAAA0"/>
            <stop offset="100%" stop-color="#D88A80"/>
        </linearGradient>

        {{-- Fallopian tube gradient --}}
        <linearGradient id="gyn-tube-grad" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#E8918E"/>
            <stop offset="100%" stop-color="#F0B0AE"/>
        </linearGradient>

        {{-- Subtle shadow filter --}}
        <filter id="gyn-shadow" x="-5%" y="-5%" width="115%" height="115%">
            <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#00000020"/>
        </filter>

        {{-- Hover glow --}}
        <filter id="gyn-hover-glow" x="-10%" y="-10%" width="120%" height="120%">
            <feDropShadow dx="0" dy="0" stdDeviation="4" flood-color="#818cf860"/>
        </filter>

        {{-- Inner shadow for depth --}}
        <filter id="gyn-inner-depth" x="-5%" y="-5%" width="110%" height="110%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur"/>
            <feOffset dx="0" dy="1"/>
            <feComposite in2="SourceAlpha" operator="arithmetic" k2="-1" k3="1"/>
            <feFlood flood-color="#00000030"/>
            <feComposite in2="SourceGraphic" operator="in"/>
            <feMerge>
                <feMergeNode in="SourceGraphic"/>
                <feMergeNode/>
            </feMerge>
        </filter>

        {{-- Tissue texture pattern --}}
        <pattern id="gyn-tissue-texture" width="4" height="4" patternUnits="userSpaceOnUse">
            <circle cx="2" cy="2" r="0.3" fill="#00000008"/>
        </pattern>
    </defs>

    {{-- Background pelvic bone outline --}}
    <g class="gyn-detail" opacity="0.15">
        {{-- Iliac wings --}}
        <path d="M60 200 Q70 120 130 90 Q170 78 200 85 Q180 100 170 140 Q165 170 168 200 Z"
              fill="#E8DCC8" stroke="#C4B08A" stroke-width="1"/>
        <path d="M440 200 Q430 120 370 90 Q330 78 300 85 Q320 100 330 140 Q335 170 332 200 Z"
              fill="#E8DCC8" stroke="#C4B08A" stroke-width="1"/>
        {{-- Pubic symphysis --}}
        <path d="M195 360 Q200 370 205 380 Q210 370 205 360" fill="none" stroke="#C4B08A" stroke-width="1.5"/>
        {{-- Sacrum hint --}}
        <rect x="230" y="100" width="40" height="60" rx="8" fill="#E8DCC8" stroke="#C4B08A" stroke-width="0.5" opacity="0.5"/>
    </g>

    {{-- Broad ligament background --}}
    <g class="gyn-detail" opacity="0.12">
        <path d="M160 170 Q120 160 100 180 Q90 200 105 230 L170 250 Z" fill="#F0C0B0"/>
        <path d="M340 170 Q380 160 400 180 Q410 200 395 230 L330 250 Z" fill="#F0C0B0"/>
    </g>

    {{-- ==================== VAGINA ==================== --}}
    <path
        d="M228 390 Q224 410 222 435 Q220 455 221 468 Q222 478 228 482 L272 482 Q278 478 279 468 Q280 455 278 435 Q276 410 272 390"
        class="gyn-zone"
        :fill="selectedZones.includes('vagina') ? '#818CF8' : 'url(#gyn-vagina-grad)'"
        stroke="#B07068" stroke-width="1.2"
        @click="toggleZone('vagina')"
        filter="url(#gyn-shadow)"
    />
    {{-- Vaginal rugae detail --}}
    <g class="gyn-detail" opacity="0.2">
        <path d="M234 405 Q250 400 266 405" fill="none" stroke="#8B5A50" stroke-width="0.6"/>
        <path d="M232 420 Q250 415 268 420" fill="none" stroke="#8B5A50" stroke-width="0.6"/>
        <path d="M231 435 Q250 430 269 435" fill="none" stroke="#8B5A50" stroke-width="0.6"/>
        <path d="M230 450 Q250 445 270 450" fill="none" stroke="#8B5A50" stroke-width="0.6"/>
        <path d="M230 462 Q250 458 270 462" fill="none" stroke="#8B5A50" stroke-width="0.6"/>
    </g>
    {{-- Vagina label --}}
    <line x1="278" y1="440" x2="340" y2="450" class="gyn-label-line"/>
    <text x="344" y="454" class="gyn-label">Vagina</text>

    {{-- ==================== CERVIX ==================== --}}
    <path
        d="M218 355 Q215 365 218 378 Q225 392 250 392 Q275 392 282 378 Q285 365 282 355 Q275 345 250 342 Q225 345 218 355 Z"
        class="gyn-zone"
        :fill="selectedZones.includes('cervix') ? '#818CF8' : 'url(#gyn-cervix-grad)'"
        stroke="#B09070" stroke-width="1.2"
        @click="toggleZone('cervix')"
        filter="url(#gyn-shadow)"
    />
    {{-- External os --}}
    <ellipse cx="250" cy="370" rx="6" ry="4" fill="none" stroke="#8B6B50" stroke-width="1" class="gyn-detail" opacity="0.6"/>
    <line x1="250" y1="366" x2="250" y2="374" stroke="#8B6B50" stroke-width="0.8" class="gyn-detail" opacity="0.4"/>
    {{-- Cervix label --}}
    <line x1="283" y1="368" x2="340" y2="375" class="gyn-label-line"/>
    <text x="344" y="379" class="gyn-label">Cervix</text>

    {{-- ==================== UTERUS (myometrium) ==================== --}}
    <path
        d="M185 175 Q178 168 185 155 Q200 135 225 125 Q240 120 250 118 Q260 120 275 125 Q300 135 315 155 Q322 168 315 175
           L322 260 Q324 300 310 325 Q295 345 275 350 Q260 352 250 352 Q240 352 225 350 Q205 345 190 325 Q176 300 178 260 Z"
        class="gyn-zone"
        :fill="selectedZones.includes('uterus') ? '#818CF8' : 'url(#gyn-uterus-grad)'"
        stroke="#A06060" stroke-width="1.5"
        @click="toggleZone('uterus')"
        filter="url(#gyn-shadow)"
    />
    {{-- Myometrium fiber detail lines --}}
    <g class="gyn-detail" opacity="0.1">
        <path d="M195 180 Q220 170 250 168 Q280 170 305 180" fill="none" stroke="#704040" stroke-width="0.5"/>
        <path d="M190 210 Q220 200 250 198 Q280 200 310 210" fill="none" stroke="#704040" stroke-width="0.5"/>
        <path d="M185 245 Q220 235 250 233 Q280 235 315 245" fill="none" stroke="#704040" stroke-width="0.5"/>
        <path d="M188 280 Q220 268 250 265 Q280 268 312 280" fill="none" stroke="#704040" stroke-width="0.5"/>
        <path d="M195 310 Q220 298 250 295 Q280 298 305 310" fill="none" stroke="#704040" stroke-width="0.5"/>
    </g>
    {{-- Uterine vessels hint along sides --}}
    <g class="gyn-detail" opacity="0.15">
        <path d="M175 200 Q172 240 174 280 Q176 310 185 330" fill="none" stroke="#C04040" stroke-width="1.5"/>
        <path d="M325 200 Q328 240 326 280 Q324 310 315 330" fill="none" stroke="#C04040" stroke-width="1.5"/>
    </g>
    <text x="250" y="215" class="gyn-label" text-anchor="middle" font-size="11">Uterus</text>

    {{-- ==================== ENDOMETRIUM ==================== --}}
    <path
        d="M202 188 Q198 182 208 170 Q225 155 250 148 Q275 155 292 170 Q302 182 298 188
           L304 255 Q305 290 295 312 Q283 330 270 336 Q260 338 250 339 Q240 338 230 336 Q217 330 205 312 Q195 290 196 255 Z"
        class="gyn-zone"
        :fill="selectedZones.includes('endometrium') ? '#6366F1' : 'url(#gyn-endo-grad)'"
        stroke="none"
        @click="toggleZone('endometrium')"
        opacity="0.55"
    />
    {{-- Endometrial gland pattern --}}
    <g class="gyn-detail" opacity="0.15">
        <path d="M220 195 Q222 205 218 210" fill="none" stroke="#800020" stroke-width="0.5"/>
        <path d="M235 190 Q237 200 233 208" fill="none" stroke="#800020" stroke-width="0.5"/>
        <path d="M250 188 Q252 198 248 206" fill="none" stroke="#800020" stroke-width="0.5"/>
        <path d="M265 190 Q267 200 263 208" fill="none" stroke="#800020" stroke-width="0.5"/>
        <path d="M280 195 Q282 205 278 210" fill="none" stroke="#800020" stroke-width="0.5"/>
        <path d="M215 230 Q217 242 213 248" fill="none" stroke="#800020" stroke-width="0.5"/>
        <path d="M235 225 Q237 237 233 245" fill="none" stroke="#800020" stroke-width="0.5"/>
        <path d="M250 224 Q252 236 248 244" fill="none" stroke="#800020" stroke-width="0.5"/>
        <path d="M265 225 Q267 237 263 245" fill="none" stroke="#800020" stroke-width="0.5"/>
        <path d="M285 230 Q287 242 283 248" fill="none" stroke="#800020" stroke-width="0.5"/>
    </g>
    {{-- Uterine cavity line --}}
    <line x1="250" y1="165" x2="250" y2="330" stroke="#800020" stroke-width="0.5" opacity="0.2" class="gyn-detail"/>
    <text x="250" y="270" class="gyn-label" text-anchor="middle" font-size="9" fill="#6B2020">Endometrium</text>

    {{-- ==================== RIGHT FALLOPIAN TUBE ==================== --}}
    <path
        d="M185 165 Q165 145 140 138 Q120 133 105 132 Q90 133 82 140 Q78 150 85 158"
        class="gyn-zone"
        :stroke="selectedZones.includes('right_fallopian_tube') ? '#818CF8' : '#D98E8C'"
        stroke-width="6" fill="none" stroke-linecap="round"
        @click="toggleZone('right_fallopian_tube')"
    />
    {{-- Tube lumen detail --}}
    <path d="M185 165 Q165 145 140 138 Q120 133 105 132 Q90 133 82 140 Q78 150 85 158"
          fill="none" stroke="#F0C8C4" stroke-width="2" class="gyn-detail" opacity="0.6" stroke-linecap="round"/>
    {{-- Isthmus narrowing --}}
    <path d="M185 165 Q175 158 168 155" fill="none"
          :stroke="selectedZones.includes('right_fallopian_tube') ? '#818CF8' : '#C07070'"
          stroke-width="4" class="gyn-zone" @click="toggleZone('right_fallopian_tube')" stroke-linecap="round"/>
    {{-- Fimbriae --}}
    <g class="gyn-zone" @click="toggleZone('right_fallopian_tube')">
        <path d="M82 140 Q72 128 70 135 Q68 142 76 148" fill="none"
              :stroke="selectedZones.includes('right_fallopian_tube') ? '#818CF8' : '#E8918E'" stroke-width="2" stroke-linecap="round"/>
        <path d="M80 142 Q68 138 66 145 Q65 152 74 154" fill="none"
              :stroke="selectedZones.includes('right_fallopian_tube') ? '#818CF8' : '#E8918E'" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M82 146 Q72 148 68 155 Q70 162 78 158" fill="none"
              :stroke="selectedZones.includes('right_fallopian_tube') ? '#818CF8' : '#E8918E'" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M84 150 Q76 155 74 162 Q78 168 84 162" fill="none"
              :stroke="selectedZones.includes('right_fallopian_tube') ? '#818CF8' : '#E8918E'" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M85 155 Q80 160 80 167 Q84 170 88 164" fill="none"
              :stroke="selectedZones.includes('right_fallopian_tube') ? '#818CF8' : '#E8918E'" stroke-width="1.2" stroke-linecap="round"/>
    </g>
    {{-- Label --}}
    <line x1="105" y1="130" x2="60" y2="105" class="gyn-label-line"/>
    <text x="10" y="102" class="gyn-label">R. Fallopian Tube</text>

    {{-- ==================== LEFT FALLOPIAN TUBE ==================== --}}
    <path
        d="M315 165 Q335 145 360 138 Q380 133 395 132 Q410 133 418 140 Q422 150 415 158"
        class="gyn-zone"
        :stroke="selectedZones.includes('left_fallopian_tube') ? '#818CF8' : '#D98E8C'"
        stroke-width="6" fill="none" stroke-linecap="round"
        @click="toggleZone('left_fallopian_tube')"
    />
    {{-- Tube lumen detail --}}
    <path d="M315 165 Q335 145 360 138 Q380 133 395 132 Q410 133 418 140 Q422 150 415 158"
          fill="none" stroke="#F0C8C4" stroke-width="2" class="gyn-detail" opacity="0.6" stroke-linecap="round"/>
    {{-- Isthmus --}}
    <path d="M315 165 Q325 158 332 155" fill="none"
          :stroke="selectedZones.includes('left_fallopian_tube') ? '#818CF8' : '#C07070'"
          stroke-width="4" class="gyn-zone" @click="toggleZone('left_fallopian_tube')" stroke-linecap="round"/>
    {{-- Fimbriae --}}
    <g class="gyn-zone" @click="toggleZone('left_fallopian_tube')">
        <path d="M418 140 Q428 128 430 135 Q432 142 424 148" fill="none"
              :stroke="selectedZones.includes('left_fallopian_tube') ? '#818CF8' : '#E8918E'" stroke-width="2" stroke-linecap="round"/>
        <path d="M420 142 Q432 138 434 145 Q435 152 426 154" fill="none"
              :stroke="selectedZones.includes('left_fallopian_tube') ? '#818CF8' : '#E8918E'" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M418 146 Q428 148 432 155 Q430 162 422 158" fill="none"
              :stroke="selectedZones.includes('left_fallopian_tube') ? '#818CF8' : '#E8918E'" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M416 150 Q424 155 426 162 Q422 168 416 162" fill="none"
              :stroke="selectedZones.includes('left_fallopian_tube') ? '#818CF8' : '#E8918E'" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M415 155 Q420 160 420 167 Q416 170 412 164" fill="none"
              :stroke="selectedZones.includes('left_fallopian_tube') ? '#818CF8' : '#E8918E'" stroke-width="1.2" stroke-linecap="round"/>
    </g>
    {{-- Label --}}
    <line x1="395" y1="130" x2="420" y2="105" class="gyn-label-line"/>
    <text x="390" y="100" class="gyn-label">L. Fallopian Tube</text>

    {{-- ==================== RIGHT OVARY ==================== --}}
    <ellipse
        cx="82" cy="182" rx="26" ry="18"
        class="gyn-zone"
        :fill="selectedZones.includes('right_ovary') ? '#818CF8' : 'url(#gyn-ovary-grad)'"
        stroke="#A07070" stroke-width="1.2"
        @click="toggleZone('right_ovary')"
        filter="url(#gyn-shadow)"
    />
    {{-- Follicle detail --}}
    <g class="gyn-detail" opacity="0.3">
        <circle cx="72" cy="178" r="4" fill="none" stroke="#906060" stroke-width="0.8"/>
        <circle cx="72" cy="178" r="1.5" fill="#C08080"/>
        <circle cx="88" cy="174" r="3" fill="none" stroke="#906060" stroke-width="0.6"/>
        <circle cx="88" cy="174" r="1" fill="#C08080"/>
        <circle cx="80" cy="188" r="3.5" fill="none" stroke="#906060" stroke-width="0.7"/>
        <circle cx="92" cy="185" r="2.5" fill="none" stroke="#906060" stroke-width="0.5"/>
        {{-- Corpus luteum --}}
        <circle cx="76" cy="185" r="5" fill="#E8C060" opacity="0.4" stroke="#B09040" stroke-width="0.5"/>
    </g>
    {{-- Ovarian ligament --}}
    <path d="M108 182 Q130 185 160 190" fill="none" stroke="#C0A090" stroke-width="1.5" stroke-dasharray="3,2" class="gyn-detail" opacity="0.4"/>
    {{-- Label --}}
    <line x1="60" y1="200" x2="35" y2="220" class="gyn-label-line"/>
    <text x="10" y="225" class="gyn-label">R. Ovary</text>

    {{-- ==================== LEFT OVARY ==================== --}}
    <ellipse
        cx="418" cy="182" rx="26" ry="18"
        class="gyn-zone"
        :fill="selectedZones.includes('left_ovary') ? '#818CF8' : 'url(#gyn-ovary-grad)'"
        stroke="#A07070" stroke-width="1.2"
        @click="toggleZone('left_ovary')"
        filter="url(#gyn-shadow)"
    />
    {{-- Follicle detail --}}
    <g class="gyn-detail" opacity="0.3">
        <circle cx="428" cy="178" r="4" fill="none" stroke="#906060" stroke-width="0.8"/>
        <circle cx="428" cy="178" r="1.5" fill="#C08080"/>
        <circle cx="412" cy="174" r="3" fill="none" stroke="#906060" stroke-width="0.6"/>
        <circle cx="412" cy="174" r="1" fill="#C08080"/>
        <circle cx="420" cy="188" r="3.5" fill="none" stroke="#906060" stroke-width="0.7"/>
        <circle cx="408" cy="185" r="2.5" fill="none" stroke="#906060" stroke-width="0.5"/>
        <circle cx="424" cy="185" r="5" fill="#E8C060" opacity="0.4" stroke="#B09040" stroke-width="0.5"/>
    </g>
    {{-- Ovarian ligament --}}
    <path d="M392 182 Q370 185 340 190" fill="none" stroke="#C0A090" stroke-width="1.5" stroke-dasharray="3,2" class="gyn-detail" opacity="0.4"/>
    {{-- Label --}}
    <line x1="440" y1="200" x2="455" y2="220" class="gyn-label-line"/>
    <text x="445" y="234" class="gyn-label">L. Ovary</text>

    {{-- ==================== Round ligaments (decorative) ==================== --}}
    <g class="gyn-detail" opacity="0.2">
        <path d="M190 160 Q150 130 110 115 Q80 108 60 115" fill="none" stroke="#B09080" stroke-width="1.5"/>
        <path d="M310 160 Q350 130 390 115 Q420 108 440 115" fill="none" stroke="#B09080" stroke-width="1.5"/>
    </g>

    {{-- ==================== Uterosacral ligaments (decorative) ==================== --}}
    <g class="gyn-detail" opacity="0.15">
        <path d="M210 340 Q200 360 190 370 Q175 380 160 375" fill="none" stroke="#B09080" stroke-width="1.5"/>
        <path d="M290 340 Q300 360 310 370 Q325 380 340 375" fill="none" stroke="#B09080" stroke-width="1.5"/>
    </g>

    {{-- Title --}}
    <text x="250" y="510" font-family="system-ui, -apple-system, sans-serif" font-size="13" font-weight="600" fill="#1F2937" text-anchor="middle">
        Gynecology — Female Reproductive System
    </text>
</svg>
