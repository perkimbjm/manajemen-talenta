<?php

namespace App\Livewire;

use LivewireUI\Modal\ModalComponent;

class EditUser extends ModalComponent
{
  public function incrementCounter()
  {
    $this->dispatch('increment')->to(Counter::class);
  }

  public function render()
  {
    return view('livewire.edit-user');
  }
}
