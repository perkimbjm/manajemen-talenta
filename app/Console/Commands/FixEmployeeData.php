<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\Mapping;
use App\Models\Unit;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\info;
use function Laravel\Prompts\progress;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;

class FixEmployeeData extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fix:employee-data {nip?}';

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
    $nip = $this->argument('nip');
    $this->fixAllEmployees($nip);
  }

  private function fixEmployeeUnit()
  {
    $tpp_unit_mappings = Mapping::where('group', 'SKPD TPP')->get();
    $employees = Employee::whereNull('unit_code')->get();
    $list_pegawai = $this->getEmployeesFromTPP();

    if (!$list_pegawai) {
      return;
    }

    progress(
      label: 'Updating employees unit',
      steps: $employees,
      callback: function ($employee) use ($list_pegawai, $tpp_unit_mappings) {
        $pegawai = $list_pegawai->where('nip', $employee->nip)->first();
        if ($pegawai['skpd_id'] === null) return;
        $unit = $tpp_unit_mappings->where('prev_id', $pegawai['skpd_id'])->first();
        if ($unit) {
          $employee->unit_code = $unit?->current_id;
          $employee->save();
        } else {
          dd($pegawai);
        }
      }
    );
  }

  private function fixAllEmployees($nip = null)
  {
    $simasn_unit_mappings = Mapping::where('group', 'SKPD SIM-ASN')->get();
    // $tpp_unit_mappings = Mapping::where('group', 'SKPD TPP')->get();

    if ($nip) {
      $employee = Employee::query()
        ->select(['id', 'nip', 'unit_code'])
        ->selectRaw("MD5(nip+'$') as encrypted_nip")
        ->where('nip', $nip)
        ->first();
      $this->fixMissingData([$employee], $simasn_unit_mappings);
      return;
    }

    $missings = collect([]);
    foreach (range(0, 32) as $i) {
      $skip = $i * 100;
      info("Skipping $skip take 100");
      $employees = Employee::select(['id', 'nip', 'unit_code'])
        ->selectRaw("MD5(nip+'$') as encrypted_nip")
        ->skip($i * 100)
        ->take(100)
        ->get();
      if ($employees->isEmpty()) break;
      $missings->concat($this->fixMissingData($employees, $simasn_unit_mappings));
    }


    if ($missings->isNotEmpty()) {
      warning('Missings x' . $missings->count());
    }
  }

  private function fixMissingData($employees, $simasn_unit_mappings)
  {
    $missings = collect([]);
    progress(
      label: 'Fixing employee data',
      steps: $employees,
      callback: function ($employee) use ($simasn_unit_mappings, $missings) {
        $data = $this->getDataFromSimASN($employee);
        if (!$data) return;

        $unit_mapping = $simasn_unit_mappings->where('prev_id', $data['kd_skpd'])->first();
        if (!$unit_mapping) {
          $missings->push($data);
          if (!$employee->unit_code && !in_array($data['kd_skpd'], ['IDK0000', 'IDK9999'])) {
            warning("{$data['kd_skpd']}, {$employee->nip}");
          }
        }

        $employee->update([
          'name' => $data['nm_pegawai'],
          'position_type' => $data['nm_jnsjab'] ?? '-',
          'position_name' => $data['ket_jabatan'] ?? '-',
          'front_title' => $data['glr_dpn'],
          'back_title' => $data['glr_blk'],
          'echelon' => $data['nm_eselon'],
          'order' => $data['gol_pangkat'],
          'rank' => $data['nm_pangkat'],
          'rank_code' => $data['kd_pangkat'],
          'echelon_code' => $data['kd_eselon'],
          'education_level' => $data['kd_pendidikan'] ? (int) $data['kd_pendidikan'] : null,
          'education_name' => $data['nm_pendidikan'],
          'work_unit' => $data['nm_unitkerja'],
          'unit_code' => $unit_mapping?->current_id,
        ]);
      }
    );

    return $missings;
  }

  private function getDataFromSimASN(Employee $employee)
  {
    $api_url = "https://app.banjarmasinkota.go.id/talent/assesment/pro/{$employee->encrypted_nip}";

    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'][0];
  }

  private function getEmployeesFromTPP()
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

    return collect($response['data']);
  }
}
