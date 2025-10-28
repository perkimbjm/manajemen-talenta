<?php

namespace App\Livewire;

use App\Models\Unit;

use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class UnitTable extends PowerGridComponent
{
  use WithExport;

  public string $tableName = 'UnitTable';

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
    return Unit::with('sectors')->orderByDesc('is_root');
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
      ->add('type')
      ->add('name')
      ->add('sector_list', function ($unit) {
        return [
          'listTemplate' => [
            'id' => $unit->id,
            'items' => $unit->sectors->toArray()
          ]
        ];
      })
      ->add('description')
      ->add('created_at');
  }

  public function rowTemplates(): array
  {
    return [
      'listTemplate' => (
        <<<'HTML'
          <ul x-data="{
            id: templateContent.listTemplate.id,
            items: templateContent.listTemplate.items
          }">
            <template x-for="item in items">
              <li>
                <span x-text="item.code" class="font-mono"></span>
                <span x-text="item.name"></span>
              </li>
            </template>
          </ul>
        HTML
      ),
    ];
  }

  public function columns(): array
  {
    return [
      Column::make('Kode', 'code')
        ->sortable()
        ->searchable(),

      Column::make('Jenis', 'type')
        ->sortable()
        ->searchable(),

      Column::make('Nama', 'name')
        ->sortable()
        ->searchable(),

      Column::make('Akronim', 'acronym')
        ->sortable()
        ->searchable(),

      Column::make('Bidang Urusan', 'sector_list')
        ->template(),

      Column::action('Action')
    ];
  }

  public function filters(): array
  {
    return [];
  }

  public function actions(Unit $row): array
  {
    return [
      Button::add('edit')
        ->slot(
          <<<'HTML'
           <span class="i-mdi-edit w-4 h-4 text-white"></span>
           HTML
        )
        ->id()
        ->class('btn btn-sm p-1 btn-info h-auto min-h-0')
        ->dispatch('notifications', ['message' => $row->id]),
      Button::add('delete')
        ->slot(
          <<<'HTML'
           <span class="i-mdi-delete w-4 h-4 text-white"></span>
           HTML
        )
        ->id()
        ->class('btn btn-sm p-1 btn-warning h-auto min-h-0')
        ->dispatch('delete-unit', [
          'id' => $row->id
        ]),
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
