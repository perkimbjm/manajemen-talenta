<?php

namespace App\Livewire;

use App\Models\Employee;
use Livewire\Component;

class PotentialPreferenceForm extends Component
{
  public Employee $employee;

  public float $value = 0;

  public function mount()
  {
    if (@$this->employee->assessment->potential_preference) {
      $this->value = $this->employee->assessment->potential_preference;
    }
  }

  public function render()
  {
    return view('livewire.potential-preference-form');
  }

  public function submit()
  {
    $this->validate([
      'value' => 'numeric|min:0|max:10',
    ]);

    try {
      $this->employee->assessments()->updateOrCreate([
        'year' => session('year', date('Y')),
      ], [
        'potential_preference' => $this->value,
      ]);

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Berhasil mengupdate nilai preferensi kinerja',
      ]);

      $this->dispatch('page-refresh');
      $this->dispatch('close-modal');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal mengupdate nilai preferensi kinerja',
        'description' => $th->getMessage(),
      ]);
    }
  }
}
