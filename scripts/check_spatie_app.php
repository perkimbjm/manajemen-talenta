<?php
// Boot the Laravel app and check Spatie permissions/roles via Eloquent
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use App\Models\User;

try {
    $perm = Permission::where('name','Root Access')->first();
    echo 'perm:' . ($perm ? $perm->id : 'not found') . ' guard:' . ($perm ? $perm->guard_name : '') . PHP_EOL;
    $user = User::where('name','admin')->first();
    echo 'user:' . ($user ? $user->id : 'not found') . PHP_EOL;
    if ($user) {
        echo 'hasRole:' . ($user->hasRole('super-admin') ? 'true' : 'false') . PHP_EOL;
        echo 'hasPerm:' . ($user->hasPermissionTo('Root Access') ? 'true' : 'false') . PHP_EOL;
        echo 'getAllPermissions:' . json_encode($user->getAllPermissions()->pluck('name')->all()) . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
