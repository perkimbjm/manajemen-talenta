<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Organization;
use Illuminate\Support\Collection;

class OrganizationForm extends Component
{
  public string $nip;
  public string $name = '';
  public string $scope = '';
  public string $position = '';
  public string $description = '';

  public Collection $scopes;
  public Collection $positions;

  public function mount()
  {
    $this->scopes = Organization::getScopes();
    $this->positions = Organization::getPositions();
  }

  public function submit()
  {
    $this->validate([
      'name' => 'required',
      'scope' => 'required',
      'description' => 'nullable',
    ]);

    try {
      $scope = $this->scopes->where('name', $this->scope)->firstOrFail();
      $position = $this->positions->where('name', $this->position)->firstOrFail();

      Organization::create([
        'nip' => $this->nip,
        'name' => $this->name,
        'scope' => $this->scope,
        'value' => $scope['value'] + $position['value'],
        'as' => $this->position,
        'description' => $this->description,
        'year' => date('Y'),
      ]);

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Inovasi berhasil ditambahkan',
      ]);

      $this->dispatch('close-modal');
      $this->dispatch('page-refresh');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Inovasi gagal ditambahkan',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    return view('livewire.organization-form');
  }
}
