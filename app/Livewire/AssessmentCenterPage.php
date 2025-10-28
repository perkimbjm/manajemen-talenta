<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Assessment;
use App\Models\Competency;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Session;

use function Illuminate\Support\defer;

#[Layout('components.layouts.authenticated')]
class AssessmentCenterPage extends Component
{
  #[Url(), Session('nip')]
  public ?string $nip = null;

  #[Computed()]
  public function employee()
  {
    $user = request()->user();

    $employee = null;
    if ($user->hasExactRoles('Pegawai')) {
      $employee = Employee::where('user_id', $user->id);
    }

    if ($this->nip) {
      $employee = Employee::where('nip', $this->nip);
    }

    return $employee?->with([
      'performance',
      'unit',
      'innovations' => fn($q) => $q->where('status', 2)->orderByDesc('value'),
      'organizations' => fn($q) => $q->where('status', 2)->orderByDesc('value'),
      'supportingTasks' => fn($q) => $q->where('status', 2)->orderByDesc('value'),
    ])->first();
  }

  // public function mount()
  // {
  //   if ($this->nip && session('nip') != $this->nip) {
  //     session()->put('nip', $this->nip);
  //   } else if (!$this->nip) {
  //     $this->nip = session('nip');
  //   }
  // }

  public function render()
  {
    return view('livewire.assessment-center-page');
  }

  public function updateAssessment()
  {
    if (!$this->employee) return;
    try {
      $employee = Employee::where('nip', $this->employee->nip)->select(['id', 'nip', 'position_name', 'echelon_code'])->selectRaw("MD5(nip+'$') as encrypted_nip")->first();

      $this->syncData($employee);
      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Berhasil menyingkronkan data',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal menyingkronkan data',
        'description' => $th->getMessage(),
      ]);
    }
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
    $data = $this->getAssessmentFromSIMASN($employee);
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

  private function getAssessmentFromSIMASN(Employee $employee)
  {
    $api_url = "https://app.banjarmasinkota.go.id/talent/assesment/0ne/{$employee->encrypted_nip}";

    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'][0];
  }

  private function getCompetenciesFromSimASN(Employee $employee, Assessment $assessment)
  {
    $encrypted_register_number = md5($assessment->register_number);
    $api_url = "https://app.banjarmasinkota.go.id/ci4-bkd/assesment/th3/{$employee->encrypted_nip}/{$encrypted_register_number}";

    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'];
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
        'register_number' => $penilaian['no_register'],
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
