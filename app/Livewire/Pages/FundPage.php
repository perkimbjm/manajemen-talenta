<?php

namespace App\Livewire\Pages;

use App\Models\Fund;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.authenticated')]
class FundPage extends Component
{
  use WithPagination;

  public function render()
  {
    return view('livewire.pages.fund', [
      'funds' => Fund::paginate(15)
    ]);
  }
}
