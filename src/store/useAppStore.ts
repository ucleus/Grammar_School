import { create } from 'zustand';
import { persist } from 'zustand/middleware';

export type Language = 'en' | 'vi' | 'es';
export type Dialect =
    | 'vi_north' | 'vi_south'
    | 'es_medellin' | 'es_cartagena' | 'es_bogota';
export type LearningMode = 'academic' | 'conversational';
export type SubMode = 'proper' | 'slang';

interface AppState {
    // User Preferences
    nativeLanguage: Language | null;
    targetDialect: Dialect | null;
    learningMode: LearningMode | null;
    subMode: SubMode | null;

    // Actions
    setNativeLanguage: (lang: Language) => void;
    setTargetDialect: (dialect: Dialect) => void;
    setLearningMode: (mode: LearningMode) => void;
    setSubMode: (sub: SubMode) => void;
    reset: () => void;
}

export const useAppStore = create<AppState>()(
    persist(
        (set) => ({
            nativeLanguage: null,
            targetDialect: null,
            learningMode: null,
            subMode: null,

            setNativeLanguage: (lang) => set({ nativeLanguage: lang }),
            setTargetDialect: (dialect) => set({ targetDialect: dialect }),
            setLearningMode: (mode) => set({ learningMode: mode }),
            setSubMode: (sub) => set({ subMode: sub }),
            reset: () => set({
                nativeLanguage: null,
                targetDialect: null,
                learningMode: null,
                subMode: null
            }),
        }),
        {
            name: 'grammar-school-storage',
        }
    )
);
