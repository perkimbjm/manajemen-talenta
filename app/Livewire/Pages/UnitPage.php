<?php

namespace App\Livewire\Pages;

use App\Models\Unit;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.authenticated')]
class UnitPage extends Component
{
  use WithPagination;

  public function render()
  {
    $units = Unit::with(['sectors'])->paginate(15);
    return view('livewire.pages.unit', compact('units'));
  }
}
