<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.authenticated')]
class UserPage extends Component
{
  public function removeUser(User $user)
  {
    try {
      $user->delete();

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Berhasil menghapus user',
      ]);
      $this->dispatch('pg:eventRefresh-UserTable');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal menghapus user',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    return view('livewire.pages.user-page');
  }
}
