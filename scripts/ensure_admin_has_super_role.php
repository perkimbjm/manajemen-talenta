<?php
// Usage: php ensure_admin_has_super_role.php [identifier]
$identifier = $argv[1] ?? 'admin';
$dbPath = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbPath)) { echo "Database not found: $dbPath\n"; exit(1); }
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function uuidv4() {
    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

try {
    // Find user by name/email/username
    $cols = $pdo->query("PRAGMA table_info('users')")->fetchAll(PDO::FETCH_ASSOC);
    $colNames = array_column($cols, 'name');
    $searchCols = [];
    if (in_array('username', $colNames)) $searchCols[] = 'username';
    if (in_array('email', $colNames)) $searchCols[] = 'email';
    if (in_array('name', $colNames)) $searchCols[] = 'name';
    if (empty($searchCols)) { echo "No identifier column in users\n"; exit(1); }
    $whereParts = array_map(function($c){return "$c = :id";}, $searchCols);
    $stmt = $pdo->prepare("SELECT id FROM users WHERE (" . implode(' OR ', $whereParts) . ") LIMIT 1");
    $stmt->execute([':id' => $identifier]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) { echo "User not found: $identifier\n"; exit(1); }
    $userId = $user['id'];
    echo "Found user id=$userId\n";

    // Ensure role 'super-admin' exists
    $roleName = 'super-admin';
    $res = $pdo->prepare('SELECT id FROM roles WHERE name = :name AND guard_name = :guard LIMIT 1');
    $res->execute([':name'=>$roleName, ':guard'=>'web']);
    $role = $res->fetch(PDO::FETCH_ASSOC);
    if ($role) {
        $roleId = $role['id'];
        echo "Role exists: $roleName (id=$roleId)\n";
    } else {
        $roleId = uuidv4();
        $now = date('Y-m-d H:i:s');
        $ins = $pdo->prepare('INSERT INTO roles (id, name, guard_name, created_at, updated_at) VALUES (:id, :name, :guard, :c, :u)');
        $ins->execute([':id'=>$roleId, ':name'=>$roleName, ':guard'=>'web', ':c'=>$now, ':u'=>$now]);
        echo "Inserted role $roleName (id=$roleId)\n";
    }

    // Assign all permissions to role
    $perms = $pdo->query('SELECT id, name FROM permissions')->fetchAll(PDO::FETCH_ASSOC);
    if (!$perms) { echo "No permissions found to assign.\n"; }
    $count=0;
    foreach ($perms as $p) {
        // check existing mapping
        $chk = $pdo->prepare('SELECT 1 FROM role_has_permissions WHERE permission_id = :pid AND role_id = :rid LIMIT 1');
        $chk->execute([':pid'=>$p['id'], ':rid'=>$roleId]);
        if (!$chk->fetch()) {
            $ins = $pdo->prepare('INSERT INTO role_has_permissions (permission_id, role_id) VALUES (:pid, :rid)');
            $ins->execute([':pid'=>$p['id'], ':rid'=>$roleId]);
            $count++;
            echo "Assigned permission {$p['name']} to role $roleName\n";
        }
    }
    echo "Total new role_has_permissions inserted: $count\n";

    // Assign role to user in model_has_roles
    // Determine model_type stored in model_has_permissions (existing) or default App\\Models\\User
    $example = $pdo->query("SELECT model_type FROM model_has_permissions LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $modelType = $example['model_type'] ?? 'App\\Models\\User';
    // check existing mapping
    $chk = $pdo->prepare('SELECT 1 FROM model_has_roles WHERE role_id = :rid AND model_type = :mt AND model_id = :mid LIMIT 1');
    $chk->execute([':rid'=>$roleId, ':mt'=>$modelType, ':mid'=>$userId]);
    if ($chk->fetch()) {
        echo "User already has role $roleName\n";
    } else {
        $ins = $pdo->prepare('INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES (:rid, :mt, :mid)');
        $ins->execute([':rid'=>$roleId, ':mt'=>$modelType, ':mid'=>$userId]);
        echo "Assigned role $roleName to user id=$userId\n";
    }

    echo "Done.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
