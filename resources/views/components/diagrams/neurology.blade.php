{{-- Brain Side-View Diagram --}}
{{-- Requires parent Alpine.js scope with: selectedZones[], toggleZone(id), zoneNotes{} --}}

<svg viewBox="0 0 800 650" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto" role="img" aria-label="Brain anatomy side-view diagram">
    <title>Brain Anatomy - Lateral View</title>

    <defs>
        {{-- Background gradient --}}
        <radialGradient id="neuro-bg" cx="40%" cy="35%" r="60%">
            <stop offset="0%" stop-color="#f8fafc" />
            <stop offset="100%" stop-color="#e2e8f0" />
        </radialGradient>

        {{-- Frontal lobe gradient --}}
        <linearGradient id="frontal-gradient" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#dbeafe" />
            <stop offset="50%" stop-color="#bfdbfe" />
            <stop offset="100%" stop-color="#93c5fd" />
        </linearGradient>

        {{-- Parietal lobe gradient --}}
        <linearGradient id="parietal-gradient" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#dcfce7" />
            <stop offset="50%" stop-color="#bbf7d0" />
            <stop offset="100%" stop-color="#86efac" />
        </linearGradient>

        {{-- Temporal lobe gradient --}}
        <linearGradient id="temporal-gradient" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#fef9c3" />
            <stop offset="50%" stop-color="#fde68a" />
            <stop offset="100%" stop-color="#fcd34d" />
        </linearGradient>

        {{-- Occipital lobe gradient --}}
        <linearGradient id="occipital-gradient" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#fce7f3" />
            <stop offset="50%" stop-color="#fbcfe8" />
            <stop offset="100%" stop-color="#f9a8d4" />
        </linearGradient>

        {{-- Cerebellum gradient --}}
        <linearGradient id="cerebellum-gradient" x1="0" y1="0" x2="0.5" y2="1">
            <stop offset="0%" stop-color="#ddd6fe" />
            <stop offset="50%" stop-color="#c4b5fd" />
            <stop offset="100%" stop-color="#a78bfa" />
        </linearGradient>

        {{-- Brainstem gradient --}}
        <linearGradient id="brainstem-gradient" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#ecfccb" />
            <stop offset="50%" stop-color="#d9f99d" />
            <stop offset="100%" stop-color="#bef264" />
        </linearGradient>

        {{-- Corpus callosum gradient --}}
        <linearGradient id="corpus-gradient" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#fecaca" />
            <stop offset="50%" stop-color="#fca5a5" />
            <stop offset="100%" stop-color="#fecaca" />
        </linearGradient>

        {{-- Thalamus gradient --}}
        <radialGradient id="thalamus-gradient" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="#fed7aa" />
            <stop offset="100%" stop-color="#fdba74" />
        </radialGradient>

        {{-- Hypothalamus gradient --}}
        <radialGradient id="hypothalamus-gradient" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="#fda4af" />
            <stop offset="100%" stop-color="#fb7185" />
        </radialGradient>

        {{-- Spinal cord gradient --}}
        <linearGradient id="spinal-gradient" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#a7f3d0" />
            <stop offset="100%" stop-color="#6ee7b7" />
        </linearGradient>

        {{-- Brain surface shadow --}}
        <filter id="brain-shadow" x="-3%" y="-3%" width="110%" height="110%">
            <feDropShadow dx="3" dy="4" stdDeviation="6" flood-color="#1e293b" flood-opacity="0.15" />
        </filter>
        <filter id="inner-shadow" x="-5%" y="-5%" width="110%" height="110%">
            <feDropShadow dx="1" dy="1" stdDeviation="2" flood-color="#1e293b" flood-opacity="0.1" />
        </filter>

        {{-- Gyri texture pattern --}}
        <filter id="brain-texture" x="0%" y="0%" width="100%" height="100%">
            <feTurbulence type="fractalNoise" baseFrequency="0.03" numOctaves="3" seed="5" result="noise" />
            <feColorMatrix type="saturate" values="0" in="noise" result="grey" />
            <feBlend mode="overlay" in="SourceGraphic" in2="grey" />
        </filter>
    </defs>

    {{-- Background --}}
    <rect width="800" height="650" fill="url(#neuro-bg)" rx="12" />

    {{-- Title --}}
    <text x="400" y="38" text-anchor="middle" font-size="22" font-weight="bold" fill="#1e293b" font-family="system-ui, sans-serif">Brain - Lateral View</text>
    <text x="400" y="56" text-anchor="middle" font-size="12" fill="#64748b" font-family="system-ui, sans-serif">Left Hemisphere with Medial Structures</text>

    {{-- ======================== BRAIN OUTLINE (shadow layer) ======================== --}}
    <path
        d="M 130 270 Q 118 215 138 160 Q 162 108 210 80
           Q 258 58 310 52 Q 365 48 410 55 Q 465 62 510 85
           Q 555 110 585 150 Q 610 185 620 230
           Q 630 280 625 330 Q 618 375 600 410
           Q 578 448 545 472 Q 510 492 470 500
           Q 430 505 390 500 Q 340 492 295 475
           Q 255 460 225 440 Q 195 418 175 390
           Q 155 360 142 325 Q 132 295 130 270 Z"
        fill="#94a3b8" opacity="0.08"
        filter="url(#brain-shadow)"
    />

    {{-- ======================== FRONTAL LOBE ======================== --}}
    <path
        d="M 130 270 Q 118 215 138 160 Q 162 108 210 80
           Q 258 58 310 52 Q 345 50 365 55
           L 342 95 L 318 145 L 292 210 L 272 275 L 255 325
           Q 210 318 178 300 Q 148 280 130 270 Z"
        :fill="selectedZones.includes('frontal_lobe') ? '#818cf8' : 'url(#frontal-gradient)'"
        stroke="#475569" stroke-width="2"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('frontal_lobe')"
        filter="url(#inner-shadow)"
    >
        <title>Frontal Lobe</title>
    </path>
    {{-- Frontal gyri (realistic folding pattern) --}}
    <g opacity="0.3">
        <path d="M 165 180 Q 195 172 225 178 Q 250 184 265 175" fill="none" stroke="#1e40af" stroke-width="1.2" stroke-linecap="round" />
        <path d="M 155 205 Q 185 195 220 200 Q 255 205 275 198" fill="none" stroke="#1e40af" stroke-width="1.2" stroke-linecap="round" />
        <path d="M 148 230 Q 180 222 215 228 Q 248 233 270 225" fill="none" stroke="#1e40af" stroke-width="1.2" stroke-linecap="round" />
        <path d="M 145 258 Q 175 250 208 255 Q 238 260 258 252" fill="none" stroke="#1e40af" stroke-width="1.1" stroke-linecap="round" />
        <path d="M 168 155 Q 198 148 228 154 Q 252 160 270 150" fill="none" stroke="#1e40af" stroke-width="1" stroke-linecap="round" />
        <path d="M 185 130 Q 215 122 248 128 Q 275 134 295 126" fill="none" stroke="#1e40af" stroke-width="0.9" stroke-linecap="round" />
        <path d="M 210 105 Q 240 98 270 104 Q 300 110 320 100" fill="none" stroke="#1e40af" stroke-width="0.8" stroke-linecap="round" />
        {{-- Precentral gyrus sulci --}}
        <path d="M 290 145 Q 282 175 275 210" fill="none" stroke="#1e40af" stroke-width="1" stroke-linecap="round" />
        <path d="M 268 155 Q 260 185 254 218" fill="none" stroke="#1e40af" stroke-width="0.8" stroke-linecap="round" />
    </g>
    <text x="215" y="195" text-anchor="middle" font-size="14" font-weight="700" fill="#1e293b">Frontal</text>
    <text x="215" y="213" text-anchor="middle" font-size="14" font-weight="700" fill="#1e293b">Lobe</text>

    {{-- ======================== PARIETAL LOBE ======================== --}}
    <path
        d="M 365 55 Q 410 50 460 60 Q 510 75 548 108
           Q 575 135 595 175 L 555 198 L 500 228
           L 435 265 L 370 302 L 320 330
           L 272 275 L 292 210 L 318 145 L 342 95 Z"
        :fill="selectedZones.includes('parietal_lobe') ? '#818cf8' : 'url(#parietal-gradient)'"
        stroke="#475569" stroke-width="2"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('parietal_lobe')"
        filter="url(#inner-shadow)"
    >
        <title>Parietal Lobe</title>
    </path>
    {{-- Parietal gyri --}}
    <g opacity="0.3">
        <path d="M 380 85 Q 415 78 448 85 Q 478 92 500 84" fill="none" stroke="#166534" stroke-width="1.1" stroke-linecap="round" />
        <path d="M 370 110 Q 408 102 445 108 Q 480 115 510 105" fill="none" stroke="#166534" stroke-width="1.2" stroke-linecap="round" />
        <path d="M 360 140 Q 400 132 440 138 Q 475 144 505 135" fill="none" stroke="#166534" stroke-width="1.2" stroke-linecap="round" />
        <path d="M 350 168 Q 392 160 430 165 Q 468 172 500 162" fill="none" stroke="#166534" stroke-width="1.2" stroke-linecap="round" />
        <path d="M 342 195 Q 382 188 418 193 Q 455 200 488 190" fill="none" stroke="#166534" stroke-width="1.1" stroke-linecap="round" />
        <path d="M 335 222 Q 372 215 408 220 Q 445 228 475 218" fill="none" stroke="#166534" stroke-width="1" stroke-linecap="round" />
        <path d="M 328 250 Q 365 242 398 248 Q 430 255 458 245" fill="none" stroke="#166534" stroke-width="0.9" stroke-linecap="round" />
        {{-- Postcentral gyrus sulcus --}}
        <path d="M 358 100 Q 348 140 340 180" fill="none" stroke="#166534" stroke-width="1" stroke-linecap="round" />
        <path d="M 378 95 Q 368 130 358 170" fill="none" stroke="#166534" stroke-width="0.8" stroke-linecap="round" />
    </g>
    <text x="440" y="155" text-anchor="middle" font-size="14" font-weight="700" fill="#1e293b">Parietal</text>
    <text x="440" y="173" text-anchor="middle" font-size="14" font-weight="700" fill="#1e293b">Lobe</text>

    {{-- ======================== TEMPORAL LOBE ======================== --}}
    <path
        d="M 255 330 Q 270 305 290 295 L 320 305 L 370 310 L 428 310
           Q 455 318 468 340 Q 482 368 478 400 Q 470 435 445 455
           Q 410 478 370 485 Q 320 492 280 480
           Q 248 468 230 445 Q 215 420 218 392
           Q 222 365 235 345 Q 245 335 255 330 Z"
        :fill="selectedZones.includes('temporal_lobe') ? '#818cf8' : 'url(#temporal-gradient)'"
        stroke="#475569" stroke-width="2"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('temporal_lobe')"
        filter="url(#inner-shadow)"
    >
        <title>Temporal Lobe</title>
    </path>
    {{-- Temporal gyri --}}
    <g opacity="0.3">
        <path d="M 270 335 Q 320 328 370 332 Q 410 338 440 330" fill="none" stroke="#92400e" stroke-width="1.2" stroke-linecap="round" />
        <path d="M 258 358 Q 310 350 362 355 Q 412 362 448 352" fill="none" stroke="#92400e" stroke-width="1.2" stroke-linecap="round" />
        <path d="M 248 382 Q 298 374 348 378 Q 400 385 440 375" fill="none" stroke="#92400e" stroke-width="1.1" stroke-linecap="round" />
        <path d="M 242 405 Q 290 398 338 402 Q 388 408 428 398" fill="none" stroke="#92400e" stroke-width="1" stroke-linecap="round" />
        <path d="M 248 428 Q 295 420 340 424 Q 385 430 415 420" fill="none" stroke="#92400e" stroke-width="0.9" stroke-linecap="round" />
        <path d="M 260 450 Q 305 442 345 446 Q 385 452 405 442" fill="none" stroke="#92400e" stroke-width="0.8" stroke-linecap="round" />
        {{-- Superior temporal sulcus --}}
        <path d="M 280 315 Q 340 310 400 315" fill="none" stroke="#92400e" stroke-width="1.1" stroke-linecap="round" />
    </g>
    <text x="350" y="402" text-anchor="middle" font-size="14" font-weight="700" fill="#1e293b">Temporal</text>
    <text x="350" y="420" text-anchor="middle" font-size="14" font-weight="700" fill="#1e293b">Lobe</text>

    {{-- ======================== OCCIPITAL LOBE ======================== --}}
    <path
        d="M 595 175 Q 612 210 618 255 Q 622 300 612 345
           Q 598 385 575 410 Q 548 435 518 450 Q 490 460 465 462
           L 478 400 Q 482 368 468 340 Q 455 318 428 310
           L 435 265 L 500 228 L 555 198 Z"
        :fill="selectedZones.includes('occipital_lobe') ? '#818cf8' : 'url(#occipital-gradient)'"
        stroke="#475569" stroke-width="2"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('occipital_lobe')"
        filter="url(#inner-shadow)"
    >
        <title>Occipital Lobe</title>
    </path>
    {{-- Occipital gyri --}}
    <g opacity="0.3">
        <path d="M 560 210 Q 575 225 580 248" fill="none" stroke="#9d174d" stroke-width="1.2" stroke-linecap="round" />
        <path d="M 548 235 Q 568 248 575 272" fill="none" stroke="#9d174d" stroke-width="1.1" stroke-linecap="round" />
        <path d="M 540 260 Q 560 270 568 295" fill="none" stroke="#9d174d" stroke-width="1.1" stroke-linecap="round" />
        <path d="M 535 288 Q 555 298 565 320" fill="none" stroke="#9d174d" stroke-width="1" stroke-linecap="round" />
        <path d="M 528 315 Q 548 328 558 348" fill="none" stroke="#9d174d" stroke-width="1" stroke-linecap="round" />
        <path d="M 518 340 Q 540 355 548 375" fill="none" stroke="#9d174d" stroke-width="0.9" stroke-linecap="round" />
        <path d="M 505 365 Q 525 378 535 398" fill="none" stroke="#9d174d" stroke-width="0.8" stroke-linecap="round" />
        {{-- Calcarine sulcus hint --}}
        <path d="M 575 310 Q 545 300 515 308" fill="none" stroke="#9d174d" stroke-width="1.2" stroke-linecap="round" />
    </g>
    <text x="555" y="300" text-anchor="middle" font-size="13" font-weight="700" fill="#1e293b">Occipital</text>
    <text x="555" y="318" text-anchor="middle" font-size="13" font-weight="700" fill="#1e293b">Lobe</text>

    {{-- ======================== CENTRAL SULCUS (Rolandic fissure) ======================== --}}
    <path d="M 348 72 Q 335 120 318 168 Q 305 210 290 258 Q 278 298 268 330"
        fill="none" stroke="#475569" stroke-width="2.5" opacity="0.45" stroke-linecap="round" />
    {{-- Central sulcus label --}}
    <text x="68" y="90" text-anchor="start" font-size="10" font-weight="600" fill="#475569">Central Sulcus</text>
    <line x1="138" y1="88" x2="330" y2="100" stroke="#94a3b8" stroke-width="0.8" stroke-dasharray="4,3" />

    {{-- ======================== LATERAL SULCUS (Sylvian fissure) ======================== --}}
    <path d="M 275 305 Q 330 300 380 308 Q 420 312 445 320"
        fill="none" stroke="#475569" stroke-width="2.5" opacity="0.45" stroke-linecap="round" />
    <text x="68" y="310" text-anchor="start" font-size="10" font-weight="600" fill="#475569">Lateral Sulcus</text>
    <line x1="140" y1="308" x2="280" y2="305" stroke="#94a3b8" stroke-width="0.8" stroke-dasharray="4,3" />

    {{-- ======================== PARIETO-OCCIPITAL SULCUS (hint) ======================== --}}
    <path d="M 540 108 Q 528 148 520 192 Q 512 238 508 268"
        fill="none" stroke="#475569" stroke-width="1.5" opacity="0.3" stroke-linecap="round" stroke-dasharray="5,4" />

    {{-- ======================== CORPUS CALLOSUM ======================== --}}
    <path
        d="M 248 325 Q 290 298 345 290 Q 410 285 465 295
           Q 490 300 500 310
           Q 475 318 420 322 Q 355 326 300 330 Q 270 332 255 332 Z"
        :fill="selectedZones.includes('corpus_callosum') ? '#818cf8' : 'url(#corpus-gradient)'"
        stroke="#991b1b" stroke-width="1.5"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('corpus_callosum')"
    >
        <title>Corpus Callosum</title>
    </path>
    {{-- Corpus callosum fiber lines --}}
    <path d="M 280 310 Q 340 302 400 305 Q 450 308 480 312" fill="none" stroke="#dc2626" stroke-width="0.5" opacity="0.25" />
    <path d="M 270 318 Q 335 310 400 313 Q 455 316 488 320" fill="none" stroke="#dc2626" stroke-width="0.5" opacity="0.2" />
    <text x="690" y="225" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Corpus</text>
    <text x="690" y="240" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Callosum</text>
    <line x1="685" y1="232" x2="500" y2="305" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== THALAMUS ======================== --}}
    <ellipse
        cx="410" cy="348" rx="35" ry="20"
        :fill="selectedZones.includes('thalamus') ? '#818cf8' : 'url(#thalamus-gradient)'"
        stroke="#92400e" stroke-width="1.5"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('thalamus')"
    >
        <title>Thalamus</title>
    </ellipse>
    {{-- Thalamic nuclei hint --}}
    <ellipse cx="400" cy="346" rx="12" ry="8" fill="none" stroke="#b45309" stroke-width="0.5" opacity="0.3" />
    <ellipse cx="420" cy="350" rx="10" ry="7" fill="none" stroke="#b45309" stroke-width="0.5" opacity="0.3" />
    <text x="690" y="340" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Thalamus</text>
    <line x1="685" y1="338" x2="448" y2="348" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== HYPOTHALAMUS ======================== --}}
    <ellipse
        cx="370" cy="382" rx="30" ry="16"
        :fill="selectedZones.includes('hypothalamus') ? '#818cf8' : 'url(#hypothalamus-gradient)'"
        stroke="#be123c" stroke-width="1.5"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('hypothalamus')"
    >
        <title>Hypothalamus</title>
    </ellipse>
    {{-- Pituitary stalk hint --}}
    <path d="M 365 398 L 360 415 Q 358 425 365 428 L 375 428 Q 382 425 380 415 L 375 398" fill="#fda4af" stroke="#be123c" stroke-width="0.8" opacity="0.5" />
    <text x="690" y="385" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Hypothalamus</text>
    <line x1="685" y1="383" x2="402" y2="382" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== BRAINSTEM ======================== --}}
    <path
        d="M 458 430 Q 472 442 478 465 Q 482 492 475 518
           Q 468 540 458 555 L 452 560
           Q 442 555 435 540 Q 428 518 425 492
           Q 422 465 430 442 Q 438 430 450 425 Z"
        :fill="selectedZones.includes('brainstem') ? '#818cf8' : 'url(#brainstem-gradient)'"
        stroke="#475569" stroke-width="2"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('brainstem')"
        filter="url(#inner-shadow)"
    >
        <title>Brainstem</title>
    </path>
    {{-- Brainstem section divisions --}}
    {{-- Midbrain --}}
    <path d="M 432 448 Q 452 445 475 450" fill="none" stroke="#475569" stroke-width="0.8" opacity="0.35" />
    <text x="488" y="445" font-size="8" fill="#64748b" opacity="0.7">Midbrain</text>
    {{-- Pons --}}
    <path d="M 428 478 Q 452 475 480 480" fill="none" stroke="#475569" stroke-width="0.8" opacity="0.35" />
    <text x="488" y="475" font-size="8" fill="#64748b" opacity="0.7">Pons</text>
    {{-- Medulla oblongata --}}
    <path d="M 430 508 Q 452 505 478 510" fill="none" stroke="#475569" stroke-width="0.8" opacity="0.35" />
    <text x="488" y="512" font-size="8" fill="#64748b" opacity="0.7">Medulla</text>
    {{-- Brainstem internal detail --}}
    <path d="M 445 440 Q 452 450 455 462" fill="none" stroke="#4d7c0f" stroke-width="0.6" opacity="0.2" />
    <path d="M 440 465 Q 448 475 450 490" fill="none" stroke="#4d7c0f" stroke-width="0.6" opacity="0.2" />
    <text x="690" y="480" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Brainstem</text>
    <line x1="685" y1="478" x2="482" y2="490" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== CEREBELLUM ======================== --}}
    <path
        d="M 500 425 Q 530 412 555 400 Q 580 390 600 398
           Q 620 408 628 432 Q 635 462 625 492
           Q 612 520 590 535 Q 562 548 530 548
           Q 498 545 475 532 Q 455 518 448 498
           Q 442 475 450 452 Q 458 435 478 425 Z"
        :fill="selectedZones.includes('cerebellum') ? '#818cf8' : 'url(#cerebellum-gradient)'"
        stroke="#475569" stroke-width="2"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('cerebellum')"
        filter="url(#inner-shadow)"
    >
        <title>Cerebellum</title>
    </path>
    {{-- Cerebellar folia (layered folding pattern) --}}
    <g opacity="0.35">
        <path d="M 475 445 Q 520 435 565 440 Q 600 445 620 440" fill="none" stroke="#5b21b6" stroke-width="1.1" stroke-linecap="round" />
        <path d="M 468 460 Q 515 450 562 455 Q 600 460 625 455" fill="none" stroke="#5b21b6" stroke-width="1.1" stroke-linecap="round" />
        <path d="M 462 475 Q 512 465 560 470 Q 598 475 622 470" fill="none" stroke="#5b21b6" stroke-width="1.1" stroke-linecap="round" />
        <path d="M 458 490 Q 508 480 555 485 Q 595 490 618 485" fill="none" stroke="#5b21b6" stroke-width="1" stroke-linecap="round" />
        <path d="M 460 505 Q 505 495 548 500 Q 585 505 610 500" fill="none" stroke="#5b21b6" stroke-width="1" stroke-linecap="round" />
        <path d="M 465 518 Q 505 510 542 514 Q 575 518 598 513" fill="none" stroke="#5b21b6" stroke-width="0.9" stroke-linecap="round" />
        <path d="M 475 530 Q 510 523 535 526 Q 565 530 585 525" fill="none" stroke="#5b21b6" stroke-width="0.8" stroke-linecap="round" />
        {{-- Arbor vitae (tree of life) central line --}}
        <path d="M 540 420 Q 538 450 535 480 Q 530 510 525 535" fill="none" stroke="#5b21b6" stroke-width="1.2" stroke-linecap="round" />
        <path d="M 535 450 Q 510 458 490 462" fill="none" stroke="#5b21b6" stroke-width="0.7" stroke-linecap="round" />
        <path d="M 535 450 Q 558 458 578 455" fill="none" stroke="#5b21b6" stroke-width="0.7" stroke-linecap="round" />
        <path d="M 532 480 Q 505 488 485 490" fill="none" stroke="#5b21b6" stroke-width="0.7" stroke-linecap="round" />
        <path d="M 532 480 Q 560 488 585 485" fill="none" stroke="#5b21b6" stroke-width="0.7" stroke-linecap="round" />
    </g>
    <text x="540" y="485" text-anchor="middle" font-size="13" font-weight="700" fill="#1e293b">Cerebellum</text>

    {{-- ======================== SPINAL CORD ======================== --}}
    <path
        d="M 455 558 Q 458 572 460 590 L 458 618
           Q 457 628 452 628 Q 447 628 446 618
           L 444 590 Q 444 572 448 558 Z"
        :fill="selectedZones.includes('spinal_cord') ? '#818cf8' : 'url(#spinal-gradient)'"
        stroke="#475569" stroke-width="1.5"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('spinal_cord')"
    >
        <title>Spinal Cord</title>
    </path>
    {{-- Spinal cord central canal --}}
    <line x1="452" y1="562" x2="452" y2="620" stroke="#065f46" stroke-width="0.5" opacity="0.3" />
    {{-- Nerve roots hint --}}
    <path d="M 444 580 L 432 585" stroke="#065f46" stroke-width="0.8" opacity="0.25" fill="none" />
    <path d="M 460 580 L 472 585" fill="none" stroke="#065f46" stroke-width="0.8" opacity="0.25" />
    <path d="M 444 600 L 430 608" stroke="#065f46" stroke-width="0.8" opacity="0.25" />
    <path d="M 460 600 L 474 608" stroke="#065f46" stroke-width="0.8" opacity="0.25" />
    <text x="690" y="525" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Spinal Cord</text>
    <line x1="685" y1="523" x2="462" y2="595" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== ADDITIONAL ANATOMICAL LABELS ======================== --}}
    {{-- Precentral gyrus (motor cortex) --}}
    <text x="68" y="160" text-anchor="start" font-size="9" fill="#64748b" font-style="italic">Precentral Gyrus</text>
    <text x="68" y="172" text-anchor="start" font-size="9" fill="#64748b" font-style="italic">(Motor Cortex)</text>
    <line x1="155" y1="165" x2="298" y2="155" stroke="#94a3b8" stroke-width="0.6" stroke-dasharray="3,3" />

    {{-- Postcentral gyrus (sensory cortex) --}}
    <text x="68" y="200" text-anchor="start" font-size="9" fill="#64748b" font-style="italic">Postcentral Gyrus</text>
    <text x="68" y="212" text-anchor="start" font-size="9" fill="#64748b" font-style="italic">(Sensory Cortex)</text>
    <line x1="165" y1="205" x2="352" y2="140" stroke="#94a3b8" stroke-width="0.6" stroke-dasharray="3,3" />

    {{-- Broca's area --}}
    <text x="68" y="260" text-anchor="start" font-size="9" fill="#64748b" font-style="italic">Broca's Area</text>
    <line x1="125" y1="258" x2="230" y2="290" stroke="#94a3b8" stroke-width="0.6" stroke-dasharray="3,3" />

    {{-- Wernicke's area --}}
    <text x="68" y="360" text-anchor="start" font-size="9" fill="#64748b" font-style="italic">Wernicke's Area</text>
    <line x1="148" y1="358" x2="340" y2="340" stroke="#94a3b8" stroke-width="0.6" stroke-dasharray="3,3" />

    {{-- Visual cortex --}}
    <text x="690" y="420" text-anchor="start" font-size="9" fill="#64748b" font-style="italic">Visual Cortex</text>
    <line x1="685" y1="418" x2="595" y2="380" stroke="#94a3b8" stroke-width="0.6" stroke-dasharray="3,3" />

    {{-- ======================== DIRECTIONAL LABELS ======================== --}}
    <text x="130" y="500" text-anchor="middle" font-size="10" fill="#94a3b8" font-style="italic">Anterior</text>
    <text x="650" y="560" text-anchor="middle" font-size="10" fill="#94a3b8" font-style="italic">Posterior</text>
    <text x="375" y="75" text-anchor="middle" font-size="10" fill="#94a3b8" font-style="italic">Superior</text>
    <text x="452" y="645" text-anchor="middle" font-size="10" fill="#94a3b8" font-style="italic">Inferior</text>

    {{-- ======================== LEGEND ======================== --}}
    <g transform="translate(20, 600)">
        <rect x="0" y="0" width="14" height="14" fill="url(#frontal-gradient)" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="19" y="11" font-size="9" fill="#475569">Frontal</text>
        <rect x="75" y="0" width="14" height="14" fill="url(#parietal-gradient)" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="94" y="11" font-size="9" fill="#475569">Parietal</text>
        <rect x="155" y="0" width="14" height="14" fill="url(#temporal-gradient)" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="174" y="11" font-size="9" fill="#475569">Temporal</text>
        <rect x="240" y="0" width="14" height="14" fill="url(#occipital-gradient)" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="259" y="11" font-size="9" fill="#475569">Occipital</text>
        <rect x="325" y="0" width="14" height="14" fill="url(#cerebellum-gradient)" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="344" y="11" font-size="9" fill="#475569">Cerebellum</text>
        <rect x="420" y="0" width="14" height="14" fill="url(#brainstem-gradient)" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="439" y="11" font-size="9" fill="#475569">Brainstem</text>
        <rect x="510" y="0" width="14" height="14" fill="#818cf8" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="529" y="11" font-size="9" fill="#475569">Selected</text>
    </g>
    <text x="400" y="640" text-anchor="middle" font-size="10" fill="#94a3b8" font-family="system-ui, sans-serif">Click zones to select/deselect</text>
</svg>
