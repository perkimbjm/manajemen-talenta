<?php
$dbPath = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbPath)) { echo "Database not found: $dbPath\n"; exit(1); }
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$res = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
if ($res->fetch() === false) {
    echo "No 'users' table found.\n";
    exit(1);
}
$cols = $pdo->query("PRAGMA table_info('users')")->fetchAll(PDO::FETCH_ASSOC);
echo "Columns in users table:\n";
foreach ($cols as $c) {
    echo sprintf("%s\t%s\t%s\n", $c['cid'], $c['name'], $c['type']);
}

// Show first 5 rows to inspect possible identifier columns
echo "\nSample rows (up to 5):\n";
$rows = $pdo->query("SELECT * FROM users LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";
}
