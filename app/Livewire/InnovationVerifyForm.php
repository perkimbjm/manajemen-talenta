<?php

namespace App\Livewire;

use App\Models\Assessment;
use Livewire\Component;
use App\Models\Innovation;
use Illuminate\Support\Collection;

class InnovationVerifyForm extends Component
{
  public string $nip;
  public string $scope = '';
  public string $description = '';

  public Innovation $innovation;

  public Collection $scopes;

  public function mount(Innovation $innovation)
  {
    $this->innovation = $innovation;
    $this->scope = $innovation->scope;
    $this->description = $innovation->description;
    $this->scopes = Innovation::getScopes();
  }

  public function verify()
  {
    $this->validate([
      'scope' => 'required',
      'description' => 'nullable',
    ]);

    try {
      $scope = $this->scopes->where('name', $this->scope)->first();

      $this->innovation->update([
        'scope' => $this->scope,
        'value' => $scope['value'],
        'description' => $this->description,
        'status' => 2,
      ]);

      $this->updateAssessmentInnovation();

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

  public function updateAssessmentInnovation()
  {
    $innovation = Innovation::query()
      ->where('nip', $this->innovation->nip)
      ->where('year', $this->innovation->year)
      ->where('status', 2)
      ->orderByDesc('value')
      ->first();

    $assessment = Assessment::updateOrCreate([
      'nip' => $innovation->nip,
      'year' => $innovation->year,
    ], [
      'innovation' => $innovation->computed_value,
    ]);

    $assessment->syncAllValue();
  }

  public function render()
  {
    return view('livewire.innovation-verify-form');
  }
}
