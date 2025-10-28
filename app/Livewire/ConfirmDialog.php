<?php

namespace App\Livewire;

use Livewire\Attributes\Lazy;
use Livewire\Component;

class ConfirmDialog extends Component
{
  public function placeholder()
  {
    return <<<'HTML'
        <dialog></dialog>
        HTML;
  }
  public function render()
  {
    return view('livewire.confirm-dialog');
  }
}
