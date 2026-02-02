"use client";

import { useState } from 'react';
import { Plus, Trash2, BarChart2, Save } from 'lucide-react';
import clsx from 'clsx';

// Simulated Type
type Ad = {
    id: string;
    name: string;
    imageUrl: string;
    targetUrl: string;
    targetLanguage: string;
    views: number;
    clicks: number;
    isActive: boolean;
};

export default function AdminPage() {
    const [ads, setAds] = useState<Ad[]>([
        {
            id: '1',
            name: 'Summer Promo',
            imageUrl: 'https://placehold.co/600x150/4ECDC4/white?text=Summer+Sale',
            targetUrl: 'https://example.com/summer',
            targetLanguage: 'all',
            views: 1205,
            clicks: 45,
            isActive: true
        }
    ]);

    const [isAdding, setIsAdding] = useState(false);
    const [newAd, setNewAd] = useState({ name: '', targetUrl: '', targetLanguage: 'all' });

    const handleAdd = () => {
        // In a real app, this would POST to an API
        const ad: Ad = {
            id: Math.random().toString(),
            name: newAd.name || 'New Campaign',
            imageUrl: 'https://placehold.co/600x150/FF6B6B/white?text=New+Ad',
            targetUrl: newAd.targetUrl || '#',
            targetLanguage: newAd.targetLanguage,
            views: 0,
            clicks: 0,
            isActive: true
        };
        setAds([...ads, ad]);
        setIsAdding(false);
        setNewAd({ name: '', targetUrl: '', targetLanguage: 'all' });
    };

    const deleteAd = (id: string) => {
        setAds(ads.filter(a => a.id !== id));
    };

    return (
        <div className="min-h-screen bg-slate-50 p-8">
            <div className="max-w-5xl mx-auto">
                <header className="flex justify-between items-center mb-10">
                    <div>
                        <h1 className="text-3xl font-extrabold text-slate-800">Ad Manager</h1>
                        <p className="text-slate-500">Manage your campaigns and view performance</p>
                    </div>
                    <button
                        onClick={() => setIsAdding(!isAdding)}
                        className="flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl hover:bg-slate-700 transition"
                    >
                        <Plus size={18} /> New Campaign
                    </button>
                </header>

                {isAdding && (
                    <div className="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 mb-8 animate-in fade-in slide-in-from-top-4">
                        <h2 className="text-lg font-bold mb-4">Create New Campaign</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <input
                                placeholder="Campaign Name"
                                className="p-3 border rounded-lg bg-slate-50"
                                value={newAd.name} onChange={e => setNewAd({ ...newAd, name: e.target.value })}
                            />
                            <input
                                placeholder="Target URL"
                                className="p-3 border rounded-lg bg-slate-50"
                                value={newAd.targetUrl} onChange={e => setNewAd({ ...newAd, targetUrl: e.target.value })}
                            />
                            <select
                                className="p-3 border rounded-lg bg-slate-50"
                                value={newAd.targetLanguage} onChange={e => setNewAd({ ...newAd, targetLanguage: e.target.value })}
                            >
                                <option value="all">All Languages</option>
                                <option value="vi">Vietnamese Only</option>
                                <option value="es">Spanish Only</option>
                            </select>
                            <div className="p-3 border rounded-lg bg-slate-50 border-dashed text-slate-400 flex items-center justify-center">
                                Image Upload (Coming Soon)
                            </div>
                        </div>
                        <div className="flex justify-end gap-2">
                            <button onClick={() => setIsAdding(false)} className="px-4 py-2 text-slate-500 hover:bg-slate-100 rounded-lg">Cancel</button>
                            <button onClick={handleAdd} className="px-4 py-2 bg-brand-blue text-white rounded-lg flex items-center gap-2 font-bold">
                                <Save size={16} /> Save Campaign
                            </button>
                        </div>
                    </div>
                )}

                <div className="space-y-4">
                    {ads.map((ad) => (
                        <div key={ad.id} className="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-6">
                            <img src={ad.imageUrl} alt={ad.name} className="w-full md:w-48 h-24 object-cover rounded-lg bg-slate-100" />

                            <div className="flex-1 text-center md:text-left">
                                <div className="flex items-center justify-center md:justify-start gap-3 mb-1">
                                    <h3 className="font-bold text-lg text-slate-800">{ad.name}</h3>
                                    <span className={clsx("px-2 py-0.5 rounded text-xs font-bold uppercase", ad.isActive ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700")}>
                                        {ad.isActive ? 'Active' : 'Inactive'}
                                    </span>
                                </div>
                                <p className="text-sm text-slate-400 truncate max-w-xs">{ad.targetUrl}</p>
                                <div className="flex items-center justify-center md:justify-start gap-4 mt-3 text-sm font-medium text-slate-600">
                                    <span className="flex items-center gap-1"><BarChart2 size={14} /> {ad.views} Views</span>
                                    <span>{ad.clicks} Clicks</span>
                                    <span className="text-brand-purple">CTR: {((ad.clicks / (ad.views || 1)) * 100).toFixed(1)}%</span>
                                </div>
                            </div>

                            <div className="flex gap-2">
                                <button onClick={() => deleteAd(ad.id)} className="p-2 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-lg transition">
                                    <Trash2 size={20} />
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
