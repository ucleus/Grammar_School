import { NextResponse } from 'next/server';

// Voice Mapping Configuration
// You will need to replace these with your actual ElevenLabs Voice IDs
const VOICE_MAP: Record<string, string> = {
    // Vietnamese Voices (Placeholders)
    'vi_north': 'SOYHLrjzK2X1ezoPC6cr', // Example ID
    'vi_south': 'SOYHLrjzK2X1ezoPC6cr', // Example ID

    // Spanish/Colombian Voices (Placeholders)
    'es_medellin': 'ErXwobaYiN019PkySvjV', // Antoni (using default for now)
    'es_cartagena': 'ErXwobaYiN019PkySvjV',
    'es_bogota': 'ErXwobaYiN019PkySvjV',

    // English Voices (for the English part)
    'en_academic': 'QNrp0IeZPxWIMP426W4P', // User Provided Voice
    'en_slang': 'QNrp0IeZPxWIMP426W4P',    // Using same voice for now (can be changed)
};

export async function POST(req: Request) {
    try {
        const { text, dialect, type } = await req.json();

        // 1. Determine Voice ID
        // If type is 'english', use the English voice map. 
        // If type is 'native', use the dialect map.
        let voiceId = '21m00Tcm4TlvDq8ikWAM'; // Default fallback

        if (type === 'english') {
            const isSlang = dialect.includes('slang'); // Simple heuristic
            voiceId = isSlang ? VOICE_MAP['en_slang'] : VOICE_MAP['en_academic'];
        } else {
            voiceId = VOICE_MAP[dialect] || VOICE_MAP['es_medellin'];
        }

        // 2. Initial Checks
        if (!process.env.ELEVENLABS_API_KEY) {
            return NextResponse.json({ error: "Missing API Key" }, { status: 500 });
        }

        // 3. Call ElevenLabs API
        const response = await fetch(
            `https://api.elevenlabs.io/v1/text-to-speech/${voiceId}`,
            {
                method: 'POST',
                headers: {
                    'Accept': 'audio/mpeg',
                    'Content-Type': 'application/json',
                    'xi-api-key': process.env.ELEVENLABS_API_KEY,
                },
                body: JSON.stringify({
                    text: text,
                    model_id: "eleven_multilingual_v2",
                    voice_settings: {
                        stability: 0.5,
                        similarity_boost: 0.75,
                    },
                }),
            }
        );

        if (!response.ok) {
            const errorData = await response.json();
            console.error("ElevenLabs Error:", errorData);
            return NextResponse.json({ error: "Failed to generate speech" }, { status: response.status });
        }

        // 4. Return Audio Stream
        // We return the audio buffer directly to the client
        const audioBuffer = await response.arrayBuffer();

        return new NextResponse(audioBuffer, {
            headers: {
                'Content-Type': 'audio/mpeg',
                'Content-Length': audioBuffer.byteLength.toString(),
            },
        });

    } catch (error) {
        console.error("API Error:", error);
        return NextResponse.json({ error: "Internal Server Error" }, { status: 500 });
    }
}
