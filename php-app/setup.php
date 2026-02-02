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
                
                // VN Flag (Star)
                const vnIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><path d="M12 2a10 10 0 1 0 10 10 10 10 0 0 0-10-10z"/><path d="m9 12 3-3 3 3"/><path d="m12 9 3 3-3 3"/><path d="m9 15 3-3 3 3"/></svg>`; // Simplified star-ish
                const latamIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-500"><circle cx="12" cy="12" r="10"/><line x1="2" x2="22" y1="12" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>`;

                addOption(container, vnIcon, "Vietnam", "Tiếng Việt", () => {
                    store.setNativeLanguage('vi');
                    step = 2; renderStep();
                });
                addOption(container, latamIcon, "Latin America", "Español", () => {
                    store.setNativeLanguage('es');
                    step = 2; renderStep();
                });
            } 
            else if (step === 2) {
                title.innerText = "Choose your Dialect";
                sub.innerText = "We'll customize the voice for you";
                const lang = store.getState().nativeLanguage;

                if (lang === 'vi') {
                    const northIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-dark"><path d="m12 3-9.5 18h19z"/><path d="m12 8 4 8H8z"/></svg>`; // Landmark
                    const southIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-orange"><circle cx="12" cy="12" r="5"/><path d="M12 1v2"/><path d="M12 21v2"/><path d="M4.2 4.2l1.4 1.4"/><path d="M18.4 18.4l1.4 1.4"/><path d="M1 12h2"/><path d="M21 12h2"/><path d="M4.2 19.8l1.4-1.4"/><path d="M18.4 5.6l1.4-1.4"/></svg>`; // Sun
                    
                    addOption(container, northIcon, "Northern (Hanoi)", "Chuẩn giọng Bắc", () => confirmDialect('vi_north'));
                    addOption(container, southIcon, "Southern (Saigon)", "Giọng Miền Nam", () => confirmDialect('vi_south'));
                } else {
                    container.className = "grid grid-cols-1 md:grid-cols-3 gap-4";
                    
                    const medellinIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-green"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>`; // Mountain
                    const cartagenaIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/></svg>`; // Waves
                    const bogotaIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-600"><rect width="8" height="18" x="8" y="3" rx="1"/><path d="M12 7h.01"/><path d="M12 11h.01"/><path d="M12 15h.01"/><path d="M12 19h.01"/></svg>`; // Building

                    addOption(container, medellinIcon, "Medellín", "Paisa", () => confirmDialect('es_medellin'));
                    addOption(container, cartagenaIcon, "Cartagena", "Costeño", () => confirmDialect('es_cartagena'));
                    addOption(container, bogotaIcon, "Bogotá", "Rolo", () => confirmDialect('es_bogota'));
                }
            }
            else if (step === 3) {
                container.className = "grid grid-cols-1 gap-4";
                title.innerText = "What's your goal?";
                sub.innerText = "Pick a learning path";

                const academicIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-purple"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>`;
                const convIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-blue"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>`;

                addOption(container, academicIcon, "Academic / School", "Formal grammar & essays", () => {
                    store.setLearningMode('academic');
                    store.setSubMode('proper');
                    finish();
                });
                addOption(container, convIcon, "Conversational", "Speaking naturally", () => {
                    store.setLearningMode('conversational');
                    step = 4; renderStep();
                });
            }
            else if (step === 4) {
                title.innerText = "Conversational Style";
                sub.innerText = "How do you want to sound?";

                const properIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-700"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M17 11l2 2 4-4"/></svg>`;
                const slangIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-pink-500"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>`;

                addOption(container, properIcon, "Polite & Proper", "Standard English", () => {
                    store.setSubMode('proper');
                    finish();
                });
                addOption(container, slangIcon, "Slang & Casual", "Idioms & Street Talk", () => {
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
