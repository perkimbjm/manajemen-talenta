<?php

namespace App\Livewire;

use Livewire\Component;

class DynamicModalRender extends Component
{
  public function placeholder()
  {
    return <<<'HTML'
      <div></div>
      HTML;
  }

  public function render()
  {
    return view('livewire.dynamic-modal-render');
  }
}
