<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Innovation;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.authenticated')]
class EmployeeDocumentsPage extends Component
{
  #[Url(), Session('nip')]
  public ?string $nip = null;

  #[Computed]
  public function employee()
  {
    return Employee::where('nip', $this->nip)->first();
  }

  public function uploadInnovation(Innovation $innovation, array $dataset)
  {
    try {
      DB::transaction(function () use ($innovation, $dataset) {
        foreach ($dataset as $data) {
          $innovation->addMedia(storage_path("app/{$data['file_path']}"))->toMediaCollection('default', 'local');
        }
      });

      $innovation->refresh();
      $this->dispatch('pg:eventRefresh-innovation-table');
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

  public function render()
  {
    $user = request()->user();
    $innovations = Innovation::where('nip', $this->employee?->nip)->orderByDesc('value')->paginate(15);
    return view('livewire.employee-documents-page', compact('user', 'innovations'));
  }
}
