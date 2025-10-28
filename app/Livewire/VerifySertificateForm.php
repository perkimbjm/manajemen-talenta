<?php

namespace App\Livewire;

use App\Models\Sertificate;
use Livewire\Component;

class VerifySertificateForm extends Component
{
  public Sertificate $sertificate;

  public string $type = '';
  public string $name = '';
  public ?string $description = null;

  public function mount()
  {
    $this->type = $this->sertificate->type;
    $this->name = $this->sertificate->name;
    $this->description = $this->sertificate->description;
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

      $this->sertificate->update([
        'name' => $this->name,
        'type' => $this->type,
        'value' => $type['value'],
        'description' => $this->description,
        'status' => 2,
      ]);

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Sertifikcat berhasil diverifikasi',
      ]);

      $this->dispatch('close-modal');
      $this->dispatch('page-refresh');
      $this->dispatch('pg:eventRefresh-sertificate-table');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Sertifikcat gagal diverifikasi',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    $types = Sertificate::getTypes();
    return view('livewire.verify-sertificate-form', compact('types'));
  }
}
