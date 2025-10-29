<?php

namespace App\Livewire;

use App\Models\Occupation;
use App\Models\OccupationType;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Reactive;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SkjStaffTable extends PowerGridComponent
{
  public string $tableName = 'skj-staff-table';

  #[Reactive]
  public OccupationType $occupation_type;

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
    return Occupation::query()
      ->where('type_code', $this->occupation_type->code);
  }

  public function relationSearch(): array
  {
    return [];
  }

  public function fields(): PowerGridFields
  {
    return PowerGrid::fields()
      ->add('id')
      ->add('code')
      ->add('name')
      ->add('nomenclature')
      ->add('created_at');
  }

  public function columns(): array
  {
    return [
      Column::make('Nama Jabatan', 'name')
        ->contentClasses('inline-block pt-1')
        ->sortable()
        ->searchable(),
      Column::make('Jenjang Jabatan', 'nomenclature')
        ->sortable()
        ->searchable(),
      Column::action('Action')->visibleInExport(false),
    ];
  }

  public function filters(): array
  {
    return [];
  }

  #[\Livewire\Attributes\On('edit')]
  public function edit($rowId): void
  {
    $this->js('alert(' . $rowId . ')');
  }

  public function actions(Occupation $row): array
  {
    $downloadDocumentClassess = 'btn btn-sm p-1';

    $downloadBtn = Button::add('document')
      ->slot(<<<HTML
        <i class="i-mdi-file-pdf text-red-500 [.btn-disabled_&]:text-gray-500 w-6 h-6"></i>
    HTML);

    if (!$row->hasMedia('skj')) {
      $downloadDocumentClassess .= ' [&.btn-disabled]:bg-opacity-0 btn-disabled cursor-not-allowed';
    } else {
      $downloadDocumentClassess .= ' btn-ghost';

      $downloadBtn->dispatch('show-modal', [
        'id' => 'modaldocumentpreview',
        'component' => 'modal.skj-staff-list-preview',
        'template' => '#OccupationStandardPreviewTemplate',
        'tempHeight' => '202px',
        'arguments' => [
          'occupation' => $row->id,
        ]
      ]);
    }

    $downloadBtn
      ->class($downloadDocumentClassess)
      ->tooltip('Download Dokumen');

    $kamusBtn = Button::add('kamus')
      ->tooltip('Kamus Kompetensi')
      ->slot(<<<HTML
        <i class="w-6 h-6 text-blue-500 i-mdi-book-open-page-variant"></i>
      HTML)
      ->class('btn btn-ghost btn-sm p-1')
      ->dispatch('show-kamus-kompetensi', [
        'name' => $row->name,
        'apiType' => 'jfu',
      ]);

    return [
      $downloadBtn,
      Button::add('upload')
        ->tooltip('Upload Dokumen')
        ->slot(<<<HTML
            <i class="w-6 h-6 i-mdi-box-upload text-primary-light"></i>
          HTML)
        ->id()
        ->class('btn btn-ghost btn-sm p-1')
        ->dispatch('upload-document', ['rowId' => $row->id]),
      $kamusBtn,
    ];
  }

  /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
