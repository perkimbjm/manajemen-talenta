<?php

namespace App\Livewire\Modal;

use App\Models\Affair;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;

class CreateAffair extends ModalComponent
{
  #[Validate('required')]
  public $name = '';

  public $description = '';


  public static function modalMaxWidth(): string
  {
    return '2xl';
  }

  public function save(): void
  {
    $this->validate([
      'name' => 'required',
      'description' => 'string|nullable',
    ]);

    Affair::create($this->only(['name', 'description']));
    $this->dispatch('affairs.updated');
    $this->dispatch('notifications', [
      'type' => 'success',
      'message' => 'Urusan berhasil ditambahkan',
    ]);

    $this->closeModal();
  }


  public function render()
  {
    return view('livewire.modal.create-affair');
  }
}
