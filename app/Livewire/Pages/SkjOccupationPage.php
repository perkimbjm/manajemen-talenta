<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Occupation;
use Livewire\Attributes\Layout;
use App\Models\CompetencyStandard;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

#[Layout('components.layouts.authenticated')]
class SkjOccupationPage extends Component
{
  public function uploadSKJ(string $occupationId, array $dataset)
  {
    try {
      $occupation = Occupation::findOrFail($occupationId);

      DB::transaction(function () use ($occupation, $dataset) {
        foreach ($dataset as $data) {
          $occupation->competencyStandards()->updateOrCreate([
            'file_path' => $data['file_path'],
            'file_disk' => $data['file_disk'],
          ], [
            'description' => $data['description'],
            'file_type' => $data['file_type'],
          ]);
        }
      });

      $occupation->refresh();
      $this->dispatch('pg:eventRefresh-OccupationStandardTable');
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
    return view('livewire.pages.skj-occupation-page');
  }
}
