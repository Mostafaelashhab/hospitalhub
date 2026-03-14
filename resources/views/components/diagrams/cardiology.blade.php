{{-- Heart Anatomy Diagram --}}
{{-- Requires parent Alpine.js scope with: selectedZones[], toggleZone(id), zoneNotes{} --}}

<svg viewBox="0 0 750 750" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto" role="img" aria-label="Heart anatomy diagram">
    <title>Heart Anatomy Diagram</title>

    <defs>
        {{-- Gradients --}}
        <radialGradient id="cardio-bg" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#f1f5f9" />
            <stop offset="100%" stop-color="#e2e8f0" />
        </radialGradient>

        {{-- Deoxygenated blood gradient (blue) --}}
        <linearGradient id="deoxy-gradient" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#93c5fd" />
            <stop offset="50%" stop-color="#7db8f5" />
            <stop offset="100%" stop-color="#60a5fa" />
        </linearGradient>
        <linearGradient id="deoxy-dark" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#7db8f5" />
            <stop offset="100%" stop-color="#3b82f6" />
        </linearGradient>
        <linearGradient id="deoxy-vessel" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#bfdbfe" />
            <stop offset="100%" stop-color="#93c5fd" />
        </linearGradient>

        {{-- Oxygenated blood gradient (red) --}}
        <linearGradient id="oxy-gradient" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#fca5a5" />
            <stop offset="50%" stop-color="#f87171" />
            <stop offset="100%" stop-color="#ef4444" />
        </linearGradient>
        <linearGradient id="oxy-light" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#fecaca" />
            <stop offset="100%" stop-color="#fca5a5" />
        </linearGradient>
        <linearGradient id="oxy-vessel" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#fecaca" />
            <stop offset="100%" stop-color="#f87171" />
        </linearGradient>

        {{-- Myocardium (muscle wall) gradient --}}
        <linearGradient id="myocardium" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#c2410c" />
            <stop offset="40%" stop-color="#9a3412" />
            <stop offset="100%" stop-color="#7c2d12" />
        </linearGradient>

        {{-- Valve gradient --}}
        <linearGradient id="valve-gradient" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#fef3c7" />
            <stop offset="50%" stop-color="#fde68a" />
            <stop offset="100%" stop-color="#fbbf24" />
        </linearGradient>

        {{-- Coronary artery gradient --}}
        <linearGradient id="coronary-gradient" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#ef4444" />
            <stop offset="100%" stop-color="#dc2626" />
        </linearGradient>

        {{-- Aorta gradient --}}
        <linearGradient id="aorta-gradient" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#f87171" />
            <stop offset="100%" stop-color="#dc2626" />
        </linearGradient>

        {{-- Pulmonary artery gradient --}}
        <linearGradient id="pulm-artery-gradient" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#fca5a5" />
            <stop offset="100%" stop-color="#c084fc" />
        </linearGradient>

        {{-- Drop shadow --}}
        <filter id="cardio-shadow" x="-5%" y="-5%" width="115%" height="115%">
            <feDropShadow dx="2" dy="3" stdDeviation="4" flood-color="#1e293b" flood-opacity="0.15" />
        </filter>
        <filter id="inner-glow" x="-10%" y="-10%" width="120%" height="120%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur" />
            <feOffset dx="0" dy="0" />
            <feComposite in2="SourceAlpha" operator="arithmetic" k2="-1" k3="1" />
            <feFlood flood-color="#7c2d12" flood-opacity="0.25" />
            <feComposite in2="SourceGraphic" operator="in" />
            <feMerge>
                <feMergeNode />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>

        {{-- Muscle texture pattern --}}
        <pattern id="muscle-texture" width="8" height="8" patternUnits="userSpaceOnUse">
            <line x1="0" y1="0" x2="8" y2="8" stroke="#7c2d12" stroke-width="0.3" opacity="0.15" />
            <line x1="4" y1="0" x2="12" y2="8" stroke="#7c2d12" stroke-width="0.2" opacity="0.1" />
        </pattern>

        {{-- Arrow marker --}}
        <marker id="arrowhead-cardio" markerWidth="8" markerHeight="6" refX="7" refY="3" orient="auto">
            <polygon points="0 0, 8 3, 0 6" fill="#64748b" />
        </marker>
        <marker id="arrowhead-red" markerWidth="7" markerHeight="5" refX="6" refY="2.5" orient="auto">
            <polygon points="0 0, 7 2.5, 0 5" fill="#dc2626" opacity="0.5" />
        </marker>
        <marker id="arrowhead-blue" markerWidth="7" markerHeight="5" refX="6" refY="2.5" orient="auto">
            <polygon points="0 0, 7 2.5, 0 5" fill="#3b82f6" opacity="0.5" />
        </marker>
    </defs>

    {{-- Background --}}
    <rect width="750" height="750" fill="url(#cardio-bg)" rx="12" />

    {{-- Title --}}
    <text x="375" y="38" text-anchor="middle" font-size="22" font-weight="bold" fill="#1e293b" font-family="system-ui, sans-serif">Heart Anatomy</text>
    <text x="375" y="56" text-anchor="middle" font-size="12" fill="#64748b" font-family="system-ui, sans-serif">Anterior Coronal Section</text>

    {{-- ======================== OUTER HEART WALL (myocardium silhouette) ======================== --}}
    <path
        d="M 265 100 Q 240 95 215 105 Q 180 120 155 160 Q 130 200 125 250
           Q 118 310 130 360 Q 145 415 180 465 Q 220 520 275 565
           Q 330 605 375 620 Q 420 605 475 565 Q 530 520 570 465
           Q 605 415 620 360 Q 632 310 625 250 Q 620 200 595 160
           Q 570 120 535 105 Q 510 95 485 100
           Q 460 105 445 120 Q 420 100 395 92 Q 375 88 355 92
           Q 330 100 310 120 Q 290 105 265 100 Z"
        fill="url(#myocardium)" stroke="#581c87" stroke-width="0.5" opacity="0.2"
        filter="url(#cardio-shadow)"
    />
    {{-- Muscle texture overlay --}}
    <path
        d="M 265 100 Q 240 95 215 105 Q 180 120 155 160 Q 130 200 125 250
           Q 118 310 130 360 Q 145 415 180 465 Q 220 520 275 565
           Q 330 605 375 620 Q 420 605 475 565 Q 530 520 570 465
           Q 605 415 620 360 Q 632 310 625 250 Q 620 200 595 160
           Q 570 120 535 105 Q 510 95 485 100
           Q 460 105 445 120 Q 420 100 395 92 Q 375 88 355 92
           Q 330 100 310 120 Q 290 105 265 100 Z"
        fill="url(#muscle-texture)" stroke="none" opacity="0.4"
    />

    {{-- ======================== SUPERIOR VENA CAVA ======================== --}}
    <path
        d="M 218 68 Q 212 68 208 78 L 196 150 Q 194 160 198 168
           L 218 178 Q 228 182 232 172 L 244 88 Q 246 72 236 68 Z"
        :fill="selectedZones.includes('superior_vena_cava') ? '#818cf8' : 'url(#deoxy-vessel)'"
        stroke="#475569" stroke-width="1.5"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('superior_vena_cava')"
    >
        <title>Superior Vena Cava</title>
    </path>
    {{-- SVC shading --}}
    <path d="M 222 72 L 214 150 Q 213 158 216 162" fill="none" stroke="#3b82f6" stroke-width="0.6" opacity="0.3" />
    <text x="148" y="110" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Superior</text>
    <text x="148" y="123" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Vena Cava</text>
    <line x1="178" y1="116" x2="206" y2="120" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== AORTA (Aortic Arch) ======================== --}}
    <path
        d="M 310 88 Q 310 65 340 56 Q 370 48 405 52 Q 445 56 475 72
           Q 505 90 518 118 L 525 150 Q 528 168 520 175
           L 498 180 Q 488 183 486 172 L 480 130 Q 474 100 450 85
           Q 425 72 395 68 Q 365 64 345 72 Q 328 80 325 100
           L 318 178 Q 316 188 306 185 L 290 182 Q 280 180 282 168
           L 290 105 Q 294 90 310 88 Z"
        :fill="selectedZones.includes('aorta') ? '#818cf8' : 'url(#aorta-gradient)'"
        stroke="#991b1b" stroke-width="1.2"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('aorta')"
        filter="url(#cardio-shadow)"
    >
        <title>Aorta</title>
    </path>
    {{-- Aorta shading highlight --}}
    <path d="M 330 62 Q 370 55 405 58 Q 435 62 460 76" fill="none" stroke="#fecaca" stroke-width="1" opacity="0.4" />
    {{-- Aortic arch branches (decorative) --}}
    <path d="M 370 54 L 365 35 Q 363 25 368 22" fill="none" stroke="#991b1b" stroke-width="1.5" opacity="0.5" />
    <path d="M 405 52 L 402 30 Q 400 20 405 18" fill="none" stroke="#991b1b" stroke-width="1.5" opacity="0.5" />
    <path d="M 440 60 L 445 38 Q 447 28 452 26" fill="none" stroke="#991b1b" stroke-width="1.5" opacity="0.5" />
    <text x="405" y="48" text-anchor="middle" font-size="12" font-weight="700" fill="#7f1d1d">Aorta</text>

    {{-- ======================== PULMONARY ARTERY ======================== --}}
    <path
        d="M 320 182 Q 328 158 358 148 Q 390 138 418 142
           Q 442 148 455 165 L 460 190 Q 462 200 452 198
           L 420 188 Q 395 182 368 184 Q 345 186 335 198
           L 328 194 Q 318 188 320 182 Z"
        :fill="selectedZones.includes('pulmonary_artery') ? '#818cf8' : 'url(#pulm-artery-gradient)'"
        stroke="#6b21a8" stroke-width="1.2"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('pulmonary_artery')"
    >
        <title>Pulmonary Artery</title>
    </path>
    {{-- PA branches --}}
    <path d="M 455 168 Q 475 155 495 155 L 520 158" fill="none" stroke="#6b21a8" stroke-width="1.8" opacity="0.5" />
    <path d="M 450 175 Q 465 170 478 172" fill="none" stroke="#6b21a8" stroke-width="1.2" opacity="0.3" />
    <text x="530" y="162" text-anchor="start" font-size="10" font-weight="600" fill="#334155">Pulmonary</text>
    <text x="530" y="175" text-anchor="start" font-size="10" font-weight="600" fill="#334155">Artery</text>
    <line x1="525" y1="168" x2="462" y2="178" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== RIGHT ATRIUM ======================== --}}
    <path
        d="M 195 200 Q 165 208 148 240 Q 132 278 135 320
           Q 138 352 158 375 L 185 385 L 275 390 Q 288 390 288 378
           L 292 218 Q 292 202 275 202 Z"
        :fill="selectedZones.includes('right_atrium') ? '#818cf8' : 'url(#deoxy-gradient)'"
        stroke="#475569" stroke-width="1.8"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('right_atrium')"
    >
        <title>Right Atrium</title>
    </path>
    {{-- RA internal texture (pectinate muscles) --}}
    <path d="M 165 250 Q 185 248 205 252" fill="none" stroke="#3b82f6" stroke-width="0.8" opacity="0.25" />
    <path d="M 160 270 Q 185 268 210 272" fill="none" stroke="#3b82f6" stroke-width="0.8" opacity="0.25" />
    <path d="M 158 290 Q 185 288 215 292" fill="none" stroke="#3b82f6" stroke-width="0.8" opacity="0.25" />
    <path d="M 155 310 Q 182 308 212 312" fill="none" stroke="#3b82f6" stroke-width="0.8" opacity="0.25" />
    <path d="M 162 330 Q 188 328 218 332" fill="none" stroke="#3b82f6" stroke-width="0.8" opacity="0.25" />
    <text x="215" y="295" text-anchor="middle" font-size="13" font-weight="700" fill="#1e293b">Right</text>
    <text x="215" y="312" text-anchor="middle" font-size="13" font-weight="700" fill="#1e293b">Atrium</text>

    {{-- ======================== LEFT ATRIUM ======================== --}}
    <path
        d="M 308 202 Q 308 192 325 190 L 440 196 Q 465 202 480 225
           Q 498 255 495 295 Q 490 330 468 358 L 440 375
           Q 428 380 420 374 L 308 382 Q 302 382 302 372 Z"
        :fill="selectedZones.includes('left_atrium') ? '#818cf8' : 'url(#oxy-light)'"
        stroke="#475569" stroke-width="1.8"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('left_atrium')"
    >
        <title>Left Atrium</title>
    </path>
    {{-- LA internal shading --}}
    <path d="M 340 240 Q 390 238 430 245" fill="none" stroke="#ef4444" stroke-width="0.6" opacity="0.2" />
    <path d="M 335 260 Q 390 258 440 265" fill="none" stroke="#ef4444" stroke-width="0.6" opacity="0.2" />
    <path d="M 330 280 Q 385 278 445 285" fill="none" stroke="#ef4444" stroke-width="0.6" opacity="0.2" />
    <path d="M 328 300 Q 380 298 442 305" fill="none" stroke="#ef4444" stroke-width="0.6" opacity="0.2" />
    {{-- Pulmonary veins entering LA --}}
    <path d="M 495 240 Q 520 230 545 235" fill="none" stroke="#ef4444" stroke-width="2" opacity="0.4" />
    <path d="M 495 260 Q 525 258 550 260" fill="none" stroke="#ef4444" stroke-width="2" opacity="0.4" />
    <path d="M 492 320 Q 520 325 548 322" fill="none" stroke="#ef4444" stroke-width="2" opacity="0.4" />
    <path d="M 490 340 Q 518 348 545 345" fill="none" stroke="#ef4444" stroke-width="2" opacity="0.4" />
    <text x="575" y="245" text-anchor="start" font-size="9" fill="#64748b">Pulmonary</text>
    <text x="575" y="257" text-anchor="start" font-size="9" fill="#64748b">Veins</text>
    <text x="395" y="290" text-anchor="middle" font-size="13" font-weight="700" fill="#1e293b">Left</text>
    <text x="395" y="307" text-anchor="middle" font-size="13" font-weight="700" fill="#1e293b">Atrium</text>

    {{-- ======================== TRICUSPID VALVE ======================== --}}
    <g class="cursor-pointer" @click="toggleZone('tricuspid_valve')">
        <path
            d="M 220 385 Q 235 395 252 405 L 270 395 Q 285 385 298 378
               L 288 390 Q 280 402 262 410 Q 245 402 235 390 Z"
            :fill="selectedZones.includes('tricuspid_valve') ? '#818cf8' : 'url(#valve-gradient)'"
            stroke="#92400e" stroke-width="1.5"
            class="transition-all duration-300 ease-out"
        >
            <title>Tricuspid Valve</title>
        </path>
        {{-- Valve leaflet detail --}}
        <path d="M 240 392 L 258 405 L 278 392" fill="none" stroke="#92400e" stroke-width="0.8" opacity="0.5" />
        {{-- Chordae tendineae --}}
        <line x1="242" y1="398" x2="230" y2="445" stroke="#92400e" stroke-width="0.6" opacity="0.35" />
        <line x1="258" y1="408" x2="255" y2="455" stroke="#92400e" stroke-width="0.6" opacity="0.35" />
        <line x1="275" y1="398" x2="278" y2="445" stroke="#92400e" stroke-width="0.6" opacity="0.35" />
    </g>
    <text x="118" y="398" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Tricuspid</text>
    <text x="118" y="411" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Valve</text>
    <line x1="148" y1="404" x2="222" y2="395" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== MITRAL VALVE ======================== --}}
    <g class="cursor-pointer" @click="toggleZone('mitral_valve')">
        <path
            d="M 362 380 Q 378 392 395 402 L 412 392 Q 428 382 440 375
               L 430 388 Q 420 400 402 408 Q 385 400 375 388 Z"
            :fill="selectedZones.includes('mitral_valve') ? '#818cf8' : 'url(#valve-gradient)'"
            stroke="#92400e" stroke-width="1.5"
            class="transition-all duration-300 ease-out"
        >
            <title>Mitral Valve</title>
        </path>
        {{-- Valve leaflet detail --}}
        <path d="M 378 388 L 398 402 L 420 388" fill="none" stroke="#92400e" stroke-width="0.8" opacity="0.5" />
        {{-- Chordae tendineae --}}
        <line x1="380" y1="394" x2="370" y2="445" stroke="#92400e" stroke-width="0.6" opacity="0.35" />
        <line x1="398" y1="406" x2="398" y2="458" stroke="#92400e" stroke-width="0.6" opacity="0.35" />
        <line x1="418" y1="394" x2="425" y2="445" stroke="#92400e" stroke-width="0.6" opacity="0.35" />
    </g>
    <text x="520" y="398" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Mitral</text>
    <text x="520" y="411" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Valve</text>
    <line x1="495" y1="404" x2="442" y2="395" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== AORTIC VALVE ======================== --}}
    <g class="cursor-pointer" @click="toggleZone('aortic_valve')">
        <path
            d="M 298 185 L 310 198 L 298 212 L 285 198 Z"
            :fill="selectedZones.includes('aortic_valve') ? '#818cf8' : 'url(#valve-gradient)'"
            stroke="#92400e" stroke-width="1.5"
            class="transition-all duration-300 ease-out"
        >
            <title>Aortic Valve</title>
        </path>
        {{-- 3 cusps hint --}}
        <circle cx="293" cy="198" r="2.5" fill="#92400e" opacity="0.2" />
        <circle cx="298" cy="204" r="2.5" fill="#92400e" opacity="0.2" />
        <circle cx="303" cy="198" r="2.5" fill="#92400e" opacity="0.2" />
    </g>
    <text x="256" y="190" text-anchor="end" font-size="10" font-weight="600" fill="#334155">Aortic</text>
    <text x="256" y="203" text-anchor="end" font-size="10" font-weight="600" fill="#334155">Valve</text>
    <line x1="260" y1="196" x2="286" y2="198" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== PULMONARY VALVE ======================== --}}
    <g class="cursor-pointer" @click="toggleZone('pulmonary_valve')">
        <path
            d="M 340 180 L 354 194 L 340 208 L 326 194 Z"
            :fill="selectedZones.includes('pulmonary_valve') ? '#818cf8' : 'url(#valve-gradient)'"
            stroke="#92400e" stroke-width="1.5"
            class="transition-all duration-300 ease-out"
        >
            <title>Pulmonary Valve</title>
        </path>
        <circle cx="335" cy="194" r="2.5" fill="#92400e" opacity="0.2" />
        <circle cx="340" cy="200" r="2.5" fill="#92400e" opacity="0.2" />
        <circle cx="345" cy="194" r="2.5" fill="#92400e" opacity="0.2" />
    </g>
    <text x="365" y="186" text-anchor="start" font-size="10" font-weight="600" fill="#334155">Pulmonary</text>
    <text x="365" y="199" text-anchor="start" font-size="10" font-weight="600" fill="#334155">Valve</text>

    {{-- ======================== RIGHT VENTRICLE ======================== --}}
    <path
        d="M 185 390 Q 165 398 155 420 Q 140 460 155 510
           Q 172 555 210 590 Q 250 620 295 640 Q 315 648 325 635
           L 330 415 Q 330 405 318 405 L 275 398 Z"
        :fill="selectedZones.includes('right_ventricle') ? '#818cf8' : 'url(#deoxy-dark)'"
        stroke="#475569" stroke-width="1.8"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('right_ventricle')"
    >
        <title>Right Ventricle</title>
    </path>
    {{-- RV trabeculae (internal muscle ridges) --}}
    <path d="M 195 440 Q 230 435 265 442" fill="none" stroke="#1e40af" stroke-width="0.8" opacity="0.2" />
    <path d="M 185 465 Q 225 460 270 468" fill="none" stroke="#1e40af" stroke-width="0.8" opacity="0.2" />
    <path d="M 180 490 Q 225 485 275 494" fill="none" stroke="#1e40af" stroke-width="0.8" opacity="0.2" />
    <path d="M 185 515 Q 230 510 280 518" fill="none" stroke="#1e40af" stroke-width="0.8" opacity="0.2" />
    <path d="M 200 540 Q 240 535 285 544" fill="none" stroke="#1e40af" stroke-width="0.8" opacity="0.2" />
    {{-- Papillary muscles --}}
    <ellipse cx="230" cy="480" rx="12" ry="20" fill="#2563eb" opacity="0.15" stroke="#1e40af" stroke-width="0.5" />
    <ellipse cx="275" cy="510" rx="10" ry="18" fill="#2563eb" opacity="0.15" stroke="#1e40af" stroke-width="0.5" />
    <text x="240" y="500" text-anchor="middle" font-size="13" font-weight="700" fill="#1e293b">Right</text>
    <text x="240" y="518" text-anchor="middle" font-size="13" font-weight="700" fill="#1e293b">Ventricle</text>

    {{-- ======================== LEFT VENTRICLE ======================== --}}
    <path
        d="M 342 408 Q 342 402 355 400 L 410 382 Q 432 378 452 395
           Q 482 418 492 460 Q 502 505 485 550
           Q 465 590 425 618 Q 388 640 350 645
           Q 335 646 335 632 Z"
        :fill="selectedZones.includes('left_ventricle') ? '#818cf8' : 'url(#oxy-gradient)'"
        stroke="#475569" stroke-width="1.8"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('left_ventricle')"
    >
        <title>Left Ventricle</title>
    </path>
    {{-- LV trabeculae --}}
    <path d="M 375 440 Q 415 435 455 445" fill="none" stroke="#991b1b" stroke-width="0.8" opacity="0.2" />
    <path d="M 370 465 Q 415 460 460 470" fill="none" stroke="#991b1b" stroke-width="0.8" opacity="0.2" />
    <path d="M 368 490 Q 415 485 462 495" fill="none" stroke="#991b1b" stroke-width="0.8" opacity="0.2" />
    <path d="M 372 515 Q 418 510 458 520" fill="none" stroke="#991b1b" stroke-width="0.8" opacity="0.2" />
    <path d="M 380 540 Q 420 535 452 545" fill="none" stroke="#991b1b" stroke-width="0.8" opacity="0.2" />
    {{-- Papillary muscles --}}
    <ellipse cx="405" cy="490" rx="12" ry="22" fill="#dc2626" opacity="0.15" stroke="#991b1b" stroke-width="0.5" />
    <ellipse cx="440" cy="520" rx="10" ry="18" fill="#dc2626" opacity="0.15" stroke="#991b1b" stroke-width="0.5" />
    <text x="415" y="505" text-anchor="middle" font-size="13" font-weight="700" fill="#1e293b">Left</text>
    <text x="415" y="523" text-anchor="middle" font-size="13" font-weight="700" fill="#1e293b">Ventricle</text>

    {{-- ======================== INTERVENTRICULAR SEPTUM ======================== --}}
    <path d="M 330 405 Q 333 500 335 640" fill="none" stroke="#475569" stroke-width="3" opacity="0.35" />
    <path d="M 337 405 Q 339 500 340 640" fill="none" stroke="#9a3412" stroke-width="1.5" opacity="0.15" />

    {{-- ======================== INFERIOR VENA CAVA ======================== --}}
    <path
        d="M 148 378 Q 135 385 130 405 L 118 490 Q 112 515 124 528
           L 142 535 Q 155 538 158 525 L 168 445 Q 172 420 168 400
           L 162 382 Q 158 375 150 378 Z"
        :fill="selectedZones.includes('inferior_vena_cava') ? '#818cf8' : 'url(#deoxy-vessel)'"
        stroke="#475569" stroke-width="1.5"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('inferior_vena_cava')"
    >
        <title>Inferior Vena Cava</title>
    </path>
    <path d="M 142 390 L 136 480 Q 134 500 138 515" fill="none" stroke="#3b82f6" stroke-width="0.6" opacity="0.3" />
    <text x="72" y="460" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Inferior</text>
    <text x="72" y="473" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Vena Cava</text>
    <line x1="102" y1="466" x2="128" y2="470" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== CORONARY ARTERIES ======================== --}}
    <g class="cursor-pointer" @click="toggleZone('coronary_arteries')">
        {{-- Left anterior descending (LAD) --}}
        <path
            d="M 315 108 Q 320 130 325 165 Q 328 200 332 250
               Q 334 310 336 380 Q 337 430 338 500
               Q 339 550 340 600"
            :stroke="selectedZones.includes('coronary_arteries') ? '#818cf8' : '#dc2626'"
            stroke-width="3.5" fill="none" stroke-linecap="round"
            class="transition-all duration-300 ease-out"
            opacity="0.7"
        >
            <title>Coronary Arteries</title>
        </path>
        {{-- Left circumflex (LCx) --}}
        <path
            d="M 315 108 Q 340 118 380 140 Q 430 165 465 200
               Q 485 225 492 260 Q 495 290 488 330"
            :stroke="selectedZones.includes('coronary_arteries') ? '#818cf8' : '#dc2626'"
            stroke-width="3" fill="none" stroke-linecap="round"
            class="transition-all duration-300 ease-out"
            opacity="0.6"
        />
        {{-- Right coronary artery (RCA) --}}
        <path
            d="M 300 105 Q 275 115 245 140 Q 210 175 185 220
               Q 165 265 155 320 Q 150 360 155 400"
            :stroke="selectedZones.includes('coronary_arteries') ? '#818cf8' : '#dc2626'"
            stroke-width="3" fill="none" stroke-linecap="round"
            class="transition-all duration-300 ease-out"
            opacity="0.6"
        />
        {{-- Diagonal branches --}}
        <path d="M 328 220 Q 300 240 275 270" :stroke="selectedZones.includes('coronary_arteries') ? '#818cf8' : '#dc2626'" stroke-width="1.8" fill="none" opacity="0.4" stroke-linecap="round" />
        <path d="M 332 290 Q 308 308 288 335" :stroke="selectedZones.includes('coronary_arteries') ? '#818cf8' : '#dc2626'" stroke-width="1.8" fill="none" opacity="0.4" stroke-linecap="round" />
        <path d="M 336 360 Q 310 380 292 405" :stroke="selectedZones.includes('coronary_arteries') ? '#818cf8' : '#dc2626'" stroke-width="1.5" fill="none" opacity="0.35" stroke-linecap="round" />
        {{-- LCx branches --}}
        <path d="M 465 200 Q 480 230 478 260" :stroke="selectedZones.includes('coronary_arteries') ? '#818cf8' : '#dc2626'" stroke-width="1.5" fill="none" opacity="0.35" stroke-linecap="round" />
        <path d="M 445 170 Q 460 195 458 220" :stroke="selectedZones.includes('coronary_arteries') ? '#818cf8' : '#dc2626'" stroke-width="1.5" fill="none" opacity="0.35" stroke-linecap="round" />
    </g>
    <text x="115" y="210" text-anchor="end" font-size="10" font-weight="600" fill="#991b1b">Coronary</text>
    <text x="115" y="223" text-anchor="end" font-size="10" font-weight="600" fill="#991b1b">Arteries</text>
    <line x1="118" y1="216" x2="182" y2="222" stroke="#dc2626" stroke-width="0.8" opacity="0.5" />

    {{-- ======================== BLOOD FLOW ARROWS ======================== --}}
    {{-- SVC flow down --}}
    <path d="M 226 82 L 226 155" fill="none" stroke="#3b82f6" stroke-width="1.2" opacity="0.35" marker-end="url(#arrowhead-blue)" />
    {{-- IVC flow up --}}
    <path d="M 140 520 L 148 410" fill="none" stroke="#3b82f6" stroke-width="1.2" opacity="0.35" marker-end="url(#arrowhead-blue)" />
    {{-- RA to RV flow --}}
    <path d="M 250 370 L 260 420" fill="none" stroke="#3b82f6" stroke-width="1.2" opacity="0.35" marker-end="url(#arrowhead-blue)" />
    {{-- RV to PA flow --}}
    <path d="M 310 420 Q 330 380 340 220" fill="none" stroke="#3b82f6" stroke-width="1" opacity="0.25" marker-end="url(#arrowhead-blue)" />
    {{-- LA to LV flow --}}
    <path d="M 400 360 L 400 420" fill="none" stroke="#dc2626" stroke-width="1.2" opacity="0.35" marker-end="url(#arrowhead-red)" />
    {{-- LV to Aorta flow --}}
    <path d="M 370 420 Q 340 350 310 210" fill="none" stroke="#dc2626" stroke-width="1" opacity="0.25" marker-end="url(#arrowhead-red)" />

    {{-- ======================== OUTER HEART WALL BORDER ======================== --}}
    <path
        d="M 265 100 Q 240 95 215 105 Q 180 120 155 160 Q 130 200 125 250
           Q 118 310 130 360 Q 145 415 180 465 Q 220 520 275 565
           Q 330 605 375 620 Q 420 605 475 565 Q 530 520 570 465
           Q 605 415 620 360 Q 632 310 625 250 Q 620 200 595 160
           Q 570 120 535 105 Q 510 95 485 100
           Q 460 105 445 120 Q 420 100 395 92 Q 375 88 355 92
           Q 330 100 310 120 Q 290 105 265 100 Z"
        fill="none" stroke="#78350f" stroke-width="2.5" opacity="0.6"
    />
    {{-- Epicardial fat pad hint at top --}}
    <path d="M 280 98 Q 330 88 380 92 Q 420 98 450 112" fill="none" stroke="#92400e" stroke-width="1.5" opacity="0.2" />

    {{-- ======================== LEGEND ======================== --}}
    <g transform="translate(25, 672)">
        <rect x="0" y="0" width="14" height="14" fill="url(#deoxy-gradient)" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="20" y="11" font-size="10" fill="#475569">Deoxygenated</text>
        <rect x="120" y="0" width="14" height="14" fill="url(#oxy-gradient)" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="140" y="11" font-size="10" fill="#475569">Oxygenated</text>
        <rect x="230" y="0" width="14" height="14" fill="url(#valve-gradient)" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="250" y="11" font-size="10" fill="#475569">Valve</text>
        <rect x="305" y="0" width="14" height="14" fill="none" stroke="#dc2626" stroke-width="2" rx="3" />
        <text x="325" y="11" font-size="10" fill="#475569">Coronary</text>
        <rect x="400" y="0" width="14" height="14" fill="#818cf8" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="420" y="11" font-size="10" fill="#475569">Selected</text>
    </g>
    <text x="375" y="720" text-anchor="middle" font-size="10" fill="#94a3b8" font-family="system-ui, sans-serif">Click zones to select/deselect</text>
</svg>
