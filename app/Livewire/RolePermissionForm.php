<?php

namespace App\Livewire;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Collection;
use Livewire\Component;

class RolePermissionForm extends Component
{
    public Role $role;
    /** @var array<string> */
    public array $selectedPermissions = [];

    public function mount()
    {
        $this->selectedPermissions = $this->role->permissions->pluck('id')->map(fn ($id) => (string) $id)->toArray();
    }

    public function updatedSelectedPermissions()
    {
        $this->selectedPermissions = array_values(array_unique(array_filter($this->selectedPermissions)));
    }

    public function selectAll()
    {
        $this->selectedPermissions = Permission::pluck('id')->map(fn ($id) => (string) $id)->toArray();
    }

    public function resetSelection()
    {
        $this->selectedPermissions = [];
    }

    public function submit()
    {
        $this->role->syncPermissions($this->selectedPermissions);

        $this->dispatch('notifications', [
            'type' => 'success',
            'message' => 'Berhasil memperbarui hak akses role',
        ]);

        $this->dispatch('close-modal');
    }

    public function render()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('livewire.role-permission-form', compact('permissions'));
    }
}
