<?php
// api/db.php

// Hostinger Credentials (Parsed from your .env)
$host = 'srv1007.hstgr.io';
$db   = 'u652263477_grammar_school';
$user = 'u652263477_school_teacher';
$pass = 'nUSewY3$';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In production, log this instead of showing it
    http_response_code(500);
    echo json_encode(['error' => 'Database Connection Failed']);
    exit;
}
?>
