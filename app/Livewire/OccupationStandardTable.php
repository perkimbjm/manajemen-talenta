<?php

namespace App\Livewire;

use App\Models\Occupation;
use Livewire\Attributes\Url;
use App\Models\OccupationType;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class OccupationStandardTable extends PowerGridComponent
{
  use WithExport;

  public string $tableName = 'OccupationStandardTable';

  #[Url]
  public string $sortField = 'code';

  #[Url]
  public string $sortDirection = 'ASC';

  #[Url]
  public array $filters = [];

  public function setUp(): array
  {
    $this->showCheckBox();

    return [
      PowerGrid::exportable(fileName: 'export')
        ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
      PowerGrid::header()->showSearchInput(),
      PowerGrid::footer()
        ->showPerPage()
        ->showRecordCount(),
    ];
  }

  public function datasource(): Builder
  {
    return Occupation::query()
      ->whereIn('type_code', [3, 4, 5, 6, 9])
      ->with(['type'])
      ->select('occupations.*')
      ->selectRaw('concat_ws(" ", name, nomenclature) as display_name');
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
      ->add('display_name')
      ->add('type_code')
      ->add('type_name', function ($row) {
        return $row->type->name;
      })
      ->add('echelon_code')
      ->add('group')
      ->add('sequence')
      ->add('grade')
      ->add('level')
      ->add('description')
      ->add('tags')
      ->add('created_at');
  }

  public function columns(): array
  {
    return [
      Column::make('Kode', 'code')
        ->sortable(),
      Column::make('Nama Jabatan', 'display_name', 'name'),
      Column::make('Jenis Jabatan', 'type_name'),
      Column::action('Action')->visibleInExport(false),
    ];
  }

  // public function filters(): array
  // {
  //   return [
  //     Filter::inputText('checkbox', 'id')
  //       ->operators(['contains'])
  //       ->component('powergrid.filter-input', [
  //         'placeholder' => 'Cari kode',
  //       ]),
  //     Filter::inputText('code', 'code')
  //       ->operators(['contains'])
  //       ->component('powergrid.filter-input', [
  //         'placeholder' => 'Cari kode',
  //       ]),
  //     Filter::multiSelect('type_name', 'type_code')
  //       ->dataSource(OccupationType::orderBy('code')->whereNot('code', '0')->get())
  //       ->optionValue('code')
  //       ->optionLabel('name'),
  //     Filter::inputText('name', 'name')->component('powergrid.filter-input', [
  //       'placeholder' => 'Cari nama jabatan',
  //     ]),
  //   ];
  // }

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

    if (!$row->competencyStandards->count()) {
      $downloadDocumentClassess .= ' [&.btn-disabled]:bg-opacity-0 btn-disabled cursor-not-allowed';
    } else {
      $downloadDocumentClassess .= ' btn-ghost';

      $downloadBtn->dispatch('show-modal', [
        'id' => 'modaldocumentpreview',
        'component' => 'modal.document-list-preview',
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

    return [
      $downloadBtn,
      Button::add('upload')
        ->tooltip('Upload Dokumen')
        ->slot(<<<HTML
            <i class="i-mdi-box-upload text-primary-light w-6 h-6"></i>
          HTML)
        ->id()
        ->class('btn btn-ghost btn-sm p-1')
        ->dispatch('upload-document', ['rowId' => $row->id]),
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
