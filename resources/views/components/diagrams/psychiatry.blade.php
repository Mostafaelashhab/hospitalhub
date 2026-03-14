{{-- Psychiatry Brain Diagram (Lateral View) --}}
{{-- Requires parent Alpine.js scope with: selectedZones[], toggleZone(id), zoneNotes{} --}}

<svg viewBox="0 0 600 500" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto max-w-lg mx-auto">
    <defs>
        <style>
            .brain-label { font-family: sans-serif; font-size: 8.5px; fill: #1e293b; pointer-events: none; }
            .brain-label-sm { font-family: sans-serif; font-size: 7px; fill: #475569; pointer-events: none; }
            .brain-label-line { stroke: #64748b; stroke-width: 0.6; fill: none; pointer-events: none; }
            .brain-zone { cursor: pointer; transition: fill 0.25s cubic-bezier(0.4,0,0.2,1), stroke 0.25s ease, transform 0.25s ease, filter 0.25s ease; transform-origin: center; }
            .brain-zone:hover { filter: brightness(0.92) drop-shadow(0 0 6px rgba(99,102,241,0.3)); transform: scale(1.012); }
        </style>

        <!-- Brain tissue base gradient -->
        <radialGradient id="psy-brain-bg" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#f0e0d4"/>
            <stop offset="60%" stop-color="#e4cfc0"/>
            <stop offset="100%" stop-color="#d4baa8"/>
        </radialGradient>

        <!-- Zone gradients -->
        <radialGradient id="psy-prefrontal" cx="40%" cy="35%" r="60%">
            <stop offset="0%" stop-color="#c8d8f0"/>
            <stop offset="100%" stop-color="#98b0d8"/>
        </radialGradient>
        <radialGradient id="psy-motor" cx="50%" cy="35%" r="55%">
            <stop offset="0%" stop-color="#d0e8c8"/>
            <stop offset="100%" stop-color="#a8cca0"/>
        </radialGradient>
        <radialGradient id="psy-sensory" cx="50%" cy="35%" r="55%">
            <stop offset="0%" stop-color="#c8e0d8"/>
            <stop offset="100%" stop-color="#90c0b0"/>
        </radialGradient>
        <radialGradient id="psy-temporal" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#f0e0c0"/>
            <stop offset="100%" stop-color="#d8c498"/>
        </radialGradient>
        <radialGradient id="psy-broca" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#e8c8d8"/>
            <stop offset="100%" stop-color="#c8a0b8"/>
        </radialGradient>
        <radialGradient id="psy-wernicke" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#e0d0e8"/>
            <stop offset="100%" stop-color="#c0a8d0"/>
        </radialGradient>
        <radialGradient id="psy-visual" cx="50%" cy="45%" r="55%">
            <stop offset="0%" stop-color="#d8d0e8"/>
            <stop offset="100%" stop-color="#b0a0d0"/>
        </radialGradient>
        <radialGradient id="psy-auditory" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#f0d8c0"/>
            <stop offset="100%" stop-color="#d8b898"/>
        </radialGradient>
        <radialGradient id="psy-thalamus" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#c0e0c8"/>
            <stop offset="100%" stop-color="#88c098"/>
        </radialGradient>
        <radialGradient id="psy-hypothalamus" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#f0d0e0"/>
            <stop offset="100%" stop-color="#d8a0c0"/>
        </radialGradient>
        <radialGradient id="psy-amygdala" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#f0c0c0"/>
            <stop offset="100%" stop-color="#d89898"/>
        </radialGradient>
        <radialGradient id="psy-hippocampus" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#f0e8c0"/>
            <stop offset="100%" stop-color="#d8cc90"/>
        </radialGradient>
        <radialGradient id="psy-basal" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#e0c8f0"/>
            <stop offset="100%" stop-color="#c0a0d8"/>
        </radialGradient>
        <radialGradient id="psy-cingulate" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#d0d8f0"/>
            <stop offset="100%" stop-color="#a8b8d8"/>
        </radialGradient>
        <radialGradient id="psy-brainstem" cx="50%" cy="35%" r="55%">
            <stop offset="0%" stop-color="#c0e0d8"/>
            <stop offset="100%" stop-color="#90c0b0"/>
        </radialGradient>
        <radialGradient id="psy-cerebellum" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#b8d8e8"/>
            <stop offset="100%" stop-color="#88b0c8"/>
        </radialGradient>

        <!-- Selected state -->
        <radialGradient id="psy-selected" cx="45%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#b8c4fc"/>
            <stop offset="100%" stop-color="#818cf8"/>
        </radialGradient>

        <!-- Shadow filter -->
        <filter id="psy-shadow" x="-5%" y="-5%" width="110%" height="110%">
            <feDropShadow dx="0.5" dy="1" stdDeviation="1.2" flood-color="#00000018"/>
        </filter>
        <filter id="psy-inner-shadow" x="-10%" y="-10%" width="120%" height="120%">
            <feDropShadow dx="0.3" dy="0.5" stdDeviation="0.8" flood-color="#00000020"/>
        </filter>

        <!-- Gyri texture pattern -->
        <pattern id="psy-gyri-texture" width="12" height="12" patternUnits="userSpaceOnUse">
            <path d="M2 6 Q6 3 10 6" fill="none" stroke="#a09080" stroke-width="0.3" opacity="0.3"/>
            <path d="M0 11 Q4 8 8 11" fill="none" stroke="#a09080" stroke-width="0.3" opacity="0.25"/>
        </pattern>
    </defs>

    <!-- Title -->
    <text x="300" y="22" text-anchor="middle" style="font-family:sans-serif;font-size:13px;font-weight:bold" fill="#1e293b">Lateral Brain View — Functional Areas</text>

    <!-- ==================== BRAIN OUTLINE with realistic sulci/gyri ==================== -->
    <path d="M280,52 C335,48 395,58 435,95 C465,125 478,165 475,210
             C472,250 458,285 430,315 C405,340 370,358 335,365
             C305,370 275,368 248,360 C220,352 195,338 175,318
             C155,298 138,270 128,238 C118,205 120,168 135,135
             C150,102 180,75 215,60 C240,52 262,50 280,52 Z"
        fill="url(#psy-brain-bg)" stroke="#8a7a68" stroke-width="1.8" filter="url(#psy-shadow)"/>

    <!-- Sulci (grooves) overlay for realistic brain texture -->
    <g opacity="0.2" stroke="#7a6a58" stroke-width="0.7" fill="none" pointer-events="none">
        <!-- Central sulcus -->
        <path d="M310 60 Q305 90 295 120 Q288 145 282 170"/>
        <!-- Lateral sulcus (Sylvian fissure) -->
        <path d="M190 220 Q220 210 260 200 Q290 192 320 188 Q350 185 375 190"/>
        <!-- Precentral sulcus -->
        <path d="M275 62 Q272 90 265 125 Q260 150 255 175"/>
        <!-- Postcentral sulcus -->
        <path d="M340 68 Q335 100 330 135 Q325 160 318 185"/>
        <!-- Superior frontal sulcus -->
        <path d="M170 100 Q200 95 230 92 Q255 90 270 88"/>
        <!-- Inferior frontal sulcus -->
        <path d="M152 145 Q180 140 210 138 Q235 137 260 140"/>
        <!-- Intraparietal sulcus -->
        <path d="M345 75 Q365 100 385 130 Q400 155 410 180"/>
        <!-- Superior temporal sulcus -->
        <path d="M200 245 Q235 235 270 230 Q305 225 340 225"/>
        <!-- Inferior temporal sulcus -->
        <path d="M210 280 Q240 272 275 268 Q310 265 345 265"/>
        <!-- Parieto-occipital sulcus -->
        <path d="M420 100 Q430 140 435 180 Q438 210 435 240"/>
        <!-- Minor gyri lines -->
        <path d="M165 120 Q185 115 205 118"/>
        <path d="M150 160 Q170 155 195 158"/>
        <path d="M355 85 Q370 105 380 130"/>
        <path d="M395 110 Q410 135 415 165"/>
        <path d="M230 255 Q260 248 290 245"/>
        <path d="M180 190 Q200 185 225 184"/>
    </g>

    <!-- Gyri texture overlay -->
    <path d="M280,52 C335,48 395,58 435,95 C465,125 478,165 475,210
             C472,250 458,285 430,315 C405,340 370,358 335,365
             C305,370 275,368 248,360 C220,352 195,338 175,318
             C155,298 138,270 128,238 C118,205 120,168 135,135
             C150,102 180,75 215,60 C240,52 262,50 280,52 Z"
        fill="url(#psy-gyri-texture)" pointer-events="none" opacity="0.4"/>

    <!-- ==================== FUNCTIONAL ZONES ==================== -->

    {{-- ===== PREFRONTAL CORTEX ===== --}}
    <path
        class="brain-zone"
        d="M145,140 C138,120 140,100 155,82 Q175,62 210,55 Q235,52 255,54
           Q248,70 235,90 Q218,112 200,130 Q185,145 170,155 Q155,160 148,152 Z"
        :fill="selectedZones.includes('prefrontal_cortex') ? 'url(#psy-selected)' : 'url(#psy-prefrontal)'"
        :stroke="selectedZones.includes('prefrontal_cortex') ? '#4f46e5' : '#6888b0'"
        stroke-width="1.2"
        @click="toggleZone('prefrontal_cortex')"
        filter="url(#psy-inner-shadow)"
    />
    <line x1="168" y1="100" x2="68" y2="72" class="brain-label-line"/>
    <text x="66" y="68" text-anchor="end" class="brain-label">Prefrontal</text>
    <text x="66" y="79" text-anchor="end" class="brain-label-sm">Decision-making</text>
    <text x="66" y="89" text-anchor="end" class="brain-label-sm">Personality</text>

    {{-- ===== MOTOR CORTEX (precentral gyrus) ===== --}}
    <path
        class="brain-zone"
        d="M260,54 Q270,52 285,55 Q295,58 300,65
           Q295,95 288,128 Q282,158 278,180
           Q268,175 258,168 Q252,158 255,140
           Q258,115 260,90 Z"
        :fill="selectedZones.includes('cingulate_cortex') ? 'url(#psy-selected)' : 'url(#psy-motor)'"
        :stroke="selectedZones.includes('cingulate_cortex') ? '#4f46e5' : '#68a060'"
        stroke-width="1.2"
        @click="toggleZone('cingulate_cortex')"
        filter="url(#psy-inner-shadow)"
    />
    <line x1="278" y1="72" x2="278" y2="38" class="brain-label-line"/>
    <text x="278" y="35" text-anchor="middle" class="brain-label">Motor Cortex</text>

    {{-- ===== SENSORY CORTEX (postcentral gyrus) ===== --}}
    <path
        class="brain-zone"
        d="M305,65 Q320,60 335,65 Q340,70 342,80
           Q338,115 332,148 Q326,175 320,192
           Q308,188 298,182 Q288,178 282,175
           Q286,145 292,118 Q298,88 305,65 Z"
        :fill="selectedZones.includes('basal_ganglia') ? 'url(#psy-selected)' : 'url(#psy-sensory)'"
        :stroke="selectedZones.includes('basal_ganglia') ? '#4f46e5' : '#58a090'"
        stroke-width="1.2"
        @click="toggleZone('basal_ganglia')"
        filter="url(#psy-inner-shadow)"
    />
    <line x1="330" y1="72" x2="368" y2="42" class="brain-label-line"/>
    <text x="370" y="38" text-anchor="start" class="brain-label">Sensory Cortex</text>
    <text x="370" y="48" text-anchor="start" class="brain-label-sm">Touch, pressure, pain</text>

    {{-- ===== TEMPORAL LOBE ===== --}}
    <path
        class="brain-zone"
        d="M145,205 Q135,218 132,240 Q130,265 135,288
           Q142,310 158,328 Q178,345 205,355 Q235,362 265,358
           Q285,354 300,345 Q280,330 260,310
           Q240,288 225,265 Q210,240 200,218 Q190,200 178,195
           Q160,195 145,205 Z"
        :fill="selectedZones.includes('temporal_lobe') ? 'url(#psy-selected)' : 'url(#psy-temporal)'"
        :stroke="selectedZones.includes('temporal_lobe') ? '#4f46e5' : '#b09860'"
        stroke-width="1.2"
        @click="toggleZone('temporal_lobe')"
        filter="url(#psy-inner-shadow)"
    />
    <line x1="165" y1="290" x2="58" y2="310" class="brain-label-line"/>
    <text x="56" y="307" text-anchor="end" class="brain-label">Temporal Lobe</text>
    <text x="56" y="317" text-anchor="end" class="brain-label-sm">Memory, hearing</text>

    {{-- ===== BROCA'S AREA (speech production) ===== --}}
    <path
        class="brain-zone"
        d="M155,165 Q165,158 178,158 Q192,160 198,170
           Q202,180 198,192 Q192,200 180,200
           Q168,200 158,192 Q148,182 155,165 Z"
        :fill="selectedZones.includes('hypothalamus') ? 'url(#psy-selected)' : 'url(#psy-broca)'"
        :stroke="selectedZones.includes('hypothalamus') ? '#4f46e5' : '#a07890'"
        stroke-width="1.2"
        @click="toggleZone('hypothalamus')"
        filter="url(#psy-inner-shadow)"
    />
    <line x1="160" y1="175" x2="62" y2="168" class="brain-label-line"/>
    <text x="60" y="164" text-anchor="end" class="brain-label">Broca's Area</text>
    <text x="60" y="174" text-anchor="end" class="brain-label-sm">Speech production</text>

    {{-- ===== WERNICKE'S AREA (language comprehension) ===== --}}
    <path
        class="brain-zone"
        d="M340,195 Q358,192 372,200 Q385,210 382,225
           Q378,238 365,245 Q348,250 335,245
           Q322,238 318,225 Q316,212 325,200
           Q332,195 340,195 Z"
        :fill="selectedZones.includes('thalamus') ? 'url(#psy-selected)' : 'url(#psy-wernicke)'"
        :stroke="selectedZones.includes('thalamus') ? '#4f46e5' : '#9080a8'"
        stroke-width="1.2"
        @click="toggleZone('thalamus')"
        filter="url(#psy-inner-shadow)"
    />
    <line x1="380" y1="218" x2="460" y2="195" class="brain-label-line"/>
    <text x="462" y="192" text-anchor="start" class="brain-label">Wernicke's Area</text>
    <text x="462" y="202" text-anchor="start" class="brain-label-sm">Language comprehension</text>

    {{-- ===== VISUAL CORTEX (occipital lobe) ===== --}}
    <path
        class="brain-zone"
        d="M430,160 Q455,175 468,205 Q475,230 470,258
           Q462,285 445,305 Q430,318 415,322
           Q425,295 432,265 Q438,235 440,208
           Q440,185 435,165 Z"
        :fill="selectedZones.includes('hippocampus') ? 'url(#psy-selected)' : 'url(#psy-visual)'"
        :stroke="selectedZones.includes('hippocampus') ? '#4f46e5' : '#8878a0'"
        stroke-width="1.2"
        @click="toggleZone('hippocampus')"
        filter="url(#psy-inner-shadow)"
    />
    <line x1="458" y1="240" x2="510" y2="238" class="brain-label-line"/>
    <text x="512" y="235" text-anchor="start" class="brain-label">Visual Cortex</text>
    <text x="512" y="245" text-anchor="start" class="brain-label-sm">Vision processing</text>

    {{-- ===== AUDITORY CORTEX ===== --}}
    <path
        class="brain-zone"
        d="M205,205 Q218,198 235,198 Q255,200 262,212
           Q268,225 260,238 Q248,248 232,248
           Q215,246 205,235 Q198,222 205,205 Z"
        :fill="selectedZones.includes('amygdala') ? 'url(#psy-selected)' : 'url(#psy-auditory)'"
        :stroke="selectedZones.includes('amygdala') ? '#4f46e5' : '#b09068'"
        stroke-width="1.2"
        @click="toggleZone('amygdala')"
        filter="url(#psy-inner-shadow)"
    />
    <line x1="215" y1="225" x2="62" y2="235" class="brain-label-line"/>
    <text x="60" y="232" text-anchor="end" class="brain-label">Auditory Cortex</text>
    <text x="60" y="242" text-anchor="end" class="brain-label-sm">Sound processing</text>

    {{-- ===== PARIETAL ASSOCIATION AREA ===== --}}
    <path
        class="brain-zone"
        d="M345,82 Q375,78 405,92 Q428,108 435,135
           Q438,155 430,168 Q418,178 400,185
           Q380,190 360,192 Q340,192 325,188
           Q330,160 335,130 Q340,105 345,82 Z"
        :fill="selectedZones.includes('temporal_lobe') ? '#818cf870' : '#e0d4c8'"
        stroke="none"
        pointer-events="none" opacity="0.3"
    />

    <!-- ==================== DEEP STRUCTURES (cutaway view) ==================== -->

    <!-- Cutaway indicator -->
    <path d="M255,155 Q275,148 310,155 Q335,162 340,180 Q342,200 330,220
             Q315,240 290,252 Q268,258 250,255 Q235,250 228,238
             Q222,225 228,208 Q235,190 245,172 Q250,162 255,155 Z"
        fill="#f8f0e8" fill-opacity="0.4" stroke="#a09080" stroke-width="0.5" stroke-dasharray="3,2" pointer-events="none" opacity="0.6"/>
    <text x="290" y="153" text-anchor="middle" class="brain-label-sm" fill="#8a7a68" style="font-style:italic" pointer-events="none">— cutaway view —</text>

    {{-- ===== THALAMUS (deep structure) ===== --}}
    {{-- NOTE: Using thalamus zone mapped to actual thalamus deep structure --}}
    {{-- The surface zones above reuse some original IDs for backward compatibility --}}

    {{-- ===== LIMBIC STRUCTURES ===== --}}

    <!-- Amygdala (deep) -->
    <ellipse
        class="brain-zone"
        cx="255" cy="232" rx="16" ry="13"
        :fill="selectedZones.includes('amygdala') ? 'url(#psy-selected)' : 'url(#psy-amygdala)'"
        :stroke="selectedZones.includes('amygdala') ? '#4f46e5' : '#b07070'"
        stroke-width="1" opacity="0.9"
        @click="toggleZone('amygdala')"
    />
    <text x="255" y="235" text-anchor="middle" style="font-family:sans-serif;font-size:6px;fill:#804040" pointer-events="none">Amygdala</text>

    <!-- Hippocampus (deep) -->
    <path
        class="brain-zone"
        d="M275,235 Q288,228 305,232 Q318,238 320,250
           Q318,260 305,262 Q290,264 278,258 Q268,250 272,240 Z"
        :fill="selectedZones.includes('hippocampus') ? 'url(#psy-selected)' : 'url(#psy-hippocampus)'"
        :stroke="selectedZones.includes('hippocampus') ? '#4f46e5' : '#a09040'"
        stroke-width="0.8" opacity="0.9"
        @click="toggleZone('hippocampus')"
    />
    <text x="296" y="250" text-anchor="middle" style="font-family:sans-serif;font-size:5.5px;fill:#706020" pointer-events="none">Hippocampus</text>

    <!-- Thalamus (deep, egg-shaped) -->
    <ellipse
        class="brain-zone"
        cx="290" cy="192" rx="28" ry="18"
        :fill="selectedZones.includes('thalamus') ? 'url(#psy-selected)' : 'url(#psy-thalamus)'"
        :stroke="selectedZones.includes('thalamus') ? '#4f46e5' : '#489068'"
        stroke-width="1" opacity="0.85"
        @click="toggleZone('thalamus')"
    />
    <text x="290" y="195" text-anchor="middle" style="font-family:sans-serif;font-size:6.5px;fill:#285838" pointer-events="none">Thalamus</text>

    <!-- Hypothalamus (deep, below thalamus) -->
    <ellipse
        class="brain-zone"
        cx="262" cy="215" rx="18" ry="12"
        :fill="selectedZones.includes('hypothalamus') ? 'url(#psy-selected)' : 'url(#psy-hypothalamus)'"
        :stroke="selectedZones.includes('hypothalamus') ? '#4f46e5' : '#a07090'"
        stroke-width="0.8" opacity="0.85"
        @click="toggleZone('hypothalamus')"
    />
    <text x="262" y="218" text-anchor="middle" style="font-family:sans-serif;font-size:5.5px;fill:#703858" pointer-events="none">Hypothal.</text>

    <!-- Basal Ganglia (deep) -->
    <ellipse
        class="brain-zone"
        cx="305" cy="165" rx="22" ry="15"
        :fill="selectedZones.includes('basal_ganglia') ? 'url(#psy-selected)' : 'url(#psy-basal)'"
        :stroke="selectedZones.includes('basal_ganglia') ? '#4f46e5' : '#8868a0'"
        stroke-width="0.8" opacity="0.85"
        @click="toggleZone('basal_ganglia')"
    />
    <text x="305" y="168" text-anchor="middle" style="font-family:sans-serif;font-size:5.5px;fill:#503868" pointer-events="none">Basal Gang.</text>

    <!-- ==================== BRAINSTEM ==================== -->
    {{-- ===== BRAINSTEM ===== --}}
    <path
        class="brain-zone"
        d="M275,278 Q285,272 295,278 Q302,290 305,310
           Q308,335 305,360 Q300,380 292,390
           Q285,398 278,392 Q272,382 268,365
           Q264,340 265,315 Q266,295 270,282 Z"
        :fill="selectedZones.includes('brainstem') ? 'url(#psy-selected)' : 'url(#psy-brainstem)'"
        :stroke="selectedZones.includes('brainstem') ? '#4f46e5' : '#4a8878'"
        stroke-width="1.2"
        @click="toggleZone('brainstem')"
        filter="url(#psy-inner-shadow)"
    />
    <!-- Brainstem sections -->
    <g :opacity="selectedZones.includes('brainstem') ? '0.2' : '0.3'" stroke="#3a6858" stroke-width="0.4" fill="none" pointer-events="none">
        <path d="M270 310 Q285 306 300 310"/>
        <path d="M268 335 Q283 330 302 335"/>
        <path d="M270 358 Q282 354 298 358"/>
    </g>
    <line x1="302" y1="340" x2="408" y2="370" class="brain-label-line"/>
    <text x="410" y="367" text-anchor="start" class="brain-label">Brainstem</text>
    <text x="410" y="377" text-anchor="start" class="brain-label-sm">Vital functions</text>

    <!-- Brainstem sub-labels -->
    <g pointer-events="none" style="font-family:sans-serif;font-size:5px;fill:#3a6858" opacity="0.6">
        <text x="285" y="302" text-anchor="middle">Midbrain</text>
        <text x="285" y="330" text-anchor="middle">Pons</text>
        <text x="285" y="368" text-anchor="middle">Medulla</text>
    </g>

    <!-- ==================== CEREBELLUM ==================== -->
    {{-- ===== CEREBELLUM ===== --}}
    <path
        class="brain-zone"
        d="M315,290 Q345,280 380,285 Q410,292 430,312
           Q442,330 438,348 Q430,362 412,368
           Q390,374 365,370 Q340,365 320,352
           Q305,340 300,322 Q298,305 308,292 Z"
        :fill="selectedZones.includes('cerebellum') ? 'url(#psy-selected)' : 'url(#psy-cerebellum)'"
        :stroke="selectedZones.includes('cerebellum') ? '#4f46e5' : '#5890a8'"
        stroke-width="1.2"
        @click="toggleZone('cerebellum')"
        filter="url(#psy-inner-shadow)"
    />
    <!-- Cerebellar folia (parallel lines for texture) -->
    <g :opacity="selectedZones.includes('cerebellum') ? '0.15' : '0.25'" stroke="#407088" stroke-width="0.5" fill="none" pointer-events="none">
        <path d="M318 300 Q350 294 385 300 Q415 308 430 320"/>
        <path d="M312 312 Q345 306 380 312 Q410 320 428 332"/>
        <path d="M308 324 Q340 318 375 324 Q405 332 425 345"/>
        <path d="M310 338 Q338 332 368 338 Q398 346 418 358"/>
        <path d="M318 350 Q342 345 365 350 Q390 358 408 366"/>
    </g>
    <line x1="430" y1="325" x2="480" y2="312" class="brain-label-line"/>
    <text x="482" y="309" text-anchor="start" class="brain-label">Cerebellum</text>
    <text x="482" y="319" text-anchor="start" class="brain-label-sm">Balance, coordination</text>

    {{-- ===== CINGULATE CORTEX ===== --}}
    <path
        class="brain-zone"
        d="M248,62 Q270,56 295,58 Q318,62 335,72
           Q322,82 308,78 Q290,72 270,70
           Q255,70 245,74 Q240,72 248,62 Z"
        :fill="selectedZones.includes('cingulate_cortex') ? 'url(#psy-selected)' : 'url(#psy-cingulate)'"
        :stroke="selectedZones.includes('cingulate_cortex') ? '#4f46e5' : '#6880a8'"
        stroke-width="0.8"
        @click="toggleZone('cingulate_cortex')"
    />

    <!-- ==================== CONNECTING LABEL LINES ==================== -->

    <!-- Deep structure labels with connector lines -->
    <g pointer-events="none">
        <line x1="318" y1="162" x2="435" y2="100" class="brain-label-line"/>
        <text x="438" y="97" text-anchor="start" class="brain-label">Basal Ganglia</text>

        <line x1="318" y1="190" x2="448" y2="145" class="brain-label-line"/>
        <text x="450" y="142" text-anchor="start" class="brain-label">Thalamus</text>

        <line x1="248" y1="218" x2="70" y2="198" class="brain-label-line"/>
        <text x="68" y="195" text-anchor="end" class="brain-label">Hypothalamus</text>

        <line x1="242" y1="235" x2="68" y2="270" class="brain-label-line"/>
        <text x="66" y="268" text-anchor="end" class="brain-label">Amygdala</text>
        <text x="66" y="278" text-anchor="end" class="brain-label-sm">Emotion, fear</text>

        <line x1="318" y1="258" x2="432" y2="268" class="brain-label-line"/>
        <text x="434" y="265" text-anchor="start" class="brain-label">Hippocampus</text>
        <text x="434" y="275" text-anchor="start" class="brain-label-sm">Memory formation</text>

        <line x1="282" y1="65" x2="308" y2="42" class="brain-label-line"/>
        <text x="310" y="42" text-anchor="start" class="brain-label-sm">Cingulate</text>
    </g>

    <!-- ==================== SPINAL CORD HINT ==================== -->
    <path d="M285 392 Q283 405 282 420 Q281 435 282 445"
        fill="none" stroke="#6a9888" stroke-width="2" opacity="0.4" pointer-events="none"/>
    <text x="282" y="460" text-anchor="middle" class="brain-label-sm" fill="#6a9888">Spinal cord</text>

    <!-- Instruction -->
    <text x="300" y="490" text-anchor="middle" style="font-family:sans-serif;font-size:8px" fill="#94a3b8">Click on a brain region to select or deselect it</text>
</svg>
