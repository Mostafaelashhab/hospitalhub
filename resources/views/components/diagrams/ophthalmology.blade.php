{{-- Eye Cross-Section Diagram --}}
{{-- Requires parent Alpine.js scope with: selectedZones[], toggleZone(id), zoneNotes{} --}}

<svg viewBox="0 0 850 600" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto" role="img" aria-label="Eye cross-section diagram">
    <title>Eye Cross-Section Diagram</title>

    <defs>
        {{-- Background gradient --}}
        <radialGradient id="eye-bg" cx="45%" cy="45%" r="55%">
            <stop offset="0%" stop-color="#f8fafc" />
            <stop offset="100%" stop-color="#e2e8f0" />
        </radialGradient>

        {{-- Sclera gradient (outer white shell) --}}
        <radialGradient id="sclera-gradient" cx="45%" cy="45%" r="55%">
            <stop offset="0%" stop-color="#f8fafc" />
            <stop offset="70%" stop-color="#f1f5f9" />
            <stop offset="100%" stop-color="#e2e8f0" />
        </radialGradient>

        {{-- Choroid layer gradient (dark vascular layer) --}}
        <linearGradient id="choroid-gradient" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#7c2d12" />
            <stop offset="50%" stop-color="#92400e" />
            <stop offset="100%" stop-color="#7c2d12" />
        </linearGradient>

        {{-- Retina gradient --}}
        <linearGradient id="retina-gradient" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#fef3c7" />
            <stop offset="50%" stop-color="#fde68a" />
            <stop offset="100%" stop-color="#fef3c7" />
        </linearGradient>

        {{-- Vitreous humor gradient --}}
        <radialGradient id="vitreous-gradient" cx="55%" cy="50%" r="50%">
            <stop offset="0%" stop-color="#e0f2fe" stop-opacity="0.6" />
            <stop offset="60%" stop-color="#bae6fd" stop-opacity="0.35" />
            <stop offset="100%" stop-color="#7dd3fc" stop-opacity="0.2" />
        </radialGradient>

        {{-- Aqueous humor gradient --}}
        <radialGradient id="aqueous-gradient" cx="40%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#cffafe" stop-opacity="0.7" />
            <stop offset="100%" stop-color="#a5f3fc" stop-opacity="0.4" />
        </radialGradient>

        {{-- Cornea gradient (transparent dome) --}}
        <linearGradient id="cornea-gradient" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#dbeafe" stop-opacity="0.85" />
            <stop offset="50%" stop-color="#bfdbfe" stop-opacity="0.7" />
            <stop offset="100%" stop-color="#93c5fd" stop-opacity="0.5" />
        </linearGradient>

        {{-- Lens gradient (biconvex refraction) --}}
        <radialGradient id="lens-gradient" cx="45%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#f5f3ff" stop-opacity="0.95" />
            <stop offset="40%" stop-color="#ede9fe" stop-opacity="0.85" />
            <stop offset="80%" stop-color="#ddd6fe" stop-opacity="0.7" />
            <stop offset="100%" stop-color="#c4b5fd" stop-opacity="0.6" />
        </radialGradient>

        {{-- Iris gradient --}}
        <radialGradient id="iris-gradient" cx="30%" cy="50%" r="70%">
            <stop offset="0%" stop-color="#6ee7b7" />
            <stop offset="50%" stop-color="#34d399" />
            <stop offset="100%" stop-color="#059669" />
        </radialGradient>

        {{-- Optic nerve gradient --}}
        <linearGradient id="optic-nerve-gradient" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#fcd34d" />
            <stop offset="100%" stop-color="#f59e0b" />
        </linearGradient>

        {{-- Ciliary body gradient --}}
        <linearGradient id="ciliary-gradient" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#a78bfa" />
            <stop offset="100%" stop-color="#7c3aed" />
        </linearGradient>

        {{-- Macula gradient --}}
        <radialGradient id="macula-gradient" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="#fbbf24" />
            <stop offset="100%" stop-color="#f59e0b" />
        </radialGradient>

        {{-- Conjunctiva gradient --}}
        <linearGradient id="conjunctiva-gradient" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#fce7f3" />
            <stop offset="100%" stop-color="#fbcfe8" />
        </linearGradient>

        {{-- Drop shadow --}}
        <filter id="eye-shadow" x="-5%" y="-5%" width="112%" height="112%">
            <feDropShadow dx="2" dy="3" stdDeviation="5" flood-color="#1e293b" flood-opacity="0.15" />
        </filter>
        <filter id="soft-glow" x="-10%" y="-10%" width="120%" height="120%">
            <feGaussianBlur in="SourceGraphic" stdDeviation="1.5" />
        </filter>

        {{-- Clip path for eye interior --}}
        <clipPath id="eye-outline-clip">
            <path d="M 175 155 Q 140 200 130 300 Q 140 400 175 445
                     Q 220 490 310 500 Q 500 510 590 460
                     Q 640 425 660 370 Q 680 310 670 280
                     Q 660 220 640 180 Q 600 130 540 110
                     Q 470 90 380 90 Q 290 95 230 120
                     Q 195 135 175 155 Z" />
        </clipPath>

        {{-- Clip for back half of eye --}}
        <clipPath id="back-half-clip">
            <rect x="350" y="70" width="400" height="480" />
        </clipPath>

        {{-- Clip for front structures --}}
        <clipPath id="front-clip">
            <rect x="100" y="70" width="280" height="480" />
        </clipPath>

        {{-- Blood vessel pattern for choroid --}}
        <pattern id="choroid-vessels" width="18" height="18" patternUnits="userSpaceOnUse">
            <path d="M 0 9 Q 5 6 9 9 Q 13 12 18 9" fill="none" stroke="#dc2626" stroke-width="0.4" opacity="0.3" />
            <path d="M 3 3 Q 7 0 11 3 Q 15 6 18 3" fill="none" stroke="#dc2626" stroke-width="0.3" opacity="0.2" />
        </pattern>
    </defs>

    {{-- Background --}}
    <rect width="850" height="600" fill="url(#eye-bg)" rx="12" />

    {{-- Title --}}
    <text x="425" y="38" text-anchor="middle" font-size="22" font-weight="bold" fill="#1e293b" font-family="system-ui, sans-serif">Eye Cross-Section</text>
    <text x="425" y="56" text-anchor="middle" font-size="12" fill="#64748b" font-family="system-ui, sans-serif">Horizontal Section - Right Eye</text>

    {{-- ======================== SCLERA (outer shell) ======================== --}}
    <path
        d="M 175 155 Q 140 200 130 300 Q 140 400 175 445
           Q 220 490 310 500 Q 500 510 590 460
           Q 640 425 660 370 Q 680 310 670 280
           Q 660 220 640 180 Q 600 130 540 110
           Q 470 90 380 90 Q 290 95 230 120
           Q 195 135 175 155 Z"
        :fill="selectedZones.includes('sclera') ? '#818cf8' : 'url(#sclera-gradient)'"
        stroke="#64748b" stroke-width="2.5"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('sclera')"
        filter="url(#eye-shadow)"
    >
        <title>Sclera</title>
    </path>
    {{-- Sclera thickness line (inner boundary) --}}
    <path
        d="M 192 170 Q 162 210 152 300 Q 162 390 192 430
           Q 232 470 315 478 Q 490 486 572 442
           Q 618 410 636 355 Q 652 305 645 275
           Q 638 225 620 192 Q 585 148 528 128
           Q 465 110 380 110 Q 300 115 245 138
           Q 210 150 192 170 Z"
        fill="none" stroke="#94a3b8" stroke-width="1" opacity="0.5"
    />
    <text x="705" y="200" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Sclera</text>
    <line x1="700" y1="198" x2="660" y2="220" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== CHOROID (vascular layer between sclera and retina) ======================== --}}
    <path
        d="M 310 118 Q 470 100 545 128 Q 610 155 632 200
           Q 650 240 650 280 Q 648 340 630 380 Q 605 430 555 455
           Q 490 485 380 482 Q 310 478 280 465
           L 290 460 Q 330 470 385 472 Q 485 475 545 445
           Q 595 420 618 372 Q 638 330 638 280
           Q 636 235 622 200 Q 600 158 540 135
           Q 470 110 315 128 Z"
        :fill="selectedZones.includes('choroid') ? '#818cf8' : 'url(#choroid-gradient)'"
        stroke="#475569" stroke-width="0.8"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('choroid')"
        clip-path="url(#eye-outline-clip)"
        opacity="0.7"
    >
        <title>Choroid</title>
    </path>
    {{-- Choroid vessel texture --}}
    <path
        d="M 310 118 Q 470 100 545 128 Q 610 155 632 200
           Q 650 240 650 280 Q 648 340 630 380 Q 605 430 555 455
           Q 490 485 380 482 Q 310 478 280 465
           L 290 460 Q 330 470 385 472 Q 485 475 545 445
           Q 595 420 618 372 Q 638 330 638 280
           Q 636 235 622 200 Q 600 158 540 135
           Q 470 110 315 128 Z"
        fill="url(#choroid-vessels)"
        clip-path="url(#eye-outline-clip)"
        opacity="0.5"
    />
    <text x="705" y="240" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Choroid</text>
    <line x1="700" y1="238" x2="645" y2="250" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== RETINA (inner lining) ======================== --}}
    <path
        d="M 290 132 Q 460 112 535 140 Q 595 165 618 208
           Q 638 248 638 288 Q 636 338 618 378 Q 595 418 548 445
           Q 485 472 375 470 Q 305 468 275 455
           L 284 448 Q 325 458 378 460 Q 478 462 540 438
           Q 588 412 608 370 Q 626 330 626 288
           Q 624 244 608 210 Q 588 172 535 150
           Q 462 122 295 142 Z"
        :fill="selectedZones.includes('retina') ? '#818cf8' : 'url(#retina-gradient)'"
        stroke="#92400e" stroke-width="0.8"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('retina')"
        clip-path="url(#eye-outline-clip)"
        opacity="0.8"
    >
        <title>Retina</title>
    </path>
    {{-- Retinal layer detail lines --}}
    <path d="M 520 150 Q 580 180 610 220" fill="none" stroke="#b45309" stroke-width="0.4" opacity="0.3" />
    <path d="M 530 155 Q 590 185 615 228" fill="none" stroke="#b45309" stroke-width="0.4" opacity="0.3" />
    <path d="M 610 360 Q 580 400 530 430" fill="none" stroke="#b45309" stroke-width="0.4" opacity="0.3" />
    <path d="M 615 350 Q 590 395 535 425" fill="none" stroke="#b45309" stroke-width="0.4" opacity="0.3" />
    <text x="705" y="340" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Retina</text>
    <line x1="700" y1="338" x2="630" y2="348" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== VITREOUS HUMOR ======================== --}}
    <ellipse
        cx="430" cy="295" rx="190" ry="155"
        :fill="selectedZones.includes('vitreous') ? '#818cf8' : 'url(#vitreous-gradient)'"
        stroke="none"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('vitreous')"
        clip-path="url(#eye-outline-clip)"
    >
        <title>Vitreous Humor</title>
    </ellipse>
    {{-- Vitreous floater hints --}}
    <circle cx="400" cy="260" r="1.5" fill="#94a3b8" opacity="0.15" />
    <circle cx="440" cy="320" r="1" fill="#94a3b8" opacity="0.12" />
    <circle cx="480" cy="280" r="1.2" fill="#94a3b8" opacity="0.1" />
    <text x="440" y="340" text-anchor="middle" font-size="12" font-weight="600" fill="#334155" opacity="0.7">Vitreous</text>
    <text x="440" y="356" text-anchor="middle" font-size="12" font-weight="600" fill="#334155" opacity="0.7">Humor</text>

    {{-- ======================== MACULA ======================== --}}
    <ellipse
        cx="610" cy="295" rx="26" ry="22"
        :fill="selectedZones.includes('macula') ? '#818cf8' : 'url(#macula-gradient)'"
        stroke="#92400e" stroke-width="1.5"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('macula')"
    >
        <title>Macula</title>
    </ellipse>
    {{-- Fovea (pit in center of macula) --}}
    <ellipse cx="610" cy="295" rx="8" ry="6" fill="#f59e0b" opacity="0.6" stroke="#92400e" stroke-width="0.5" />
    <text x="705" y="290" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Macula</text>
    <line x1="700" y1="288" x2="638" y2="293" stroke="#94a3b8" stroke-width="0.8" />
    <text x="705" y="305" text-anchor="start" font-size="9" fill="#64748b">(Fovea centralis)</text>

    {{-- ======================== OPTIC NERVE ======================== --}}
    <g class="cursor-pointer" @click="toggleZone('optic_nerve')">
        {{-- Optic disc (where nerve exits) --}}
        <ellipse cx="640" cy="295" rx="14" ry="20"
            :fill="selectedZones.includes('optic_nerve') ? '#818cf8' : '#fef3c7'"
            stroke="#92400e" stroke-width="1"
        />
        {{-- Optic nerve trunk --}}
        <path
            d="M 652 278 Q 672 270 695 260 L 730 248 Q 745 242 755 248
               L 755 258 Q 752 268 738 272 L 700 282 Q 680 290 670 300
               L 700 310 Q 738 320 752 328 L 755 338 Q 755 348 745 342
               L 730 336 Q 695 325 672 318 L 652 312 Q 648 305 648 295
               Q 648 285 652 278 Z"
            :fill="selectedZones.includes('optic_nerve') ? '#818cf8' : 'url(#optic-nerve-gradient)'"
            stroke="#92400e" stroke-width="1.2"
            class="transition-all duration-300 ease-out"
        >
            <title>Optic Nerve</title>
        </path>
        {{-- Nerve fiber detail --}}
        <path d="M 658 282 Q 690 272 720 256" fill="none" stroke="#92400e" stroke-width="0.4" opacity="0.3" />
        <path d="M 658 290 Q 690 285 720 276" fill="none" stroke="#92400e" stroke-width="0.4" opacity="0.3" />
        <path d="M 658 300 Q 690 305 720 316" fill="none" stroke="#92400e" stroke-width="0.4" opacity="0.3" />
        <path d="M 658 308 Q 690 318 720 330" fill="none" stroke="#92400e" stroke-width="0.4" opacity="0.3" />
        {{-- Optic nerve sheath --}}
        <path d="M 654 272 Q 675 262 700 252 L 738 240" fill="none" stroke="#78350f" stroke-width="0.6" opacity="0.25" />
        <path d="M 654 318 Q 675 330 700 340 L 738 350" fill="none" stroke="#78350f" stroke-width="0.6" opacity="0.25" />
    </g>
    <text x="770" y="290" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Optic</text>
    <text x="770" y="305" text-anchor="start" font-size="11" font-weight="600" fill="#334155">Nerve</text>

    {{-- ======================== CONJUNCTIVA ======================== --}}
    <path
        d="M 185 160 Q 155 195 145 260 Q 148 325 155 355 Q 165 395 185 435
           Q 180 425 172 405 Q 158 370 152 330 Q 148 295 148 260
           Q 150 210 162 180 Q 170 168 180 158 Z"
        :fill="selectedZones.includes('conjunctiva') ? '#818cf8' : 'url(#conjunctiva-gradient)'"
        stroke="#475569" stroke-width="0.8"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('conjunctiva')"
        clip-path="url(#eye-outline-clip)"
    >
        <title>Conjunctiva</title>
    </path>
    <text x="60" y="180" text-anchor="middle" font-size="11" font-weight="600" fill="#334155">Conjunctiva</text>
    <line x1="92" y1="178" x2="158" y2="195" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== CORNEA ======================== --}}
    <path
        d="M 175 155 Q 138 200 128 300 Q 138 400 175 445
           Q 160 430 148 400 Q 135 365 130 320
           Q 128 300 130 280 Q 135 235 148 200
           Q 160 172 175 155 Z"
        :fill="selectedZones.includes('cornea') ? '#818cf8' : 'url(#cornea-gradient)'"
        stroke="#2563eb" stroke-width="2"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('cornea')"
        opacity="0.9"
    >
        <title>Cornea</title>
    </path>
    {{-- Corneal layers hint --}}
    <path d="M 168 175 Q 150 215 145 300 Q 150 385 168 425" fill="none" stroke="#3b82f6" stroke-width="0.5" opacity="0.35" />
    <path d="M 160 185 Q 142 225 138 300 Q 142 375 160 415" fill="none" stroke="#3b82f6" stroke-width="0.5" opacity="0.25" />
    {{-- Corneal light reflex --}}
    <path d="M 162 210 Q 155 240 153 270" fill="none" stroke="white" stroke-width="1.5" opacity="0.4" stroke-linecap="round" />
    <text x="60" y="300" text-anchor="middle" font-size="11" font-weight="600" fill="#334155">Cornea</text>
    <line x1="80" y1="298" x2="132" y2="298" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== AQUEOUS HUMOR (anterior chamber) ======================== --}}
    <path
        d="M 168 200 Q 165 240 165 295 Q 165 350 168 395
           L 185 395 Q 190 350 192 295 Q 190 240 185 200 Z"
        :fill="selectedZones.includes('aqueous') ? '#818cf8' : 'url(#aqueous-gradient)'"
        stroke="none"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('aqueous')"
        clip-path="url(#eye-outline-clip)"
    >
        <title>Aqueous Humor</title>
    </path>
    <text x="60" y="240" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Aqueous</text>
    <text x="60" y="253" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Humor</text>
    <line x1="88" y1="246" x2="168" y2="265" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== IRIS ======================== --}}
    {{-- Upper iris --}}
    <path
        d="M 200 180 Q 190 200 186 235 L 196 235 Q 200 208 208 192 Z"
        :fill="selectedZones.includes('iris') ? '#818cf8' : 'url(#iris-gradient)'"
        stroke="#065f46" stroke-width="1.2"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('iris')"
    >
        <title>Iris (upper)</title>
    </path>
    {{-- Lower iris --}}
    <path
        d="M 200 420 Q 190 400 186 365 L 196 365 Q 200 392 208 408 Z"
        :fill="selectedZones.includes('iris') ? '#818cf8' : 'url(#iris-gradient)'"
        stroke="#065f46" stroke-width="1.2"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('iris')"
    >
        <title>Iris (lower)</title>
    </path>
    {{-- Iris radial muscle fiber pattern --}}
    <line x1="192" y1="198" x2="194" y2="232" stroke="#065f46" stroke-width="0.4" opacity="0.3" />
    <line x1="196" y1="195" x2="196" y2="232" stroke="#065f46" stroke-width="0.4" opacity="0.3" />
    <line x1="200" y1="192" x2="198" y2="234" stroke="#065f46" stroke-width="0.4" opacity="0.3" />
    <line x1="192" y1="402" x2="194" y2="368" stroke="#065f46" stroke-width="0.4" opacity="0.3" />
    <line x1="196" y1="405" x2="196" y2="368" stroke="#065f46" stroke-width="0.4" opacity="0.3" />
    <line x1="200" y1="408" x2="198" y2="366" stroke="#065f46" stroke-width="0.4" opacity="0.3" />
    <text x="60" y="360" text-anchor="middle" font-size="11" font-weight="600" fill="#334155">Iris</text>
    <line x1="76" y1="358" x2="188" y2="380" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== PUPIL ======================== --}}
    <ellipse
        cx="192" cy="300" rx="9" ry="62"
        :fill="selectedZones.includes('pupil') ? '#818cf8' : '#0f172a'"
        stroke="#1e293b" stroke-width="1"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('pupil')"
    >
        <title>Pupil</title>
    </ellipse>
    {{-- Pupil light reflection --}}
    <ellipse cx="190" cy="278" rx="3" ry="8" fill="white" opacity="0.15" />
    <text x="60" y="410" text-anchor="middle" font-size="11" font-weight="600" fill="#334155">Pupil</text>
    <line x1="76" y1="408" x2="185" y2="348" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== CILIARY BODY ======================== --}}
    {{-- Upper ciliary body --}}
    <path
        d="M 210 165 Q 215 155 228 150 L 258 142
           Q 268 140 270 148 L 260 165
           Q 250 175 238 182 Q 225 188 215 185 Z"
        :fill="selectedZones.includes('ciliary_body') ? '#818cf8' : 'url(#ciliary-gradient)'"
        stroke="#4c1d95" stroke-width="1"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('ciliary_body')"
        clip-path="url(#eye-outline-clip)"
    >
        <title>Ciliary Body</title>
    </path>
    {{-- Lower ciliary body --}}
    <path
        d="M 210 435 Q 215 445 228 450 L 258 458
           Q 268 460 270 452 L 260 435
           Q 250 425 238 418 Q 225 412 215 415 Z"
        :fill="selectedZones.includes('ciliary_body') ? '#818cf8' : 'url(#ciliary-gradient)'"
        stroke="#4c1d95" stroke-width="1"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('ciliary_body')"
        clip-path="url(#eye-outline-clip)"
    >
        <title>Ciliary Body</title>
    </path>
    {{-- Ciliary processes (finger-like projections) --}}
    <path d="M 228 155 L 222 168" stroke="#6d28d9" stroke-width="0.8" opacity="0.4" />
    <path d="M 238 150 L 232 165" stroke="#6d28d9" stroke-width="0.8" opacity="0.4" />
    <path d="M 248 147 L 242 162" stroke="#6d28d9" stroke-width="0.8" opacity="0.4" />
    <path d="M 228 445 L 222 432" stroke="#6d28d9" stroke-width="0.8" opacity="0.4" />
    <path d="M 238 450 L 232 435" stroke="#6d28d9" stroke-width="0.8" opacity="0.4" />
    <path d="M 248 453 L 242 438" stroke="#6d28d9" stroke-width="0.8" opacity="0.4" />
    {{-- Zonular fibers (suspensory ligaments) connecting ciliary body to lens --}}
    <line x1="222" y1="182" x2="232" y2="210" stroke="#6d28d9" stroke-width="0.5" opacity="0.3" stroke-dasharray="3,2" />
    <line x1="230" y1="178" x2="238" y2="215" stroke="#6d28d9" stroke-width="0.5" opacity="0.3" stroke-dasharray="3,2" />
    <line x1="240" y1="175" x2="244" y2="218" stroke="#6d28d9" stroke-width="0.5" opacity="0.3" stroke-dasharray="3,2" />
    <line x1="222" y1="418" x2="232" y2="390" stroke="#6d28d9" stroke-width="0.5" opacity="0.3" stroke-dasharray="3,2" />
    <line x1="230" y1="422" x2="238" y2="385" stroke="#6d28d9" stroke-width="0.5" opacity="0.3" stroke-dasharray="3,2" />
    <line x1="240" y1="425" x2="244" y2="382" stroke="#6d28d9" stroke-width="0.5" opacity="0.3" stroke-dasharray="3,2" />
    <text x="60" y="140" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Ciliary</text>
    <text x="60" y="153" text-anchor="middle" font-size="10" font-weight="600" fill="#334155">Body</text>
    <line x1="85" y1="146" x2="215" y2="162" stroke="#94a3b8" stroke-width="0.8" />

    {{-- ======================== LENS ======================== --}}
    <ellipse
        cx="242" cy="300" rx="32" ry="70"
        :fill="selectedZones.includes('lens') ? '#818cf8' : 'url(#lens-gradient)'"
        stroke="#6366f1" stroke-width="1.5"
        class="cursor-pointer transition-all duration-300 ease-out"
        @click="toggleZone('lens')"
        opacity="0.88"
    >
        <title>Lens</title>
    </ellipse>
    {{-- Lens capsule highlight --}}
    <ellipse cx="238" cy="295" rx="24" ry="55" fill="none" stroke="white" stroke-width="0.8" opacity="0.25" />
    {{-- Lens fiber layers --}}
    <ellipse cx="242" cy="300" rx="18" ry="45" fill="none" stroke="#a5b4fc" stroke-width="0.4" opacity="0.4" />
    <ellipse cx="242" cy="300" rx="10" ry="28" fill="none" stroke="#a5b4fc" stroke-width="0.3" opacity="0.3" />
    {{-- Lens nucleus --}}
    <ellipse cx="242" cy="300" rx="6" ry="14" fill="#c7d2fe" opacity="0.3" />
    <text x="242" y="303" text-anchor="middle" font-size="11" font-weight="600" fill="#334155">Lens</text>

    {{-- ======================== RETINAL BLOOD VESSELS (decorative) ======================== --}}
    <g opacity="0.3" clip-path="url(#back-half-clip)">
        {{-- Central retinal artery branches --}}
        <path d="M 640 290 Q 590 270 550 250 Q 510 235 470 228" fill="none" stroke="#dc2626" stroke-width="1.2" />
        <path d="M 640 300 Q 590 320 550 340 Q 510 355 470 362" fill="none" stroke="#dc2626" stroke-width="1.2" />
        <path d="M 550 250 Q 530 245 500 248" fill="none" stroke="#dc2626" stroke-width="0.8" />
        <path d="M 550 340 Q 530 348 500 345" fill="none" stroke="#dc2626" stroke-width="0.8" />
        {{-- Central retinal vein branches --}}
        <path d="M 640 288 Q 585 260 540 240 Q 500 225 460 222" fill="none" stroke="#1d4ed8" stroke-width="1" />
        <path d="M 640 302 Q 585 330 540 350 Q 500 365 460 368" fill="none" stroke="#1d4ed8" stroke-width="1" />
    </g>

    {{-- ======================== LIGHT RAY PATH (decorative) ======================== --}}
    <g opacity="0.15">
        <line x1="80" y1="260" x2="192" y2="290" stroke="#eab308" stroke-width="1" />
        <line x1="80" y1="300" x2="192" y2="298" stroke="#eab308" stroke-width="1" />
        <line x1="80" y1="340" x2="192" y2="308" stroke="#eab308" stroke-width="1" />
        <line x1="192" y1="290" x2="610" y2="295" stroke="#eab308" stroke-width="0.8" stroke-dasharray="4,4" />
        <line x1="192" y1="298" x2="610" y2="295" stroke="#eab308" stroke-width="0.8" stroke-dasharray="4,4" />
        <line x1="192" y1="308" x2="610" y2="295" stroke="#eab308" stroke-width="0.8" stroke-dasharray="4,4" />
    </g>

    {{-- ======================== ANTERIOR / POSTERIOR LABELS ======================== --}}
    <text x="140" y="530" text-anchor="middle" font-size="10" fill="#94a3b8" font-style="italic">Anterior</text>
    <text x="620" y="530" text-anchor="middle" font-size="10" fill="#94a3b8" font-style="italic">Posterior</text>
    <line x1="180" y1="528" x2="580" y2="528" stroke="#cbd5e1" stroke-width="0.5" marker-end="url(#eye-arrow)" />

    {{-- ======================== LEGEND ======================== --}}
    <g transform="translate(25, 560)">
        <rect x="0" y="0" width="14" height="14" fill="#818cf8" stroke="#475569" stroke-width="0.8" rx="3" />
        <text x="20" y="11" font-size="10" fill="#475569">Selected zone</text>
        <text x="425" y="11" text-anchor="middle" font-size="10" fill="#94a3b8" font-family="system-ui, sans-serif">Click zones to select/deselect</text>
    </g>
</svg>
