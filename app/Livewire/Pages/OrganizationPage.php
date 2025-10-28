<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Assessment;
use App\Models\Occupation;
use Livewire\Attributes\On;
use App\Models\Organization;
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
class OrganizationPage extends Component
{
  use WithPagination;

  #[Url(), Session('nip')]
  public ?string $nip = null;

  public ?User $user = null;

  #[Computed()]
  public function employee()
  {
    if ($this->user?->hasExactRoles('Pegawai')) {
      return Employee::with(['performance', 'unit'])->where('user_id', $this->user?->id)->first();
    }

    if ($this->nip) {
      return Employee::with(['performance', 'unit'])->where('nip', $this->nip)->first();
    }
  }

  public function mount()
  {
    $this->user = request()->user();
  }

  public function changeStatus(Organization $organization, int $status, ?string $description = null)
  {
    try {
      Validator::validate(['status' => $status], [
        'status' => ['required', 'integer', 'min:0', 'max:2'],
      ]);

      $organization->update([
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

  public function updateAssessmentOrganization()
  {
    $organization = Organization::query()
      ->where('nip', $this->nip)
      ->where('year', session('year', date('Y')))
      ->where('status', 2)
      ->orderByDesc('value')
      ->first();
    Assessment::updateOrCreate([
      'nip' => $organization->nip,
      'year' => $organization->year,
    ], [
      'organizational' => $organization->computed_value,
    ]);
  }


  public function render()
  {
    $organizations = Organization::where('nip', $this->employee?->nip)->orderByDesc('value')->paginate(15);
    return view('livewire.pages.organization-page', compact('organizations'));
  }
}
