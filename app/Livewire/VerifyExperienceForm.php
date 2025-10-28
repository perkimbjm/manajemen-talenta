<?php

namespace App\Livewire;

use App\Models\Experience;
use Livewire\Component;

class VerifyExperienceForm extends Component
{
  public Experience $experience;

  public string $type = '';
  public string $name = '';
  public ?string $description = null;

  public function mount()
  {
    $this->type = $this->experience->type;
    $this->name = $this->experience->name;
    $this->description = $this->experience->description;
  }

  public function submit()
  {
    $this->validate([
      'type' => 'required',
      'name' => 'required',
      'description' => 'nullable',
    ]);

    try {
      $types = Experience::getTypes();
      $type = $types->where('name', $this->type)->first();

      $this->experience->update([
        'name' => $this->name,
        'type' => $this->type,
        'value' => $type['value'],
        'description' => $this->description,
        'status' => 2,
      ]);

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Pengalaman Organisasi berhasil diverifikasi',
      ]);

      $this->dispatch('close-modal');
      $this->dispatch('page-refresh');
      $this->dispatch('pg:eventRefresh-experience-table');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Pengalaman Organisasi gagal diverifikasi',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    $types = Experience::getTypes();
    return view('livewire.verify-experience-form', compact('types'));
  }
}
