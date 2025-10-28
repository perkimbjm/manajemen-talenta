<?php

namespace App\Livewire;

use Livewire\Component;

class TestComponents extends Component
{
  public array $pins = [5, 6, 7, 8];
  public function render()
  {
    return view('livewire.test-components');
  }
}
