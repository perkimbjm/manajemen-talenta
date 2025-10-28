<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use App\Models\Mapping;
use App\Models\Employee;
use App\Models\Assessment;

use Illuminate\Database\Seeder;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\warning;
use function Laravel\Prompts\progress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // $this->resetEmployees();
    $this->importEmployeesFromTPP();
  }

  private function importEmployeesFromTPP()
  {
    $response = null;

    spin(message: 'Fetching employees...', callback: function () use (&$response) {
      $api_url = 'https://tpp.banjarmasinkota.go.id/api/pegawai/';
      $response = Http::get($api_url)->json();
    });

    if (@$response['message_code'] !== 200) {
      warning($response['message']);
      return;
    }

    info("Total employees fetched: " . $response['jumlah']);

    $units = Unit::where('is_root', 1)->where('level', 1)->get();
    $unit_mappings = Mapping::where('group', 'SKPD TPP')->get();

    progress(
      label: 'Seeding employees',
      steps: $response['data'],
      callback: function ($pegawai) use ($units, $unit_mappings) {
        $user = User::updateOrCreate(
          ['name' => $pegawai['nip']],
          ['password' => Hash::make($pegawai['nip'])]
        );

        $user->syncRoles(['Pegawai']);

        $employee = Employee::updateOrCreate(
          ['nip' => $pegawai['nip']],
          [
            'name' => $pegawai['nama'],
            'user_id' => $user->id,
            'position_name' => @$pegawai['jabatan']['nama'] ?? '-',
            'position_type' => @$pegawai['jabatan']['jenis_jabatan'] ?? '-',
          ]
        );

        $unit_mapping = $unit_mappings->where('prev_id', $pegawai['skpd_id'])->first();
        if (!$unit_mapping) {
          $unit = $units->where('code', $employee->unit_code)->first();
          if ($unit) {
            Mapping::updateOrCreate([
              'group' => 'SKPD TPP',
              'prev_id' => $pegawai['skpd_id'],
            ], [
              'current_id' => $unit->code,
              'prev_name' => $unit->name,
              'current_name' => $unit->name,
            ]);
          }
        } else {
          $unit = $units->where('code', $unit_mapping->current_id)->first();
        }

        if (!$employee->unit_code) {
          $employee->update([
            'unit_code' => $unit?->code
          ]);
        }
      }
    );
  }

  private function resetEmployees()
  {
    spin(
      callback: function () {
        Assessment::truncate();
        Employee::whereNotNull('id')->delete();
      },
      message: 'Resetting employees & its assessments'
    );
  }

  private function importEmployeesFromSipejabat()
  {
    $list_pegawai = DB::table('pegawai')->where('id_skpd', 7)->get();
    $list_skpd = DB::table('skpd')->get();
    $units = DB::table('units')->get();

    progress(
      label: 'Inserting employees',
      steps: $list_pegawai,
      callback: function ($pegawai) use ($list_skpd, $units) {
        $skpd = $list_skpd->where('id', $pegawai->id_skpd)->first();
        $skpd_nama = str($skpd->nama)->replace(',', '')->squish()->toString();
        $unit = $units->filter(fn($unit) => str($unit->name)->replace(',', '')->squish()->contains($skpd_nama, true))->first();
        if (!$unit) {
          dd("Unit with ID SKPD {$skpd->id} {$skpd_nama} not found");
          return;
        }

        Employee::updateOrCreate(
          ['nip' => str($pegawai->nip)->squish()->replace(' ', '')],
          [
            'name' => $pegawai->nama,
            'unit_code' => $unit->code,
            'position_type' => '-',
            'position_name' => '-',
          ]
        );
      }
    );
  }
}
