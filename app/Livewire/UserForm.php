<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class UserForm extends Component
{
  public string $name = '';
  public string $password = '';
  public ?string $role = null;

  public ?User $user = null;

  public function submit()
  {
    if (!$this->user) {
      $this->validate([
        'name' => 'required|unique:users,name',
        'password' => 'required|min:8',
      ]);
    } else {
      $this->validate([
        'name' => 'required|unique:users,name,' . $this->user->id,
        'password' => 'nullable|min:8',
      ]);
    }


    try {
      if ($this->user) {
        $this->user->update([
          'name' => $this->name,
          'password' => $this->password,
        ]);
      } else {
        User::create([
          'name' => $this->name,
          'password' => $this->password,
        ]);
      }

      if ($this->role) {
        $this->user->syncRoles([$this->role]);
      }

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Berhasil menyimpan data user',
      ]);
      $this->dispatch('close-modal');
      $this->dispatch('pg:eventRefresh-UserTable');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal menyimpan data user',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function mount()
  {
    if ($this->user) {
      $this->name = $this->user->name;
      $this->role = $this->user->getRoleNames()->first();
    }
  }

  public function render()
  {
    $roles = Role::all();
    return view('livewire.user-form', compact('roles'));
  }
}
