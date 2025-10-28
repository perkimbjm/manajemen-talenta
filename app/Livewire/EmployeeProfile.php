<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;

class EmployeeProfile extends Component
{
  #[Reactive]
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

  public function render()
  {
    return view('livewire.employee-profile');
  }

  public function syncProfile()
  {
    try {
      if (!$this->employee) {
        throw new \Exception("Pegawai belum dipilih", 1);
      }

      $this->employee->syncProfileFromSimASN();
      $this->employee->refresh();

      $this->dispatch('page-refresh');
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
}
