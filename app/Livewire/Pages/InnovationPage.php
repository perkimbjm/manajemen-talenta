<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Assessment;
use App\Models\Innovation;
use App\Models\Occupation;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use WireUi\Attributes\Mount;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Computed;
use App\Models\CompetencyStandard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

#[Layout('components.layouts.authenticated')]
class InnovationPage extends Component
{
  use WithPagination;

  #[Url(), Session('nip')]
  public ?string $nip = null;

  #[Computed()]
  public function employee()
  {
    $user = request()->user();
    if ($user?->hasExactRoles('Pegawai')) {
      return Employee::with(['performance', 'unit'])->where('user_id', $user?->id)->first();
    }

    if ($this->nip) {
      return Employee::with(['performance', 'unit'])->where('nip', $this->nip)->first();
    }
  }

  public function changeStatus(Innovation $innovation, int $status, ?string $description = null)
  {
    try {
      Validator::validate(['status' => $status], [
        'status' => ['required', 'integer', 'min:0', 'max:2'],
      ]);

      $innovation->update([
        'status' => $status,
        'description' => $description,
      ]);

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Berhasil mengubah status inovasi',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal mengubah status inovasi',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function updateAssessmentInnovation()
  {
    $innovation = Innovation::query()
      ->where('nip', $this->nip)
      ->where('year', session('year', date("y")))
      ->where('status', 2)
      ->orderByDesc('value')
      ->first();

    Assessment::updateOrCreate([
      'nip' => $innovation->nip,
      'year' => $innovation->year,
    ], [
      'innovation' => $innovation->computed_value,
    ]);
  }

  public function render()
  {
    $user = request()->user();
    $innovations = Innovation::where('nip', $this->employee?->nip)->orderByDesc('value')->paginate(15);
    return view('livewire.pages.innovation-page', compact('innovations', 'user'));
  }
}
