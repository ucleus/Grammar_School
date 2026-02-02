"use client";

import { useEffect, useState } from "react";
import { useAppStore } from "@/store/useAppStore";
import { useRouter } from "next/navigation";
import { ArrowLeft, RefreshCw, Volume2 } from "lucide-react";
import { motion } from "framer-motion";

export default function LearnPage() {
    const router = useRouter();
    const { nativeLanguage, targetDialect, learningMode, subMode } = useAppStore();
    const [loading, setLoading] = useState(true);

    // Protected Route Check
    useEffect(() => {
        if (!nativeLanguage || !targetDialect) {
            router.replace("/setup");
        } else {
            setLoading(false);
        }
    }, [nativeLanguage, targetDialect, router]);

    if (loading) return null;

    return (
        <div className="min-h-screen bg-brand-light p-6">
            {/* Header */}
            <header className="flex items-center justify-between max-w-4xl mx-auto mb-8">
                <button onClick={() => router.push("/setup")} className="text-slate-400 hover:text-slate-600 transition-colors">
                    <ArrowLeft />
                </button>
                <div className="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm">
                    <span className="text-xl">🔥</span>
                    <span className="font-bold text-brand-orange">0 Streaks</span>
                </div>
            </header>

            {/* Main Content Area */}
            <main className="max-w-2xl mx-auto space-y-8">
                <div className="text-center">
                    <h1 className="text-2xl font-bold text-slate-700">
                        {learningMode === 'academic' ? "Academic Library" : "Conversational Practice"}
                        {subMode === 'slang' && <span className="ml-2 text-brand-purple text-sm uppercase px-2 py-1 bg-brand-purple/10 rounded-lg">Slang Mode</span>}
                    </h1>
                    <p className="text-slate-500 text-sm mt-1">Dialect: {targetDialect}</p>
                </div>

                {/* Phrase Card - Placeholder for now */}
                <PhraseCard
                    english="I'm gonna hit the sack."
                    native={nativeLanguage === 'vi' ? "Tui đi khò đây." : "Voy p'al sobre."}
                    context="Slang / Informal"
                />

                {/* Ad Space */}
                <AdBanner />

                <div className="flex justify-center">
                    <button className="flex items-center gap-2 px-6 py-3 bg-white border-2 border-brand-blue text-brand-blue font-bold rounded-full hover:bg-brand-blue hover:text-white transition-all shadow-sm">
                        <RefreshCw size={20} /> Next Phrase
                    </button>
                </div>
            </main>
        </div>
    );
}

function AdBanner() {
    const { nativeLanguage } = useAppStore();
    const [ad, setAd] = useState<any>(null);

    useEffect(() => {
        // Fetch valid ad based on user language
        fetch(`/api/ads?lang=${nativeLanguage || 'all'}`)
            .then(res => res.json())
            .then(data => setAd(data))
            .catch(err => console.error(err));
    }, [nativeLanguage]);

    if (!ad) return (
        <div className="w-full h-24 bg-slate-100 rounded-xl flex items-center justify-center text-slate-300 text-xs border-2 border-dashed border-slate-200">
            Sponsored Content
        </div>
    );

    return (
        <motion.div
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            className="w-full rounded-xl overflow-hidden shadow-sm border border-slate-100 relative group"
        >
            <div className="absolute top-1 right-1 bg-black/20 text-white text-[10px] px-1 rounded">Ad</div>
            <a href={ad.targetUrl} target="_blank" rel="noopener noreferrer">
                <img src={ad.imageUrl} alt={ad.name} className="w-full h-32 object-cover" />
            </a>
        </motion.div>
    );
}

function PhraseCard({ english, native, context }: { english: string, native: string, context: string }) {
    const [isPlaying, setIsPlaying] = useState(false);

    const playAudio = async () => {
        setIsPlaying(true);
        // Simulate playing
        setTimeout(() => setIsPlaying(false), 2000);
        // TODO: Call API /api/speak
    };

    return (
        <motion.div
            initial={{ scale: 0.95, opacity: 0 }}
            animate={{ scale: 1, opacity: 1 }}
            className="bg-white rounded-[32px] p-8 shadow-xl border-b-8 border-slate-100 flex flex-col gap-6 relative overflow-hidden"
        >
            <div className="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-brand-blue to-brand-green"></div>

            <div className="flex justify-between items-start">
                <span className="bg-slate-100 text-slate-500 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">
                    {context}
                </span>
                <button
                    onClick={playAudio}
                    className="w-12 h-12 rounded-full bg-brand-blue/10 text-brand-blue flex items-center justify-center hover:bg-brand-blue hover:text-white transition-colors"
                >
                    {isPlaying ? <span className="animate-pulse">🔊</span> : <Volume2 size={24} />}
                </button>
            </div>

            <div className="space-y-4 text-center py-4">
                <h2 className="text-4xl font-extrabold text-brand-dark font-display leading-tight">
                    {english}
                </h2>
                <div className="w-16 h-1 bg-slate-100 mx-auto rounded-full"></div>
                <p className="text-2xl text-slate-500 font-medium">
                    {native}
                </p>
            </div>
        </motion.div>
    );
}
