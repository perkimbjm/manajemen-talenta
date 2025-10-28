<?php

namespace App\Livewire\Modal;

use App\Models\Unit;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class CreateUnit extends ModalComponent
{
    public array $form = [
        'code' => '',
        'name' => '',
        'acronym' => '',
        'type' => '',
        'parent_code' => null,
        'description' => '',
    ];

    public function render()
    {
        return view('livewire.modal.create-unit', [
            'parentUnits' => Unit::where('type', 'OPD')->get(['code', 'name']),
        ]);
    }

    public function save()
    {
        $this->validate([
            'form.code' => 'required|unique:units,code',
            'form.name' => 'required',
            'form.acronym' => 'required',
            'form.type' => 'required|in:OPD,SUB',
            'form.parent_code' => 'nullable|exists:units,code',
            'form.description' => 'nullable',
        ]);

        Unit::create([
            ...$this->form,
            'level' => $this->form['parent_code'] ? 2 : 1,
            'root_code' => $this->form['parent_code'] ?? $this->form['code'],
        ]);

        $this->closeModalWithEvents([
            'unitCreated' => true
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Unit berhasil ditambahkan',
        ]);
    }
}