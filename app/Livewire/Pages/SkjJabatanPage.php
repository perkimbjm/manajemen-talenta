<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Position;
use App\Models\Occupation;
use Livewire\Attributes\On;
use App\Models\OccupationType;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.authenticated')]
class SkjJabatanPage extends Component
{

  public OccupationType $occupation_type;

  public function uploadSKJ(string $positionId, array $dataset)
  {
    try {
      $position = Position::findOrFail($positionId);

      DB::transaction(function () use ($position, $dataset) {
        foreach ($dataset as $data) {
          $position->addMedia(storage_path("app/{$data['file_path']}"))->toMediaCollection('skj', 'local');
        }
      });

      $position->refresh();
      $this->dispatch('pg:eventRefresh-skj-jabatan-table');
      $this->dispatch('notifications', [
        'type' => 'success',
        'duration' => -1,
        'message' => 'SKJ uploaded successfully.',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Error uploading SKJ.',
        'description' => $th->getMessage(),
      ]);
    }
  }

  #[On('download-document')]
  public function downloadDocument(string $rowId)
  {
    try {
      $occupation = Occupation::findOrFail($rowId);
      $competency = $occupation->competencyStandards?->first();
      return response()->download(
        url($competency->file_path),
        $competency->description
      );
    } catch (\Throwable $th) {
      abort(404);
    }
  }

  public function render()
  {
    return view('livewire.pages.skj-jabatan-page');
  }
}
