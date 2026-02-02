/**
 * assets/js/app.js
 * State Management & UI Logic (Vanilla JS version of Zustand store)
 */

class AppStore {
    constructor() {
        this.state = this.loadState();
        this.listeners = [];
    }

    loadState() {
        const stored = localStorage.getItem('grammar-school-storage');
        if (stored) {
            try {
                return JSON.parse(stored);
            } catch (e) {
                console.error("Failed to parse state", e);
            }
        }
        return {
            nativeLanguage: null,
            targetDialect: null,
            learningMode: null,
            subMode: null
        };
    }

    saveState() {
        localStorage.setItem('grammar-school-storage', JSON.stringify(this.state));
        this.notify();
    }

    getState() {
        return this.state;
    }

    setNativeLanguage(lang) {
        this.state.nativeLanguage = lang;
        this.saveState();
    }

    setTargetDialect(dialect) {
        this.state.targetDialect = dialect;
        this.saveState();
    }

    setLearningMode(mode) {
        this.state.learningMode = mode;
        this.saveState();
    }

    setSubMode(sub) {
        this.state.subMode = sub;
        this.saveState();
    }

    subscribe(listener) {
        this.listeners.push(listener);
        return () => {
            this.listeners = this.listeners.filter(l => l !== listener);
        };
    }

    notify() {
        this.listeners.forEach(listener => listener(this.state));
    }
    
    // Check if user is setup, otherwise redirect
    requireSetup() {
        if (!this.state.nativeLanguage || !this.state.targetDialect) {
            window.location.href = 'setup.php';
        }
    }
}

const store = new AppStore();
