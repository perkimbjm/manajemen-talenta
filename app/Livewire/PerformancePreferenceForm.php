<?php

namespace App\Livewire;

use App\Models\Employee;
use Livewire\Component;

class PerformancePreferenceForm extends Component
{
  public Employee $employee;

  public float $value = 0;

  public function mount()
  {
    if (@$this->employee->assessment->performance_preference) {
      $this->value = $this->employee->assessment->performance_preference;
    }
  }

  public function render()
  {
    return view('livewire.performance-preference-form');
  }

  public function submit()
  {
    $this->validate([
      'value' => 'numeric|min:0|max:10',
    ]);

    try {
      $assessment = $this->employee->assessments()->updateOrCreate([
        'year' => session('year', date('Y')),
      ], [
        'performance_preference' => $this->value,
      ]);

      $assessment->update([
        'performance_value' => $assessment->get_performance_value,
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
