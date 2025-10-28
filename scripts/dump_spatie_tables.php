<?php
$dbPath = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbPath)) { echo "Database not found: $dbPath\n"; exit(1); }
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$tables = ['permissions','roles','model_has_permissions','model_has_roles','role_has_permissions'];
foreach ($tables as $t) {
    echo "\n-- $t --\n";
    try {
        $rows = $pdo->query("SELECT * FROM $t")->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) { echo "(empty)\n"; continue; }
        foreach ($rows as $r) {
            echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";
        }
    } catch (Exception $e) {
        echo "Error reading $t: " . $e->getMessage() . "\n";
    }
}
