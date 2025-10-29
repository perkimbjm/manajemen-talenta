<?php

namespace App\Livewire;

use App\Models\Permission;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PermissionForm extends Component
{
    public string $name = '';
    public string $guard_name = 'web';
    public ?Permission $permission = null;

    public function submit()
    {
        $this->validate([
            'name' => [
                'required',
                Rule::unique('permissions', 'name')->ignore($this->permission?->id),
            ],
            'guard_name' => ['required', 'string'],
        ]);

        try {
            if ($this->permission) {
                $this->permission->update([
                    'name' => $this->name,
                    'guard_name' => $this->guard_name,
                ]);
            } else {
                Permission::create([
                    'name' => $this->name,
                    'guard_name' => $this->guard_name,
                ]);
            }

            $this->dispatch('notifications', [
                'type' => 'success',
                'message' => 'Berhasil menyimpan data permission',
            ]);
            $this->dispatch('close-modal');
            $this->dispatch('pg:eventRefresh-PermissionTable');
        } catch (\Throwable $th) {
            $this->dispatch('notifications', [
                'type' => 'danger',
                'message' => 'Gagal menyimpan data permission',
                'description' => $th->getMessage(),
            ]);
        }
    }

    public function mount()
    {
        if ($this->permission) {
            $this->name = $this->permission->name;
            $this->guard_name = $this->permission->guard_name;
        }
    }

    public function render()
    {
        return view('livewire.permission-form');
    }
}
