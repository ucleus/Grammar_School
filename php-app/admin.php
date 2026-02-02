<?php
// admin.php
require_once 'api/db.php';

// Handle Actions (Placeholder for now)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add ad logic would go here
}

// Fetch Ads
try {
    $stmt = $pdo->query("SELECT * FROM AdCampaign ORDER BY createdAt DESC");
    $ads = $stmt->fetchAll();
} catch (Exception $e) {
    $ads = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Grammar School</title>
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 min-h-screen p-8 font-sans">
    
    <div class="max-w-5xl mx-auto">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800">Ad Manager</h1>
                <p class="text-slate-500">Manage your campaigns (PHP Version)</p>
            </div>
            <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl hover:bg-slate-700 transition font-bold">
                + New Campaign
            </button>
        </header>

        <div class="space-y-4">
            <?php if (empty($ads)): ?>
                <div class="bg-white p-8 rounded-2xl text-center text-slate-500">
                    No active campaigns found in database.
                </div>
            <?php else: ?>
                <?php foreach ($ads as $ad): ?>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-6">
                        <img src="<?= htmlspecialchars($ad['imageUrl']) ?>" alt="Ad" class="w-full md:w-48 h-24 object-cover rounded-lg bg-slate-100">
                        
                        <div class="flex-1 text-center md:text-left">
                            <div class="flex items-center justify-center md:justify-start gap-3 mb-1">
                                <h3 class="font-bold text-lg text-slate-800"><?= htmlspecialchars($ad['name']) ?></h3>
                                <span class="px-2 py-0.5 rounded text-xs font-bold uppercase <?= $ad['isActive'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= $ad['isActive'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                            <p class="text-sm text-slate-400 truncate max-w-xs"><?= htmlspecialchars($ad['targetUrl']) ?></p>
                            <div class="flex items-center justify-center md:justify-start gap-4 mt-3 text-sm font-medium text-slate-600">
                                <span><?= $ad['views'] ?> Views</span>
                                <span><?= $ad['clicks'] ?> Clicks</span>
                                <span class="text-brand-purple">Target: <?= htmlspecialchars($ad['targetLanguage'] ?? 'All') ?></span>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button class="p-2 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-lg transition">
                                🗑
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
