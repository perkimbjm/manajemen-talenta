<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Assessment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;

#[Layout('components.layouts.authenticated')]
class TalentPoolPage extends Component
{
  public function render()
  {
    $boxs = Assessment::getBoxs();
    return view('livewire.talent-pool-page', compact('boxs'));
  }
}
