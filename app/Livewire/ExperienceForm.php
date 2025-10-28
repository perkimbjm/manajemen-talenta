<?php

namespace App\Livewire;

use App\Models\Experience;
use Livewire\Component;

class ExperienceForm extends Component
{
  public string $nip;
  public ?Experience $experience = null;

  public string $type = '';
  public string $name = '';
  public ?string $description = null;

  public function mount()
  {
    if ($this->experience) {
      $this->type = $this->experience->type;
      $this->name = $this->experience->name;
      $this->description = $this->experience->description;
    }
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

      if ($this->experience) {
        $this->experience->update([
          'name' => $this->name,
          'type' => $this->type,
          'value' => $type['value'],
          'description' => $this->description,
        ]);
      } else {
        Experience::create([
          'nip' => $this->nip,
          'name' => $this->name,
          'type' => $this->type,
          'value' => $type['value'],
          'description' => $this->description,
          'year' => date('Y'),
          'status' => 0,
        ]);
      }

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Pengalaman organisasi berhasil ditambahkan',
      ]);

      $this->dispatch('close-modal');
      $this->dispatch('page-refresh');
      $this->dispatch('pg:eventRefresh-experience-table');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Pengalaman organisasi gagal ditambahkan',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    $types = Experience::getTypes();
    return view('livewire.experience-form', compact('types'));
  }
}
