<?php

namespace App\Livewire\Modal;

use App\Models\Occupation;
use Livewire\Component;

class DocumentListPreview extends Component
{
  public Occupation $occupation;

  public function render()
  {
    return view('livewire.modal.document-list-preview');
  }
}
