<?php

namespace App\Livewire\Modal;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class PdfPreview extends ModalComponent
{
  public string $url;

  public static function modalMaxWidth(): string
  {
    return '7xl';
  }

  public function render()
  {
    return view('livewire.modal.pdf-preview');
  }
}
