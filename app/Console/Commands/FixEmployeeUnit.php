<?php

namespace App\Console\Commands;

use App\Models\Unit;
use App\Models\Mapping;
use App\Models\Employee;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\progress;

class FixEmployeeUnit extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fix:employee-unit';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Fix employees unit';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $units = Unit::all();
    $employees = $this->getEmployees();
    $mappings = Mapping::where('group', 'SKPD SIM-ASN')->get();

    DB::transaction(function () use ($employees, $units, $mappings) {
      progress(
        label: 'Fixing employees unit',
        steps: $employees,
        callback: function ($employee) use ($units, $mappings) {
          $this->fixEmployeeUnit($employee, $units, $mappings);
        }
      );
    });
  }

  public function fixEmployeeUnit($employee, $units, $mappings)
  {
    $data = $this->getDataFromSimASN($employee);

    if (!$data) {
      return;
    }

    if (in_array($data['kd_skpd'], ['IDK0000', 'IDK9999'])) {
      return;
    }

    $mapping = $mappings->where('prev_id', $data['kd_skpd'])->first();
    $unit = $units->where('code', $mapping?->current_id)->first();
    if (!$unit) {
      dd(compact('mapping', 'data', 'employee'));
    }

    $employee->unit_code = $unit->code;
    $employee->save();
  }

  public function getEmployees(?array $nips = null)
  {
    $query = Employee::query();
    $query->select('*');
    $query->selectRaw("MD5(nip+'$') as encrypted_nip");

    if (is_array($nips)) {
      $query->whereIn('nip', $nips);
    }

    return $query->get();
  }


  private function getDataFromSimASN(Employee $employee)
  {
    $api_url = "https://app.banjarmasinkota.go.id/talent/assesment/pro/{$employee->encrypted_nip}";

    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'][0];
  }
}
