<?php
// Usage: php scripts/update_admin_password.php <new-password> [username_or_email]
// This script computes a bcrypt hash and updates the 'password' column in the users table

if ($argc < 2) {
    echo "Usage: php scripts/update_admin_password.php <new-password> [username_or_email]\n";
    exit(1);
}

$password = $argv[1];
$identifier = $argv[2] ?? 'admin';

// Path to the Laravel sqlite database file (relative to repo root)
$dbPath = __DIR__ . '/../database/database.sqlite';

if (!file_exists($dbPath)) {
    echo "Database file not found: $dbPath\n";
    exit(1);
}

// Create bcrypt hash
$hash = password_hash($password, PASSWORD_BCRYPT);
if ($hash === false) {
    echo "Failed to hash password.\n";
    exit(1);
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the users table exists
    $res = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    if ($res->fetch() === false) {
        echo "No 'users' table found in database.\n";
        exit(1);
    }

    // Determine which identifier column(s) exist: prefer username, email, then name
    $cols = $pdo->query("PRAGMA table_info('users')")->fetchAll(PDO::FETCH_ASSOC);
    $colNames = array_column($cols, 'name');
    $searchCols = [];
    if (in_array('username', $colNames)) { $searchCols[] = 'username'; }
    if (in_array('email', $colNames)) { $searchCols[] = 'email'; }
    if (in_array('name', $colNames)) { $searchCols[] = 'name'; }

    if (empty($searchCols)) {
        echo "No suitable identifier column (username/email/name) found in users table.\n";
        exit(1);
    }

    // Build query to find user using available identifier columns
    $whereParts = array_map(function ($c) { return "$c = :id"; }, $searchCols);
    $whereSql = implode(' OR ', $whereParts);
    $selectCols = array_merge(['id'], $searchCols);
    $selectSql = implode(', ', $selectCols);

    $stmt = $pdo->prepare("SELECT $selectSql FROM users WHERE $whereSql LIMIT 1");
    $stmt->execute([':id' => $identifier]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "No user found matching identifier = $identifier (checked: " . implode(', ', $searchCols) . ").\n";
        exit(1);
    }

    // Update password by id
    $upd = $pdo->prepare("UPDATE users SET password = :hash WHERE id = :id");
    $upd->execute([':hash' => $hash, ':id' => $user['id']]);

    echo "Password updated for user id={$user['id']} (username={$user['username']}, email={$user['email']}).\n";
    echo "Stored hash prefix: " . substr($hash, 0, 4) . "... (should be \"$2y$\" or \"$2b$\").\n";
    exit(0);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
