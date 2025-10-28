<?php

namespace App\Livewire\Pages;

use App\Models\Recap;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.authenticated')]
class RecapPage extends Component
{
  use WithPagination;

  public function render()
  {
    return view('livewire.pages.recap', [
      'recaps' => Recap::with([
        'affair',
        'sector',
        'unit',
        'program',
        'activity',
        'subActivity',
        'fund',
        'expense'
      ])->paginate(15)
    ]);
  }
}
