{{-- Pediatric Anatomical Diagram (Child Body) --}}
{{-- Requires parent Alpine.js scope with: selectedZones[], toggleZone(id), zoneNotes{} --}}

<svg viewBox="0 0 460 680" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto max-w-md mx-auto">
    <defs>
        <style>
            .ped-label { font-family: sans-serif; font-size: 8.5px; fill: #374151; pointer-events: none; }
            .ped-label-sm { font-family: sans-serif; font-size: 7px; fill: #374151; pointer-events: none; }
            .zone-area { cursor: pointer; transition: fill 0.2s ease, stroke 0.2s ease; }
            .zone-area:hover { filter: brightness(0.92); }
        </style>

        <!-- Skin gradient -->
        <radialGradient id="ped-skin" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#fce8d8"/>
            <stop offset="100%" stop-color="#f0d0b8"/>
        </radialGradient>
        <radialGradient id="ped-skin-limb" cx="50%" cy="40%" r="60%">
            <stop offset="0%" stop-color="#fae0cc"/>
            <stop offset="100%" stop-color="#e8c8aa"/>
        </radialGradient>

        <!-- Head gradient -->
        <radialGradient id="ped-head-grad" cx="48%" cy="38%" r="55%">
            <stop offset="0%" stop-color="#fdecd4"/>
            <stop offset="80%" stop-color="#f0d4b4"/>
            <stop offset="100%" stop-color="#e4c4a0"/>
        </radialGradient>

        <!-- Organ gradients -->
        <radialGradient id="ped-heart-grad" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#e85060"/>
            <stop offset="100%" stop-color="#b83040"/>
        </radialGradient>
        <radialGradient id="ped-lung-grad" cx="40%" cy="35%" r="60%">
            <stop offset="0%" stop-color="#e8b0b8"/>
            <stop offset="100%" stop-color="#c88898"/>
        </radialGradient>
        <radialGradient id="ped-stomach-grad" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#f0c898"/>
            <stop offset="100%" stop-color="#d0a070"/>
        </radialGradient>
        <radialGradient id="ped-chest-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#d8ecf8"/>
            <stop offset="100%" stop-color="#b8d4ec"/>
        </radialGradient>
        <radialGradient id="ped-abdomen-grad" cx="50%" cy="45%" r="55%">
            <stop offset="0%" stop-color="#e8f4d8"/>
            <stop offset="100%" stop-color="#c8e0a8"/>
        </radialGradient>

        <!-- Selected state -->
        <radialGradient id="ped-selected" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#b8c4fc"/>
            <stop offset="100%" stop-color="#a5b4fc"/>
        </radialGradient>

        <!-- Shadow -->
        <filter id="ped-shadow" x="-5%" y="-5%" width="110%" height="112%">
            <feDropShadow dx="0.5" dy="1" stdDeviation="1.5" flood-color="#00000020"/>
        </filter>
        <filter id="ped-organ-shadow" x="-8%" y="-8%" width="116%" height="116%">
            <feDropShadow dx="0.3" dy="0.6" stdDeviation="1" flood-color="#00000025"/>
        </filter>

        <!-- Growth plate pattern -->
        <pattern id="ped-growth-plate" width="4" height="2" patternUnits="userSpaceOnUse">
            <line x1="0" y1="1" x2="4" y2="1" stroke="#60a0d0" stroke-width="0.5" opacity="0.6"/>
        </pattern>
    </defs>

    <!-- ==================== BODY OUTLINE (child proportions: large head) ==================== -->

    {{-- ===== HEAD ===== --}}
    <g @click="toggleZone('head')" class="zone-area" filter="url(#ped-shadow)">
        <!-- Main head shape - larger relative to body for child proportions -->
        <ellipse cx="230" cy="88" rx="62" ry="70"
            :fill="selectedZones.includes('head') ? 'url(#ped-selected)' : 'url(#ped-head-grad)'"
            :stroke="selectedZones.includes('head') ? '#4f46e5' : '#c4a882'"
            stroke-width="1.5"/>
        <!-- Fontanelle (anterior) - diamond-shaped soft spot -->
        <path d="M230 28 L238 38 L230 48 L222 38 Z"
            fill="none" :stroke="selectedZones.includes('head') ? '#4f46e5' : '#d4a060'" stroke-width="0.8"
            stroke-dasharray="2,1" pointer-events="none" opacity="0.7"/>
        <!-- Hair suggestion -->
        <path d="M172 62 Q180 30 215 20 Q230 16 245 20 Q280 30 288 62"
            fill="none" stroke="#a08060" stroke-width="0.8" opacity="0.25" pointer-events="none"/>
        <!-- Cheek blush -->
        <circle cx="195" cy="100" r="10" fill="#f0a0a0" opacity="0.15" pointer-events="none"/>
        <circle cx="265" cy="100" r="10" fill="#f0a0a0" opacity="0.15" pointer-events="none"/>
    </g>
    <!-- Fontanelle label -->
    <line x1="230" y1="28" x2="230" y2="10" stroke="#94a3b8" stroke-width="0.5" pointer-events="none"/>
    <text x="230" y="7" text-anchor="middle" class="ped-label-sm" fill="#8a6a40">Fontanelle</text>
    <text x="230" y="68" text-anchor="middle" class="ped-label" :fill="selectedZones.includes('head') ? '#312e81' : '#5a4a38'">Head</text>

    {{-- ===== EYES ===== --}}
    <g @click="toggleZone('eyes')" class="zone-area">
        <!-- Left eye -->
        <ellipse cx="210" cy="82" rx="12" ry="7"
            :fill="selectedZones.includes('eyes') ? '#c7d2fe' : '#ffffff'"
            :stroke="selectedZones.includes('eyes') ? '#4f46e5' : '#8a7a6a'"
            stroke-width="0.8"/>
        <circle cx="210" cy="82" r="4" fill="#6a4a2a" pointer-events="none"/>
        <circle cx="211" cy="81" r="1.5" fill="#fff" opacity="0.6" pointer-events="none"/>
        <!-- Right eye -->
        <ellipse cx="250" cy="82" rx="12" ry="7"
            :fill="selectedZones.includes('eyes') ? '#c7d2fe' : '#ffffff'"
            :stroke="selectedZones.includes('eyes') ? '#4f46e5' : '#8a7a6a'"
            stroke-width="0.8"/>
        <circle cx="250" cy="82" r="4" fill="#6a4a2a" pointer-events="none"/>
        <circle cx="251" cy="81" r="1.5" fill="#fff" opacity="0.6" pointer-events="none"/>
        <!-- Eyebrows -->
        <path d="M200 73 Q210 70 220 73" fill="none" stroke="#8a7060" stroke-width="0.6" opacity="0.4" pointer-events="none"/>
        <path d="M240 73 Q250 70 260 73" fill="none" stroke="#8a7060" stroke-width="0.6" opacity="0.4" pointer-events="none"/>
    </g>
    <text x="230" y="76" text-anchor="middle" class="ped-label-sm">Eyes</text>

    {{-- ===== EARS ===== --}}
    <g @click="toggleZone('ears')" class="zone-area">
        <path d="M168 82 Q162 72 164 86 Q166 100 170 96 Q172 90 168 82 Z"
            :fill="selectedZones.includes('ears') ? '#c7d2fe' : '#f0d0b4'"
            :stroke="selectedZones.includes('ears') ? '#4f46e5' : '#c4a882'"
            stroke-width="0.8"/>
        <path d="M292 82 Q298 72 296 86 Q294 100 290 96 Q288 90 292 82 Z"
            :fill="selectedZones.includes('ears') ? '#c7d2fe' : '#f0d0b4'"
            :stroke="selectedZones.includes('ears') ? '#4f46e5' : '#c4a882'"
            stroke-width="0.8"/>
    </g>
    <text x="153" y="88" text-anchor="end" class="ped-label-sm">Ear</text>
    <text x="307" y="88" text-anchor="start" class="ped-label-sm">Ear</text>

    {{-- ===== MOUTH ===== --}}
    <g @click="toggleZone('mouth')" class="zone-area">
        <path d="M220 108 Q230 114 240 108"
            fill="none"
            :stroke="selectedZones.includes('mouth') ? '#4f46e5' : '#d06060'"
            stroke-width="1.5" stroke-linecap="round"/>
        <!-- Lips -->
        <ellipse cx="230" cy="108" rx="13" ry="5"
            :fill="selectedZones.includes('mouth') ? '#c7d2fe' : '#f0a8a8'"
            :stroke="selectedZones.includes('mouth') ? '#4f46e5' : '#c07070'"
            stroke-width="0.6" fill-opacity="0.5"/>
        <!-- Nose hint -->
        <path d="M226 96 Q230 100 234 96" fill="none" stroke="#c4a882" stroke-width="0.6" opacity="0.5" pointer-events="none"/>
    </g>
    <text x="230" y="122" text-anchor="middle" class="ped-label-sm">Mouth</text>

    {{-- ===== NECK ===== --}}
    <path d="M215 155 Q218 148 222 145 Q228 142 232 142 Q238 142 242 148 Q245 155 245 160"
        fill="url(#ped-skin)" stroke="#c4a882" stroke-width="0.8" opacity="0.9"/>

    {{-- ===== CHEST ===== --}}
    <g @click="toggleZone('chest')" class="zone-area" filter="url(#ped-shadow)">
        <path d="M185 170 Q190 162 215 158 Q230 156 245 158 Q270 162 275 170
                 L280 210 Q282 230 278 245 Q270 255 250 258 L210 258 Q190 255 182 245
                 Q178 230 180 210 Z"
            :fill="selectedZones.includes('chest') ? 'url(#ped-selected)' : 'url(#ped-chest-grad)'"
            :stroke="selectedZones.includes('chest') ? '#4f46e5' : '#7aaccc'"
            stroke-width="1.2"/>
        <!-- Rib hints for child -->
        <g opacity="0.1" stroke="#5a8aaa" stroke-width="0.5" fill="none" pointer-events="none">
            <path d="M195 180 Q215 175 230 174 Q245 175 265 180"/>
            <path d="M192 192 Q215 186 230 185 Q245 186 268 192"/>
            <path d="M190 204 Q215 197 230 196 Q245 197 270 204"/>
            <path d="M190 216 Q215 209 230 208 Q245 209 270 216"/>
            <path d="M192 228 Q215 222 230 221 Q245 222 268 228"/>
        </g>
        <!-- Sternum -->
        <line x1="230" y1="165" x2="230" y2="248" stroke="#5a8aaa" stroke-width="0.4" opacity="0.1" pointer-events="none"/>
    </g>
    <text x="230" y="175" text-anchor="middle" class="ped-label" :fill="selectedZones.includes('chest') ? '#312e81' : '#3a6a8a'">Chest</text>

    {{-- ===== HEART (overlay on chest) ===== --}}
    <g @click="toggleZone('heart')" class="zone-area" filter="url(#ped-organ-shadow)">
        <path d="M218 192 Q214 184 220 180 Q226 176 232 182 Q236 176 242 178 Q248 182 246 190
                 L240 208 Q236 216 230 222 Q224 218 220 210 Q216 202 218 192 Z"
            :fill="selectedZones.includes('heart') ? '#818cf8' : 'url(#ped-heart-grad)'"
            :stroke="selectedZones.includes('heart') ? '#4f46e5' : '#901828'"
            stroke-width="0.8" fill-opacity="0.85"/>
    </g>
    <text x="232" y="240" text-anchor="middle" class="ped-label-sm" :fill="selectedZones.includes('heart') ? '#312e81' : '#801020'">Heart</text>

    {{-- ===== LUNGS (overlay on chest) ===== --}}
    <g @click="toggleZone('lungs')" class="zone-area" filter="url(#ped-organ-shadow)">
        <!-- Left lung -->
        <path d="M200 182 Q194 178 192 188 Q188 202 190 218 Q192 230 198 236
                 Q204 240 210 235 Q214 228 215 215 Q216 200 212 188 Q208 180 200 182 Z"
            :fill="selectedZones.includes('lungs') ? '#818cf8' : 'url(#ped-lung-grad)'"
            :stroke="selectedZones.includes('lungs') ? '#4f46e5' : '#a06878'"
            stroke-width="0.7" fill-opacity="0.8"/>
        <!-- Right lung -->
        <path d="M260 182 Q266 178 268 188 Q272 202 270 218 Q268 230 262 236
                 Q256 240 250 235 Q246 228 245 215 Q244 200 248 188 Q252 180 260 182 Z"
            :fill="selectedZones.includes('lungs') ? '#818cf8' : 'url(#ped-lung-grad)'"
            :stroke="selectedZones.includes('lungs') ? '#4f46e5' : '#a06878'"
            stroke-width="0.7" fill-opacity="0.8"/>
        <!-- Bronchial hints -->
        <g opacity="0.2" stroke="#804858" fill="none" stroke-width="0.4" pointer-events="none">
            <path d="M210 190 L202 200 L198 215"/>
            <path d="M250 190 L258 200 L262 215"/>
        </g>
    </g>
    <text x="200" y="248" text-anchor="middle" class="ped-label-sm" :fill="selectedZones.includes('lungs') ? '#312e81' : '#804060'">Lungs</text>

    {{-- ===== ABDOMEN ===== --}}
    <g @click="toggleZone('abdomen')" class="zone-area" filter="url(#ped-shadow)">
        <!-- Rounded child abdomen -->
        <path d="M182 258 Q178 268 178 290 Q178 320 184 340 Q192 358 210 365
                 L250 365 Q268 358 276 340 Q282 320 282 290 Q282 268 278 258 Z"
            :fill="selectedZones.includes('abdomen') ? 'url(#ped-selected)' : 'url(#ped-abdomen-grad)'"
            :stroke="selectedZones.includes('abdomen') ? '#4f46e5' : '#7aaa5a'"
            stroke-width="1.2"/>
        <!-- Navel -->
        <circle cx="230" cy="310" r="3" fill="none" stroke="#a08860" stroke-width="0.6" opacity="0.3" pointer-events="none"/>
    </g>
    <text x="230" y="278" text-anchor="middle" class="ped-label" :fill="selectedZones.includes('abdomen') ? '#312e81' : '#4a6a2a'">Abdomen</text>

    {{-- ===== STOMACH (overlay on abdomen) ===== --}}
    <g @click="toggleZone('stomach')" class="zone-area" filter="url(#ped-organ-shadow)">
        <path d="M218 288 Q210 286 206 294 Q202 305 208 314 Q214 320 222 318
                 Q230 315 232 306 Q234 296 228 290 Q224 286 218 288 Z"
            :fill="selectedZones.includes('stomach') ? '#818cf8' : 'url(#ped-stomach-grad)'"
            :stroke="selectedZones.includes('stomach') ? '#4f46e5' : '#a08050'"
            stroke-width="0.7" fill-opacity="0.85"/>
        <!-- Rugae -->
        <g opacity="0.2" stroke="#806040" fill="none" stroke-width="0.3" pointer-events="none">
            <path d="M210 296 Q218 294 226 298"/>
            <path d="M208 305 Q218 302 228 306"/>
        </g>
    </g>
    <text x="218" y="332" text-anchor="middle" class="ped-label-sm" :fill="selectedZones.includes('stomach') ? '#312e81' : '#6a5020'">Stomach</text>

    {{-- ===== RIGHT ARM ===== --}}
    <g @click="toggleZone('right_arm')" class="zone-area" filter="url(#ped-shadow)">
        <!-- Upper arm -->
        <path d="M182 172 Q168 175 158 190 Q148 210 144 235 Q140 258 142 275
                 Q146 278 152 275 Q155 258 158 240 Q162 220 170 205 Q178 192 185 185 Z"
            :fill="selectedZones.includes('right_arm') ? 'url(#ped-selected)' : 'url(#ped-skin-limb)'"
            :stroke="selectedZones.includes('right_arm') ? '#4f46e5' : '#c4a882'"
            stroke-width="1.2"/>
        <!-- Forearm -->
        <path d="M142 275 Q138 290 136 310 Q134 330 133 345 Q132 358 136 360
                 Q142 362 144 358 Q146 345 148 325 Q150 305 152 290 Q154 280 152 275 Z"
            :fill="selectedZones.includes('right_arm') ? 'url(#ped-selected)' : 'url(#ped-skin-limb)'"
            :stroke="selectedZones.includes('right_arm') ? '#4f46e5' : '#c4a882'"
            stroke-width="1"/>
        <!-- Hand -->
        <ellipse cx="138" cy="368" rx="10" ry="12"
            :fill="selectedZones.includes('right_arm') ? 'url(#ped-selected)' : '#f0d4b8'"
            :stroke="selectedZones.includes('right_arm') ? '#4f46e5' : '#c4a882'"
            stroke-width="0.8"/>
        <!-- Growth plate indicators -->
        <rect x="140" y="273" width="14" height="3" rx="1" fill="url(#ped-growth-plate)" opacity="0.6" pointer-events="none"/>
        <rect x="134" y="356" width="10" height="3" rx="1" fill="url(#ped-growth-plate)" opacity="0.6" pointer-events="none"/>
        <!-- Elbow hint -->
        <circle cx="148" cy="275" r="4" fill="none" stroke="#b09878" stroke-width="0.4" opacity="0.3" pointer-events="none"/>
    </g>
    <text x="130" y="250" text-anchor="middle" class="ped-label" transform="rotate(-75 130 250)" :fill="selectedZones.includes('right_arm') ? '#312e81' : '#6a5a48'">R. Arm</text>

    {{-- ===== LEFT ARM ===== --}}
    <g @click="toggleZone('left_arm')" class="zone-area" filter="url(#ped-shadow)">
        <!-- Upper arm -->
        <path d="M278 172 Q292 175 302 190 Q312 210 316 235 Q320 258 318 275
                 Q314 278 308 275 Q305 258 302 240 Q298 220 290 205 Q282 192 275 185 Z"
            :fill="selectedZones.includes('left_arm') ? 'url(#ped-selected)' : 'url(#ped-skin-limb)'"
            :stroke="selectedZones.includes('left_arm') ? '#4f46e5' : '#c4a882'"
            stroke-width="1.2"/>
        <!-- Forearm -->
        <path d="M318 275 Q322 290 324 310 Q326 330 327 345 Q328 358 324 360
                 Q318 362 316 358 Q314 345 312 325 Q310 305 308 290 Q306 280 308 275 Z"
            :fill="selectedZones.includes('left_arm') ? 'url(#ped-selected)' : 'url(#ped-skin-limb)'"
            :stroke="selectedZones.includes('left_arm') ? '#4f46e5' : '#c4a882'"
            stroke-width="1"/>
        <!-- Hand -->
        <ellipse cx="322" cy="368" rx="10" ry="12"
            :fill="selectedZones.includes('left_arm') ? 'url(#ped-selected)' : '#f0d4b8'"
            :stroke="selectedZones.includes('left_arm') ? '#4f46e5' : '#c4a882'"
            stroke-width="0.8"/>
        <!-- Growth plate indicators -->
        <rect x="306" y="273" width="14" height="3" rx="1" fill="url(#ped-growth-plate)" opacity="0.6" pointer-events="none"/>
        <rect x="316" y="356" width="10" height="3" rx="1" fill="url(#ped-growth-plate)" opacity="0.6" pointer-events="none"/>
        <!-- Elbow hint -->
        <circle cx="312" cy="275" r="4" fill="none" stroke="#b09878" stroke-width="0.4" opacity="0.3" pointer-events="none"/>
    </g>
    <text x="330" y="250" text-anchor="middle" class="ped-label" transform="rotate(75 330 250)" :fill="selectedZones.includes('left_arm') ? '#312e81' : '#6a5a48'">L. Arm</text>

    {{-- ===== RIGHT LEG ===== --}}
    <g @click="toggleZone('right_leg')" class="zone-area" filter="url(#ped-shadow)">
        <!-- Thigh -->
        <path d="M210 365 Q200 370 196 385 Q192 410 190 440 Q188 465 190 480
                 Q194 484 200 480 Q202 465 204 445 Q206 425 210 405 Q214 388 216 375 Z"
            :fill="selectedZones.includes('right_leg') ? 'url(#ped-selected)' : 'url(#ped-skin-limb)'"
            :stroke="selectedZones.includes('right_leg') ? '#4f46e5' : '#c4a882'"
            stroke-width="1.2"/>
        <!-- Lower leg -->
        <path d="M190 480 Q186 500 184 525 Q182 555 182 575 Q182 590 186 595
                 Q192 598 196 595 Q198 585 198 570 Q198 548 200 525 Q202 505 200 490 Z"
            :fill="selectedZones.includes('right_leg') ? 'url(#ped-selected)' : 'url(#ped-skin-limb)'"
            :stroke="selectedZones.includes('right_leg') ? '#4f46e5' : '#c4a882'"
            stroke-width="1"/>
        <!-- Foot -->
        <path d="M182 595 Q176 598 174 602 Q172 608 178 610 Q186 612 196 610 Q200 608 200 602 Q198 598 196 595 Z"
            :fill="selectedZones.includes('right_leg') ? 'url(#ped-selected)' : '#f0d4b8'"
            :stroke="selectedZones.includes('right_leg') ? '#4f46e5' : '#c4a882'"
            stroke-width="0.8"/>
        <!-- Growth plate indicators (knee and ankle) -->
        <rect x="188" y="478" width="14" height="3" rx="1" fill="url(#ped-growth-plate)" opacity="0.6" pointer-events="none"/>
        <rect x="182" y="592" width="12" height="3" rx="1" fill="url(#ped-growth-plate)" opacity="0.6" pointer-events="none"/>
        <!-- Knee cap hint -->
        <ellipse cx="196" cy="482" rx="5" ry="4" fill="none" stroke="#b09878" stroke-width="0.4" opacity="0.3" pointer-events="none"/>
    </g>
    <text x="178" y="530" text-anchor="middle" class="ped-label" transform="rotate(-80 178 530)" :fill="selectedZones.includes('right_leg') ? '#312e81' : '#6a5a48'">R. Leg</text>

    {{-- ===== LEFT LEG ===== --}}
    <g @click="toggleZone('left_leg')" class="zone-area" filter="url(#ped-shadow)">
        <!-- Thigh -->
        <path d="M250 365 Q260 370 264 385 Q268 410 270 440 Q272 465 270 480
                 Q266 484 260 480 Q258 465 256 445 Q254 425 250 405 Q246 388 244 375 Z"
            :fill="selectedZones.includes('left_leg') ? 'url(#ped-selected)' : 'url(#ped-skin-limb)'"
            :stroke="selectedZones.includes('left_leg') ? '#4f46e5' : '#c4a882'"
            stroke-width="1.2"/>
        <!-- Lower leg -->
        <path d="M270 480 Q274 500 276 525 Q278 555 278 575 Q278 590 274 595
                 Q268 598 264 595 Q262 585 262 570 Q262 548 260 525 Q258 505 260 490 Z"
            :fill="selectedZones.includes('left_leg') ? 'url(#ped-selected)' : 'url(#ped-skin-limb)'"
            :stroke="selectedZones.includes('left_leg') ? '#4f46e5' : '#c4a882'"
            stroke-width="1"/>
        <!-- Foot -->
        <path d="M278 595 Q284 598 286 602 Q288 608 282 610 Q274 612 264 610 Q260 608 260 602 Q262 598 264 595 Z"
            :fill="selectedZones.includes('left_leg') ? 'url(#ped-selected)' : '#f0d4b8'"
            :stroke="selectedZones.includes('left_leg') ? '#4f46e5' : '#c4a882'"
            stroke-width="0.8"/>
        <!-- Growth plate indicators -->
        <rect x="258" y="478" width="14" height="3" rx="1" fill="url(#ped-growth-plate)" opacity="0.6" pointer-events="none"/>
        <rect x="266" y="592" width="12" height="3" rx="1" fill="url(#ped-growth-plate)" opacity="0.6" pointer-events="none"/>
        <!-- Knee cap hint -->
        <ellipse cx="264" cy="482" rx="5" ry="4" fill="none" stroke="#b09878" stroke-width="0.4" opacity="0.3" pointer-events="none"/>
    </g>
    <text x="282" y="530" text-anchor="middle" class="ped-label" transform="rotate(80 282 530)" :fill="selectedZones.includes('left_leg') ? '#312e81' : '#6a5a48'">L. Leg</text>

    {{-- ===== GROWTH PLATE LEGEND ===== --}}
    <g pointer-events="none" opacity="0.7">
        <rect x="355" y="460" width="12" height="3" rx="1" fill="url(#ped-growth-plate)"/>
        <text x="372" y="464" class="ped-label-sm" fill="#4a8ab0">Growth plates</text>
    </g>

    {{-- ===== SIDE LABELS WITH LINES ===== --}}
    <g pointer-events="none" style="font-family:sans-serif">
        <!-- Fontanelle annotation -->
        <line x1="240" y1="34" x2="310" y2="20" stroke="#b09878" stroke-width="0.4" stroke-dasharray="2,2"/>
        <text x="312" y="18" text-anchor="start" class="ped-label-sm" fill="#8a6a40">(soft spot - infants)</text>
    </g>

    <!-- Instruction -->
    <text x="230" y="650" text-anchor="middle" style="font-family:sans-serif;font-size:8px" fill="#94a3b8">Click on a body region to select or deselect it</text>
</svg>
