<svg viewBox="0 0 500 620" class="w-full max-w-md mx-auto" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <!-- Gradients -->
        <radialGradient id="im-lung-grad" cx="40%" cy="35%" r="65%">
            <stop offset="0%" stop-color="#e8b4b8"/>
            <stop offset="50%" stop-color="#d4979c"/>
            <stop offset="100%" stop-color="#b87880"/>
        </radialGradient>
        <radialGradient id="im-heart-grad" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#d44050"/>
            <stop offset="60%" stop-color="#a82030"/>
            <stop offset="100%" stop-color="#801520"/>
        </radialGradient>
        <radialGradient id="im-liver-grad" cx="40%" cy="35%" r="60%">
            <stop offset="0%" stop-color="#a0422a"/>
            <stop offset="50%" stop-color="#7a2e1a"/>
            <stop offset="100%" stop-color="#5c1e10"/>
        </radialGradient>
        <radialGradient id="im-stomach-grad" cx="45%" cy="40%" r="60%">
            <stop offset="0%" stop-color="#e8b090"/>
            <stop offset="50%" stop-color="#d49878"/>
            <stop offset="100%" stop-color="#c08060"/>
        </radialGradient>
        <radialGradient id="im-spleen-grad" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#9060a0"/>
            <stop offset="100%" stop-color="#603870"/>
        </radialGradient>
        <radialGradient id="im-kidney-grad" cx="40%" cy="35%" r="60%">
            <stop offset="0%" stop-color="#c06060"/>
            <stop offset="50%" stop-color="#a04545"/>
            <stop offset="100%" stop-color="#803030"/>
        </radialGradient>
        <radialGradient id="im-gb-grad" cx="40%" cy="30%" r="60%">
            <stop offset="0%" stop-color="#70b870"/>
            <stop offset="100%" stop-color="#408040"/>
        </radialGradient>
        <radialGradient id="im-pancreas-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#f0c898"/>
            <stop offset="100%" stop-color="#d0a070"/>
        </radialGradient>
        <radialGradient id="im-si-grad" cx="50%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#e8a0b0"/>
            <stop offset="100%" stop-color="#c87888"/>
        </radialGradient>
        <radialGradient id="im-li-grad" cx="50%" cy="50%" r="55%">
            <stop offset="0%" stop-color="#b898c8"/>
            <stop offset="100%" stop-color="#8868a0"/>
        </radialGradient>
        <radialGradient id="im-bladder-grad" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#80c8e8"/>
            <stop offset="100%" stop-color="#5098c0"/>
        </radialGradient>
        <linearGradient id="im-skin-grad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#f5e6d8"/>
            <stop offset="100%" stop-color="#e8d4c0"/>
        </linearGradient>

        <!-- Shadow filter -->
        <filter id="im-organ-shadow" x="-8%" y="-8%" width="116%" height="116%">
            <feDropShadow dx="0.5" dy="1" stdDeviation="1.5" flood-color="#00000030"/>
        </filter>
        <filter id="im-inner-glow" x="-15%" y="-15%" width="130%" height="130%">
            <feGaussianBlur stdDeviation="2" result="blur"/>
            <feComposite in="SourceGraphic" in2="blur" operator="over"/>
        </filter>

        <!-- Lung texture pattern -->
        <pattern id="im-lung-texture" width="8" height="8" patternUnits="userSpaceOnUse">
            <circle cx="4" cy="4" r="1.2" fill="#c0808a" opacity="0.3"/>
            <circle cx="1" cy="1" r="0.8" fill="#c0808a" opacity="0.2"/>
            <circle cx="7" cy="6" r="0.6" fill="#c0808a" opacity="0.25"/>
        </pattern>

        <!-- Intestine texture -->
        <pattern id="im-intestine-texture" width="6" height="6" patternUnits="userSpaceOnUse">
            <path d="M0 3 Q3 1 6 3" fill="none" stroke="#a06070" stroke-width="0.3" opacity="0.3"/>
        </pattern>

        <!-- Selected state gradient -->
        <radialGradient id="im-selected-grad" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#a5b4fc"/>
            <stop offset="100%" stop-color="#818cf8"/>
        </radialGradient>
    </defs>

    <!-- Title -->
    <text x="250" y="22" text-anchor="middle" class="text-[13px] font-bold" fill="#1e293b" style="font-family: sans-serif;">Internal Organs — Anterior View</text>

    <!-- Torso outline with skin tone -->
    <path d="M175 42 Q195 34 250 34 Q305 34 325 42 L345 68 Q365 100 360 140 L355 200 Q352 250 348 300 L342 370 Q338 420 330 460 Q310 490 250 498 Q190 490 170 460 Q162 420 158 370 L152 300 Q148 250 145 200 L140 140 Q135 100 155 68 Z"
        fill="url(#im-skin-grad)" stroke="#c4a882" stroke-width="1.8"/>

    <!-- Rib hints -->
    <g opacity="0.15" stroke="#8a7060" stroke-width="0.8" fill="none">
        <path d="M180 80 Q215 70 250 68 Q285 70 320 80"/>
        <path d="M172 98 Q215 86 250 84 Q285 86 328 98"/>
        <path d="M168 116 Q215 102 250 100 Q285 102 332 116"/>
        <path d="M165 134 Q215 118 250 116 Q285 118 335 134"/>
        <path d="M164 152 Q215 134 250 132 Q285 134 336 152"/>
        <path d="M165 170 Q215 152 250 150 Q285 152 335 170"/>
        <path d="M168 188 Q215 170 250 168 Q285 170 332 188"/>
        <path d="M172 206 Q215 190 250 188 Q285 190 328 206"/>
        <path d="M175 222 Q215 208 250 206 Q285 208 325 222"/>
        <path d="M178 238 Q215 226 250 224 Q285 226 322 238"/>
    </g>

    <!-- Sternum hint -->
    <line x1="250" y1="55" x2="250" y2="220" stroke="#8a7060" stroke-width="0.6" opacity="0.12"/>

    <!-- Clavicle hints -->
    <path d="M205 50 Q230 46 250 48 Q270 46 295 50" fill="none" stroke="#8a7060" stroke-width="0.8" opacity="0.15"/>

    <!-- ==================== ORGANS ==================== -->

    <!-- Large Intestine (behind other organs) -->
    <g @click="toggleZone('large_intestine')" class="cursor-pointer" :class="isSelected('large_intestine') ? 'opacity-100' : 'opacity-75 hover:opacity-90'" filter="url(#im-organ-shadow)">
        <path d="M178 310 L178 270 Q178 256 192 252 L196 252 Q200 252 200 262 L200 290
                 Q200 298 208 298 L292 298 Q300 298 300 290 L300 262 Q300 252 304 252 L308 252
                 Q322 256 322 270 L322 310 L322 400 Q322 418 304 422 L196 422 Q178 418 178 400 Z"
            :fill="isSelected('large_intestine') ? 'url(#im-selected-grad)' : 'url(#im-li-grad)'"
            :stroke="isSelected('large_intestine') ? '#4f46e5' : '#6a4880'"
            stroke-width="1.2" fill-opacity="0.85"/>
        <!-- Haustra markings -->
        <g :opacity="isSelected('large_intestine') ? '0.2' : '0.25'" stroke="#604070" stroke-width="0.5" fill="none" pointer-events="none">
            <path d="M178 280 Q185 276 178 272"/>
            <path d="M178 300 Q185 296 178 292"/>
            <path d="M178 330 Q185 326 178 322"/>
            <path d="M178 360 Q185 356 178 352"/>
            <path d="M178 390 Q185 386 178 382"/>
            <path d="M322 280 Q315 276 322 272"/>
            <path d="M322 300 Q315 296 322 292"/>
            <path d="M322 330 Q315 326 322 322"/>
            <path d="M322 360 Q315 356 322 352"/>
            <path d="M322 390 Q315 386 322 382"/>
            <path d="M210 422 Q210 415 220 422"/>
            <path d="M240 422 Q240 415 250 422"/>
            <path d="M270 422 Q270 415 280 422"/>
        </g>
        <text x="250" y="416" text-anchor="middle" class="text-[8px] font-semibold" style="font-family:sans-serif" :fill="isSelected('large_intestine') ? '#fff' : '#fff'" pointer-events="none">Large Intestine</text>
    </g>

    <!-- Small Intestine -->
    <g @click="toggleZone('small_intestine')" class="cursor-pointer" :class="isSelected('small_intestine') ? 'opacity-100' : 'opacity-75 hover:opacity-90'" filter="url(#im-organ-shadow)">
        <path d="M205 310 Q195 320 208 330 Q220 340 208 350 Q195 360 208 370
                 Q220 380 232 370 Q244 360 232 350 Q220 340 232 330 Q244 320 256 330
                 Q268 340 256 350 Q244 360 256 370 Q268 380 280 370 Q295 358 280 348
                 Q268 340 280 330 Q295 318 280 310 Q268 302 256 310 Q244 318 232 310
                 Q220 302 205 310 Z"
            :fill="isSelected('small_intestine') ? 'url(#im-selected-grad)' : 'url(#im-si-grad)'"
            :stroke="isSelected('small_intestine') ? '#4f46e5' : '#a06070'"
            stroke-width="0.8"/>
        <rect x="200" y="305" width="100" height="75" fill="url(#im-intestine-texture)" opacity="0.3" pointer-events="none"/>
        <text x="250" y="395" text-anchor="middle" class="text-[7px] font-semibold" style="font-family:sans-serif" :fill="isSelected('small_intestine') ? '#4338ca' : '#6e3040'" pointer-events="none">Small Intestine</text>
    </g>

    <!-- Left Lung -->
    <g @click="toggleZone('lungs_left')" class="cursor-pointer" :class="isSelected('lungs_left') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" filter="url(#im-organ-shadow)">
        <path d="M192 72 Q185 68 180 75 Q168 95 164 120 Q160 150 162 178 Q165 200 172 215
                 Q178 225 188 228 Q198 230 208 224 Q218 218 222 200 Q226 180 225 155
                 Q224 130 220 105 Q216 82 205 72 Q198 68 192 72 Z"
            :fill="isSelected('lungs_left') ? 'url(#im-selected-grad)' : 'url(#im-lung-grad)'"
            :stroke="isSelected('lungs_left') ? '#4f46e5' : '#986068'"
            stroke-width="1"/>
        <!-- Bronchial tree hint -->
        <g :opacity="isSelected('lungs_left') ? '0.25' : '0.3'" stroke="#804850" stroke-width="0.6" fill="none" pointer-events="none">
            <path d="M215 88 L200 105 L190 125"/>
            <path d="M200 105 L205 128"/>
            <path d="M190 125 L182 148"/>
            <path d="M190 125 L196 150"/>
            <path d="M205 128 L200 155"/>
            <path d="M205 128 L212 150"/>
        </g>
        <!-- Lung texture overlay -->
        <path d="M192 72 Q185 68 180 75 Q168 95 164 120 Q160 150 162 178 Q165 200 172 215
                 Q178 225 188 228 Q198 230 208 224 Q218 218 222 200 Q226 180 225 155
                 Q224 130 220 105 Q216 82 205 72 Q198 68 192 72 Z"
            fill="url(#im-lung-texture)" pointer-events="none" opacity="0.5"/>
        <!-- Horizontal fissure hint -->
        <path d="M168 140 Q190 135 218 145" fill="none" stroke="#905058" stroke-width="0.5" opacity="0.3" pointer-events="none"/>
        <text x="192" y="155" text-anchor="middle" class="text-[8px] font-semibold" style="font-family:sans-serif" :fill="isSelected('lungs_left') ? '#fff' : '#fff'" pointer-events="none">L. Lung</text>
    </g>

    <!-- Right Lung -->
    <g @click="toggleZone('lungs_right')" class="cursor-pointer" :class="isSelected('lungs_right') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" filter="url(#im-organ-shadow)">
        <path d="M308 72 Q315 68 320 75 Q332 95 336 120 Q340 150 338 178 Q335 200 328 215
                 Q322 225 312 228 Q302 230 292 224 Q282 218 278 200 Q274 180 275 155
                 Q276 130 280 105 Q284 82 295 72 Q302 68 308 72 Z"
            :fill="isSelected('lungs_right') ? 'url(#im-selected-grad)' : 'url(#im-lung-grad)'"
            :stroke="isSelected('lungs_right') ? '#4f46e5' : '#986068'"
            stroke-width="1"/>
        <!-- Bronchial tree -->
        <g :opacity="isSelected('lungs_right') ? '0.25' : '0.3'" stroke="#804850" stroke-width="0.6" fill="none" pointer-events="none">
            <path d="M285 88 L300 105 L310 125"/>
            <path d="M300 105 L295 128"/>
            <path d="M310 125 L318 148"/>
            <path d="M310 125 L304 150"/>
            <path d="M295 128 L300 155"/>
            <path d="M295 128 L288 150"/>
        </g>
        <path d="M308 72 Q315 68 320 75 Q332 95 336 120 Q340 150 338 178 Q335 200 328 215
                 Q322 225 312 228 Q302 230 292 224 Q282 218 278 200 Q274 180 275 155
                 Q276 130 280 105 Q284 82 295 72 Q302 68 308 72 Z"
            fill="url(#im-lung-texture)" pointer-events="none" opacity="0.5"/>
        <!-- Fissures -->
        <path d="M332 128 Q310 125 282 135" fill="none" stroke="#905058" stroke-width="0.5" opacity="0.3" pointer-events="none"/>
        <path d="M330 160 Q310 155 285 162" fill="none" stroke="#905058" stroke-width="0.5" opacity="0.3" pointer-events="none"/>
        <text x="308" y="155" text-anchor="middle" class="text-[8px] font-semibold" style="font-family:sans-serif" :fill="isSelected('lungs_right') ? '#fff' : '#fff'" pointer-events="none">R. Lung</text>
    </g>

    <!-- Heart -->
    <g @click="toggleZone('heart')" class="cursor-pointer" :class="isSelected('heart') ? 'opacity-100' : 'opacity-85 hover:opacity-100'" filter="url(#im-organ-shadow)">
        <path d="M234 98 Q228 88 238 82 Q248 78 256 86 Q260 78 270 78 Q280 82 278 92
                 L270 118 Q262 138 250 150 Q240 158 234 154 Q228 148 226 135 Q224 120 234 98 Z"
            :fill="isSelected('heart') ? 'url(#im-selected-grad)' : 'url(#im-heart-grad)'"
            :stroke="isSelected('heart') ? '#4f46e5' : '#601018'"
            stroke-width="1.2"/>
        <!-- Heart detail lines -->
        <g :opacity="isSelected('heart') ? '0.2' : '0.3'" stroke="#400810" stroke-width="0.4" fill="none" pointer-events="none">
            <path d="M245 92 Q252 100 258 92"/>
            <path d="M235 110 Q248 118 268 108"/>
            <path d="M238 128 Q248 135 260 125"/>
        </g>
        <!-- Aorta hint -->
        <path d="M250 82 Q250 70 258 62 Q268 56 278 60" fill="none" :stroke="isSelected('heart') ? '#4338ca' : '#801020'" stroke-width="1.2" pointer-events="none" :opacity="isSelected('heart') ? '0.4' : '0.5'"/>
        <text x="252" y="122" text-anchor="middle" class="text-[8px] font-bold" style="font-family:sans-serif" fill="#fff" pointer-events="none">Heart</text>
    </g>

    <!-- Liver -->
    <g @click="toggleZone('liver')" class="cursor-pointer" :class="isSelected('liver') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" filter="url(#im-organ-shadow)">
        <path d="M252 222 Q275 218 302 222 Q325 228 335 242 Q340 252 332 262
                 Q320 275 298 280 Q275 284 258 280 Q242 276 235 265 Q230 255 235 245
                 Q240 232 252 222 Z"
            :fill="isSelected('liver') ? 'url(#im-selected-grad)' : 'url(#im-liver-grad)'"
            :stroke="isSelected('liver') ? '#4f46e5' : '#401008'"
            stroke-width="1.2"/>
        <!-- Liver lobe division -->
        <path d="M278 225 Q275 250 270 278" fill="none" :stroke="isSelected('liver') ? '#3730a3' : '#301008'" stroke-width="0.6" :opacity="isSelected('liver') ? '0.3' : '0.4'" pointer-events="none"/>
        <!-- Surface texture -->
        <g :opacity="isSelected('liver') ? '0.15' : '0.2'" pointer-events="none">
            <path d="M260 235 Q270 233 280 236" fill="none" stroke="#200808" stroke-width="0.4"/>
            <path d="M290 240 Q305 238 318 245" fill="none" stroke="#200808" stroke-width="0.4"/>
            <path d="M255 255 Q270 252 285 256" fill="none" stroke="#200808" stroke-width="0.4"/>
            <path d="M295 258 Q310 256 322 262" fill="none" stroke="#200808" stroke-width="0.4"/>
        </g>
        <text x="285" y="256" text-anchor="middle" class="text-[9px] font-bold" style="font-family:sans-serif" fill="#fff" pointer-events="none">Liver</text>
    </g>

    <!-- Gallbladder -->
    <g @click="toggleZone('gallbladder')" class="cursor-pointer" :class="isSelected('gallbladder') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" filter="url(#im-organ-shadow)">
        <path d="M262 278 Q258 272 260 265 Q264 258 270 258 Q276 258 278 265 Q280 272 276 280
                 Q272 288 268 292 Q264 288 262 278 Z"
            :fill="isSelected('gallbladder') ? 'url(#im-selected-grad)' : 'url(#im-gb-grad)'"
            :stroke="isSelected('gallbladder') ? '#4f46e5' : '#2d5a2d'"
            stroke-width="0.8"/>
        <!-- Label with line -->
        <line x1="268" y1="292" x2="268" y2="304" :stroke="isSelected('gallbladder') ? '#4f46e5' : '#2d5a2d'" stroke-width="0.5" pointer-events="none"/>
        <text x="268" y="312" text-anchor="middle" class="text-[6px] font-semibold" style="font-family:sans-serif" :fill="isSelected('gallbladder') ? '#4338ca' : '#1a3a1a'" pointer-events="none">Gallbladder</text>
    </g>

    <!-- Stomach -->
    <g @click="toggleZone('stomach')" class="cursor-pointer" :class="isSelected('stomach') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" filter="url(#im-organ-shadow)">
        <path d="M198 230 Q188 228 180 235 Q170 245 168 260 Q166 278 172 292
                 Q178 305 190 312 Q202 316 214 310 Q226 302 230 288 Q234 272 228 255
                 Q222 240 212 232 Q205 228 198 230 Z"
            :fill="isSelected('stomach') ? 'url(#im-selected-grad)' : 'url(#im-stomach-grad)'"
            :stroke="isSelected('stomach') ? '#4f46e5' : '#8a5a3a'"
            stroke-width="1"/>
        <!-- Rugae (stomach folds) -->
        <g :opacity="isSelected('stomach') ? '0.2' : '0.3'" stroke="#7a4a2a" stroke-width="0.5" fill="none" pointer-events="none">
            <path d="M182 250 Q195 245 215 252"/>
            <path d="M178 265 Q195 260 222 268"/>
            <path d="M175 280 Q192 275 220 282"/>
            <path d="M180 295 Q195 290 215 296"/>
        </g>
        <!-- Esophagus connection -->
        <path d="M198 230 Q200 218 204 210" fill="none" :stroke="isSelected('stomach') ? '#4338ca' : '#8a5a3a'" stroke-width="1" :opacity="isSelected('stomach') ? '0.4' : '0.5'" pointer-events="none"/>
        <text x="198" y="275" text-anchor="middle" class="text-[8px] font-bold" style="font-family:sans-serif" :fill="isSelected('stomach') ? '#fff' : '#4a2a18'" pointer-events="none">Stomach</text>
    </g>

    <!-- Spleen -->
    <g @click="toggleZone('spleen')" class="cursor-pointer" :class="isSelected('spleen') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" filter="url(#im-organ-shadow)">
        <ellipse cx="162" cy="250" rx="18" ry="26"
            :fill="isSelected('spleen') ? 'url(#im-selected-grad)' : 'url(#im-spleen-grad)'"
            :stroke="isSelected('spleen') ? '#4f46e5' : '#402050'"
            stroke-width="0.8" transform="rotate(-15 162 250)"/>
        <!-- Hilum -->
        <path d="M174 248 Q178 250 174 252" fill="none" stroke="#301840" stroke-width="0.5" opacity="0.4" pointer-events="none"/>
        <text x="162" y="254" text-anchor="middle" class="text-[7px] font-semibold" style="font-family:sans-serif" fill="#fff" pointer-events="none">Spleen</text>
    </g>

    <!-- Pancreas -->
    <g @click="toggleZone('pancreas')" class="cursor-pointer" :class="isSelected('pancreas') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" filter="url(#im-organ-shadow)">
        <path d="M186 290 Q200 284 220 282 Q240 280 258 282 Q272 285 278 292
                 Q282 298 275 302 Q260 308 240 306 Q220 304 200 306 Q188 308 184 302
                 Q180 296 186 290 Z"
            :fill="isSelected('pancreas') ? 'url(#im-selected-grad)' : 'url(#im-pancreas-grad)'"
            :stroke="isSelected('pancreas') ? '#4f46e5' : '#9a7040'"
            stroke-width="0.8"/>
        <!-- Lobular texture -->
        <g :opacity="isSelected('pancreas') ? '0.15' : '0.2'" stroke="#806030" stroke-width="0.3" fill="none" pointer-events="none">
            <path d="M200 290 Q205 288 210 290"/>
            <path d="M220 288 Q225 286 230 288"/>
            <path d="M240 286 Q245 284 250 286"/>
            <path d="M258 288 Q263 286 268 290"/>
        </g>
        <text x="232" y="298" text-anchor="middle" class="text-[7px] font-semibold" style="font-family:sans-serif" :fill="isSelected('pancreas') ? '#fff' : '#5a3818'" pointer-events="none">Pancreas</text>
    </g>

    <!-- Left Kidney -->
    <g @click="toggleZone('kidneys_left')" class="cursor-pointer" :class="isSelected('kidneys_left') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" filter="url(#im-organ-shadow)">
        <path d="M160 295 Q152 290 148 300 Q144 315 146 330 Q148 345 155 352
                 Q162 358 168 352 Q174 342 174 325 Q174 310 170 300 Q166 294 160 295 Z"
            :fill="isSelected('kidneys_left') ? 'url(#im-selected-grad)' : 'url(#im-kidney-grad)'"
            :stroke="isSelected('kidneys_left') ? '#4f46e5' : '#602020'"
            stroke-width="0.8"/>
        <!-- Hilum notch -->
        <path d="M168 318 Q172 322 172 328 Q172 334 168 338" fill="none" :stroke="isSelected('kidneys_left') ? '#3730a3' : '#401515'" stroke-width="0.6" :opacity="isSelected('kidneys_left') ? '0.4' : '0.5'" pointer-events="none"/>
        <text x="158" y="328" text-anchor="middle" class="text-[6px] font-semibold" style="font-family:sans-serif" fill="#fff" pointer-events="none">L.Kid</text>
    </g>

    <!-- Right Kidney -->
    <g @click="toggleZone('kidneys_right')" class="cursor-pointer" :class="isSelected('kidneys_right') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" filter="url(#im-organ-shadow)">
        <path d="M340 305 Q348 300 352 310 Q356 325 354 340 Q352 355 345 362
                 Q338 368 332 362 Q326 352 326 335 Q326 320 330 310 Q334 304 340 305 Z"
            :fill="isSelected('kidneys_right') ? 'url(#im-selected-grad)' : 'url(#im-kidney-grad)'"
            :stroke="isSelected('kidneys_right') ? '#4f46e5' : '#602020'"
            stroke-width="0.8"/>
        <!-- Hilum notch -->
        <path d="M332 328 Q328 332 328 338 Q328 344 332 348" fill="none" :stroke="isSelected('kidneys_right') ? '#3730a3' : '#401515'" stroke-width="0.6" :opacity="isSelected('kidneys_right') ? '0.4' : '0.5'" pointer-events="none"/>
        <text x="342" y="338" text-anchor="middle" class="text-[6px] font-semibold" style="font-family:sans-serif" fill="#fff" pointer-events="none">R.Kid</text>
    </g>

    <!-- Bladder -->
    <g @click="toggleZone('bladder')" class="cursor-pointer" :class="isSelected('bladder') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" filter="url(#im-organ-shadow)">
        <path d="M235 448 Q228 440 228 452 Q228 468 238 476 Q248 482 258 476
                 Q268 468 268 452 Q268 440 262 448 Q250 456 235 448 Z"
            :fill="isSelected('bladder') ? 'url(#im-selected-grad)' : 'url(#im-bladder-grad)'"
            :stroke="isSelected('bladder') ? '#4f46e5' : '#2a6888'"
            stroke-width="1"/>
        <!-- Ureters hint -->
        <g :opacity="isSelected('bladder') ? '0.3' : '0.4'" pointer-events="none">
            <path d="M172 355 Q200 400 240 448" fill="none" stroke="#4088a8" stroke-width="0.6" stroke-dasharray="2,2"/>
            <path d="M328 365 Q300 410 258 448" fill="none" stroke="#4088a8" stroke-width="0.6" stroke-dasharray="2,2"/>
        </g>
        <text x="248" y="468" text-anchor="middle" class="text-[7px] font-bold" style="font-family:sans-serif" :fill="isSelected('bladder') ? '#fff' : '#fff'" pointer-events="none">Bladder</text>
    </g>

    <!-- Side labels with connector lines -->
    <g style="font-family:sans-serif" class="text-[7px]" fill="#475569" pointer-events="none">
        <!-- Left side labels -->
        <line x1="162" y1="150" x2="130" y2="145" stroke="#94a3b8" stroke-width="0.5"/>
        <text x="128" y="148" text-anchor="end" class="text-[7px]" fill="#475569">L. Lung</text>

        <line x1="162" y1="250" x2="128" y2="248" stroke="#94a3b8" stroke-width="0.5"/>
        <text x="126" y="251" text-anchor="end" class="text-[7px]" fill="#475569">Spleen</text>

        <line x1="148" y1="325" x2="120" y2="322" stroke="#94a3b8" stroke-width="0.5"/>
        <text x="118" y="325" text-anchor="end" class="text-[7px]" fill="#475569">L. Kidney</text>

        <!-- Right side labels -->
        <line x1="338" y1="150" x2="370" y2="145" stroke="#94a3b8" stroke-width="0.5"/>
        <text x="372" y="148" text-anchor="start" class="text-[7px]" fill="#475569">R. Lung</text>

        <line x1="335" y1="252" x2="370" y2="248" stroke="#94a3b8" stroke-width="0.5"/>
        <text x="372" y="251" text-anchor="start" class="text-[7px]" fill="#475569">Liver</text>

        <line x1="354" y1="335" x2="378" y2="332" stroke="#94a3b8" stroke-width="0.5"/>
        <text x="380" y="335" text-anchor="start" class="text-[7px]" fill="#475569">R. Kidney</text>
    </g>

    <!-- Instruction text -->
    <text x="250" y="610" text-anchor="middle" class="text-[8px]" fill="#94a3b8" style="font-family:sans-serif">Click on an organ to select or deselect it</text>
</svg>
