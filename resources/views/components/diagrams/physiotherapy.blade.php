{{-- Physiotherapy — Muscular System Anatomical Diagram (Front View) --}}
{{-- Expects parent Alpine.js scope with: selectedZones (array), toggleZone(id), isSelected(id) --}}

<svg viewBox="0 0 500 780" class="w-full max-w-md mx-auto" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <!-- Muscle base gradient -->
        <linearGradient id="muscleGrad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#d4a8a0"/>
            <stop offset="50%" stop-color="#c9918a"/>
            <stop offset="100%" stop-color="#b87a72"/>
        </linearGradient>
        <!-- Skin tone -->
        <linearGradient id="skinGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#f5deb3"/>
            <stop offset="100%" stop-color="#e8cfa0"/>
        </linearGradient>
        <!-- Pectoral gradient -->
        <linearGradient id="pectoralGrad" x1="0" y1="0" x2="0.3" y2="1">
            <stop offset="0%" stop-color="#d4908a"/>
            <stop offset="100%" stop-color="#c07a72"/>
        </linearGradient>
        <!-- Quad gradient -->
        <linearGradient id="quadGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#c9918a"/>
            <stop offset="100%" stop-color="#b5756d"/>
        </linearGradient>
        <!-- Deltoid gradient -->
        <linearGradient id="deltoidGrad" x1="0.5" y1="0" x2="0.5" y2="1">
            <stop offset="0%" stop-color="#d4968e"/>
            <stop offset="100%" stop-color="#be7e76"/>
        </linearGradient>
        <!-- Abdominal gradient -->
        <linearGradient id="absGrad" x1="0.5" y1="0" x2="0.5" y2="1">
            <stop offset="0%" stop-color="#d4a098"/>
            <stop offset="100%" stop-color="#c08a82"/>
        </linearGradient>
        <!-- Calf gradient -->
        <linearGradient id="calfGrad" x1="0.5" y1="0" x2="0.5" y2="1">
            <stop offset="0%" stop-color="#c99088"/>
            <stop offset="100%" stop-color="#b07068"/>
        </linearGradient>
        <!-- Muscle fiber pattern -->
        <pattern id="fiberPattern" patternUnits="userSpaceOnUse" width="6" height="6" patternTransform="rotate(15)">
            <line x1="0" y1="0" x2="0" y2="6" stroke="#00000008" stroke-width="0.8"/>
        </pattern>
        <pattern id="fiberH" patternUnits="userSpaceOnUse" width="6" height="6" patternTransform="rotate(75)">
            <line x1="0" y1="0" x2="0" y2="6" stroke="#00000008" stroke-width="0.8"/>
        </pattern>
        <pattern id="fiberV" patternUnits="userSpaceOnUse" width="5" height="5" patternTransform="rotate(0)">
            <line x1="0" y1="0" x2="0" y2="5" stroke="#00000006" stroke-width="0.6"/>
        </pattern>
        <!-- Shadow filter -->
        <filter id="muscleShadow" x="-5%" y="-5%" width="110%" height="110%">
            <feDropShadow dx="0" dy="1" stdDeviation="1.5" flood-color="#6b4040" flood-opacity="0.15"/>
        </filter>
        <!-- Inner shadow for depth -->
        <filter id="innerDepth" x="-10%" y="-10%" width="120%" height="120%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="2" result="blur"/>
            <feOffset dx="1" dy="1" result="offset"/>
            <feComposite in="offset" in2="SourceGraphic" operator="arithmetic" k1="0" k2="0" k3="0.15" k4="0"/>
            <feMerge>
                <feMergeNode/>
                <feMergeNode in="SourceGraphic"/>
            </feMerge>
        </filter>
        <!-- Selected glow -->
        <filter id="selectedGlow" x="-15%" y="-15%" width="130%" height="130%">
            <feDropShadow dx="0" dy="0" stdDeviation="3" flood-color="#6366f1" flood-opacity="0.5"/>
        </filter>
    </defs>

    <!-- Title -->
    <text x="250" y="24" text-anchor="middle" font-family="sans-serif" font-size="14" font-weight="700" fill="#1e293b">Muscular System — Anterior View</text>

    <!-- Body silhouette outline (underlying anatomy reference) -->
    <path d="M250 52 Q268 52 273 65 Q278 78 273 95 L272 100
             Q295 108 320 118 L355 130 Q380 138 395 148 L402 155 Q406 162 400 165 L365 160 Q340 155 318 148 L295 140 Q282 135 276 130
             L278 160 Q282 200 284 240 L286 275 Q288 310 292 345 L298 400 Q302 430 306 460 L310 490 Q313 510 310 525 L306 535 Q300 542 294 535 L290 515 Q286 490 282 460 L276 420 Q272 390 268 355 L264 310 Q262 280 260 255 L258 230 Q256 210 254 190 L252 170 L250 170
             L248 170 L246 190 Q244 210 242 230 L240 255 Q238 280 236 310 L232 355 Q228 390 224 420 L218 460 Q214 490 210 515 L206 535 Q200 542 194 535 L190 525 Q187 510 190 490 L194 460 Q198 430 202 400 L208 345 Q212 310 214 275 L216 240 Q218 200 222 160
             L224 130 Q218 135 205 140 L182 148 Q160 155 135 160 L100 165 Q94 162 98 155 L105 148 Q120 138 145 130 L180 118 Q205 108 228 100
             L227 95 Q222 78 227 65 Q232 52 250 52 Z"
          fill="url(#skinGrad)" stroke="#c4a882" stroke-width="0.8" opacity="0.35"/>

    <!-- Head (non-interactive, anatomical reference) -->
    <ellipse cx="250" cy="62" rx="22" ry="25" fill="#f5deb3" stroke="#c4a882" stroke-width="0.6" opacity="0.3"/>

    <!-- ==================== NECK MUSCLES ==================== -->
    <g @click="toggleZone('neck')" class="cursor-pointer" :class="isSelected('neck') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('neck') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <!-- Sternocleidomastoid left -->
        <path d="M240 88 Q235 92 228 105 Q225 112 230 116 L235 114 Q238 106 242 96 Z"
              :fill="isSelected('neck') ? '#818cf8' : '#c9918a'" stroke="#a06858" stroke-width="0.6"/>
        <!-- Sternocleidomastoid right -->
        <path d="M260 88 Q265 92 272 105 Q275 112 270 116 L265 114 Q262 106 258 96 Z"
              :fill="isSelected('neck') ? '#818cf8' : '#c9918a'" stroke="#a06858" stroke-width="0.6"/>
        <!-- Fiber lines -->
        <line x1="236" y1="92" x2="230" y2="112" stroke="#a0685830" stroke-width="0.4"/>
        <line x1="264" y1="92" x2="270" y2="112" stroke="#a0685830" stroke-width="0.4"/>
        <!-- Trapezius upper visible portion -->
        <path d="M230 106 Q240 102 250 100 Q260 102 270 106 L272 112 Q260 108 250 107 Q240 108 228 112 Z"
              :fill="isSelected('neck') ? '#7c7cf8' : '#c08078'" stroke="#a06858" stroke-width="0.4" opacity="0.7"/>
        <text x="250" y="98" text-anchor="middle" font-family="sans-serif" font-size="7" font-weight="600"
              :fill="isSelected('neck') ? '#fff' : '#5a2a1a'">Neck</text>
    </g>

    <!-- ==================== SHOULDERS (DELTOIDS) ==================== -->
    <!-- Left Deltoid -->
    <g @click="toggleZone('shoulders')" class="cursor-pointer" :class="isSelected('shoulders') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('shoulders') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <path d="M222 112 Q205 108 190 118 Q178 128 175 142 Q178 148 184 145 L192 135 Q198 126 210 120 L222 118 Z"
              :fill="isSelected('shoulders') ? '#818cf8' : 'url(#deltoidGrad)'" stroke="#a06858" stroke-width="0.7"/>
        <!-- Fiber lines -->
        <line x1="210" y1="114" x2="182" y2="140" stroke="#a0685820" stroke-width="0.5"/>
        <line x1="215" y1="116" x2="186" y2="138" stroke="#a0685820" stroke-width="0.5"/>
        <line x1="205" y1="112" x2="178" y2="142" stroke="#a0685820" stroke-width="0.5"/>
        <!-- Anterior/lateral/posterior deltoid separation -->
        <line x1="200" y1="114" x2="184" y2="140" stroke="#a0685830" stroke-width="0.3" stroke-dasharray="2,2"/>
    </g>
    <!-- Right Deltoid -->
    <g @click="toggleZone('shoulders')" class="cursor-pointer" :class="isSelected('shoulders') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('shoulders') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <path d="M278 112 Q295 108 310 118 Q322 128 325 142 Q322 148 316 145 L308 135 Q302 126 290 120 L278 118 Z"
              :fill="isSelected('shoulders') ? '#818cf8' : 'url(#deltoidGrad)'" stroke="#a06858" stroke-width="0.7"/>
        <line x1="290" y1="114" x2="318" y2="140" stroke="#a0685820" stroke-width="0.5"/>
        <line x1="285" y1="116" x2="314" y2="138" stroke="#a0685820" stroke-width="0.5"/>
        <line x1="295" y1="112" x2="322" y2="142" stroke="#a0685820" stroke-width="0.5"/>
        <line x1="300" y1="114" x2="316" y2="140" stroke="#a0685830" stroke-width="0.3" stroke-dasharray="2,2"/>
        <text x="250" y="130" text-anchor="middle" font-family="sans-serif" font-size="6" font-weight="600"
              :fill="isSelected('shoulders') ? '#fff' : '#5a2a1a'">Deltoids</text>
    </g>

    <!-- ==================== CHEST (PECTORALS) ==================== -->
    <g @click="toggleZone('chest')" class="cursor-pointer" :class="isSelected('chest') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('chest') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <!-- Left Pectoral -->
        <path d="M225 118 Q215 120 200 128 Q188 138 184 150 Q182 158 188 162 Q198 164 212 158 Q222 152 228 142 L230 130 Z"
              :fill="isSelected('chest') ? '#818cf8' : 'url(#pectoralGrad)'" stroke="#a06858" stroke-width="0.7"/>
        <!-- Pec fiber lines (fan pattern) -->
        <line x1="224" y1="120" x2="190" y2="158" stroke="#a0685818" stroke-width="0.5"/>
        <line x1="226" y1="125" x2="194" y2="160" stroke="#a0685818" stroke-width="0.5"/>
        <line x1="228" y1="130" x2="200" y2="160" stroke="#a0685818" stroke-width="0.5"/>
        <line x1="228" y1="136" x2="208" y2="158" stroke="#a0685818" stroke-width="0.5"/>
        <!-- Pec minor hint -->
        <path d="M210 130 Q202 138 198 148" stroke="#a0685825" stroke-width="0.4" fill="none" stroke-dasharray="1.5,1.5"/>

        <!-- Right Pectoral -->
        <path d="M275 118 Q285 120 300 128 Q312 138 316 150 Q318 158 312 162 Q302 164 288 158 Q278 152 272 142 L270 130 Z"
              :fill="isSelected('chest') ? '#818cf8' : 'url(#pectoralGrad)'" stroke="#a06858" stroke-width="0.7"/>
        <line x1="276" y1="120" x2="310" y2="158" stroke="#a0685818" stroke-width="0.5"/>
        <line x1="274" y1="125" x2="306" y2="160" stroke="#a0685818" stroke-width="0.5"/>
        <line x1="272" y1="130" x2="300" y2="160" stroke="#a0685818" stroke-width="0.5"/>
        <line x1="272" y1="136" x2="292" y2="158" stroke="#a0685818" stroke-width="0.5"/>
        <path d="M290 130 Q298 138 302 148" stroke="#a0685825" stroke-width="0.4" fill="none" stroke-dasharray="1.5,1.5"/>

        <!-- Sternal separation line -->
        <line x1="250" y1="118" x2="250" y2="165" stroke="#a06858" stroke-width="0.3" opacity="0.4"/>
        <text x="250" y="148" text-anchor="middle" font-family="sans-serif" font-size="7" font-weight="600"
              :fill="isSelected('chest') ? '#fff' : '#5a2a1a'">Pectorals</text>
    </g>

    <!-- ==================== UPPER BACK ==================== -->
    <g @click="toggleZone('upper_back')" class="cursor-pointer" :class="isSelected('upper_back') ? 'opacity-100' : 'opacity-70 hover:opacity-90'">
        <!-- Shown as dashed overlay indicating posterior muscles -->
        <path d="M228 115 Q240 112 250 112 Q260 112 272 115 L275 145 Q265 150 250 152 Q235 150 225 145 Z"
              :fill="isSelected('upper_back') ? '#818cf840' : 'transparent'" stroke="#6366f1" stroke-width="1" stroke-dasharray="3,2" opacity="0.6"/>
        <text x="250" y="163" text-anchor="middle" font-family="sans-serif" font-size="5" font-weight="500"
              :fill="isSelected('upper_back') ? '#6366f1' : '#6366f1'" opacity="0.7">(Upper Back)</text>
    </g>

    <!-- ==================== BICEPS ==================== -->
    <!-- Left Bicep -->
    <g @click="toggleZone('biceps')" class="cursor-pointer" :class="isSelected('biceps') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('biceps') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <path d="M180 148 Q174 155 170 168 Q168 182 170 195 Q174 205 180 208 Q186 205 188 195 Q190 182 188 168 Q186 155 180 148 Z"
              :fill="isSelected('biceps') ? '#818cf8' : '#c49088'" stroke="#a06858" stroke-width="0.6"/>
        <!-- Bicep peak hint -->
        <path d="M174 168 Q178 162 182 168" stroke="#a0685825" stroke-width="0.4" fill="none"/>
        <!-- Fiber lines -->
        <line x1="178" y1="154" x2="178" y2="202" stroke="#a0685815" stroke-width="0.5"/>
        <line x1="182" y1="152" x2="182" y2="204" stroke="#a0685815" stroke-width="0.5"/>
        <text x="180" y="182" text-anchor="middle" font-family="sans-serif" font-size="5" font-weight="600"
              :fill="isSelected('biceps') ? '#fff' : '#5a2a1a'">Bicep</text>
    </g>
    <!-- Right Bicep -->
    <g @click="toggleZone('biceps')" class="cursor-pointer" :class="isSelected('biceps') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('biceps') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <path d="M320 148 Q326 155 330 168 Q332 182 330 195 Q326 205 320 208 Q314 205 312 195 Q310 182 312 168 Q314 155 320 148 Z"
              :fill="isSelected('biceps') ? '#818cf8' : '#c49088'" stroke="#a06858" stroke-width="0.6"/>
        <path d="M326 168 Q322 162 318 168" stroke="#a0685825" stroke-width="0.4" fill="none"/>
        <line x1="318" y1="154" x2="318" y2="202" stroke="#a0685815" stroke-width="0.5"/>
        <line x1="322" y1="152" x2="322" y2="204" stroke="#a0685815" stroke-width="0.5"/>
        <text x="320" y="182" text-anchor="middle" font-family="sans-serif" font-size="5" font-weight="600"
              :fill="isSelected('biceps') ? '#fff' : '#5a2a1a'">Bicep</text>
    </g>

    <!-- ==================== TRICEPS ==================== -->
    <!-- Left Tricep (shown slightly behind bicep) -->
    <g @click="toggleZone('triceps')" class="cursor-pointer" :class="isSelected('triceps') ? 'opacity-100' : 'opacity-75 hover:opacity-90'" :filter="isSelected('triceps') ? 'url(#selectedGlow)' : 'none'">
        <path d="M172 150 Q166 158 164 172 Q163 188 165 200 Q168 208 172 210 Q175 207 176 198 Q177 186 176 172 Q175 158 172 150 Z"
              :fill="isSelected('triceps') ? '#818cf8' : '#b8807a'" stroke="#a06858" stroke-width="0.5" opacity="0.85"/>
        <text x="168" y="183" text-anchor="middle" font-family="sans-serif" font-size="4" font-weight="500"
              :fill="isSelected('triceps') ? '#fff' : '#5a2a1a'">Tri</text>
    </g>
    <!-- Right Tricep -->
    <g @click="toggleZone('triceps')" class="cursor-pointer" :class="isSelected('triceps') ? 'opacity-100' : 'opacity-75 hover:opacity-90'" :filter="isSelected('triceps') ? 'url(#selectedGlow)' : 'none'">
        <path d="M328 150 Q334 158 336 172 Q337 188 335 200 Q332 208 328 210 Q325 207 324 198 Q323 186 324 172 Q325 158 328 150 Z"
              :fill="isSelected('triceps') ? '#818cf8' : '#b8807a'" stroke="#a06858" stroke-width="0.5" opacity="0.85"/>
        <text x="332" y="183" text-anchor="middle" font-family="sans-serif" font-size="4" font-weight="500"
              :fill="isSelected('triceps') ? '#fff' : '#5a2a1a'">Tri</text>
    </g>

    <!-- ==================== FOREARM MUSCLES ==================== -->
    <!-- Left Forearm -->
    <g @click="toggleZone('forearms')" class="cursor-pointer" :class="isSelected('forearms') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('forearms') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <path d="M174 210 Q168 218 164 235 Q160 255 158 270 Q158 280 162 282 Q168 278 172 265 Q175 250 176 235 Q177 222 174 210 Z"
              :fill="isSelected('forearms') ? '#818cf8' : '#c49890'" stroke="#a06858" stroke-width="0.5"/>
        <!-- Brachioradialis -->
        <path d="M178 212 Q182 220 182 238 Q180 258 176 272 Q174 278 170 280"
              stroke="#a0685820" stroke-width="0.5" fill="none"/>
        <!-- Extensor group hint -->
        <path d="M170 215 Q165 225 162 245 Q160 265 160 275"
              stroke="#a0685818" stroke-width="0.4" fill="none"/>
        <text x="168" y="250" text-anchor="middle" font-family="sans-serif" font-size="4.5" font-weight="500"
              :fill="isSelected('forearms') ? '#fff' : '#5a2a1a'" transform="rotate(-8,168,250)">Forearm</text>
    </g>
    <!-- Right Forearm -->
    <g @click="toggleZone('forearms')" class="cursor-pointer" :class="isSelected('forearms') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('forearms') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <path d="M326 210 Q332 218 336 235 Q340 255 342 270 Q342 280 338 282 Q332 278 328 265 Q325 250 324 235 Q323 222 326 210 Z"
              :fill="isSelected('forearms') ? '#818cf8' : '#c49890'" stroke="#a06858" stroke-width="0.5"/>
        <path d="M322 212 Q318 220 318 238 Q320 258 324 272 Q326 278 330 280"
              stroke="#a0685820" stroke-width="0.5" fill="none"/>
        <path d="M330 215 Q335 225 338 245 Q340 265 340 275"
              stroke="#a0685818" stroke-width="0.4" fill="none"/>
        <text x="332" y="250" text-anchor="middle" font-family="sans-serif" font-size="4.5" font-weight="500"
              :fill="isSelected('forearms') ? '#fff' : '#5a2a1a'" transform="rotate(8,332,250)">Forearm</text>
    </g>

    <!-- ==================== CORE / ABDOMINALS ==================== -->
    <g @click="toggleZone('core')" class="cursor-pointer" :class="isSelected('core') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('core') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <!-- Rectus abdominis -->
        <path d="M237 168 Q234 175 233 190 Q232 210 232 230 Q233 250 235 265 Q238 272 244 272 L244 168 Z"
              :fill="isSelected('core') ? '#818cf8' : 'url(#absGrad)'" stroke="#a06858" stroke-width="0.5"/>
        <path d="M263 168 Q266 175 267 190 Q268 210 268 230 Q267 250 265 265 Q262 272 256 272 L256 168 Z"
              :fill="isSelected('core') ? '#818cf8' : 'url(#absGrad)'" stroke="#a06858" stroke-width="0.5"/>
        <!-- Linea alba (center line) -->
        <line x1="250" y1="168" x2="250" y2="272" stroke="#a06858" stroke-width="0.5" opacity="0.5"/>
        <!-- Tendinous inscriptions (six-pack lines) -->
        <line x1="234" y1="185" x2="244" y2="185" stroke="#a0685830" stroke-width="0.6"/>
        <line x1="256" y1="185" x2="266" y2="185" stroke="#a0685830" stroke-width="0.6"/>
        <line x1="233" y1="205" x2="244" y2="205" stroke="#a0685830" stroke-width="0.6"/>
        <line x1="256" y1="205" x2="267" y2="205" stroke="#a0685830" stroke-width="0.6"/>
        <line x1="233" y1="225" x2="244" y2="225" stroke="#a0685830" stroke-width="0.6"/>
        <line x1="256" y1="225" x2="267" y2="225" stroke="#a0685830" stroke-width="0.6"/>
        <line x1="234" y1="245" x2="244" y2="245" stroke="#a0685830" stroke-width="0.6"/>
        <line x1="256" y1="245" x2="266" y2="245" stroke="#a0685830" stroke-width="0.6"/>
        <!-- External obliques -->
        <path d="M225 172 Q220 185 218 200 Q216 220 218 240 Q220 255 224 265 L233 265 Q230 250 230 230 Q230 210 232 190 Q233 178 235 170 Z"
              :fill="isSelected('core') ? '#7c7cf8' : '#c4908870'" stroke="#a06858" stroke-width="0.4"/>
        <path d="M275 172 Q280 185 282 200 Q284 220 282 240 Q280 255 276 265 L267 265 Q270 250 270 230 Q270 210 268 190 Q267 178 265 170 Z"
              :fill="isSelected('core') ? '#7c7cf8' : '#c4908870'" stroke="#a06858" stroke-width="0.4"/>
        <!-- Oblique fiber hints -->
        <line x1="220" y1="178" x2="232" y2="195" stroke="#a0685815" stroke-width="0.4"/>
        <line x1="219" y1="198" x2="231" y2="215" stroke="#a0685815" stroke-width="0.4"/>
        <line x1="218" y1="218" x2="230" y2="235" stroke="#a0685815" stroke-width="0.4"/>
        <line x1="280" y1="178" x2="268" y2="195" stroke="#a0685815" stroke-width="0.4"/>
        <line x1="281" y1="198" x2="269" y2="215" stroke="#a0685815" stroke-width="0.4"/>
        <line x1="282" y1="218" x2="270" y2="235" stroke="#a0685815" stroke-width="0.4"/>
        <text x="250" y="218" text-anchor="middle" font-family="sans-serif" font-size="7" font-weight="600"
              :fill="isSelected('core') ? '#fff' : '#5a2a1a'">Abs</text>
    </g>

    <!-- ==================== LOWER BACK ==================== -->
    <g @click="toggleZone('lower_back')" class="cursor-pointer" :class="isSelected('lower_back') ? 'opacity-100' : 'opacity-70 hover:opacity-90'">
        <path d="M235 200 Q240 198 250 198 Q260 198 265 200 L268 260 Q262 265 250 266 Q238 265 232 260 Z"
              :fill="isSelected('lower_back') ? '#818cf840' : 'transparent'" stroke="#f97316" stroke-width="0.8" stroke-dasharray="3,2" opacity="0.5"/>
        <text x="250" y="276" text-anchor="middle" font-family="sans-serif" font-size="5" font-weight="500"
              :fill="isSelected('lower_back') ? '#f97316' : '#f97316'" opacity="0.6">(Lower Back)</text>
    </g>

    <!-- ==================== HIP FLEXORS / GLUTES ==================== -->
    <!-- Hips (Iliopsoas / Hip Flexors) -->
    <g @click="toggleZone('hips')" class="cursor-pointer" :class="isSelected('hips') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('hips') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <!-- Left hip flexor region -->
        <path d="M225 268 Q218 275 212 290 Q210 300 214 308 Q220 312 228 308 Q234 300 236 290 Q238 278 235 270 Z"
              :fill="isSelected('hips') ? '#818cf8' : '#c49890'" stroke="#a06858" stroke-width="0.5"/>
        <!-- Right hip flexor region -->
        <path d="M275 268 Q282 275 288 290 Q290 300 286 308 Q280 312 272 308 Q266 300 264 290 Q262 278 265 270 Z"
              :fill="isSelected('hips') ? '#818cf8' : '#c49890'" stroke="#a06858" stroke-width="0.5"/>
        <!-- Fiber hints -->
        <line x1="222" y1="275" x2="220" y2="302" stroke="#a0685818" stroke-width="0.4"/>
        <line x1="278" y1="275" x2="280" y2="302" stroke="#a0685818" stroke-width="0.4"/>
        <text x="250" y="298" text-anchor="middle" font-family="sans-serif" font-size="6" font-weight="600"
              :fill="isSelected('hips') ? '#fff' : '#5a2a1a'">Hip Flexors</text>
    </g>

    <!-- ==================== GLUTES (shown as outline - posterior) ==================== -->
    <g @click="toggleZone('glutes')" class="cursor-pointer" :class="isSelected('glutes') ? 'opacity-100' : 'opacity-70 hover:opacity-90'">
        <path d="M218 280 Q210 290 208 305 Q210 315 220 318 L240 316 Q248 314 250 310 Q252 314 260 316 L280 318 Q290 315 292 305 Q290 290 282 280 Z"
              :fill="isSelected('glutes') ? '#818cf840' : 'transparent'" stroke="#e11d48" stroke-width="0.8" stroke-dasharray="3,2" opacity="0.5"/>
        <text x="250" y="322" text-anchor="middle" font-family="sans-serif" font-size="5" font-weight="500"
              :fill="isSelected('glutes') ? '#e11d48' : '#e11d48'" opacity="0.6">(Glutes)</text>
    </g>

    <!-- ==================== QUADRICEPS ==================== -->
    <!-- Left Quad -->
    <g @click="toggleZone('quadriceps')" class="cursor-pointer" :class="isSelected('quadriceps') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('quadriceps') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <!-- Rectus femoris -->
        <path d="M234 315 Q228 330 226 360 Q225 390 226 420 Q228 440 232 450 Q238 455 242 450 Q244 440 244 420 Q244 390 243 360 Q242 335 240 318 Z"
              :fill="isSelected('quadriceps') ? '#818cf8' : 'url(#quadGrad)'" stroke="#a06858" stroke-width="0.6"/>
        <!-- Vastus lateralis -->
        <path d="M224 320 Q216 335 214 360 Q213 390 214 420 Q216 440 220 448 L226 450 Q224 440 224 420 Q224 390 225 360 Q226 335 228 320 Z"
              :fill="isSelected('quadriceps') ? '#7c7cf8' : '#c08078'" stroke="#a06858" stroke-width="0.4" opacity="0.8"/>
        <!-- Vastus medialis (teardrop) -->
        <path d="M244 400 Q246 410 248 425 Q250 440 248 450 L242 452 Q240 445 240 430 Q240 415 242 402 Z"
              :fill="isSelected('quadriceps') ? '#7c7cf8' : '#c08078'" stroke="#a06858" stroke-width="0.4" opacity="0.8"/>
        <!-- Fiber lines -->
        <line x1="234" y1="325" x2="232" y2="445" stroke="#a0685812" stroke-width="0.5"/>
        <line x1="238" y1="320" x2="238" y2="448" stroke="#a0685812" stroke-width="0.5"/>
        <text x="232" y="385" text-anchor="middle" font-family="sans-serif" font-size="5.5" font-weight="600"
              :fill="isSelected('quadriceps') ? '#fff' : '#5a2a1a'" transform="rotate(-2,232,385)">Quad</text>
    </g>
    <!-- Right Quad -->
    <g @click="toggleZone('quadriceps')" class="cursor-pointer" :class="isSelected('quadriceps') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('quadriceps') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <path d="M266 315 Q272 330 274 360 Q275 390 274 420 Q272 440 268 450 Q262 455 258 450 Q256 440 256 420 Q256 390 257 360 Q258 335 260 318 Z"
              :fill="isSelected('quadriceps') ? '#818cf8' : 'url(#quadGrad)'" stroke="#a06858" stroke-width="0.6"/>
        <path d="M276 320 Q284 335 286 360 Q287 390 286 420 Q284 440 280 448 L274 450 Q276 440 276 420 Q276 390 275 360 Q274 335 272 320 Z"
              :fill="isSelected('quadriceps') ? '#7c7cf8' : '#c08078'" stroke="#a06858" stroke-width="0.4" opacity="0.8"/>
        <path d="M256 400 Q254 410 252 425 Q250 440 252 450 L258 452 Q260 445 260 430 Q260 415 258 402 Z"
              :fill="isSelected('quadriceps') ? '#7c7cf8' : '#c08078'" stroke="#a06858" stroke-width="0.4" opacity="0.8"/>
        <line x1="266" y1="325" x2="268" y2="445" stroke="#a0685812" stroke-width="0.5"/>
        <line x1="262" y1="320" x2="262" y2="448" stroke="#a0685812" stroke-width="0.5"/>
        <text x="268" y="385" text-anchor="middle" font-family="sans-serif" font-size="5.5" font-weight="600"
              :fill="isSelected('quadriceps') ? '#fff' : '#5a2a1a'" transform="rotate(2,268,385)">Quad</text>
    </g>

    <!-- ==================== HAMSTRINGS ==================== -->
    <!-- Left Hamstring (posterior, shown as outline) -->
    <g @click="toggleZone('hamstrings')" class="cursor-pointer" :class="isSelected('hamstrings') ? 'opacity-100' : 'opacity-75 hover:opacity-90'" :filter="isSelected('hamstrings') ? 'url(#selectedGlow)' : 'none'">
        <path d="M216 320 Q210 340 208 370 Q208 400 210 430 Q212 442 216 448 Q220 440 220 420 Q220 390 218 360 Q218 338 216 320 Z"
              :fill="isSelected('hamstrings') ? '#818cf8' : '#b87a7260'" stroke="#a06858" stroke-width="0.5" opacity="0.7"/>
        <text x="210" y="385" text-anchor="middle" font-family="sans-serif" font-size="4" font-weight="500"
              :fill="isSelected('hamstrings') ? '#fff' : '#7a4a42'" transform="rotate(-3,210,385)">Ham</text>
    </g>
    <!-- Right Hamstring -->
    <g @click="toggleZone('hamstrings')" class="cursor-pointer" :class="isSelected('hamstrings') ? 'opacity-100' : 'opacity-75 hover:opacity-90'" :filter="isSelected('hamstrings') ? 'url(#selectedGlow)' : 'none'">
        <path d="M284 320 Q290 340 292 370 Q292 400 290 430 Q288 442 284 448 Q280 440 280 420 Q280 390 282 360 Q282 338 284 320 Z"
              :fill="isSelected('hamstrings') ? '#818cf8' : '#b87a7260'" stroke="#a06858" stroke-width="0.5" opacity="0.7"/>
        <text x="290" y="385" text-anchor="middle" font-family="sans-serif" font-size="4" font-weight="500"
              :fill="isSelected('hamstrings') ? '#fff' : '#7a4a42'" transform="rotate(3,290,385)">Ham</text>
    </g>

    <!-- ==================== CALVES ==================== -->
    <!-- Left Calf -->
    <g @click="toggleZone('calves')" class="cursor-pointer" :class="isSelected('calves') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('calves') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <!-- Gastrocnemius -->
        <path d="M228 462 Q222 475 220 498 Q219 520 220 545 Q222 560 226 570 Q230 575 234 570 Q236 558 236 540 Q236 518 234 498 Q233 478 230 465 Z"
              :fill="isSelected('calves') ? '#818cf8' : 'url(#calfGrad)'" stroke="#a06858" stroke-width="0.5"/>
        <!-- Soleus hint -->
        <path d="M224 500 Q220 515 220 535 Q220 550 222 562"
              stroke="#a0685822" stroke-width="0.5" fill="none"/>
        <!-- Tibialis anterior -->
        <path d="M238 464 Q240 478 242 500 Q243 520 242 540 Q240 555 238 562 Q236 558 236 540 Q236 518 236 500 Q236 480 238 464 Z"
              :fill="isSelected('calves') ? '#7c7cf8' : '#c0887e'" stroke="#a06858" stroke-width="0.3" opacity="0.6"/>
        <text x="230" y="520" text-anchor="middle" font-family="sans-serif" font-size="5" font-weight="600"
              :fill="isSelected('calves') ? '#fff' : '#5a2a1a'" transform="rotate(-2,230,520)">Calf</text>
    </g>
    <!-- Right Calf -->
    <g @click="toggleZone('calves')" class="cursor-pointer" :class="isSelected('calves') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('calves') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <path d="M272 462 Q278 475 280 498 Q281 520 280 545 Q278 560 274 570 Q270 575 266 570 Q264 558 264 540 Q264 518 266 498 Q267 478 270 465 Z"
              :fill="isSelected('calves') ? '#818cf8' : 'url(#calfGrad)'" stroke="#a06858" stroke-width="0.5"/>
        <path d="M276 500 Q280 515 280 535 Q280 550 278 562"
              stroke="#a0685822" stroke-width="0.5" fill="none"/>
        <path d="M262 464 Q260 478 258 500 Q257 520 258 540 Q260 555 262 562 Q264 558 264 540 Q264 518 264 500 Q264 480 262 464 Z"
              :fill="isSelected('calves') ? '#7c7cf8' : '#c0887e'" stroke="#a06858" stroke-width="0.3" opacity="0.6"/>
        <text x="270" y="520" text-anchor="middle" font-family="sans-serif" font-size="5" font-weight="600"
              :fill="isSelected('calves') ? '#fff' : '#5a2a1a'" transform="rotate(2,270,520)">Calf</text>
    </g>

    <!-- ==================== ANKLES ==================== -->
    <!-- Left Ankle -->
    <g @click="toggleZone('ankles')" class="cursor-pointer" :class="isSelected('ankles') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('ankles') ? 'url(#selectedGlow)' : 'none'">
        <ellipse cx="230" cy="580" rx="10" ry="7"
                 :fill="isSelected('ankles') ? '#818cf8' : '#d4a898'" stroke="#a06858" stroke-width="0.5"/>
        <text x="230" y="583" text-anchor="middle" font-family="sans-serif" font-size="4.5" font-weight="500"
              :fill="isSelected('ankles') ? '#fff' : '#5a2a1a'">Ankle</text>
    </g>
    <!-- Right Ankle -->
    <g @click="toggleZone('ankles')" class="cursor-pointer" :class="isSelected('ankles') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('ankles') ? 'url(#selectedGlow)' : 'none'">
        <ellipse cx="270" cy="580" rx="10" ry="7"
                 :fill="isSelected('ankles') ? '#818cf8' : '#d4a898'" stroke="#a06858" stroke-width="0.5"/>
        <text x="270" y="583" text-anchor="middle" font-family="sans-serif" font-size="4.5" font-weight="500"
              :fill="isSelected('ankles') ? '#fff' : '#5a2a1a'">Ankle</text>
    </g>

    <!-- ==================== FEET ==================== -->
    <!-- Left Foot -->
    <g @click="toggleZone('feet')" class="cursor-pointer" :class="isSelected('feet') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('feet') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <path d="M222 588 Q216 592 212 598 Q210 604 214 608 Q220 612 230 612 Q238 610 240 604 Q240 596 236 590 L230 588 Z"
              :fill="isSelected('feet') ? '#818cf8' : '#d4a898'" stroke="#a06858" stroke-width="0.5"/>
        <text x="226" y="603" text-anchor="middle" font-family="sans-serif" font-size="4.5" font-weight="500"
              :fill="isSelected('feet') ? '#fff' : '#5a2a1a'">Foot</text>
    </g>
    <!-- Right Foot -->
    <g @click="toggleZone('feet')" class="cursor-pointer" :class="isSelected('feet') ? 'opacity-100' : 'opacity-80 hover:opacity-95'" :filter="isSelected('feet') ? 'url(#selectedGlow)' : 'url(#muscleShadow)'">
        <path d="M278 588 Q284 592 288 598 Q290 604 286 608 Q280 612 270 612 Q262 610 260 604 Q260 596 264 590 L270 588 Z"
              :fill="isSelected('feet') ? '#818cf8' : '#d4a898'" stroke="#a06858" stroke-width="0.5"/>
        <text x="274" y="603" text-anchor="middle" font-family="sans-serif" font-size="4.5" font-weight="500"
              :fill="isSelected('feet') ? '#fff' : '#5a2a1a'">Foot</text>
    </g>

    <!-- ==================== ANATOMICAL REFERENCE LABELS ==================== -->
    <!-- Side labels with leader lines -->
    <line x1="170" y1="62" x2="130" y2="50" stroke="#94a3b8" stroke-width="0.4"/>
    <text x="128" y="48" text-anchor="end" font-family="sans-serif" font-size="5" fill="#94a3b8">Head</text>

    <line x1="175" y1="145" x2="128" y2="145" stroke="#94a3b8" stroke-width="0.3"/>
    <text x="125" y="148" text-anchor="end" font-family="sans-serif" font-size="5" fill="#94a3b8">Arm</text>

    <line x1="248" y1="455" x2="296" y2="455" stroke="#94a3b8" stroke-width="0.3"/>
    <text x="298" y="458" font-family="sans-serif" font-size="5" fill="#94a3b8">Knee</text>

    <!-- Midline reference -->
    <line x1="250" y1="42" x2="250" y2="620" stroke="#e2e8f0" stroke-width="0.3" stroke-dasharray="4,6" opacity="0.4"/>
</svg>
