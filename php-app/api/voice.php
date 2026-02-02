<?php
// api/voice.php

// 1. Configuration (Move to env file in real production)
$ELEVENLABS_API_KEY = getenv('ELEVENLABS_API_KEY') ?: ''; // Set this in Hostinger env vars or hardcode VERY carefully (not recommended)

// Voice Map
$VOICE_MAP = [
    'vi_north' => 'SOYHLrjzK2X1ezoPC6cr',
    'vi_south' => 'SOYHLrjzK2X1ezoPC6cr',
    'es_medellin' => 'ErXwobaYiN019PkySvjV',
    'es_cartagena' => 'ErXwobaYiN019PkySvjV',
    'es_bogota' => 'ErXwobaYiN019PkySvjV',
    'en_academic' => 'QNrp0IeZPxWIMP426W4P',
    'en_slang' => 'QNrp0IeZPxWIMP426W4P'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$text = $input['text'] ?? '';
$dialect = $input['dialect'] ?? 'en_academic';
$type = $input['type'] ?? 'native';

if (!$text) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing text']);
    exit;
}

// Determine Voice ID
$voiceId = 'QNrp0IeZPxWIMP426W4P'; // Default
if ($type === 'english') {
    $voiceId = (strpos($dialect, 'slang') !== false) ? $VOICE_MAP['en_slang'] : $VOICE_MAP['en_academic'];
} else {
    $voiceId = $VOICE_MAP[$dialect] ?? $VOICE_MAP['es_medellin'];
}

// Call ElevenLabs
$url = "https://api.elevenlabs.io/v1/text-to-speech/$voiceId";
$data = [
    "text" => $text,
    "model_id" => "eleven_multilingual_v2",
    "voice_settings" => [
        "stability" => 0.5,
        "similarity_boost" => 0.75
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: audio/mpeg',
    'Content-Type: application/json',
    'xi-api-key: ' . $ELEVENLABS_API_KEY
]);

// IMPORTANT: Fix for Shared Hosting SSL issues
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(500);
    // Return detailed error for debugging (remove in strict production)
    echo json_encode(['error' => 'ElevenLabs Error (' . $httpCode . ')', 'details' => $response, 'curl_error' => $curlError]);
    exit;
}

// Stream Audio Back
header('Content-Type: audio/mpeg');
header('Content-Length: ' . strlen($response));
echo $response;
?>
