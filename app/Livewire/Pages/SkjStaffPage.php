<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Occupation;
use Livewire\Attributes\On;
use App\Models\OccupationType;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.authenticated')]
class SkjStaffPage extends Component
{

  public OccupationType $occupation_type;

  public function uploadSKJ(string $occupationId, array $dataset)
  {
    try {
      $occupation = Occupation::findOrFail($occupationId);

      DB::transaction(function () use ($occupation, $dataset) {
        foreach ($dataset as $data) {
          $occupation->addMedia(storage_path("app/{$data['file_path']}"))->toMediaCollection('skj', 'local');
        }
      });

      $occupation->refresh();
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
    return view('livewire.pages.skj-staff-page');
  }
}
