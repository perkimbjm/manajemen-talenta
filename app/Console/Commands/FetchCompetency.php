<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\Assessment;
use App\Models\Competency;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

use function Laravel\Prompts\spin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\progress;

class FetchCompetency extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fetch:competency {nip?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Fetch competency value from SIM-ASN';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $nip = $this->argument('nip');
    $employees = Employee::query()
      ->with('assessment')
      ->when($nip, fn($q) => $q->where('nip', $nip))
      ->select(['id', 'nip'])
      ->selectRaw("MD5(nip+'$') as encrypted_nip")
      ->get();

    progress(
      label: 'Fetching competency value',
      steps: $employees,
      callback: function ($employee) {
        if (!$employee->assessment) {
          return;
        }

        $data = $this->getDataFromSimASN($employee);
        if (!$data) return;
        if (@$data['error']) {
          return;
        }

        foreach ($data as $penilaian) {
          $nilai_skj = (float) $penilaian['nilai_skj'];
          Competency::updateOrCreate([
            'nip' => $employee->nip,
            'register_number' => $employee->assessment->register_number,
            'code' => $penilaian['kd_skj'],
          ], [
            'label' => $penilaian['nm_skj'],
            'value' => $nilai_skj
          ]);
        }
      }
    );
  }

  private function getDataFromSimASN(Employee $employee)
  {
    $encrypted_register_number = md5($employee->assessment->register_number);
    $api_url = "https://app.banjarmasinkota.go.id/ci4-bkd/assesment/th3/{$employee->encrypted_nip}/{$encrypted_register_number}";

    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'];
  }
}
