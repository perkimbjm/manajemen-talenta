<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Laravel\Prompts\spin;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Permission::findOrCreate('Root Access');
    spin(message: 'Creating users...', callback: function () {
      $password = 'password123';

      // Create root user
      $root = User::updateOrCreate(
        ['email' => 'root@mata-asn.test'],
        [
          'name' => 'Root User',
          'password' => $password,
          'email_verified_at' => now(),
        ]
      );
      $root->givePermissionTo('Root Access');
      $root->assignRole('Super Admin');

      // Create super admin
      $superAdmin = User::updateOrCreate(
        ['email' => 'superadmin@mata-asn.test'],
        [
          'name' => 'Super Administrator',
          'password' => $password,
          'email_verified_at' => now(),
        ]
      );
      $superAdmin->assignRole('Super Admin');

      // Create BKPSDM admin
      $bkpsdmAdmin = User::updateOrCreate(
        ['email' => 'admin.bkpsdm@mata-asn.test'],
        [
          'name' => 'Admin BKPSDM',
          'password' => $password,
          'email_verified_at' => now(),
        ]
      );
      $bkpsdmAdmin->assignRole('Admin SKPD');

      // Create some unit admins
      $units = [
        'SETDA' => 'Sekretariat Daerah',
        'DINKES' => 'Dinas Kesehatan',
        'DISDIK' => 'Dinas Pendidikan',
      ];

      foreach ($units as $acronym => $name) {
        $unitAdmin = User::updateOrCreate(
          ['email' => strtolower("admin.$acronym@mata-asn.test")],
          [
            'name' => "Admin $name",
            'password' => $password,
            'email_verified_at' => now(),
          ]
        );
        $unitAdmin->assignRole('Admin SKPD');
      }

      // Create some regular employees
      $employees = [
        ['name' => 'Ahmad Pegawai', 'unit' => 'SETDA'],
        ['name' => 'Budi Pegawai', 'unit' => 'DINKES'],
        ['name' => 'Cindy Pegawai', 'unit' => 'DISDIK'],
      ];

      foreach ($employees as $employee) {
        $email = strtolower(str_replace(' ', '.', $employee['name']) . '@mata-asn.test');
        $user = User::updateOrCreate(
          ['email' => $email],
          [
            'name' => $employee['name'],
            'password' => $password,
            'email_verified_at' => now(),
          ]
        );
        $user->assignRole('Pegawai');
      }
    });
  }
}
