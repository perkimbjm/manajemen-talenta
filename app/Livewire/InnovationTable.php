<?php

namespace App\Livewire;

use App\Models\Innovation;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Reactive;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class InnovationTable extends PowerGridComponent
{
  public string $tableName = 'innovation-table';

  #[Reactive]
  public ?string $nip = null;

  public function setUp(): array
  {
    $this->showCheckBox();

    return [
      PowerGrid::header()
        ->showSearchInput(),
      PowerGrid::footer()
        ->showPerPage()
        ->showRecordCount(),
    ];
  }

  public function datasource(): Builder
  {
    $query =  Innovation::query();

    $query->where('nip', $this->nip);

    return $query;
  }

  public function relationSearch(): array
  {
    return [];
  }

  public function fields(): PowerGridFields
  {
    return PowerGrid::fields()
      ->add('id')
      ->add('nip')
      ->add('scope')
      ->add('name')
      ->add('description')
      ->add('value')
      ->add('status')
      ->add('status_template', function ($row) {
        return [
          'statusTemplate' => [
            'id' => $row->id,
            'status' => $row->status,
            'status_label' => $row->status_label,
            'verifyEvent' => 'verify-innovation',
          ]
        ];
      })
      ->add('year')
      ->add('created_at');
  }

  public function columns(): array
  {
    $columns = [

      Column::make('Nama Inovasi', 'name')
        ->sortable()
        ->searchable(),

      Column::make('Ruang Lingkup', 'scope')
        ->sortable()
        ->searchable(),

      Column::make('Keterangan', 'description')
        ->searchable(),

      Column::make('Nilai', 'value')
        ->sortable()
        ->searchable(),

      Column::make('Status', 'status_template', 'status')
        ->sortable()
        ->template(),

      Column::action('Action')
    ];

    return $columns;
  }

  public function filters(): array
  {
    return [];
  }

  #[\Livewire\Attributes\On('verify-innovation')]
  public function verifyRow($rowId): void
  {
    $this->dispatch(
      event: 'show-modal',
      component: 'verify-innovation-form',
      title: 'Verifikasi Berkas Sertifikat',
      arguments: [
        'innovation' => $rowId,
      ]
    );
  }

  public function changeStatus(Innovation $innovation, int $status, ?string $description = null)
  {
    try {
      $innovation->update(compact('status', 'description'));

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Status Sertifikat berhasil diubah',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Status Sertifikat gagal diubah',
        'message' => $th->getMessage(),
      ]);
    }
  }


  public function rowTemplates(): array
  {
    $user = request()->user();
    if ($user->hasRole('Super Admin')) {
      $template = view('components.change-status-button')->render();
    } else {
      $template = <<<'HTML'
        <span class="text-gray-900" x-text="templateContent.statusTemplate.status_label"></span>
      HTML;
    }

    return [
      'statusTemplate' => (
        $template
      ),
    ];
  }

  public function actions(Innovation $row): array
  {
    $downloadDocumentClassess = 'btn btn-sm p-1';

    $downloadBtn = Button::add('document')
      ->slot(<<<HTML
        <i class="i-mdi-file-pdf text-red-500 [.btn-disabled_&]:text-gray-500 w-6 h-6"></i>
    HTML);

    if (!$row->hasMedia('*')) {
      $downloadDocumentClassess .= ' [&.btn-disabled]:bg-opacity-0 btn-disabled cursor-not-allowed';
    } else {
      $downloadDocumentClassess .= ' btn-ghost';

      $downloadBtn->dispatch('show-modal', [
        'id' => 'modaldocumentpreview',
        'component' => 'modal.innovation-document-preview',
        'template' => '#DocumentPreviewTemplate',
        'tempHeight' => '202px',
        'arguments' => [
          'innovation' => $row->id,
        ]
      ]);
    }

    $downloadBtn
      ->class($downloadDocumentClassess)
      ->tooltip('Download Dokumen');
    return [
      $downloadBtn,
      Button::add('upload')
        ->tooltip('Upload Dokumen')
        ->slot(<<<HTML
            <i class="i-mdi-box-upload text-primary-light w-6 h-6"></i>
          HTML)
        ->id()
        ->class('btn btn-ghost btn-sm p-1')
        ->dispatch('upload-innovation', ['rowId' => $row->id]),
      Button::add('edit')
        ->slot('Edit')
        ->id()
        ->class('btn btn-sm btn-info')
        ->dispatch('edit', ['rowId' => $row->id])
    ];
  }

  public function actionRules($row): array
  {
    return [
      // Hide button edit for ID 1
      Rule::button('edit')
        ->hide()
        ->when(fn($row) => $row->status !== 0)
    ];
  }
}
