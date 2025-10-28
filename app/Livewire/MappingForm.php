<?php

namespace App\Livewire;

use App\Models\Mapping;
use Livewire\Component;

class MappingForm extends Component
{
  public string $group = '';
  public ?int $_id = null;
  public string $prev_id = '';
  public string $prev_name = '';
  public string $current_id = '';
  public string $current_name = '';

  public function submit()
  {
    $data = $this->validate([
      'group' => 'required',
      'prev_id' => 'required',
      'prev_name' => 'required',
      'current_id' => 'required',
      'current_name' => 'required',
    ]);

    try {
      if ($this->_id) {
        Mapping::find($this->_id)->update($data);
      } else {
        Mapping::updateOrCreate([
          'group' => $data['group'],
          'prev_id' => $data['prev_id'],
          'current_id' => $data['current_id'],
        ], $data);
      }

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => "Berhasil menyimpan data mapping {$data['group']}",
      ]);
      $this->dispatch('pg:eventRefresh-unit-mapping-table');
      $this->reset();
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => "Gagal menyimpan data mapping {$this->group}",
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    return view('livewire.mapping-form');
  }
}
