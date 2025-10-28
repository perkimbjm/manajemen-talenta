<?php

namespace App\Livewire;

use App\Models\Role;
use Livewire\Component;

class RoleForm extends Component
{
  public string $name = '';
  public ?Role $role = null;

  public function submit()
  {
    if (!$this->role) {
      $this->validate([
        'name' => 'required|unique:roles,name',
      ]);
    } else {
      $this->validate([
        'name' => 'required|unique:roles,name,' . $this->role->id,
      ]);
    }

    try {
      if ($this->role) {
        $this->role->update([
          'name' => $this->name,
        ]);
      } else {
        Role::create([
          'name' => $this->name,
        ]);
      }

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Berhasil menyimpan data role',
      ]);
      $this->dispatch('close-modal');
      $this->dispatch('pg:eventRefresh-RoleTable');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal menyimpan data role',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function mount()
  {
    if ($this->role) {
      $this->name = $this->role->name;
    }
  }

  public function render()
  {
    $roles = Role::all();
    return view('livewire.role-form', compact('roles'));
  }
}
