<?php

namespace App\Livewire;

use App\Models\Award;
use Livewire\Component;

class VerifyAwardForm extends Component
{
  public Award $award;

  public string $type = '';
  public string $name = '';
  public ?string $description = null;

  public function mount()
  {
    $this->type = $this->award->type;
    $this->name = $this->award->name;
    $this->description = $this->award->description;
  }

  public function submit()
  {
    $this->validate([
      'type' => 'required',
      'name' => 'required',
      'description' => 'nullable',
    ]);

    try {
      $types = Award::getTypes();
      $type = $types->where('name', $this->type)->first();

      $this->award->update([
        'name' => $this->name,
        'type' => $this->type,
        'value' => $type['value'],
        'description' => $this->description,
        'status' => 2,
      ]);

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Penghargaan berhasil diverifikasi',
      ]);

      $this->dispatch('close-modal');
      $this->dispatch('page-refresh');
      $this->dispatch('pg:eventRefresh-award-table');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Penghargaan gagal diverifikasi',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    $types = Award::getTypes();
    return view('livewire.verify-award-form', compact('types'));
  }
}
