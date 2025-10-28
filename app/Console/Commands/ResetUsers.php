<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\progress;

class ResetUsers extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'reset:users';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $employees = Employee::all();
    $role = Role::findOrCreate('Pegawai');
    $view_profile = Permission::findOrCreate('Lihat Profil');
    $view_assessment = Permission::findOrCreate('Lihat Penilaian');

    $role->givePermissionTo($view_profile, $view_assessment);

    progress(
      label: 'Resetting users [employee]',
      steps: $employees,
      callback: function ($employee) {
        $user = User::updateOrCreate([
          'name' => $employee->nip,
        ], [
          'password' => Hash::make($employee->nip),
        ]);

        $employee->user()->associate($user);
        $employee->save();

        $user->syncRoles(['Pegawai']);
      }
    );
  }
}
