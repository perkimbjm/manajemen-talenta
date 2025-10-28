<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\progress;

class FetchPunishment extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fetch:punishment {nip?}';

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

    $employees = $this->getEmployees($nip);

    progress(
      label: 'Fetching punishment value',
      steps: $employees,
      callback: function ($employee) {
        $data = $this->getDataFromSimASN($employee);
        if (!$data) return;
        if (@$data['error']) {
          return;
        }

        $employee->punishment()->updateOrCreate([
          'register_date' => $data['sk_tanggal'],
        ], [
          'end_date' => $data['tanggal_akhir_hukuman'],
          'year' => substr($data['tanggal_akhir_hukuman'], 0, 4),
        ]);
      }
    );
  }

  public function getEmployees($nip)
  {
    $query = Employee::select(['id', 'nip'])->selectRaw("MD5(nip+'$') as encrypted_nip");
    if ($nip) {
      $query->where('nip', $nip);
    }

    return $query->get();
  }

  public function getDataFromSimASN(Employee $employee)
  {
    $api_url = "https://app.banjarmasinkota.go.id/talent/assesment/hukdis/{$employee->encrypted_nip}";
    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'][0];
  }
}
