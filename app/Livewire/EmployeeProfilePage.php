<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Computed;

#[Layout('components.layouts.authenticated')]
class EmployeeProfilePage extends Component
{
  #[Url(), Session('nip')]
  public ?string $nip = null;

  #[Computed()]
  public function employee()
  {
    $user = request()->user();

    $query = Employee::with(['assessment', 'unit']);
    if ($user->hasExactRoles('Pegawai')) {
      $query->where('user_id', $user->id);
    } else {
      $query->where('nip', $this->nip);
    }

    $employee = $query->first();

    return $employee;
  }

  public function render()
  {
    return view('livewire.employee-profile-page');
  }
}
