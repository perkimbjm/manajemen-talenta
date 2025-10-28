<?php

namespace App\Livewire;

use Livewire\Attributes\{Layout, Title};
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('components.layouts.authenticated')]
#[Title('Counter')]
class Counter extends Component
{
  public $count = 0;

  public function mount()
  {
    $this->count = session('count', 0);
  }

  #[On('increment')]
  public function increment()
  {
    $this->count++;
  }

  public function decrement()
  {
    $this->count--;
  }

  public function save()
  {
    session()->put('count', $this->count);
    $this->dispatch('count-saved', count: $this->count);
  }

  public function render()
  {
    return view('livewire.counter');
  }
}
