<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Assessment;
use App\Models\TalentPoolBox;

class TalentPoolChart extends Component
{
  public function render()
  {
    $dataset = [];
    $assessments = Assessment::query()
      ->with('employee')
      ->orderByDesc('box_id')
      ->where('year', session('year', date('Y')))
      ->whereNotNull('box_id')
      ->get();

    foreach ($assessments as $assessment) {
      $performance_value  = $assessment->performance_value;
      $potential_value = $assessment->potential_value;
      $dataset[] = [
        'label' => "{$assessment->employee->name} ({$potential_value}, {$performance_value})",
        'name' => $assessment->employee->name,
        'nip' => $assessment->employee->nip,
        'y' => $performance_value,
        'x' => $potential_value,
      ];
    }

    $boxs = Assessment::getBoxs();

    return view('livewire.talent-pool-chart', compact('dataset', 'boxs'));
  }

  public function placeholder()
  {
    $boxs = TalentPoolBox::all();
    return view('livewire.talent-pool-chart-placeholder', compact('boxs'));
  }
}
