<?php

namespace App\Livewire;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class EmployeeTable extends PowerGridComponent
{
  use WithExport;

  public string $tableName = 'EmployeeTable';

  public function setUp(): array
  {
    $this->showCheckBox();

    return [
      Powergrid::exportable('export')
        ->striped()
        ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
      Powergrid::header()->showSearchInput(),
      Powergrid::footer()
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
        ->dispatch('create-' . $this->tableName, []),
    ];
  }

  public function datasource(): Builder
  {
    return Employee::query();
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
      ->add('name')
      ->add('position_name')
      ->add('created_at');
  }

  public function columns(): array
  {
    return [
      Column::make('Id', 'id')
        ->sortable()
        ->searchable()
        ->hidden(),

      Column::make('NIP', 'nip')
        ->sortable()
        ->searchable(),

      Column::make('Nama', 'name')
        ->sortable()
        ->searchable(),

      Column::make('Jabatan', 'position_name')
        ->sortable()
        ->searchable(),

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

  public function actions(Employee $row): array
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
        ->dispatch('delete-employee', [
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
