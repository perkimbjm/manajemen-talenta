<?php

namespace App\Livewire;

use Livewire\Component;

class TestModalContent extends Component
{
  public $code = '123';

  public function render()
  {
    return view('livewire.test-modal-content');
  }
}
