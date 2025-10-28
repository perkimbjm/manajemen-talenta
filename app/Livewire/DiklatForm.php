<?php

namespace App\Livewire;

use App\Models\Diklat;
use Livewire\Component;

class DiklatForm extends Component
{
  public string $nip;
  public ?Diklat $diklat = null;

  public string $type = '';
  public string $name = '';
  public int $status = 0;
  public ?int $rank = null;
  public ?int $year = null;
  public string $letter_number = '';
  public ?string $description = null;

  public function mount()
  {
    if ($this->diklat) {
      $this->type = $this->diklat->type;
      $this->name = $this->diklat->name;
      $this->year = $this->diklat->year;
      $this->rank = $this->diklat->rank;
      $this->letter_number = $this->diklat->letter_number;
      $this->description = $this->diklat->description;
    }
  }

  public function submit()
  {
    $this->validate([
      'type' => 'required',
      'name' => 'required',
      'year' => 'required',
      'rank' => 'nullable',
      'status' => 'required',
      'description' => 'nullable',
    ]);

    try {
      if ($this->diklat) {
        $this->diklat->update([
          'type' => $this->type,
          'name' => $this->name,
          'rank' => $this->rank,
          'year' => $this->year,
          'status' => $this->status,
          'description' => $this->description,
          'letter_number' => $this->letter_number,
        ]);
      } else {

        $codes = [
          4 => '2',
          3 => '3',
          2 => '4',
          1 => '5',
        ];

        $code = '1';

        if ($this->type === 'Pim') {
          $code = @$codes[$this->rank] ?? '1';
        } else {
          $code = '9';
        }

        Diklat::create([
          'code' => $code,
          'nip' => $this->nip,
          'name' => $this->name,
          'type' => $this->type,
          'rank' => $this->rank,
          'letter_number' => $this->letter_number,
          'description' => $this->description,
          'year' => $this->year,
          'status' => $this->status,
        ]);
      }

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Diklat berhasil ditambahkan',
      ]);

      $this->dispatch('close-modal');
      $this->dispatch('page-refresh');
      $this->dispatch('pg:eventRefresh-diklat-table');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Diklat gagal ditambahkan',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    $types = Diklat::getTypes();
    $statuses = Diklat::getStatuses();
    return view('livewire.diklat-form', compact('types', 'statuses'));
  }
}
