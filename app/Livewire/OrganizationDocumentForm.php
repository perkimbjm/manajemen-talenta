<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Organization;
use App\Models\Media;

class OrganizationDocumentForm extends Component
{
  public Organization $organization;
  public string $type = '';

  public ?Media $organization_document = null;
  public ?string $organization_document_id = null;

  public function mount(Organization $organization)
  {
    $this->organization = $organization;
    $this->organization_document = $organization->getMedia($this->type)->first();
    $this->organization_document_id = $this->organization_document?->id;
  }


  public function removeDocument()
  {
    try {
      $this->organization_document->delete();
      $this->organization_document = null;
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
      if ($this->organization_document && $this->organization_document_id) {
        $this->organization_document->delete();
        $this->organization_document = null;
      }

      $file_path = str($file['uploadURL'])->afterLast('/');
      $this->organization_document = $this
        ->organization
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
    return view('livewire.organization-document-form');
  }
}
