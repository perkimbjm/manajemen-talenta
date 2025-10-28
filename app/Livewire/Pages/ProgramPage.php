<?php

namespace App\Livewire\Pages;

use App\Models\Program;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.authenticated')]
class ProgramPage extends Component
{
  use WithPagination;

  public function render()
  {
    $programs = Program::with(['sector'])->where('zone_id', 3)->paginate(15);
    return view('livewire.pages.program', compact('programs'));
  }
}
