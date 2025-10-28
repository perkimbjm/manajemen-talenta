<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Assessment;
use App\Models\Occupation;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use WireUi\Attributes\Mount;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Computed;
use App\Models\CompetencyStandard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

#[Layout('components.layouts.authenticated')]
class AsnPotensialPage extends Component
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
      'unit',
    ])->first();
  }

  public function syncAssessment()
  {
    if (!$this->employee) return;

    try {
      $employee = Employee::select(['id', 'nip', 'position_name', 'echelon_code'])->selectRaw("MD5(nip+'$') as encrypted_nip")->where('nip', $this->employee->nip)->first();
      $data = $this->getDataFromSimASN($employee);
      if (!$data) throw new \Exception("Belum ada penilaian dari SIM-ASN", 1);

      $nilai_potensi = (float) $data['nilai_potensi'];
      $nilai_kompetensi = (float) $data['nilai_kompetensi'];

      $assessment = Assessment::updateOrCreate(
        [
          'nip' => $this->employee->nip,
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

      $assessment->syncPotentialValue();
      $assessment->update([
        'box_id' => @$assessment->box['id']
      ]);

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Berhasil sinkronisasi data',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal sinkronisasi data',
        'description' => $th->getMessage(),
      ]);
    }
  }

  private function getDataFromSimASN(Employee $employee)
  {
    $api_url = "https://app.banjarmasinkota.go.id/talent/assesment/0ne/{$employee->encrypted_nip}";

    $response = Http::withOptions([
      'verify' => false,
    ])->get($api_url)->json();

    return @$response['mydata'][0];
  }

  public function render()
  {
    return view('livewire.pages.asn-potensial-page');
  }
}
