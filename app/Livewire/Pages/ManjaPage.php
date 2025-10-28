<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Employee;
use App\Models\SkpReport;
use App\Models\Assessment;
use App\Models\Occupation;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use WireUi\Attributes\Mount;
use App\Models\SupportingTask;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Computed;
use App\Models\CompetencyStandard;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.authenticated')]
class ManjaPage extends Component
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
      'assessment',
      'performance',
      'unit',
      'innovations' => fn($q) => $q->where('status', 2)->orderByDesc('value'),
      'organizations' => fn($q) => $q->where('status', 2)->orderByDesc('value'),
      'supportingTasks' => fn($q) => $q->where('status', 2)->orderByDesc('value'),
    ])->first();
  }

  public function recalculateAssessment()
  {
    if (!$this->employee) return;

    try {
      $skp = SkpReport::where('nip', $this->employee->nip)->first();
      $evaluations = Assessment::getSpecificLabels();
      $evaluation = $evaluations
        ->filter(fn($eval) => strtolower($eval['name']) === strtolower($skp?->final_result))->first();

      $this->employee->assessment()->updateOrCreate([
        'year' => date('Y')
      ], [
        'specific' => @$evaluation['value'] ?? 0,
      ]);

      $this->employee->refresh();
      $this->employee->assessment->syncPerformanceValue();
      $this->employee->assessment->update([
        'box_id' => @$this->employee->assessment->box['id']
      ]);

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Berhasil merekalkulasi data',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal merekalkulasi data',
        'description' => $th->getMessage(),
      ]);
    }
  }

  // public function mount()
  // {
  //   if ($this->nip && session('nip') != $this->nip) {
  //     session()->put('nip', $this->nip);
  //   } else if (!$this->nip) {
  //     // dd(session('nip'));
  //     $this->nip = session('nip');
  //   }
  // }

  public function render()
  {
    return view('livewire.pages.manja-page');
  }
}
