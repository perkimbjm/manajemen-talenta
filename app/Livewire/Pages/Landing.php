<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Livewire\Actions\Logout;

class Landing extends Component
{
  /**
   * Log the current user out of the application.
   */
  public function logout(Logout $logout): void
  {
    $logout();

    $this->redirect('/', navigate: true);
  }

  public function render()
  {
    return view('livewire.pages.landing');
  }
}
