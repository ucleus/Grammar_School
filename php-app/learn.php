<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practice - Grammar School</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-brand-light min-h-screen p-6 font-sans">
    
    <div class="max-w-2xl mx-auto space-y-8">
        <!-- Header -->
        <header class="flex items-center justify-between">
            <a href="setup.php" class="text-slate-400 hover:text-slate-600">← Settings</a>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm">
                <span class="text-xl">🔥</span>
                <span class="font-bold text-brand-orange">0 Streaks</span>
            </div>
        </header>

        <!-- Mode Indicator -->
        <div class="text-center">
            <h1 id="mode-display" class="text-2xl font-bold text-slate-700 capitalize">...</h1>
            <p id="dialect-display" class="text-slate-500 text-sm mt-1">...</p>
        </div>

        <!-- Phrase Card -->
        <div class="bg-white rounded-[32px] p-8 shadow-xl border-b-8 border-slate-100 flex flex-col gap-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-brand-blue to-brand-green"></div>
            
            <div class="flex justify-between items-start">
               <span id="context-badge" class="bg-slate-100 text-slate-500 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">
                 Loading...
               </span>
               <button id="play-btn" class="w-12 h-12 rounded-full bg-brand-blue/10 text-brand-blue flex items-center justify-center hover:bg-brand-blue hover:text-white transition-colors">
                 🔊
               </button>
            </div>

            <div class="space-y-4 text-center py-4">
                <h2 id="english-text" class="text-4xl font-extrabold text-brand-dark leading-tight">...</h2>
                <div class="w-16 h-1 bg-slate-100 mx-auto rounded-full"></div>
                <p id="native-text" class="text-2xl text-slate-500 font-medium">...</p>
            </div>
        </div>

        <!-- Ad Space -->
        <div id="ad-container" class="hidden w-full rounded-xl overflow-hidden shadow-sm border border-slate-100 relative group cursor-pointer">
             <div class="absolute top-1 right-1 bg-black/20 text-white text-[10px] px-1 rounded">Ad</div>
             <a id="ad-link" href="#" target="_blank">
                <img id="ad-image" src="" class="w-full h-32 object-cover" />
             </a>
        </div>

        <!-- Controls -->
        <div class="flex justify-center">
           <button onclick="loadPhrase()" class="flex items-center gap-2 px-6 py-3 bg-white border-2 border-brand-blue text-brand-blue font-bold rounded-full hover:bg-brand-blue hover:text-white transition-all shadow-sm">
              Next Phrase →
           </button>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
    <script>
        // Check Auth
        store.requireSetup();
        const state = store.getState();

        document.getElementById('mode-display').innerText = `${state.learningMode} Practice`;
        document.getElementById('dialect-display').innerText = state.targetDialect;

        // Mock Content (Would eventually come from DB too)
        const PHRASES = [
            { en: "I'm gonna hit the sack.", vi: "Tui đi khò đây.", es: "Voy p'al sobre.", tag: "Slang" },
            { en: "Can I get a coffee?", vi: "Cho tôi một cà phê?", es: "Me regala un tinto?", tag: "Casual" },
            { en: "It's raining cats and dogs.", vi: "Mưa như trút nước.", es: "Está lloviendo a cántaros.", tag: "Idiom" }
        ];

        let currentPhrase = null;

        function loadPhrase() {
            const index = Math.floor(Math.random() * PHRASES.length);
            currentPhrase = PHRASES[index];
            
            document.getElementById('english-text').innerText = currentPhrase.en;
            document.getElementById('native-text').innerText = state.nativeLanguage === 'vi' ? currentPhrase.vi : currentPhrase.es;
            document.getElementById('context-badge').innerText = currentPhrase.tag;
        }

        async function playAudio() {
            const btn = document.getElementById('play-btn');
            btn.classList.add('animate-pulse');
            
            try {
                const response = await fetch('api/voice.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        text: currentPhrase.en,
                        dialect: state.targetDialect,
                        type: 'english'
                    })
                });

                if (!response.ok) throw new Error('Speech failed');

                const blob = await response.blob();
                const audio = new Audio(URL.createObjectURL(blob));
                audio.play();
                audio.onended = () => btn.classList.remove('animate-pulse');

            } catch (e) {
                console.error(e);
                alert("Audio error. Check API Key.");
                btn.classList.remove('animate-pulse');
            }
        }

        async function loadAd() {
            try {
                const res = await fetch(`api/ads.php?lang=${state.nativeLanguage}`);
                const ad = await res.json();
                
                if (ad) {
                    document.getElementById('ad-image').src = ad.imageUrl;
                    document.getElementById('ad-link').href = ad.targetUrl;
                    document.getElementById('ad-container').classList.remove('hidden');
                }
            } catch (e) {
                console.log("Ad load failed", e);
            }
        }

        document.getElementById('play-btn').onclick = playAudio;

        // Init
        loadPhrase();
        loadAd();

    </script>
</body>
</html>
