<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Occupation;
use Livewire\Attributes\Layout;
use App\Models\CompetencyStandard;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use WireUi\Attributes\Mount;

#[Layout('components.layouts.authenticated')]
class ProfileAsnPage extends Component
{
  #[Url(), Session('nip')]
  public ?string $nip = null;

  #[Url()]
  public bool $is_redirected = false;

  #[Computed()]
  public function employee()
  {
    $user = request()->user();

    $query = Employee::query();

    $query->with(['unit', 'assessment', 'educationLevel']);

    if ($user->hasExactRoles('Pegawai')) {
      $query->where('user_id', $user->id);
    }

    if ($this->nip) {
      $query->where('nip', $this->nip);
    }

    $employee = $query->first();

    return $employee;
  }

  public function mount()
  {
    if (request()->get('nip')) {
      $this->nip = request()->get('nip');
    }
  }

  public function syncAll()
  {
    if (!$this->employee) return;
    try {
      $this->employee->assessment?->syncAllValue();

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Sinkronisasi berhasil',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Sinkronisasi gagal',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    return view('livewire.profile-asn-page');
  }
}
