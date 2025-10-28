<?php
$dbPath = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbPath)) { echo "Database not found: $dbPath\n"; exit(1); }
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$tables = ['permissions','roles','model_has_permissions','model_has_roles','role_has_permissions'];
foreach ($tables as $t) {
    echo "\n-- schema $t --\n";
    try {
        $cols = $pdo->query("PRAGMA table_info('$t')")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $c) {
            echo sprintf("%s\t%s\t%s\t%s\n", $c['cid'], $c['name'], $c['type'], $c['dflt_value']);
        }
    } catch (Exception $e) {
        echo "Error reading schema for $t: " . $e->getMessage() . "\n";
    }
}
