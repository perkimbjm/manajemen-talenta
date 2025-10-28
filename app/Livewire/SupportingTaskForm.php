<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SupportingTask;
use Illuminate\Support\Collection;

class SupportingTaskForm extends Component
{
  public string $nip;
  public string $name = '';
  public string $scope = '';
  public string $description = '';

  public Collection $scopes;

  public function mount()
  {
    $this->scopes = SupportingTask::getScopes();
  }

  public function submit()
  {
    $this->validate([
      'name' => 'required',
      'scope' => 'required',
      'description' => 'nullable',
    ]);

    try {
      $scope = $this->scopes->where('name', $this->scope)->first();

      SupportingTask::create([
        'nip' => $this->nip,
        'name' => $this->name,
        'scope' => $this->scope,
        'value' => $scope['value'],
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
    return view('livewire.supporting-task-form');
  }
}
