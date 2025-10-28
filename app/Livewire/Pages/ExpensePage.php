<?php

namespace App\Livewire\Pages;

use App\Models\Expense;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.authenticated')]
class ExpensePage extends Component
{
  use WithPagination;

  public function render()
  {
    return view('livewire.pages.expense', [
      'expenses' => Expense::paginate(15)
    ]);
  }
}
