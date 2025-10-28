<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\progress;

class FetchDiklat extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fetch:diklat {nip?}';

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
      label: 'Fetching diklat value',
      steps: $employees,
      callback: function ($employee) {
        $dataset = $this->getDataFromSimASN($employee);
        if (!$dataset) return;
        if (@$dataset['error']) {
          return;
        }

        $ranks = [
          '1' => null,
          '2' => 4,
          '3' => 3,
          '4' => 2,
          '5' => 1,
        ];

        foreach ($dataset as $data) {
          $type = 'Pim';

          $employee->diklats()->updateOrCreate([
            'type' => $type,
            'year' => $data['tahun'],
            'code' => $data['latihanStrukturalId'],
          ], [
            'rank' => @$ranks[$data['latihanStrukturalId']],
            'name' => $data['latihanStrukturalNama'],
            'date' => $data['tanggal'],
            'letter_number' => $data['nomor'],
            'status' => 1,
          ]);
        }
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
    $api_url = "https://app.banjarmasinkota.go.id/talent/assesment/diklat/{$employee->encrypted_nip}";
    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'];
  }
}
