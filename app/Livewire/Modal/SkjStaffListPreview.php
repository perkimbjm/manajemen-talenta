<?php

namespace App\Livewire\Modal;

use Livewire\Component;
use App\Models\Occupation;
use App\Models\OccupationType;

class SkjStaffListPreview extends Component
{
  public Occupation $occupation;

  public function render()
  {
    return view('livewire.modal.skj-staff-list-preview');
  }
}
