<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SupportingTask;
use App\Models\Media;

class SupportingTaskDocumentForm extends Component
{
  public SupportingTask $supportingTask;
  public string $type = '';

  public ?Media $supportingTask_document = null;
  public ?string $supportingTask_document_id = null;

  public function mount(SupportingTask $supportingTask)
  {
    $this->supportingTask = $supportingTask;
    $this->supportingTask_document = $supportingTask->getMedia($this->type)->first();
    $this->supportingTask_document_id = $this->supportingTask_document?->id;
  }


  public function removeDocument()
  {
    try {
      $this->supportingTask_document->delete();
      $this->supportingTask_document = null;
      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Berhasil menghapus berkas',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal menghapus berkas',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function saveDocument(array $file)
  {
    try {
      if ($this->supportingTask_document && $this->supportingTask_document_id) {
        $this->supportingTask_document->delete();
        $this->supportingTask_document = null;
      }

      $file_path = str($file['uploadURL'])->afterLast('/');

      $this->supportingTask_document = $this
        ->supportingTask
        ->addMedia(storage_path("app/upload/{$file_path}.{$file['extension']}"))
        ->toMediaCollection($this->type);

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => "Berhasil menyimpan berkas [{$this->type}]",
      ]);
      $this->dispatch('page-refresh');
      $this->dispatch('close-modal');
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal menyimpan berkas',
        'description' => $th->getMessage(),
      ]);
    }
  }

  public function render()
  {
    return view('livewire.supporting-task-document-form');
  }
}
