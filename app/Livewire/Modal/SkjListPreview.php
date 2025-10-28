<?php

namespace App\Livewire\Modal;

use Livewire\Component;
use App\Models\Occupation;
use App\Models\OccupationType;

class SkjListPreview extends Component
{
  public OccupationType $occupationType;

  public function render()
  {
    return view('livewire.modal.skj-list-preview');
  }
}
