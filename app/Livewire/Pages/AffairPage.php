<?php

namespace App\Livewire\Pages;

use App\Models\Affair;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.authenticated')]
class AffairPage extends Component
{
  use WithPagination;

  public function render()
  {
    return view('livewire.pages.affair', [
      'affairs' => Affair::paginate(15)
    ]);
  }
}
