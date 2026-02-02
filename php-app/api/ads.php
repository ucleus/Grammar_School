<?php
// api/ads.php
header('Content-Type: application/json');

require_once 'db.php';

// Mock Data (Fallback)
$mockAds = [
    [
        'id' => 'mock-1',
        'name' => 'Grammar Book Promo',
        'imageUrl' => 'https://placehold.co/600x150/4ECDC4/white?text=Master+English+Grammar',
        'targetUrl' => '#',
        'targetLanguage' => 'all'
    ],
    [
        'id' => 'mock-2',
        'name' => 'Vocabulary App (Vietnam)',
        'imageUrl' => 'https://placehold.co/600x150/FF6B6B/white?text=Học+Từ+Vựng+Mới',
        'targetUrl' => '#',
        'targetLanguage' => 'vi'
    ],
    [
        'id' => 'mock-3',
        'name' => 'Spanish Course',
        'imageUrl' => 'https://placehold.co/600x150/FFE66D/black?text=Curso+de+Inglés',
        'targetUrl' => '#',
        'targetLanguage' => 'es'
    ]
];

$lang = $_GET['lang'] ?? 'all';

try {
    // Query DB for active ads targeting this language (or global)
    $stmt = $pdo->prepare("
        SELECT id, name, imageUrl, targetUrl 
        FROM AdCampaign 
        WHERE isActive = 1 
        AND (targetLanguage IS NULL OR targetLanguage = 'all' OR targetLanguage = ?)
    ");
    $stmt->execute([$lang]);
    $ads = $stmt->fetchAll();

    if (empty($ads)) {
        // Filter mock ads
        $ads = array_filter($mockAds, function($ad) use ($lang) {
            return $ad['targetLanguage'] === 'all' || $ad['targetLanguage'] === $lang;
        });
        // Re-index array
        $ads = array_values($ads);
    }

    if (empty($ads)) {
        echo json_encode(null);
        exit;
    }

    // Pick random ad
    $randomAd = $ads[array_rand($ads)];
    
    // (Optional) Record view
    // $pdo->prepare("UPDATE AdCampaign SET views = views + 1 WHERE id = ?")->execute([$randomAd['id']]);

    echo json_encode($randomAd);

} catch (Exception $e) {
    echo json_encode(['error' => 'Server Error']);
}
?>
