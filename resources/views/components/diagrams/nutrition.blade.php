{{-- Nutrition — Digestive System Anatomical Diagram --}}
{{-- Expects parent Alpine.js scope with: selectedZones, toggleZone(zoneId), zoneNotes --}}

<svg viewBox="0 0 480 720" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto max-w-lg mx-auto">
    <defs>
        <style>
            .dig-zone { cursor: pointer; transition: fill 0.25s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease, transform 0.25s ease, filter 0.25s ease; transform-origin: center; }
            .dig-zone:hover { opacity: 0.88; filter: drop-shadow(0 0 6px rgba(99,102,241,0.3)); transform: scale(1.015); }
            .dig-label { font-family: sans-serif; font-size: 10px; fill: #374151; pointer-events: none; font-weight: 500; }
            .dig-label-sm { font-family: sans-serif; font-size: 8px; fill: #6b7280; pointer-events: none; }
        </style>

        <!-- Organ shadow -->
        <filter id="organShadow" x="-5%" y="-5%" width="110%" height="115%">
            <feDropShadow dx="0" dy="1" stdDeviation="2" flood-color="#4a2020" flood-opacity="0.12"/>
        </filter>

        <!-- Inner glow for selected -->
        <filter id="organGlow" x="-15%" y="-15%" width="130%" height="130%">
            <feDropShadow dx="0" dy="0" stdDeviation="4" flood-color="#6366f1" flood-opacity="0.45"/>
        </filter>

        <!-- Stomach gradient -->
        <linearGradient id="stomachGrad" x1="0" y1="0" x2="0.4" y2="1">
            <stop offset="0%" stop-color="#f8a4a4"/>
            <stop offset="50%" stop-color="#e88888"/>
            <stop offset="100%" stop-color="#d47070"/>
        </linearGradient>

        <!-- Liver gradient -->
        <linearGradient id="liverGrad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#c96b5a"/>
            <stop offset="40%" stop-color="#a85545"/>
            <stop offset="100%" stop-color="#8b4035"/>
        </linearGradient>

        <!-- Esophagus gradient -->
        <linearGradient id="esophGrad" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#e8a898"/>
            <stop offset="50%" stop-color="#d4887a"/>
            <stop offset="100%" stop-color="#e8a898"/>
        </linearGradient>

        <!-- Intestine gradient -->
        <linearGradient id="smallIntGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#f0b0b8"/>
            <stop offset="100%" stop-color="#e09098"/>
        </linearGradient>

        <!-- Large intestine gradient -->
        <linearGradient id="colonGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#c8a0d8"/>
            <stop offset="100%" stop-color="#a880b8"/>
        </linearGradient>

        <!-- Pancreas gradient -->
        <linearGradient id="pancreasGrad" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#f0d890"/>
            <stop offset="100%" stop-color="#e0c870"/>
        </linearGradient>

        <!-- Gallbladder gradient -->
        <radialGradient id="gallGrad" cx="0.4" cy="0.3">
            <stop offset="0%" stop-color="#90d8a0"/>
            <stop offset="100%" stop-color="#60b870"/>
        </radialGradient>

        <!-- Mouth gradient -->
        <radialGradient id="mouthGrad" cx="0.5" cy="0.4">
            <stop offset="0%" stop-color="#f8b0b0"/>
            <stop offset="100%" stop-color="#e08888"/>
        </radialGradient>

        <!-- Rugae pattern for stomach -->
        <pattern id="rugae" patternUnits="userSpaceOnUse" width="12" height="8" patternTransform="rotate(-15)">
            <path d="M0 4 Q3 2 6 4 Q9 6 12 4" fill="none" stroke="#c0606020" stroke-width="0.6"/>
        </pattern>

        <!-- Villi pattern for small intestine -->
        <pattern id="villi" patternUnits="userSpaceOnUse" width="5" height="5">
            <circle cx="2.5" cy="2.5" r="0.8" fill="#c0707015"/>
        </pattern>

        <!-- Haustra pattern for colon -->
        <pattern id="haustra" patternUnits="userSpaceOnUse" width="14" height="14" patternTransform="rotate(0)">
            <path d="M0 7 Q3.5 4 7 7 Q10.5 10 14 7" fill="none" stroke="#90609020" stroke-width="0.8"/>
        </pattern>
    </defs>

    <!-- Title -->
    <text x="240" y="24" font-family="sans-serif" font-size="15" font-weight="700" fill="#1f2937" text-anchor="middle">
        Digestive System
    </text>

    <!-- Torso silhouette (faded anatomical reference) -->
    <path d="M130 45 Q240 28 350 45 L360 100 Q368 210 358 360 L348 470 Q335 550 325 610 L155 610 Q145 550 132 470 L122 360 Q112 210 120 100 Z"
          fill="#fef3c7" stroke="#d1a47b" stroke-width="0.8" opacity="0.12"/>

    <!-- Spine reference line -->
    <line x1="240" y1="50" x2="240" y2="640" stroke="#d4c4a8" stroke-width="0.4" stroke-dasharray="6,8" opacity="0.2"/>

    <!-- ==================== MOUTH / ORAL CAVITY ==================== -->
    <g @click="toggleZone('mouth')" class="cursor-pointer dig-zone" filter="url(#organShadow)">
        <!-- Oral cavity -->
        <path d="M218 50 Q225 42 240 38 Q255 42 262 50 Q268 60 265 72 Q258 82 248 86 Q240 88 232 86 Q222 82 215 72 Q212 60 218 50 Z"
              :fill="selectedZones.includes('mouth') ? '#818cf8' : 'url(#mouthGrad)'"
              stroke="#9c6060" stroke-width="1"/>
        <!-- Teeth hint -->
        <path d="M222 55 Q230 50 240 48 Q250 50 258 55" fill="none" stroke="#ffffff40" stroke-width="1.5"/>
        <!-- Tongue hint -->
        <ellipse cx="240" cy="68" rx="10" ry="6" fill="#d0707040" stroke="none"/>
        <!-- Salivary glands -->
        <ellipse cx="208" cy="62" rx="6" ry="4" fill="#f0c0a8" stroke="#c09878" stroke-width="0.5" opacity="0.7"/>
        <ellipse cx="272" cy="62" rx="6" ry="4" fill="#f0c0a8" stroke="#c09878" stroke-width="0.5" opacity="0.7"/>
        <!-- Salivary duct hints -->
        <path d="M214 62 Q218 65 222 66" fill="none" stroke="#c0987850" stroke-width="0.4"/>
        <path d="M266 62 Q262 65 258 66" fill="none" stroke="#c0987850" stroke-width="0.4"/>
    </g>
    <!-- Label -->
    <line x1="265" y1="58" x2="310" y2="50" stroke="#9ca3af" stroke-width="0.5"/>
    <text x="312" y="54" class="dig-label">Oral Cavity</text>
    <text x="312" y="64" class="dig-label-sm">Salivary glands</text>

    <!-- ==================== ESOPHAGUS ==================== -->
    <g @click="toggleZone('esophagus')" class="cursor-pointer dig-zone" filter="url(#organShadow)">
        <!-- Esophagus tube with slight curve -->
        <path d="M235 86 Q233 110 232 140 Q231 170 232 200 Q233 220 234 240 L246 240 Q247 220 248 200 Q249 170 248 140 Q247 110 245 86 Z"
              :fill="selectedZones.includes('esophagus') ? '#818cf8' : 'url(#esophGrad)'"
              stroke="#a07060" stroke-width="0.8"/>
        <!-- Peristalsis rings -->
        <path d="M234 105 Q240 102 246 105" fill="none" stroke="#a0706030" stroke-width="0.6"/>
        <path d="M233 130 Q240 127 247 130" fill="none" stroke="#a0706030" stroke-width="0.6"/>
        <path d="M232 155 Q240 152 248 155" fill="none" stroke="#a0706030" stroke-width="0.6"/>
        <path d="M232 180 Q240 177 248 180" fill="none" stroke="#a0706030" stroke-width="0.6"/>
        <path d="M233 205 Q240 202 247 205" fill="none" stroke="#a0706030" stroke-width="0.6"/>
        <path d="M234 228 Q240 225 246 228" fill="none" stroke="#a0706030" stroke-width="0.6"/>
        <!-- Mucosal lining hint -->
        <line x1="237" y1="90" x2="237" y2="236" stroke="#c0808018" stroke-width="0.4"/>
        <line x1="243" y1="90" x2="243" y2="236" stroke="#c0808018" stroke-width="0.4"/>
    </g>
    <line x1="248" y1="160" x2="310" y2="150" stroke="#9ca3af" stroke-width="0.5"/>
    <text x="312" y="154" class="dig-label">Esophagus</text>

    <!-- ==================== LIVER ==================== -->
    <g @click="toggleZone('liver')" class="cursor-pointer dig-zone" filter="url(#organShadow)">
        <!-- Main liver body - right lobe larger -->
        <path d="M148 218 Q142 210 150 198 Q168 186 200 184 Q232 182 265 186 Q290 192 305 204 Q312 215 308 230 Q298 252 270 262 Q240 268 210 265 Q178 260 158 245 Q145 232 148 218 Z"
              :fill="selectedZones.includes('liver') ? '#818cf8' : 'url(#liverGrad)'"
              stroke="#7a3828" stroke-width="1"/>
        <!-- Falciform ligament (divides lobes) -->
        <path d="M240 186 Q238 210 236 240 Q235 255 238 265"
              fill="none" stroke="#60282018" stroke-width="1.2"/>
        <!-- Portal vein hint -->
        <path d="M240 230 Q230 235 225 242" fill="none" stroke="#60404060" stroke-width="0.6"/>
        <!-- Surface texture -->
        <path d="M170 205 Q190 198 210 200" fill="none" stroke="#6028200a" stroke-width="0.5"/>
        <path d="M180 225 Q210 218 240 222" fill="none" stroke="#6028200a" stroke-width="0.5"/>
        <path d="M260 205 Q280 210 295 220" fill="none" stroke="#6028200a" stroke-width="0.5"/>
        <!-- Hepatic duct -->
        <path d="M238 258 L236 275 Q234 282 232 288" fill="none" stroke="#8a6838" stroke-width="1" stroke-linecap="round"/>
    </g>
    <line x1="310" y1="220" x2="360" y2="210" stroke="#9ca3af" stroke-width="0.5"/>
    <text x="362" y="214" class="dig-label">Liver</text>
    <text x="362" y="224" class="dig-label-sm">Hepatic ducts</text>

    <!-- ==================== GALLBLADDER ==================== -->
    <g @click="toggleZone('gallbladder')" class="cursor-pointer dig-zone" filter="url(#organShadow)">
        <!-- Pear-shaped gallbladder -->
        <path d="M272 248 Q276 242 282 244 Q290 252 290 268 Q288 282 282 290 Q276 294 272 288 Q266 278 268 262 Q268 252 272 248 Z"
              :fill="selectedZones.includes('gallbladder') ? '#818cf8' : 'url(#gallGrad)'"
              stroke="#408848" stroke-width="1"/>
        <!-- Cystic duct -->
        <path d="M272 256 Q262 262 254 272 Q248 280 244 288"
              fill="none" stroke="#408848" stroke-width="0.8" stroke-dasharray="2,1.5"/>
        <!-- Fundus highlight -->
        <ellipse cx="280" cy="280" rx="4" ry="5" fill="#ffffff15" stroke="none"/>
    </g>
    <line x1="292" y1="268" x2="360" y2="260" stroke="#9ca3af" stroke-width="0.5"/>
    <text x="362" y="264" class="dig-label">Gallbladder</text>

    <!-- ==================== STOMACH ==================== -->
    <g @click="toggleZone('stomach')" class="cursor-pointer dig-zone" filter="url(#organShadow)">
        <!-- J-shaped stomach -->
        <path d="M215 244 Q205 248 192 262 Q178 282 172 308 Q168 332 172 355 Q178 378 195 390 Q215 400 238 396 Q258 388 268 370 Q275 350 275 325 Q273 298 265 278 Q258 258 248 248 Q238 240 225 242 Z"
              :fill="selectedZones.includes('stomach') ? '#818cf8' : 'url(#stomachGrad)'"
              stroke="#a05050" stroke-width="1.2"/>
        <!-- Rugae folds (internal ridges) -->
        <path d="M195 270 Q210 265 225 270 Q238 275 248 268" fill="none" stroke="#c0606025" stroke-width="0.8"/>
        <path d="M188 290 Q205 284 220 290 Q235 296 250 288" fill="none" stroke="#c0606025" stroke-width="0.8"/>
        <path d="M178 312 Q198 305 215 312 Q232 318 252 310" fill="none" stroke="#c0606025" stroke-width="0.8"/>
        <path d="M176 335 Q196 328 215 335 Q232 340 255 332" fill="none" stroke="#c0606025" stroke-width="0.8"/>
        <path d="M180 355 Q200 348 218 355 Q235 360 255 352" fill="none" stroke="#c0606025" stroke-width="0.8"/>
        <path d="M190 372 Q208 366 225 372 Q240 376 252 370" fill="none" stroke="#c0606025" stroke-width="0.8"/>
        <!-- Rugae pattern overlay -->
        <path d="M215 244 Q205 248 192 262 Q178 282 172 308 Q168 332 172 355 Q178 378 195 390 Q215 400 238 396 Q258 388 268 370 Q275 350 275 325 Q273 298 265 278 Q258 258 248 248 Q238 240 225 242 Z"
              fill="url(#rugae)" stroke="none" opacity="0.5"/>
        <!-- Greater curvature highlight -->
        <path d="M192 262 Q178 285 172 315 Q170 345 178 375"
              fill="none" stroke="#ffffff18" stroke-width="1.5"/>
        <!-- Pyloric sphincter -->
        <ellipse cx="242" cy="395" rx="8" ry="4" fill="none" stroke="#a05050" stroke-width="0.8"/>
        <!-- Cardiac region -->
        <ellipse cx="222" cy="246" rx="6" ry="3" fill="none" stroke="#a05050" stroke-width="0.6"/>
    </g>
    <line x1="172" y1="330" x2="108" y2="325" stroke="#9ca3af" stroke-width="0.5"/>
    <text x="50" y="320" class="dig-label">Stomach</text>
    <text x="50" y="332" class="dig-label-sm">with rugae folds</text>

    <!-- ==================== PANCREAS ==================== -->
    <g @click="toggleZone('pancreas')" class="cursor-pointer dig-zone" filter="url(#organShadow)">
        <!-- Pancreas body - tadpole shape -->
        <path d="M178 398 Q190 392 218 394 Q248 396 278 402 Q300 408 312 416 Q318 424 312 430 Q300 434 278 430 Q248 426 218 422 Q190 420 178 424 Q168 420 168 412 Q168 404 178 398 Z"
              :fill="selectedZones.includes('pancreas') ? '#818cf8' : 'url(#pancreasGrad)'"
              stroke="#a09040" stroke-width="0.8"/>
        <!-- Pancreatic duct -->
        <path d="M240 410 L242 400 Q244 392 246 388"
              fill="none" stroke="#a09040" stroke-width="0.6" stroke-dasharray="1.5,1"/>
        <!-- Lobular texture -->
        <circle cx="200" cy="412" r="4" fill="#e0c86018" stroke="none"/>
        <circle cx="220" cy="408" r="5" fill="#e0c86018" stroke="none"/>
        <circle cx="245" cy="412" r="4.5" fill="#e0c86018" stroke="none"/>
        <circle cx="268" cy="416" r="4" fill="#e0c86018" stroke="none"/>
        <circle cx="290" cy="420" r="3.5" fill="#e0c86018" stroke="none"/>
        <!-- Head, body, tail labels -->
        <text x="305" y="422" font-family="sans-serif" font-size="5" fill="#a09040" opacity="0.6">head</text>
        <text x="178" y="416" font-family="sans-serif" font-size="5" fill="#a09040" opacity="0.6">tail</text>
    </g>
    <line x1="312" y1="420" x2="360" y2="416" stroke="#9ca3af" stroke-width="0.5"/>
    <text x="362" y="420" class="dig-label">Pancreas</text>

    <!-- ==================== SMALL INTESTINE ==================== -->
    <g @click="toggleZone('small_intestine')" class="cursor-pointer dig-zone" filter="url(#organShadow)">
        <!-- Duodenum (C-shape around pancreas head) -->
        <path d="M248 396 Q260 400 268 410 Q278 430 278 448 Q275 465 265 475 Q255 480 245 478 Q238 475 235 468"
              fill="none"
              :stroke="selectedZones.includes('small_intestine') ? '#818cf8' : '#d88898'"
              stroke-width="8" stroke-linecap="round" opacity="0.8"/>

        <!-- Jejunum and Ileum - coiled loops -->
        <path d="M235 468 Q225 478 210 480 Q195 478 188 468 Q182 458 190 448
                 Q200 440 215 442 Q230 445 240 455 Q248 465 242 478
                 Q235 488 220 492 Q205 490 195 482 Q185 472 192 462
                 Q200 454 215 456 Q228 460 235 470 Q240 480 235 492
                 Q228 500 215 504 Q200 502 192 494 Q185 485 190 476
                 Q198 468 212 470 Q225 474 232 484 Q236 494 230 505
                 Q222 512 210 514 Q198 512 192 504 Q186 496 192 488
                 Q200 482 212 484 Q222 488 228 498 Q232 508 226 516
                 Q218 522 208 520 Q198 516 195 508"
              fill="none"
              :stroke="selectedZones.includes('small_intestine') ? '#818cf8' : '#e09098'"
              stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>

        <!-- Villi texture overlay on coils -->
        <path d="M235 468 Q225 478 210 480 Q195 478 188 468 Q182 458 190 448
                 Q200 440 215 442 Q230 445 240 455 Q248 465 242 478
                 Q235 488 220 492 Q205 490 195 482 Q185 472 192 462
                 Q200 454 215 456 Q228 460 235 470 Q240 480 235 492
                 Q228 500 215 504 Q200 502 192 494 Q185 485 190 476
                 Q198 468 212 470 Q225 474 232 484 Q236 494 230 505
                 Q222 512 210 514 Q198 512 192 504 Q186 496 192 488
                 Q200 482 212 484 Q222 488 228 498 Q232 508 226 516
                 Q218 522 208 520 Q198 516 195 508"
              fill="none" stroke="url(#villi)" stroke-width="5" opacity="0.3"/>

        <!-- Section labels -->
        <text x="278" y="442" font-family="sans-serif" font-size="5.5" fill="#b06878" opacity="0.7">duodenum</text>
        <text x="245" y="462" font-family="sans-serif" font-size="5.5" fill="#b06878" opacity="0.7">jejunum</text>
        <text x="178" y="510" font-family="sans-serif" font-size="5.5" fill="#b06878" opacity="0.7">ileum</text>
    </g>
    <line x1="242" y1="480" x2="360" y2="478" stroke="#9ca3af" stroke-width="0.5"/>
    <text x="362" y="474" class="dig-label">Small Intestine</text>
    <text x="362" y="486" class="dig-label-sm">Duodenum, Jejunum, Ileum</text>

    <!-- ==================== LARGE INTESTINE (COLON) ==================== -->
    <g @click="toggleZone('large_intestine')" class="cursor-pointer dig-zone" filter="url(#organShadow)">
        <!-- Cecum -->
        <path d="M150 445 Q142 455 140 470 Q140 488 148 498 Q158 505 168 500 L170 485 Q168 470 162 458 Q158 450 150 445 Z"
              :fill="selectedZones.includes('large_intestine') ? '#818cf8' : 'url(#colonGrad)'"
              stroke="#705888" stroke-width="0.8" opacity="0.85"/>

        <!-- Ascending colon (right side going up) -->
        <path d="M148 445 Q142 430 140 410 Q138 388 140 368 Q142 350 148 340 L162 340 Q158 350 156 368 Q154 388 156 410 Q158 430 162 445 Z"
              :fill="selectedZones.includes('large_intestine') ? '#818cf8' : 'url(#colonGrad)'"
              stroke="#705888" stroke-width="0.8" opacity="0.85"/>

        <!-- Hepatic flexure -->
        <path d="M148 340 Q148 328 158 322 Q170 318 182 322 L182 336 Q170 332 162 336 Q156 340 162 345 Z"
              :fill="selectedZones.includes('large_intestine') ? '#818cf8' : 'url(#colonGrad)'"
              stroke="#705888" stroke-width="0.8" opacity="0.85"/>

        <!-- Transverse colon -->
        <path d="M182 322 Q210 315 240 312 Q270 315 298 322 L298 336 Q270 328 240 326 Q210 328 182 336 Z"
              :fill="selectedZones.includes('large_intestine') ? '#818cf8' : 'url(#colonGrad)'"
              stroke="#705888" stroke-width="0.8" opacity="0.85"/>

        <!-- Splenic flexure -->
        <path d="M298 322 Q310 318 320 322 Q330 328 330 340 L318 345 Q318 336 314 332 Q308 328 298 336 Z"
              :fill="selectedZones.includes('large_intestine') ? '#818cf8' : 'url(#colonGrad)'"
              stroke="#705888" stroke-width="0.8" opacity="0.85"/>

        <!-- Descending colon (left side going down) -->
        <path d="M318 340 Q322 355 324 375 Q326 400 324 425 Q322 445 318 460 L304 460 Q308 445 310 425 Q312 400 310 375 Q308 355 304 340 Z"
              :fill="selectedZones.includes('large_intestine') ? '#818cf8' : 'url(#colonGrad)'"
              stroke="#705888" stroke-width="0.8" opacity="0.85"/>

        <!-- Sigmoid colon -->
        <path d="M318 460 Q322 475 320 490 Q315 505 302 515 Q288 522 272 518 Q258 510 255 498 Q255 488 260 480 Q268 472 278 474 Q285 478 285 488"
              fill="none"
              :stroke="selectedZones.includes('large_intestine') ? '#818cf8' : '#b090c8'"
              stroke-width="12" stroke-linecap="round" opacity="0.75"/>

        <!-- Haustra markings on colon segments -->
        <!-- Ascending -->
        <path d="M144 360 Q152 356 160 360" fill="none" stroke="#70588820" stroke-width="0.6"/>
        <path d="M142 385 Q152 380 162 385" fill="none" stroke="#70588820" stroke-width="0.6"/>
        <path d="M144 410 Q152 406 160 410" fill="none" stroke="#70588820" stroke-width="0.6"/>
        <path d="M146 432 Q154 428 162 432" fill="none" stroke="#70588820" stroke-width="0.6"/>
        <!-- Transverse -->
        <path d="M200 318 Q200 326 200 332" fill="none" stroke="#70588820" stroke-width="0.6"/>
        <path d="M225 316 Q225 324 225 330" fill="none" stroke="#70588820" stroke-width="0.6"/>
        <path d="M250 316 Q250 324 250 330" fill="none" stroke="#70588820" stroke-width="0.6"/>
        <path d="M275 318 Q275 326 275 332" fill="none" stroke="#70588820" stroke-width="0.6"/>
        <!-- Descending -->
        <path d="M308 365 Q316 360 324 365" fill="none" stroke="#70588820" stroke-width="0.6"/>
        <path d="M310 390 Q318 386 326 390" fill="none" stroke="#70588820" stroke-width="0.6"/>
        <path d="M310 415 Q318 410 326 415" fill="none" stroke="#70588820" stroke-width="0.6"/>
        <path d="M310 440 Q318 436 324 440" fill="none" stroke="#70588820" stroke-width="0.6"/>

        <!-- Section labels on colon -->
        <text x="130" y="395" font-family="sans-serif" font-size="5" fill="#705888" opacity="0.6" transform="rotate(-90,130,395)">ascending</text>
        <text x="240" y="320" font-family="sans-serif" font-size="5" fill="#705888" opacity="0.6" text-anchor="middle">transverse</text>
        <text x="338" y="400" font-family="sans-serif" font-size="5" fill="#705888" opacity="0.6" transform="rotate(90,338,400)">descending</text>
        <text x="296" y="508" font-family="sans-serif" font-size="5" fill="#705888" opacity="0.6">sigmoid</text>
    </g>
    <line x1="140" y1="480" x2="88" y2="488" stroke="#9ca3af" stroke-width="0.5"/>
    <text x="30" y="484" class="dig-label">Large</text>
    <text x="30" y="496" class="dig-label">Intestine</text>
    <text x="30" y="507" class="dig-label-sm">Colon segments</text>

    <!-- ==================== APPENDIX ==================== -->
    <g @click="toggleZone('appendix')" class="cursor-pointer dig-zone" filter="url(#organShadow)">
        <path d="M152 500 Q148 508 142 520 Q138 532 140 540 Q144 546 150 544 Q156 538 158 528 Q158 516 155 506 Z"
              :fill="selectedZones.includes('appendix') ? '#818cf8' : '#f0c848'"
              stroke="#b09830" stroke-width="0.8"/>
        <!-- Vermiform texture -->
        <path d="M150 510 Q146 520 144 530" fill="none" stroke="#b0983020" stroke-width="0.4"/>
    </g>
    <line x1="142" y1="538" x2="88" y2="545" stroke="#9ca3af" stroke-width="0.5"/>
    <text x="40" y="549" class="dig-label">Appendix</text>

    <!-- ==================== RECTUM ==================== -->
    <g @click="toggleZone('rectum')" class="cursor-pointer dig-zone" filter="url(#organShadow)">
        <path d="M268 520 Q262 530 258 548 Q256 568 260 585 Q264 598 272 604 Q280 608 288 604 Q296 595 298 578 Q298 558 294 540 Q290 525 282 518 Q275 515 268 520 Z"
              :fill="selectedZones.includes('rectum') ? '#818cf8' : '#e89090'"
              stroke="#a06060" stroke-width="0.8"/>
        <!-- Internal ridges -->
        <path d="M266 545 Q275 540 286 545" fill="none" stroke="#a0606025" stroke-width="0.5"/>
        <path d="M262 565 Q275 560 292 565" fill="none" stroke="#a0606025" stroke-width="0.5"/>
        <path d="M264 582 Q276 577 294 582" fill="none" stroke="#a0606025" stroke-width="0.5"/>
        <!-- Anal sphincter hint -->
        <ellipse cx="278" cy="602" rx="6" ry="3" fill="none" stroke="#a06060" stroke-width="0.5"/>
    </g>
    <line x1="298" y1="570" x2="360" y2="568" stroke="#9ca3af" stroke-width="0.5"/>
    <text x="362" y="572" class="dig-label">Rectum</text>

    <!-- ==================== CONNECTION ANNOTATIONS ==================== -->
    <!-- Common bile duct connection hint (liver -> duodenum) -->
    <path d="M236 288 Q238 300 240 320 Q242 340 244 360 Q245 380 246 392"
          fill="none" stroke="#8a6838" stroke-width="0.5" stroke-dasharray="2,2" opacity="0.4"/>
    <text x="250" y="360" font-family="sans-serif" font-size="4.5" fill="#8a6838" opacity="0.4" transform="rotate(-85,250,360)">common bile duct</text>

    <!-- Blood vessel hints around organs -->
    <path d="M240 240 Q230 236 220 240" fill="none" stroke="#c0404040" stroke-width="0.3" stroke-dasharray="1,2"/>
    <path d="M240 250 Q248 246 258 250" fill="none" stroke="#4040c040" stroke-width="0.3" stroke-dasharray="1,2"/>

    <!-- Title bar -->
    <text x="240" y="690" font-family="sans-serif" font-size="14" font-weight="700" fill="#1f2937" text-anchor="middle">
        Nutrition — Digestive System
    </text>
    <text x="240" y="706" font-family="sans-serif" font-size="9" fill="#9ca3af" text-anchor="middle">
        Click any organ to select
    </text>
</svg>
