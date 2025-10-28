<?php

namespace App\Livewire;

use App\Models\AttendancePercentage;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AttendanceTable extends PowerGridComponent
{
  public string $tableName = 'attendance-table-wnirg5-table';

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
    return AttendancePercentage::query()
      ->select([
        'attendance_percentages.*',
        'employees.name as employee_name',
        'employees.position_name as employee_position'
      ])
      ->join('employees', function ($join) {
        $join->on('attendance_percentages.nip', '=', 'employees.nip');
      });
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
      ->add('year')
      ->add('januari')
      ->add('februari')
      ->add('maret')
      ->add('april')
      ->add('mei')
      ->add('juni')
      ->add('juli')
      ->add('agustus')
      ->add('september')
      ->add('oktober')
      ->add('november')
      ->add('desember')
      ->add('summary', function ($row) {
        return $row->summary;
      })
      ->add('employee_name')
      ->add('created_at');
  }

  public function columns(): array
  {
    return [
      Column::make('Nip', 'nip')
        ->sortable()
        ->searchable(),

      Column::make('Nama', 'employee_name', 'employees.name')
        ->sortable()
        ->searchable(),

      Column::make('Jabatan', 'employee_position', 'employees.position_name')
        ->sortable()
        ->searchable(),

      Column::make('Tahun', 'year')
        ->sortable()
        ->searchable(),

      Column::make('Januari', 'januari')
        ->sortable()
        ->searchable(),

      Column::make('Februari', 'februari')
        ->sortable()
        ->searchable(),

      Column::make('Maret', 'maret')
        ->sortable()
        ->searchable(),

      Column::make('April', 'april')
        ->sortable()
        ->searchable(),

      Column::make('Mei', 'mei')
        ->sortable()
        ->searchable(),

      Column::make('Juni', 'juni')
        ->sortable()
        ->searchable(),

      Column::make('Juli', 'juli')
        ->sortable()
        ->searchable(),

      Column::make('Agustus', 'agustus')
        ->sortable()
        ->searchable(),

      Column::make('September', 'september')
        ->sortable()
        ->searchable(),

      Column::make('Oktober', 'oktober')
        ->sortable()
        ->searchable(),

      Column::make('November', 'november')
        ->sortable()
        ->searchable(),

      Column::make('Desember', 'desember')
        ->sortable()
        ->searchable(),

      Column::make('Total Persentase', 'summary'),

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

  public function actions(AttendancePercentage $row): array
  {
    return [
      Button::add('edit')
        ->slot('Edit')
        ->id()
        ->class('btn btn-sm')
        ->dispatch('edit', ['rowId' => $row->id])
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
