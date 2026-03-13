{{-- General Anatomy Diagram - Front View Full Body with Internal Organs --}}
{{-- Requires parent Alpine.js scope with: selectedZones, toggleZone(zoneId), zoneNotes --}}

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 820" class="w-full h-auto max-w-md mx-auto">
    <defs>
        <style>
            .gen-zone { cursor: pointer; transition: fill 0.2s ease, opacity 0.2s ease, stroke 0.2s ease; }
            .gen-zone:hover { opacity: 0.85; filter: url(#gen-hover-glow); }
            .zone-label { font-family: system-ui, -apple-system, sans-serif; font-size: 10px; fill: #4b5563; pointer-events: none; text-anchor: middle; font-weight: 500; }
            .gen-label-line { stroke: #9CA3AF; stroke-width: 0.75; fill: none; stroke-dasharray: 2,2; pointer-events: none; }
            .gen-detail { pointer-events: none; }
        </style>

        {{-- Skin tone gradient --}}
        <linearGradient id="gen-skin" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#F5DEB3"/>
            <stop offset="100%" stop-color="#E8CFA0"/>
        </linearGradient>

        {{-- Torso transparency gradient --}}
        <linearGradient id="gen-torso-trans" x1="0.5" y1="0" x2="0.5" y2="1">
            <stop offset="0%" stop-color="#F0E0D0" stop-opacity="0.3"/>
            <stop offset="100%" stop-color="#E8D0C0" stop-opacity="0.15"/>
        </linearGradient>

        {{-- Heart gradient --}}
        <radialGradient id="gen-heart-grad" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#E85050"/>
            <stop offset="60%" stop-color="#C03030"/>
            <stop offset="100%" stop-color="#901818"/>
        </radialGradient>

        {{-- Lung gradient --}}
        <radialGradient id="gen-lung-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#F0B0B0"/>
            <stop offset="50%" stop-color="#D89090"/>
            <stop offset="100%" stop-color="#C07878"/>
        </radialGradient>

        {{-- Liver gradient --}}
        <radialGradient id="gen-liver-grad" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#C07050"/>
            <stop offset="60%" stop-color="#984838"/>
            <stop offset="100%" stop-color="#783028"/>
        </radialGradient>

        {{-- Stomach gradient --}}
        <radialGradient id="gen-stomach-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#F0C8A0"/>
            <stop offset="100%" stop-color="#D0A070"/>
        </radialGradient>

        {{-- Intestine gradient --}}
        <radialGradient id="gen-intestine-grad" cx="50%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#F0C0B0"/>
            <stop offset="100%" stop-color="#D09878"/>
        </radialGradient>

        {{-- Kidney gradient --}}
        <radialGradient id="gen-kidney-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#D09080"/>
            <stop offset="100%" stop-color="#A86050"/>
        </radialGradient>

        {{-- Brain gradient --}}
        <radialGradient id="gen-brain-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#F0C8C0"/>
            <stop offset="50%" stop-color="#E0A8A0"/>
            <stop offset="100%" stop-color="#C89088"/>
        </radialGradient>

        {{-- Shadow --}}
        <filter id="gen-shadow" x="-5%" y="-5%" width="115%" height="115%">
            <feDropShadow dx="0" dy="1" stdDeviation="2" flood-color="#00000015"/>
        </filter>

        {{-- Hover glow --}}
        <filter id="gen-hover-glow" x="-10%" y="-10%" width="120%" height="120%">
            <feDropShadow dx="0" dy="0" stdDeviation="4" flood-color="#818cf860"/>
        </filter>

        {{-- Body outline clip --}}
        <clipPath id="gen-torso-clip">
            <path d="M200 110 Q155 115 140 130 L132 260 Q130 320 135 380 Q140 420 155 450 L160 460 L340 460 L345 450 Q360 420 365 380 Q370 320 368 260 L360 130 Q345 115 300 110 Z"/>
        </clipPath>
    </defs>

    {{-- Title --}}
    <text x="250" y="22" text-anchor="middle" style="font-family: system-ui, -apple-system, sans-serif; font-size: 13px; font-weight: 600; fill: #374151;">
        General Anatomy — Front View
    </text>

    {{-- ==================== BODY OUTLINE (non-interactive reference) ==================== --}}

    {{-- Full body outline --}}
    <g class="gen-detail" opacity="0.25">
        {{-- Head outline --}}
        <ellipse cx="250" cy="65" rx="38" ry="40" fill="url(#gen-skin)" stroke="#C4A880" stroke-width="1"/>
        {{-- Neck --}}
        <rect x="237" y="100" width="26" height="20" rx="5" fill="url(#gen-skin)" stroke="#C4A880" stroke-width="0.5"/>
        {{-- Torso --}}
        <path d="M200 115 Q155 120 140 135 L130 265 Q128 330 135 390 Q142 430 158 460 L162 470 L180 475 L200 478 Q230 480 250 480 Q270 480 300 478 L320 475 L338 470 L342 460 Q358 430 365 390 Q372 330 370 265 L360 135 Q345 120 300 115 Z"
              fill="url(#gen-skin)" stroke="#C4A880" stroke-width="1"/>
        {{-- Right arm --}}
        <path d="M140 135 L115 140 Q95 148 82 175 L60 290 Q55 310 58 320 L75 325 L90 310 Q95 295 98 275 L112 185 Q118 160 128 145 Z"
              fill="url(#gen-skin)" stroke="#C4A880" stroke-width="0.8"/>
        {{-- Right hand --}}
        <path d="M58 318 Q52 340 50 355 Q48 370 55 375 L78 375 Q82 370 80 355 Q78 340 75 322 Z"
              fill="url(#gen-skin)" stroke="#C4A880" stroke-width="0.6"/>
        {{-- Left arm --}}
        <path d="M360 135 L385 140 Q405 148 418 175 L440 290 Q445 310 442 320 L425 325 L410 310 Q405 295 402 275 L388 185 Q382 160 372 145 Z"
              fill="url(#gen-skin)" stroke="#C4A880" stroke-width="0.8"/>
        {{-- Left hand --}}
        <path d="M442 318 Q448 340 450 355 Q452 370 445 375 L422 375 Q418 370 420 355 Q422 340 425 322 Z"
              fill="url(#gen-skin)" stroke="#C4A880" stroke-width="0.6"/>
        {{-- Right leg --}}
        <path d="M180 475 Q175 478 172 485 L165 580 Q162 640 160 680 Q158 710 162 720 L195 720 Q198 710 196 680 Q195 640 196 580 L200 485 Q200 478 198 475 Z"
              fill="url(#gen-skin)" stroke="#C4A880" stroke-width="0.8"/>
        {{-- Right foot --}}
        <path d="M162 718 L195 718 L196 740 Q196 750 190 755 L145 755 Q138 750 138 742 L140 730 Q145 722 162 718 Z"
              fill="url(#gen-skin)" stroke="#C4A880" stroke-width="0.6"/>
        {{-- Left leg --}}
        <path d="M302 475 Q305 478 308 485 L315 580 Q318 640 320 680 Q322 710 318 720 L285 720 Q282 710 284 680 Q285 640 284 580 L280 485 Q280 478 282 475 Z"
              fill="url(#gen-skin)" stroke="#C4A880" stroke-width="0.8"/>
        {{-- Left foot --}}
        <path d="M318 718 L285 718 L284 740 Q284 750 290 755 L335 755 Q342 750 342 742 L340 730 Q335 722 318 718 Z"
              fill="url(#gen-skin)" stroke="#C4A880" stroke-width="0.6"/>
    </g>

    {{-- Skeleton hints --}}
    <g class="gen-detail" opacity="0.06">
        {{-- Rib cage --}}
        <path d="M250 145 Q200 150 175 175" fill="none" stroke="#806040" stroke-width="2"/>
        <path d="M250 145 Q300 150 325 175" fill="none" stroke="#806040" stroke-width="2"/>
        <path d="M250 160 Q200 165 172 190" fill="none" stroke="#806040" stroke-width="2"/>
        <path d="M250 160 Q300 165 328 190" fill="none" stroke="#806040" stroke-width="2"/>
        <path d="M250 175 Q205 180 170 205" fill="none" stroke="#806040" stroke-width="2"/>
        <path d="M250 175 Q295 180 330 205" fill="none" stroke="#806040" stroke-width="2"/>
        <path d="M250 190 Q210 195 175 218" fill="none" stroke="#806040" stroke-width="2"/>
        <path d="M250 190 Q290 195 325 218" fill="none" stroke="#806040" stroke-width="2"/>
        {{-- Spine --}}
        <line x1="250" y1="130" x2="250" y2="470" stroke="#806040" stroke-width="3"/>
        {{-- Pelvis hint --}}
        <path d="M195 420 Q175 430 165 455 Q160 470 170 478 L200 480" fill="none" stroke="#806040" stroke-width="2"/>
        <path d="M305 420 Q325 430 335 455 Q340 470 330 478 L300 480" fill="none" stroke="#806040" stroke-width="2"/>
    </g>

    {{-- ==================== HEAD (clickable zone) ==================== --}}
    <ellipse
        @click="toggleZone('head')"
        :fill="selectedZones.includes('head') ? '#818cf8' : '#F5E0D0'"
        :stroke="selectedZones.includes('head') ? '#6366F1' : '#C4A080'"
        stroke-width="1.5"
        cx="250" cy="65" rx="38" ry="40"
        class="gen-zone"
        opacity="0.7"
    />
    {{-- Brain visible through skull --}}
    <path
        @click="toggleZone('head')"
        d="M228 48 Q225 38 235 32 Q245 28 250 28 Q255 28 265 32 Q275 38 272 48 Q278 55 276 65 Q275 75 268 80 Q260 84 250 85 Q240 84 232 80 Q225 75 224 65 Q222 55 228 48 Z"
        class="gen-zone"
        :fill="selectedZones.includes('head') ? '#818cf8' : 'url(#gen-brain-grad)'"
        :stroke="selectedZones.includes('head') ? '#6366F1' : '#A08078'"
        stroke-width="0.8"
        opacity="0.6"
    />
    {{-- Brain convolution detail --}}
    <g class="gen-detail" opacity="0.15">
        <path d="M235 40 Q240 35 250 36 Q260 35 265 40" fill="none" stroke="#806060" stroke-width="0.5"/>
        <path d="M230 50 Q240 46 250 47 Q260 46 270 50" fill="none" stroke="#806060" stroke-width="0.5"/>
        <path d="M228 60 Q240 56 250 57 Q260 56 272 60" fill="none" stroke="#806060" stroke-width="0.5"/>
        <path d="M230 70 Q240 66 250 67 Q260 66 270 70" fill="none" stroke="#806060" stroke-width="0.5"/>
        {{-- Central fissure --}}
        <line x1="250" y1="30" x2="250" y2="82" stroke="#806060" stroke-width="0.5"/>
    </g>
    {{-- Face features hint --}}
    <g class="gen-detail" opacity="0.15">
        <ellipse cx="240" cy="60" rx="4" ry="2.5" fill="#806040"/>
        <ellipse cx="260" cy="60" rx="4" ry="2.5" fill="#806040"/>
        <path d="M245 72 Q250 76 255 72" fill="none" stroke="#806040" stroke-width="0.8"/>
        <line x1="250" y1="64" x2="250" y2="70" stroke="#806040" stroke-width="0.5"/>
    </g>
    <text class="zone-label" x="250" y="95" font-size="9">Head / Brain</text>

    {{-- ==================== CHEST (clickable zone) ==================== --}}
    <path
        @click="toggleZone('chest')"
        :fill="selectedZones.includes('chest') ? '#818cf8' : 'url(#gen-torso-trans)'"
        :stroke="selectedZones.includes('chest') ? '#6366F1' : '#C4A88080'"
        stroke-width="1"
        d="M200 120 Q160 125 148 138 L142 240 L358 240 L352 138 Q340 125 300 120 Z"
        class="gen-zone"
        opacity="0.5"
    />
    <text class="zone-label" x="250" y="135" font-size="9" fill="#9CA3AF">Chest</text>

    {{-- ==================== LUNGS (inside chest) ==================== --}}
    {{-- Right lung --}}
    <path
        @click="toggleZone('lungs')"
        d="M168 148 Q160 155 155 180 Q152 210 158 232 Q165 240 180 242 Q200 244 215 240 Q225 235 228 220 L230 165 Q225 148 215 142 Q200 138 185 140 Q175 142 168 148 Z"
        class="gen-zone"
        :fill="selectedZones.includes('lungs') ? '#818cf8' : 'url(#gen-lung-grad)'"
        :stroke="selectedZones.includes('lungs') ? '#6366F1' : '#A07070'"
        stroke-width="1"
        filter="url(#gen-shadow)"
    />
    {{-- Right lung lobes --}}
    <g class="gen-detail" opacity="0.2">
        <path d="M165 190 Q190 185 225 192" fill="none" stroke="#704040" stroke-width="0.8"/>
        <path d="M160 210 Q190 205 225 212" fill="none" stroke="#704040" stroke-width="0.8"/>
    </g>
    {{-- Left lung --}}
    <path
        @click="toggleZone('lungs')"
        d="M332 148 Q340 155 345 180 Q348 210 342 232 Q335 240 320 242 Q300 244 285 240 Q275 235 272 220 L270 165 Q275 148 285 142 Q300 138 315 140 Q325 142 332 148 Z"
        class="gen-zone"
        :fill="selectedZones.includes('lungs') ? '#818cf8' : 'url(#gen-lung-grad)'"
        :stroke="selectedZones.includes('lungs') ? '#6366F1' : '#A07070'"
        stroke-width="1"
        filter="url(#gen-shadow)"
    />
    <g class="gen-detail" opacity="0.2">
        <path d="M335 190 Q310 185 275 192" fill="none" stroke="#704040" stroke-width="0.8"/>
    </g>
    {{-- Bronchi hint --}}
    <g class="gen-detail" opacity="0.1">
        <path d="M250 145 Q235 155 225 165" fill="none" stroke="#704040" stroke-width="1.5"/>
        <path d="M250 145 Q265 155 275 165" fill="none" stroke="#704040" stroke-width="1.5"/>
    </g>
    <text class="zone-label" x="190" y="195" font-size="9">R. Lung</text>
    <text class="zone-label" x="310" y="195" font-size="9">L. Lung</text>

    {{-- ==================== HEART ==================== --}}
    <path
        @click="toggleZone('heart')"
        d="M235 175 Q228 168 232 160 Q238 154 245 158 Q250 162 255 158 Q262 154 268 160 Q272 168 265 175 L250 200 Z"
        class="gen-zone"
        :fill="selectedZones.includes('heart') ? '#818cf8' : 'url(#gen-heart-grad)'"
        :stroke="selectedZones.includes('heart') ? '#6366F1' : '#802020'"
        stroke-width="1"
        filter="url(#gen-shadow)"
        transform="scale(1.6) translate(-95, -58)"
    />
    {{-- Heart detail --}}
    <g class="gen-detail" opacity="0.2" transform="scale(1.6) translate(-95, -58)">
        {{-- Septum --}}
        <line x1="250" y1="162" x2="250" y2="195" stroke="#600010" stroke-width="0.5"/>
        {{-- Aorta arch hint --}}
        <path d="M248 158 Q248 148 255 145 Q262 148 262 155" fill="none" stroke="#802020" stroke-width="0.8"/>
    </g>
    <text class="zone-label" x="250" y="225" font-size="9">Heart</text>

    {{-- ==================== ABDOMEN (clickable zone) ==================== --}}
    <path
        @click="toggleZone('abdomen')"
        :fill="selectedZones.includes('abdomen') ? '#818cf8' : 'url(#gen-torso-trans)'"
        :stroke="selectedZones.includes('abdomen') ? '#6366F1' : '#C4A88060'"
        stroke-width="1"
        d="M142 242 L358 242 L362 380 Q358 430 345 455 L155 455 Q142 430 138 380 Z"
        class="gen-zone"
        opacity="0.4"
    />
    <text class="zone-label" x="250" y="258" font-size="9" fill="#9CA3AF">Abdomen</text>

    {{-- ==================== LIVER ==================== --}}
    <path
        @click="toggleZone('liver')"
        d="M170 252 Q158 255 152 265 Q148 280 155 295 Q162 305 180 310 Q210 315 240 312 Q268 308 280 295 Q288 282 282 268 Q275 255 258 250 Q230 248 200 248 Q185 248 170 252 Z"
        class="gen-zone"
        :fill="selectedZones.includes('liver') ? '#818cf8' : 'url(#gen-liver-grad)'"
        :stroke="selectedZones.includes('liver') ? '#6366F1' : '#604030'"
        stroke-width="1"
        filter="url(#gen-shadow)"
    />
    {{-- Liver lobe division --}}
    <path d="M220 252 Q218 275 222 310" fill="none" stroke="#503020" stroke-width="0.5" class="gen-detail" opacity="0.2"/>
    {{-- Gallbladder hint --}}
    <ellipse cx="225" cy="308" rx="6" ry="10" fill="#80A840" opacity="0.3" class="gen-detail" transform="rotate(-15, 225, 308)"/>
    <text class="zone-label" x="215" y="282" font-size="9" fill="#F0D0C0">Liver</text>

    {{-- ==================== STOMACH ==================== --}}
    <path
        @click="toggleZone('stomach')"
        d="M262 268 Q275 272 285 285 Q292 300 288 318 Q282 338 268 348 Q252 355 240 352 Q228 348 222 340 Q218 332 220 318 Q222 308 230 298 Q240 285 252 275 Q258 270 262 268 Z"
        class="gen-zone"
        :fill="selectedZones.includes('stomach') ? '#818cf8' : 'url(#gen-stomach-grad)'"
        :stroke="selectedZones.includes('stomach') ? '#6366F1' : '#A08050'"
        stroke-width="1"
        filter="url(#gen-shadow)"
    />
    {{-- Stomach rugae --}}
    <g class="gen-detail" opacity="0.15">
        <path d="M248 290 Q260 288 275 295" fill="none" stroke="#806030" stroke-width="0.5"/>
        <path d="M238 305 Q255 302 278 308" fill="none" stroke="#806030" stroke-width="0.5"/>
        <path d="M232 320 Q250 316 275 322" fill="none" stroke="#806030" stroke-width="0.5"/>
        <path d="M228 335 Q245 332 268 338" fill="none" stroke="#806030" stroke-width="0.5"/>
    </g>
    <text class="zone-label" x="272" y="318" font-size="9" fill="#604020">Stomach</text>

    {{-- ==================== KIDNEYS (behind other organs) ==================== --}}
    {{-- Right kidney --}}
    <path
        @click="toggleZone('kidneys')"
        d="M168 290 Q158 300 156 315 Q158 335 168 345 Q178 350 182 340 Q178 315 182 295 Q178 288 168 290 Z"
        class="gen-zone"
        :fill="selectedZones.includes('kidneys') ? '#818cf8' : 'url(#gen-kidney-grad)'"
        :stroke="selectedZones.includes('kidneys') ? '#6366F1' : '#805048'"
        stroke-width="0.8"
        filter="url(#gen-shadow)"
    />
    {{-- Left kidney --}}
    <path
        @click="toggleZone('kidneys')"
        d="M332 290 Q342 300 344 315 Q342 335 332 345 Q322 350 318 340 Q322 315 318 295 Q322 288 332 290 Z"
        class="gen-zone"
        :fill="selectedZones.includes('kidneys') ? '#818cf8' : 'url(#gen-kidney-grad)'"
        :stroke="selectedZones.includes('kidneys') ? '#6366F1' : '#805048'"
        stroke-width="0.8"
        filter="url(#gen-shadow)"
    />
    <text class="zone-label" x="155" y="325" font-size="8" text-anchor="end">R.Kidney</text>
    <text class="zone-label" x="345" y="325" font-size="8" text-anchor="start">L.Kidney</text>

    {{-- ==================== INTESTINES ==================== --}}
    {{-- Small intestine --}}
    <path
        @click="toggleZone('intestines')"
        d="M195 355 Q185 360 180 375 Q178 395 185 410 Q195 425 215 430 Q235 432 255 430 Q275 428 290 420 Q305 410 310 395 Q312 378 305 365 Q295 355 280 352 Q260 350 240 352 Q218 354 195 355 Z"
        class="gen-zone"
        :fill="selectedZones.includes('intestines') ? '#818cf8' : 'url(#gen-intestine-grad)'"
        :stroke="selectedZones.includes('intestines') ? '#6366F1' : '#A08060'"
        stroke-width="1"
        filter="url(#gen-shadow)"
    />
    {{-- Intestinal loops detail --}}
    <g class="gen-detail" opacity="0.2">
        <path d="M195 365 Q220 360 250 362 Q280 365 300 370" fill="none" stroke="#806040" stroke-width="0.5"/>
        <path d="M190 378 Q215 374 248 375 Q280 378 305 382" fill="none" stroke="#806040" stroke-width="0.5"/>
        <path d="M185 392 Q215 388 248 389 Q280 392 308 396" fill="none" stroke="#806040" stroke-width="0.5"/>
        <path d="M188 405 Q215 400 248 402 Q278 405 302 410" fill="none" stroke="#806040" stroke-width="0.5"/>
        <path d="M195 418 Q220 414 252 415 Q278 418 295 422" fill="none" stroke="#806040" stroke-width="0.5"/>
    </g>
    {{-- Large intestine frame --}}
    <g class="gen-detail" opacity="0.25">
        <path d="M175 355 Q168 370 165 400 Q165 420 172 435 Q185 445 210 448 Q240 450 270 448 Q300 445 315 435 Q325 420 328 400 Q330 370 325 355"
              fill="none" stroke="#A07050" stroke-width="3" stroke-linecap="round"/>
        {{-- Ascending colon --}}
        <path d="M175 355 L175 340" fill="none" stroke="#A07050" stroke-width="3" stroke-linecap="round"/>
        {{-- Descending colon --}}
        <path d="M325 355 L325 340" fill="none" stroke="#A07050" stroke-width="3" stroke-linecap="round"/>
    </g>
    <text class="zone-label" x="250" y="395" font-size="9">Intestines</text>

    {{-- ==================== RIGHT ARM ==================== --}}
    <path
        @click="toggleZone('right_arm')"
        :fill="selectedZones.includes('right_arm') ? '#818cf8' : '#F0D8C0'"
        :stroke="selectedZones.includes('right_arm') ? '#6366F1' : '#C4A080'"
        stroke-width="1"
        d="M140 135 L118 140 Q100 150 88 175 L68 280 Q64 298 66 310 L80 315 L94 300 Q98 285 102 265 L115 180 Q122 158 132 145 Z"
        class="gen-zone"
        opacity="0.6"
    />
    {{-- Muscle detail --}}
    <g class="gen-detail" opacity="0.08">
        <path d="M125 155 Q115 175 108 200" fill="none" stroke="#806040" stroke-width="1.5"/>
        <path d="M118 165 Q108 185 100 210" fill="none" stroke="#806040" stroke-width="1"/>
    </g>
    <text class="zone-label" x="100" y="220" font-size="9" transform="rotate(-12, 100, 220)">R. Arm</text>

    {{-- ==================== LEFT ARM ==================== --}}
    <path
        @click="toggleZone('left_arm')"
        :fill="selectedZones.includes('left_arm') ? '#818cf8' : '#F0D8C0'"
        :stroke="selectedZones.includes('left_arm') ? '#6366F1' : '#C4A080'"
        stroke-width="1"
        d="M360 135 L382 140 Q400 150 412 175 L432 280 Q436 298 434 310 L420 315 L406 300 Q402 285 398 265 L385 180 Q378 158 368 145 Z"
        class="gen-zone"
        opacity="0.6"
    />
    <g class="gen-detail" opacity="0.08">
        <path d="M375 155 Q385 175 392 200" fill="none" stroke="#806040" stroke-width="1.5"/>
        <path d="M382 165 Q392 185 400 210" fill="none" stroke="#806040" stroke-width="1"/>
    </g>
    <text class="zone-label" x="400" y="220" font-size="9" transform="rotate(12, 400, 220)">L. Arm</text>

    {{-- ==================== RIGHT LEG ==================== --}}
    <path
        @click="toggleZone('right_leg')"
        :fill="selectedZones.includes('right_leg') ? '#818cf8' : '#F0D8C0'"
        :stroke="selectedZones.includes('right_leg') ? '#6366F1' : '#C4A080'"
        stroke-width="1"
        d="M185 462 Q178 465 174 475 L168 565 Q165 625 163 670 Q162 698 165 710 L193 710 Q196 698 194 670 Q193 625 194 565 L198 475 Q198 465 195 462 Z"
        class="gen-zone"
        opacity="0.6"
    />
    {{-- Knee hint --}}
    <ellipse cx="180" cy="590" rx="12" ry="6" fill="none" stroke="#C4A080" stroke-width="0.5" class="gen-detail" opacity="0.15"/>
    {{-- Muscle lines --}}
    <g class="gen-detail" opacity="0.06">
        <path d="M180 480 Q178 520 175 560" fill="none" stroke="#806040" stroke-width="1.5"/>
        <path d="M188 480 Q186 520 184 560" fill="none" stroke="#806040" stroke-width="1"/>
        <path d="M178 610 Q176 650 172 690" fill="none" stroke="#806040" stroke-width="1"/>
    </g>
    <text class="zone-label" x="180" y="540" font-size="9">R. Leg</text>

    {{-- ==================== LEFT LEG ==================== --}}
    <path
        @click="toggleZone('left_leg')"
        :fill="selectedZones.includes('left_leg') ? '#818cf8' : '#F0D8C0'"
        :stroke="selectedZones.includes('left_leg') ? '#6366F1' : '#C4A080'"
        stroke-width="1"
        d="M305 462 Q312 465 316 475 L322 565 Q325 625 327 670 Q328 698 325 710 L297 710 Q294 698 296 670 Q297 625 296 565 L292 475 Q292 465 295 462 Z"
        class="gen-zone"
        opacity="0.6"
    />
    <ellipse cx="310" cy="590" rx="12" ry="6" fill="none" stroke="#C4A080" stroke-width="0.5" class="gen-detail" opacity="0.15"/>
    <g class="gen-detail" opacity="0.06">
        <path d="M310 480 Q312 520 315 560" fill="none" stroke="#806040" stroke-width="1.5"/>
        <path d="M302 480 Q304 520 306 560" fill="none" stroke="#806040" stroke-width="1"/>
        <path d="M312 610 Q314 650 318 690" fill="none" stroke="#806040" stroke-width="1"/>
    </g>
    <text class="zone-label" x="310" y="540" font-size="9">L. Leg</text>

    {{-- ==================== LABEL LINES for internal organs ==================== --}}
    <g class="gen-detail">
        {{-- Heart label line --}}
        <line x1="250" y1="228" x2="250" y2="222" class="gen-label-line"/>
        {{-- Liver label line --}}
        <line x1="170" y1="275" x2="152" y2="275" class="gen-label-line"/>
        <text x="148" y="279" class="zone-label" font-size="8" text-anchor="end">Liver</text>
        {{-- Stomach label line --}}
        <line x1="290" y1="308" x2="335" y2="305" class="gen-label-line"/>
        <text x="340" y="309" class="zone-label" font-size="8" text-anchor="start">Stomach</text>
    </g>

    {{-- Note indicators --}}
    <template x-for="zone in selectedZones" :key="zone">
        <template x-if="zoneNotes[zone]">
            <circle cx="0" cy="0" r="4" fill="#ef4444" class="pointer-events-none" />
        </template>
    </template>
</svg>
