<?php

namespace App\Livewire\Pages;

use App\Models\SubActivity;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.authenticated')]
class SubActivityPage extends Component
{
  use WithPagination;

  public function render()
  {
    $sub_activities = SubActivity::with(['sector', 'activity.program'])->paginate(15);
    return view('livewire.pages.sub-activity', compact('sub_activities'));
  }
}
