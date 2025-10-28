<?php

namespace App\Livewire\Modal;

use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;

class CreateSector extends ModalComponent
{
  #[Validate('required')]
  public $code = '';

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
      'code' => 'required',
      'name' => 'required',
      'description' => 'string|nullable',
    ]);

    $this->closeModal();
  }


  public function render()
  {
    return view('livewire.modal.create-sector');
  }
}
