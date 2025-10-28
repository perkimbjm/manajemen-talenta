<?php

namespace App\Livewire;

use App\Models\Occupation;
use Livewire\Attributes\Url;
use App\Models\OccupationType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class SkjTable extends PowerGridComponent
{
  use WithExport;

  public string $tableName = 'SkjTable';

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
      PowerGrid::header()
        ->showSearchInput(),
      PowerGrid::footer()
        ->showPerPage()
        ->showRecordCount(),
    ];
  }

  public function datasource(): Builder
  {
    return OccupationType::query()
      ->whereIn('code', [3, 4, 5, 6, 9]);
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
      ->add('group')
      ->add('name', function ($row) {
        $group_lowercase = strtolower($row->group);
        $route = route("features.skj.{$group_lowercase}", [
          'occupation_type' => $row->code
        ]);

        return Blade::render(
          (<<<'HTML'
          <a href="{{$route}}" class="link hover:text-primary" wire:navigate>
            {{$row->name}}
          </a>
          HTML),
          compact('row', 'route')
        );
      })
      ->add('created_at');
  }

  public function columns(): array
  {
    return [
      Column::make('Nama Jenis Jabatan', 'name')
        ->contentClasses('inline-block pt-1')
        ->searchable(),
      Column::make('Kategori', 'group')
        ->contentClasses('inline-block pt-1')
        ->searchable(),
      Column::action('Action')->visibleInExport(false),
    ];
  }

  public function filters(): array
  {
    return [
      Filter::inputText('checkbox', 'id')
        ->operators(['contains'])
        ->component('powergrid.filter-input', [
          'placeholder' => 'Cari kode',
        ]),
    ];
  }

  public function actions(OccupationType $row): array
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
        'component' => 'modal.skj-list-preview',
        'template' => '#OccupationStandardPreviewTemplate',
        'tempHeight' => '202px',
        'arguments' => [
          'occupationType' => $row->id,
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
