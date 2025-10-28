<?php
// Boot the Laravel app and ensure admin has given permissions/role via package methods
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

try {
    $user = User::where('name','admin')->first();
    if (! $user) { echo "User admin not found\n"; exit(1); }
    echo "Found user: {$user->id}\n";
    // Give all permissions to user via package method
    $perms = Spatie\Permission\Models\Permission::pluck('name')->all();
    foreach ($perms as $p) {
        if (! $user->hasPermissionTo($p)) {
            $user->givePermissionTo($p);
            echo "Gave permission: $p\n";
        }
    }
    // Assign role 'Super Admin' via package method (match role name used in seeders/Menu)
    if (! $user->hasRole('Super Admin')) {
        $user->assignRole('Super Admin');
        echo "Assigned role Super Admin\n";
    }
    echo "Done\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
