<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\PerformanceManagement;
use Illuminate\Support\Collection;
use Livewire\Component;

class PerformanceSpecificForm extends Component
{
  public string $nip;
  public float $value = 0;
  public Collection $evaluations;

  public function submit()
  {
    $this->validate([
      'value' => 'required|numeric',
    ]);

    try {
      Assessment::updateOrCreate([
        'nip' => $this->nip,
        'year' => session('year', date('Y')),
      ], [
        'specific' => $this->value,
      ]);

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Nilai kinerja diri berhasil ditambahkan',
      ]);
      $this->dispatch('close-modal');
      $this->dispatch('page-refresh');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Nilai kinerja diri gagal ditambahkan',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function mount()
  {
    $this->evaluations = Assessment::getSpecificLabels();
    $assessment = Assessment::where('nip', $this->nip)->first();
    if ($assessment) {
      $this->value = $assessment->specific ?? 0;
    }
  }

  public function render()
  {
    return view('livewire.performance-specific-form');
  }
}
