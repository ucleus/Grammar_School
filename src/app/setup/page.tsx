"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { motion, AnimatePresence } from "framer-motion";
import { ArrowLeft, ArrowRight, Check, Globe, MapPin, BookOpen, MessageCircle } from "lucide-react";
import { useAppStore, type Language, type Dialect, type LearningMode, type SubMode } from "@/store/useAppStore";
import clsx from "clsx";

export default function SetupPage() {
    const router = useRouter();
    const [step, setStep] = useState(1);
    const {
        nativeLanguage, setNativeLanguage,
        targetDialect, setTargetDialect,
        learningMode, setLearningMode,
        subMode, setSubMode
    } = useAppStore();

    const nextStep = () => setStep(s => s + 1);
    const prevStep = () => setStep(s => s - 1);

    const finishSetup = () => {
        router.push("/learn");
    };

    return (
        <div className="min-h-screen bg-brand-light flex flex-col items-center justify-center p-6">
            <div className="w-full max-w-2xl">
                {/* Progress Bar */}
                <div className="flex justify-between mb-8 px-4">
                    {[1, 2, 3, 4].map((i) => (
                        <div key={i} className="flex flex-col items-center gap-2">
                            <div
                                className={clsx(
                                    "w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg transition-colors duration-300",
                                    step >= i ? "bg-secondary text-white shadow-lg" : "bg-white text-slate-300 border-2 border-slate-100"
                                )}
                            >
                                {step > i ? <Check size={20} /> : i}
                            </div>
                        </div>
                    ))}
                </div>

                <AnimatePresence mode="wait">
                    {step === 1 && (
                        <StepWrapper key="step1" title="Where are you from?" subtitle="Choose your native language">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <OptionCard
                                    active={nativeLanguage === 'vi'}
                                    onClick={() => { setNativeLanguage('vi'); nextStep(); }}
                                    icon="🇻🇳"
                                    title="Vietnam"
                                    desc="Tiếng Việt"
                                />
                                <OptionCard
                                    active={nativeLanguage === 'es'}
                                    onClick={() => { setNativeLanguage('es'); nextStep(); }}
                                    icon="🇨🇴" // Using Colombia flag as primary target
                                    title="Latin America"
                                    desc="Español"
                                />
                            </div>
                        </StepWrapper>
                    )}

                    {step === 2 && (
                        <StepWrapper key="step2" title="Choose your Dialect" subtitle="We'll customize the voice and slang for you">
                            {nativeLanguage === 'vi' && (
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <OptionCard
                                        active={targetDialect === 'vi_north'}
                                        onClick={() => { setTargetDialect('vi_north'); nextStep(); }}
                                        icon="🗼"
                                        title="Northern (Hanoi)"
                                        desc="Chuẩn giọng Bắc"
                                    />
                                    <OptionCard
                                        active={targetDialect === 'vi_south'}
                                        onClick={() => { setTargetDialect('vi_south'); nextStep(); }}
                                        icon="🌴"
                                        title="Southern (Saigon)"
                                        desc="Giọng Miền Nam"
                                    />
                                </div>
                            )}
                            {nativeLanguage === 'es' && (
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <OptionCard
                                        active={targetDialect === 'es_medellin'}
                                        onClick={() => { setTargetDialect('es_medellin'); nextStep(); }}
                                        icon="⛰️"
                                        title="Medellín"
                                        desc="Paisa"
                                    />
                                    <OptionCard
                                        active={targetDialect === 'es_cartagena'}
                                        onClick={() => { setTargetDialect('es_cartagena'); nextStep(); }}
                                        icon="🌊"
                                        title="Cartagena"
                                        desc="Costeño"
                                    />
                                    <OptionCard
                                        active={targetDialect === 'es_bogota'}
                                        onClick={() => { setTargetDialect('es_bogota'); nextStep(); }}
                                        icon="🏙️"
                                        title="Bogotá"
                                        desc="Rolo"
                                    />
                                </div>
                            )}
                        </StepWrapper>
                    )}

                    {step === 3 && (
                        <StepWrapper key="step3" title="What's your goal?" subtitle="Pick a learning path">
                            <div className="grid grid-cols-1 gap-4">
                                <OptionCard
                                    active={learningMode === 'academic'}
                                    onClick={() => {
                                        setLearningMode('academic');
                                        setSubMode('proper'); // Default for academic
                                        finishSetup();
                                    }}
                                    icon={<BookOpen className="text-brand-purple" size={32} />}
                                    title="Academic / School"
                                    desc="Formal grammar, essays, and exams"
                                />
                                <OptionCard
                                    active={learningMode === 'conversational'}
                                    onClick={() => { setLearningMode('conversational'); nextStep(); }}
                                    icon={<MessageCircle className="text-brand-blue" size={32} />}
                                    title="Conversational"
                                    desc="Speaking naturally with friends"
                                />
                            </div>
                        </StepWrapper>
                    )}

                    {step === 4 && learningMode === 'conversational' && (
                        <StepWrapper key="step4" title="Conversational Style" subtitle="How do you want to sound?">
                            <div className="grid grid-cols-1 gap-4">
                                <OptionCard
                                    active={subMode === 'proper'}
                                    onClick={() => { setSubMode('proper'); finishSetup(); }}
                                    icon="👔"
                                    title="Polite & Proper"
                                    desc="Standard English for everyday use"
                                />
                                <OptionCard
                                    active={subMode === 'slang'}
                                    onClick={() => { setSubMode('slang'); finishSetup(); }}
                                    icon="😎"
                                    title="Slang & Casual"
                                    desc="Idioms, street talk, and cool phrases"
                                />
                            </div>
                        </StepWrapper>
                    )}
                </AnimatePresence>

                {step > 1 && (
                    <button
                        onClick={prevStep}
                        className="mt-8 flex items-center gap-2 text-slate-400 hover:text-slate-600 font-bold transition-colors mx-auto"
                    >
                        <ArrowLeft size={16} /> Back
                    </button>
                )}
            </div>
        </div>
    );
}

// Helper Components
function StepWrapper({ children, title, subtitle }: { children: React.ReactNode, title: string, subtitle: string }) {
    return (
        <motion.div
            initial={{ opacity: 0, x: 20 }}
            animate={{ opacity: 1, x: 0 }}
            exit={{ opacity: 0, x: -20 }}
            className="bg-white rounded-3xl p-8 shadow-xl border border-white/50"
        >
            <div className="text-center mb-8">
                <h2 className="text-3xl font-extrabold font-display text-brand-dark mb-2">{title}</h2>
                <p className="text-slate-500 font-medium">{subtitle}</p>
            </div>
            {children}
        </motion.div>
    );
}

function OptionCard({ active, onClick, icon, title, desc }: any) {
    return (
        <button
            onClick={onClick}
            className={clsx(
                "flex flex-col items-center p-6 rounded-2xl border-2 transition-all duration-300 hover:scale-105 active:scale-95 text-center h-full",
                active
                    ? "border-secondary bg-secondary/5 ring-4 ring-secondary/20"
                    : "border-slate-100 bg-slate-50 hover:border-secondary/50 hover:bg-white"
            )}
        >
            <div className="text-4xl mb-4">{icon}</div>
            <h3 className="text-xl font-bold text-brand-dark mb-2 font-display">{title}</h3>
            <p className="text-sm text-slate-500 font-semibold">{desc}</p>
        </button>
    );
}
