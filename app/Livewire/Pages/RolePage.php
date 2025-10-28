<?php

namespace App\Livewire\Pages;

use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.authenticated')]
class RolePage extends Component
{
  public function removeRole(Role $role)
  {
    try {
      $role->delete();

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Berhasil menghapus role',
      ]);
      $this->dispatch('pg:eventRefresh-RoleTable');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal menghapus role',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    return view('livewire.pages.role-page');
  }
}
