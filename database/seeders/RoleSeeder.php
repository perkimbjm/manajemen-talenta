<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use function Laravel\Prompts\progress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $roles = [
      'Super Admin',
      'Admin SKPD',
      'Pegawai',
    ];

    // Create base permissions
    $permissions = [
      'view dashboard',
      'manage users',
      'manage roles',
      'manage units',
      'manage employees',
      'manage positions',
      'manage assessments',
      'view reports',
      'export reports',
      'manage settings',
      'Root Access'
    ];

    foreach ($permissions as $permission) {
      Permission::findOrCreate($permission);
    }

    progress(
      label: "Creating roles with permissions",
      steps: $roles,
      callback: function ($roleName) {
        $role = Role::updateOrCreate(['name' => $roleName]);
        
        // Assign permissions based on role
        switch ($roleName) {
          case 'Super Admin':
            $role->givePermissionTo(Permission::all());
            break;
          case 'Admin SKPD':
            $role->givePermissionTo([
              'view dashboard',
              'manage users',
              'manage units',
              'manage employees',
              'manage positions',
              'manage assessments',
              'view reports',
              'export reports',
            ]);
            break;
          case 'Pegawai':
            $role->givePermissionTo([
              'view dashboard',
              'view reports',
            ]);
            break;
        }
      }
    );
  }
}
