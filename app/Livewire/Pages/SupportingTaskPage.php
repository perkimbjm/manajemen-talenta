<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Assessment;
use App\Models\Occupation;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use WireUi\Attributes\Mount;
use App\Models\SupportingTask;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Computed;
use App\Models\CompetencyStandard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

#[Layout('components.layouts.authenticated')]
class SupportingTaskPage extends Component
{
  use WithPagination;

  #[Url(), Session('nip')]
  public ?string $nip = null;

  #[Computed()]
  public function employee()
  {
    $user = request()->user();
    if ($user?->hasExactRoles('Pegawai')) {
      return Employee::with(['performance', 'unit'])->where('user_id', $user?->id)->first();
    }

    if ($this->nip) {
      return Employee::with(['performance', 'unit'])->where('nip', $this->nip)->first();
    }
  }

  public function changeStatus(SupportingTask $supportingTask, int $status, ?string $description = null)
  {
    try {
      Validator::validate(['status' => $status], [
        'status' => ['required', 'integer', 'min:0', 'max:2'],
      ]);

      $supportingTask->update([
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

  public function updateAssessmentExtra()
  {
    $extra_value = $this->calculateFinalValue();
    Assessment::updateOrCreate([
      'nip' => $this->nip,
      'year' => session('year', date('Y')),
    ], [
      'extra' => $extra_value,
    ]);
  }


  private function calculateFinalValue()
  {
    $supportingTasks = SupportingTask::query()
      ->where('nip', $this->nip)
      ->where('year', session('year', date('Y')))
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
    $user = request()->user();
    $supportingTasks = SupportingTask::where('nip', $this->employee?->nip)->orderByDesc('value')->paginate(15);
    return view('livewire.pages.supporting-task-page', compact('supportingTasks', 'user'));
  }
}
