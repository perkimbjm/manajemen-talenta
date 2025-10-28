<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Occupation;
use Livewire\Attributes\Layout;
use App\Models\CompetencyStandard;
use App\Models\OccupationType;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

#[Layout('components.layouts.authenticated')]
class SkjPage extends Component
{
  public function uploadSKJ(OccupationType $occupationType, array $dataset)
  {
    try {
      DB::transaction(function () use ($occupationType, $dataset) {
        foreach ($dataset as $data) {
          $occupationType->addMedia(storage_path("app/{$data['file_path']}"))->toMediaCollection('skj', 'local');
        }
      });

      $occupationType->refresh();
      $this->dispatch('pg:eventRefresh-SkjTable');
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
    return view('livewire.pages.skj-page');
  }
}
