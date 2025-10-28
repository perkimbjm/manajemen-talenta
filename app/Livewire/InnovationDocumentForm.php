<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Innovation;
use App\Models\Media;

class InnovationDocumentForm extends Component
{
  public Innovation $innovation;
  public string $type = '';

  public ?Media $innovation_document = null;
  public ?string $innovation_document_id = null;

  public function mount(Innovation $innovation)
  {
    $this->innovation = $innovation;
    $this->innovation_document = $innovation->getMedia($this->type)->first();
    $this->innovation_document_id = $this->innovation_document?->id;
  }


  public function removeDocument()
  {
    try {
      $this->innovation_document->delete();
      $this->innovation_document = null;
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
      if ($this->innovation_document && $this->innovation_document_id) {
        $this->innovation_document->delete();
        $this->innovation_document = null;
      }

      $file_path = str($file['uploadURL'])->afterLast('/');

      $this->innovation_document = $this
        ->innovation
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
    return view('livewire.innovation-document-form');
  }
}
