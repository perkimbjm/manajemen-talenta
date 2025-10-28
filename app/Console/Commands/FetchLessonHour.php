<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\progress;

class FetchLessonHour extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fetch:lesson-hour {nip?}';

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

    DB::transaction(fn() => $this->syncData($employees));
  }

  public function syncData($employees)
  {
    progress(
      label: 'Fetching punishment value',
      steps: $employees,
      callback: function ($employee) {
        foreach ([2022, 2023, 2024] as $year) {
          $data = $this->getDataFromSimASN($employee, $year, $year);
          if (!$data) return;
          if (@$data['error']) {
            return;
          }
          $employee->lesson_hours()->updateOrCreate([
            'year' => $year,
          ], [
            'total_hours' => $data['total_jp'],
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

  public function getDataFromSimASN(Employee $employee, $start_year, $end_year)
  {
    $api_url = "https://app.banjarmasinkota.go.id/talent/assesment/kursus/{$employee->encrypted_nip}/{$start_year}/{$end_year}";

    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'][0];
  }
}
