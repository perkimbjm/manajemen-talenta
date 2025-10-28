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

class FetchAssessment extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fetch:assessment {nip?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Fetch assessment value from SIM-ASN';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    DB::transaction(function () {
      $nip = $this->argument('nip');
      $query = Employee::select(['id', 'nip', 'position_name', 'echelon_code'])->selectRaw("MD5(nip+'$') as encrypted_nip");
      if ($nip) {
        $query->where('nip', $nip);
      }

      // $query->where('unit_code', '5.03.5.04.0.00.02.0000'); // BKD

      $employees = $query->get();
      progress(
        label: 'Fetching assessment value',
        steps: $employees,
        callback: function ($employee) {
          $this->syncData($employee);
        }
      );
    });
  }

  private function syncData(Employee $employee)
  {
    $assessment = $this->fillData($employee);

    if (!$assessment) {
      return;
    }

    $competencies = $this->fillCompetencies($employee, $assessment);

    $upper_competency = null;
    if ($competencies) {
      $upper_competency = $assessment->calcUpperCompetency(
        $employee->echelon_code,
        $employee->position_name,
        $competencies
      );
    }

    $assessment->update(array_filter([
      'potential_value' => $assessment->get_potential_value,
      'upper_competency' => $upper_competency
    ]));
  }

  private function fillData($employee)
  {
    $data = $this->getDataFromSimASN($employee);
    if (!$data) return;
    $nilai_potensi = (float) $data['nilai_potensi'];
    $nilai_kompetensi = (float) $data['nilai_kompetensi'];

    $assessment = Assessment::updateOrCreate(
      [
        'nip' => $employee->nip,
        'year' => date('Y'),
      ],
      [
        'register_number' => $data['no_register'],
        'potential' => $nilai_potensi,
        'competency' => $nilai_kompetensi,
        'manajerial' => $data['nilai_manajer'],
        'sosialkultural' => $data['nilai_sosial'],
        'teknis' => $data['nilai_teknis'],
        'jpm' => $data['nilai_jpm'],
        'compatibility' => $data['nm_kesesuaian'],
        'recommendation' => $data['nm_rekomendasi'],
      ]
    );

    $assessment = Assessment::find($assessment->id);

    return $assessment;
  }

  private function getDataFromSimASN(Employee $employee)
  {
    try {
      $api_url = "https://app.banjarmasinkota.go.id/talent/assesment/0ne/{$employee->encrypted_nip}";

      $response = Http::timeout(60)->withOptions([
        'verify' => false,
      ])->get($api_url)->json();

      return @$response['mydata'][0];
    } catch (\Throwable $th) {
      return null;
    }
  }

  private function getCompetenciesFromSimASN(Employee $employee, Assessment $assessment)
  {
    try {
      $encrypted_register_number = md5($assessment->register_number);
      $api_url = "https://app.banjarmasinkota.go.id/ci4-bkd/assesment/th3/{$employee->encrypted_nip}/{$encrypted_register_number}";

      $response = Http::timeout(60)->withOptions([
        'verify' => false,
      ])->get($api_url)->json();

      return @$response['mydata'];
    } catch (\Throwable $th) {
      return null;
    }
  }

  private function fillCompetencies(Employee $employee, Assessment $assessment)
  {
    $data = $this->getCompetenciesFromSimASN($employee, $assessment);

    if (!$data) return collect([]);
    if (@$data['error']) {
      return collect([]);
    }

    $competencies = collect([]);

    foreach ($data as $penilaian) {
      $nilai_skj = (float) $penilaian['nilai_skj'];
      $competencies->push(Competency::updateOrCreate([
        'nip' => $employee->nip,
        'register_number' => $employee->assessment->register_number,
        'code' => $penilaian['kd_skj'],
      ], [
        'value' => $nilai_skj,
        'label' => $penilaian['nm_skj'],
        'skj' => $penilaian['skj'],
        'gap' => $penilaian['gap_skj'],
        'recommendation' => $penilaian['rekomendasi'],
        'description' => $penilaian['kesenjangan'],
        'manajerial' => $penilaian['kmanajerial'],
        'kultural' => $penilaian['kkultural'],
        'ket_manajerial' => $penilaian['ket_kmanajerial'],
        'ket_kultural' => $penilaian['ket_kkultural'],
      ]));
    }

    return $competencies;
  }
}
