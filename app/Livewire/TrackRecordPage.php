<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Computed;

#[Layout('components.layouts.authenticated')]
class TrackRecordPage extends Component
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

  public function render()
  {
    return view('livewire.track-record-page');
  }
}
