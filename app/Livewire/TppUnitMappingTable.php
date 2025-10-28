<?php

namespace App\Livewire;

use App\Models\Mapping;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class TppUnitMappingTable extends PowerGridComponent
{
  use WithExport;

  public string $tableName = 'ttp-skpd-mapping-table';
  public string $mappingGroup = 'SKPD TPP';

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

  public function header(): array
  {
    return [
      Button::add('bulk-delete')
        ->slot(
          <<<'HTML'
              <span class="i-mdi-plus w-5 h-5"></span>
              HTML
        )
        ->class('btn btn-primary h-auto min-h-0 py-2 border-none')
        ->dispatch('show-modal', [
          'component' => 'mapping-form',
          'title' => "Tambahkan Mapping Data: {$this->mappingGroup}",
          'template' => '#SimpleFormTemplate',
          'arguments' => [
            'group' => $this->mappingGroup
          ]
        ]),
    ];
  }

  public function datasource(): Builder
  {
    return Mapping::where('group', $this->mappingGroup);
  }

  public function relationSearch(): array
  {
    return [];
  }

  public function fields(): PowerGridFields
  {
    return PowerGrid::fields()
      ->add('id')
      ->add('created_at');
  }

  public function columns(): array
  {
    return [
      Column::make('Prev ID', 'prev_id'),
      Column::make('Prev Name', 'prev_name'),
      Column::make('Current ID', 'current_id'),
      Column::make('Current Name', 'current_name'),

      Column::action('Action')
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

  public function actions(Mapping $row): array
  {
    return [
      Button::add('edit')
        ->slot('Edit')
        ->id()
        ->class('btn btn-sm btn-primary')
        ->dispatch('show-modal', [
          'component' => 'mapping-form',
          'title' => "Edit Mapping Data: {$this->mappingGroup}",
          'template' => '#SimpleFormTemplate',
          'arguments' => [
            'group' => $this->mappingGroup,
            '_id' => $row->id,
            'prev_id' => $row->prev_id,
            'current_id' => $row->current_id,
            'prev_name' => $row->prev_name,
            'current_name' => $row->current_name
          ]
        ])
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
