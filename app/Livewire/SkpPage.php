<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.authenticated')]
class SkpPage extends Component
{
  public function render()
  {
    return view('livewire.skp-page');
  }
}
