<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Assessment;
use App\Models\Organization;
use Illuminate\Support\Collection;

class OrganizationVerifyForm extends Component
{
  public string $scope = '';
  public string $position = '';
  public string $description = '';

  public Organization $organization;

  public Collection $scopes;
  public Collection $positions;

  public function mount(Organization $organization)
  {
    $this->organization = $organization;
    $this->scope = $organization->scope;
    $this->description = $organization->description;
    $this->position = $organization->as;
    $this->scopes = Organization::getScopes();
    $this->positions = Organization::getPositions();
  }

  public function verify()
  {
    $this->validate([
      'scope' => 'required',
      'description' => 'nullable',
    ]);

    try {
      $scope = $this->scopes->where('name', $this->scope)->first();
      $position = $this->positions->where('name', $this->position)->firstOrFail();

      $this->organization->update([
        'scope' => $this->scope,
        'value' => $scope['value'] + $position['value'],
        'description' => $this->description,
        'status' => 2,
      ]);

      $this->updateAssessmentOrganization();

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

  public function updateAssessmentOrganization()
  {
    $organization = Organization::query()
      ->where('nip', $this->organization->nip)
      ->where('year', $this->organization->year)
      ->where('status', 2)
      ->orderByDesc('value')
      ->first();
    $assessment = Assessment::updateOrCreate([
      'nip' => $organization->nip,
      'year' => $organization->year,
    ], [
      'organizational' => $organization->computed_value,
    ]);

    $assessment->syncAllValue();
  }

  public function render()
  {
    return view('livewire.organization-verify-form');
  }
}
