<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Assessment;
use App\Models\SupportingTask;
use Illuminate\Support\Collection;

class SupportingTaskVerifyForm extends Component
{
  public string $nip;
  public string $scope = '';
  public string $description = '';

  public SupportingTask $supportingTask;

  public Collection $scopes;

  public function mount(SupportingTask $supportingTask)
  {
    $this->supportingTask = $supportingTask;
    $this->scope = $supportingTask->scope;
    $this->description = $supportingTask->description;
    $this->scopes = SupportingTask::getScopes();
  }

  public function verify()
  {
    $this->validate([
      'scope' => 'required',
      'description' => 'nullable',
    ]);

    try {
      $scope = $this->scopes->where('name', $this->scope)->first();

      $this->supportingTask->update([
        'scope' => $this->scope,
        'value' => $scope['value'],
        'description' => $this->description,
        'status' => 2,
      ]);

      $this->updateAssessmentExtra();

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Inovasi berhasil diverifikasi',
      ]);

      $this->dispatch('close-modal');
      $this->dispatch('page-refresh');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Inovasi gagal diverifikasi',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function updateAssessmentExtra()
  {
    $extra_value = $this->calculateFinalValue();
    $assessment = Assessment::updateOrCreate([
      'nip' => $this->supportingTask->nip,
      'year' => $this->supportingTask->year,
    ], [
      'extra' => $extra_value,
    ]);

    $assessment->syncAllValue();
  }

  private function calculateFinalValue()
  {
    $supportingTasks = SupportingTask::query()
      ->where('nip', $this->supportingTask->nip)
      ->where('year', $this->supportingTask->year)
      ->where('status', 2)
      ->orderByDesc('value')
      ->get();

    $supportingTask = $supportingTasks->first();
    $taskBonus = 0;

    $supportingTask_final_value = 0;
    if ($supportingTask) {
      $scope = SupportingTask::getScopes()->where('name', $supportingTask->scope)->firstOrFail();
      $supportingTaskCount = $supportingTasks->where('scope', $supportingTask->scope)->count();
      if ($supportingTaskCount >= 3) {
        $taskBonus = $scope['bonus_as_leader'];
      }
      $supportingTask_final_value = $supportingTask->weight * ($supportingTask->value + $taskBonus);
    }

    return $supportingTask_final_value;
  }

  public function render()
  {
    return view('livewire.supporting-task-verify-form');
  }
}
