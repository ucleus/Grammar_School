<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setup - Grammar School</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-brand-light min-h-screen flex flex-col items-center justify-center p-6 font-sans">

    <div class="w-full max-w-2xl bg-white rounded-3xl p-8 shadow-xl border border-white/50 relative overflow-hidden">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 id="step-title" class="text-3xl font-extrabold text-brand-dark mb-2">Where are you from?</h2>
            <p id="step-subtitle" class="text-slate-500 font-medium">Choose your native language</p>
        </div>

        <!-- Options Container -->
        <div id="options-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Options injected by JS -->
        </div>

        <!-- Back Button -->
        <button id="back-btn" class="mt-8 hidden text-slate-400 hover:text-slate-600 font-bold flex items-center gap-2 mx-auto">
            ← Back
        </button>

    </div>

    <script src="assets/js/app.js"></script>
    <script>
        let step = 1;

        const renderStep = () => {
            const container = document.getElementById('options-container');
            const title = document.getElementById('step-title');
            const sub = document.getElementById('step-subtitle');
            const backBtn = document.getElementById('back-btn');

            container.innerHTML = '';
            backBtn.classList.toggle('hidden', step === 1);

            if (step === 1) {
                title.innerText = "Where are you from?";
                sub.innerText = "Choose your native language";
                
                addOption(container, "🇻🇳", "Vietnam", "Tiếng Việt", () => {
                    store.setNativeLanguage('vi');
                    step = 2; renderStep();
                });
                addOption(container, "🇨🇴", "Latin America", "Español", () => {
                    store.setNativeLanguage('es');
                    step = 2; renderStep();
                });
            } 
            else if (step === 2) {
                title.innerText = "Choose your Dialect";
                sub.innerText = "We'll customize the voice for you";
                const lang = store.getState().nativeLanguage;

                if (lang === 'vi') {
                    addOption(container, "🗼", "Northern (Hanoi)", "Chuẩn giọng Bắc", () => confirmDialect('vi_north'));
                    addOption(container, "🌴", "Southern (Saigon)", "Giọng Miền Nam", () => confirmDialect('vi_south'));
                } else {
                    container.className = "grid grid-cols-1 md:grid-cols-3 gap-4";
                    addOption(container, "⛰️", "Medellín", "Paisa", () => confirmDialect('es_medellin'));
                    addOption(container, "🌊", "Cartagena", "Costeño", () => confirmDialect('es_cartagena'));
                    addOption(container, "🏙️", "Bogotá", "Rolo", () => confirmDialect('es_bogota'));
                }
            }
            else if (step === 3) {
                container.className = "grid grid-cols-1 gap-4";
                title.innerText = "What's your goal?";
                sub.innerText = "Pick a learning path";

                addOption(container, "📖", "Academic / School", "Formal grammar & essays", () => {
                    store.setLearningMode('academic');
                    store.setSubMode('proper');
                    finish();
                });
                addOption(container, "💬", "Conversational", "Speaking naturally", () => {
                    store.setLearningMode('conversational');
                    step = 4; renderStep();
                });
            }
            else if (step === 4) {
                title.innerText = "Conversational Style";
                sub.innerText = "How do you want to sound?";

                addOption(container, "👔", "Polite & Proper", "Standard English", () => {
                    store.setSubMode('proper');
                    finish();
                });
                addOption(container, "😎", "Slang & Casual", "Idioms & Street Talk", () => {
                    store.setSubMode('slang');
                    finish();
                });
            }
        };

        const addOption = (parent, icon, title, desc, onClick) => {
            const btn = document.createElement('button');
            btn.className = "flex flex-col items-center p-6 rounded-2xl border-2 border-slate-100 bg-slate-50 hover:border-secondary/50 hover:bg-white hover:scale-105 transition-all duration-300 text-center cursor-pointer";
            btn.innerHTML = `
                <div class="text-4xl mb-4">${icon}</div>
                <h3 class="text-xl font-bold text-brand-dark mb-2">${title}</h3>
                <p class="text-sm text-slate-500 font-semibold">${desc}</p>
            `;
            btn.onclick = onClick;
            parent.appendChild(btn);
        };

        const confirmDialect = (dialect) => {
            store.setTargetDialect(dialect);
            step = 3;
            renderStep();
        };

        const finish = () => {
            window.location.href = 'learn.php';
        };

        document.getElementById('back-btn').onclick = () => {
            if (step > 1) {
                step--;
                renderStep();
            }
        };

        renderStep();
    </script>
</body>
</html>
