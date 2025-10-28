<?php

namespace App\Livewire\Modal;

use Livewire\Component;
use App\Models\Occupation;
use App\Models\Innovation;

class InnovationDocumentPreview extends Component
{
  public Innovation $innovation;

  public function render()
  {
    return view('livewire.modal.innovation-document-preview');
  }
}
