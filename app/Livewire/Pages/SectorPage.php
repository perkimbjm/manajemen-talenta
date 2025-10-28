<?php

namespace App\Livewire\Pages;

use App\Models\Sector;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.authenticated')]
class SectorPage extends Component
{
  use WithPagination;

  public function render()
  {
    $sectors = Sector::with(['affair'])->paginate(15);
    return view('livewire.pages.sector', compact('sectors'));
  }
}
