<?php

namespace App\Livewire;

use App\Models\Sertificate;
use Livewire\Component;

class SertificateForm extends Component
{
  public string $nip;
  public ?Sertificate $sertificate = null;

  public string $type = '';
  public string $name = '';
  public ?string $description = null;

  public function mount()
  {
    if ($this->sertificate) {
      $this->type = $this->sertificate->type;
      $this->name = $this->sertificate->name;
      $this->description = $this->sertificate->description;
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
      $types = Sertificate::getTypes();
      $type = $types->where('name', $this->type)->first();

      if ($this->sertificate) {
        $this->sertificate->update([
          'name' => $this->name,
          'type' => $this->type,
          'value' => $type['value'],
          'description' => $this->description,
        ]);
      } else {
        Sertificate::create([
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
        'message' => 'Sertifikat berhasil ditambahkan',
      ]);

      $this->dispatch('close-modal');
      $this->dispatch('page-refresh');
      $this->dispatch('pg:eventRefresh-sertificate-table');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Sertifikat gagal ditambahkan',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    $types = Sertificate::getTypes();
    return view('livewire.sertificate-form', compact('types'));
  }
}
