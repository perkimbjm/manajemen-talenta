<?php

namespace App\Livewire\Pages;

use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class Unit extends Component
{
    use WithPagination;

    public bool $showCreateModal = false;
    public array $form = [
        'code' => '',
        'name' => '',
        'acronym' => '',
        'type' => '',
        'parent_code' => null,
        'description' => '',
    ];

    public function mount()
    {
        //
    }

    public function render()
    {
        return view('livewire.pages.unit', [
            'units' => Unit::paginate(10),
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

        $this->reset('form');
        $this->showCreateModal = false;
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Unit berhasil ditambahkan',
        ]);
    }
}