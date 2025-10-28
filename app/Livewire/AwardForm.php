<?php

namespace App\Livewire;

use App\Models\Award;
use Livewire\Component;

class AwardForm extends Component
{
  public string $nip;
  public ?Award $award = null;

  public string $type = 'Lingkup OPD';
  public string $name = '';
  public ?string $description = null;

  public function mount()
  {
    if ($this->award) {
      $this->type = $this->award->type;
      $this->name = $this->award->name;
      $this->description = $this->award->description;
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
      $types = Award::getTypes();
      $type = $types->where('name', $this->type)->first();

      if ($this->award) {
        $this->award->update([
          'name' => $this->name,
          'type' => $this->type,
          'value' => $type['value'],
          'description' => $this->description,
        ]);
      } else {
        Award::create([
          'nip' => $this->nip,
          'name' => $this->name,
          'type' => $this->type,
          'value' => $type['value'],
          'description' => $this->description,
          'year' => date('Y'),
        ]);
      }

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Penghargaan berhasil ditambahkan',
      ]);

      $this->dispatch('close-modal');
      $this->dispatch('page-refresh');
      $this->dispatch('pg:eventRefresh-award-table');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Penghargaan gagal ditambahkan',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    $types = Award::getTypes();
    return view('livewire.award-form', compact('types'));
  }
}
