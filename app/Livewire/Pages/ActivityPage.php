<?php

namespace App\Livewire\Pages;

use App\Models\Activity;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.authenticated')]
class ActivityPage extends Component
{
  use WithPagination;

  public function render()
  {
    $activities = Activity::with(['sector', 'program'])->where('zone_id', 3)->paginate(15);
    return view('livewire.pages.activity', compact('activities'));
  }
}
