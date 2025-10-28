<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\progress;

class FetchPositionHistory extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fetch:position-history {nip?}';

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
    $query = Employee::query();

    if ($nip) {
      $query->where('nip', $nip);
    }

    $employees = $query
      ->with('assessment')
      ->select(['id', 'nip'])
      ->selectRaw("MD5(nip+'$') as encrypted_nip")
      ->get();

    progress(
      label: 'Fetching position histories',
      steps: $employees,
      callback: function ($employee) {
        $dataset = $this->getDataFromSimASN($employee);
        if (!$dataset) return;
        if (@$dataset['error']) {
          return;
        }

        foreach ($dataset as $data) {
          $tmt_date = Carbon::createFromFormat('m-d-Y', $data['tmt_jabatan'])->format('Y-m-d');
          $decree_date = $data['tgl_sk'] ? Carbon::createFromFormat('m-d-Y', $data['tgl_sk'])->format('Y-m-d') : $tmt_date;
          $inauguration_date = $data['tmt_pelantikan'] ? Carbon::createFromFormat('m-d-Y', $data['tmt_pelantikan'])->format('Y-m-d') : $tmt_date;

          $employee->positions()->updateOrCreate([
            'decree_number' => $data['no_sk'],
          ], [
            'name' => $data['nm_jabatan'],
            'type' => $data['jenis_jabatan'] ?? '-',
            'echelon' => $data['nm_eselon'],
            'decree_date' => $decree_date,
            'tmt_date' => $tmt_date,
            'inauguration_date' => $inauguration_date,
          ]);
        }
      }
    );
  }

  private function getDataFromSimASN(Employee $employee)
  {
    $api_url = "https://app.banjarmasinkota.go.id/talent/assesment/hisjab/{$employee->encrypted_nip}";

    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'];
  }
}
