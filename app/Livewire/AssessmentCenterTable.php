<?php

namespace App\Livewire;

use App\Models\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class AssessmentCenterTable extends PowerGridComponent
{
  use WithExport;

  public string $tableName = 'AssessmentCenterTable';

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
    return Employee::with(['assessment']);
  }

  public function relationSearch(): array
  {
    return [];
  }

  public function fields(): PowerGridFields
  {
    return PowerGrid::fields()
      ->add('id')
      ->add('name')
      ->add('nip')
      ->add('position_name')
      ->add('potential', function ($row) {
        return @$row->assessment->potential;
      })
      ->add('competency', function ($row) {
        return @$row->assessment->competency;
      })
      ->add('created_at');
  }

  public function columns(): array
  {
    return [
      Column::make('Nip', 'nip')
        ->sortable()
        ->searchable(),

      Column::make('Nama', 'name')
        ->sortable()
        ->searchable(),

      Column::make('Jabatan', 'position_name')
        ->sortable()
        ->searchable(),

      Column::make('Jenis Jabatan', 'position_type')
        ->sortable()
        ->searchable(),
      Column::make('Eselon', 'echelon')
        ->sortable()
        ->searchable(),

      Column::make('Potensi', 'potential'),
      Column::make('Kompetensi', 'competency'),

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
      Button::add('sync')
        ->slot('Sync')
        ->id()
        ->class('btn btn-sm')
        ->dispatch('sync', ['nip' => $row->nip])
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
