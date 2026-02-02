import { NextResponse } from 'next/server';
import { PrismaClient } from '@prisma/client';

// Initialize Prisma (Singleton pattern recommended for Next.js, simplified here)
const prisma = new PrismaClient();

// Fallback Mock Data (For when DB is not connected)
const MOCK_ADS = [
    {
        id: 'mock-1',
        name: 'Grammar Book Promo',
        imageUrl: 'https://placehold.co/600x150/4ECDC4/white?text=Master+English+Grammar',
        targetUrl: '#',
        targetLanguage: 'all'
    },
    {
        id: 'mock-2',
        name: 'Vocabulary App (Vietnam)',
        imageUrl: 'https://placehold.co/600x150/FF6B6B/white?text=Học+Từ+Vựng+Mới',
        targetUrl: '#',
        targetLanguage: 'vi'
    },
    {
        id: 'mock-3',
        name: 'Spanish Course',
        imageUrl: 'https://placehold.co/600x150/FFE66D/black?text=Curso+de+Inglés',
        targetUrl: '#',
        targetLanguage: 'es'
    }
];

export async function GET(req: Request) {
    try {
        const { searchParams } = new URL(req.url);
        const lang = searchParams.get('lang'); // 'vi' or 'es'

        // Try to fetch from DB
        let ads;
        try {
            ads = await prisma.adCampaign.findMany({
                where: {
                    isActive: true,
                    OR: [
                        { targetLanguage: null },      // Global ads
                        { targetLanguage: lang },      // Targeted ads
                        { targetLanguage: 'all' }
                    ]
                }
            });
        } catch (dbError) {
            console.warn("Database unavailable, using mock ads:", dbError);
            ads = null;
        }

        // Use Mock data if DB failed or returned nothing (for testing)
        if (!ads || ads.length === 0) {
            ads = MOCK_ADS.filter(ad =>
                !lang || ad.targetLanguage === 'all' || ad.targetLanguage === lang
            );
        }

        // Pick Random Ad
        if (ads.length === 0) {
            return NextResponse.json(null); // No ads available
        }

        const randomAd = ads[Math.floor(Math.random() * ads.length)];

        // (Optional) Record View - Fire and forget
        // if (prisma) prisma.adCampaign.update({ where: { id: randomAd.id }, data: { views: { increment: 1 } } });

        return NextResponse.json(randomAd);

    } catch (error) {
        console.error("Ad API Error:", error);
        return NextResponse.json({ error: "Internal Server Error" }, { status: 500 });
    }
}
