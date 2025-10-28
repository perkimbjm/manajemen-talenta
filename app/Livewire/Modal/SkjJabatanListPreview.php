<?php

namespace App\Livewire\Modal;

use Livewire\Component;
use App\Models\Position;
use App\Models\Occupation;
use App\Models\OccupationType;

class SkjJabatanListPreview extends Component
{
  public Position $position;

  public function render()
  {
    return view('livewire.modal.skj-jabatan-list-preview');
  }
}
