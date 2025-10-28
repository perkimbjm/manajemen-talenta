<?php

namespace App\Livewire;

use App\Models\SkpReport;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SkpTable extends PowerGridComponent
{
  public string $tableName = 'skp-table-hgny1e-table';

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
    return SkpReport::query()
      ->select(['skp_reports.*', 'employees.name as employee_name'])
      ->join('employees', function ($join) {
        $join->on('skp_reports.nip', '=', 'employees.nip');
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
      ->add('type_id')
      ->add('nip')
      ->add('year')
      ->add('start_period_formatted', fn(SkpReport $model) => Carbon::parse($model->start_period)->format('d/m/Y'))
      ->add('end_period_formatted', fn(SkpReport $model) => Carbon::parse($model->end_period)->format('d/m/Y'))
      ->add('work_behavior')
      ->add('work_result')
      ->add('final_result')
      ->add('skp_unor_id')
      ->add('skp_unor')
      ->add('skp_unor_induk')
      ->add('skp_jabatan')
      ->add('skp_jenis_jabatan')
      ->add('is_skp_plt_plh_pjb')
      ->add('golru')
      ->add('rated_at')
      ->add('rated_at_formatted', fn(SkpReport $model) => Carbon::parse($model->rated_at)->format('d/m/Y H:i:s'))
      ->add('employee_name')
      ->add('created_at');
  }

  public function columns(): array
  {
    return [
      Column::make('NIP', 'nip')
        ->sortable()
        ->searchable(),

      Column::make('Nama', 'employee_name', 'employees.name')
        ->sortable()
        ->searchable(),

      Column::make('Jabatan', 'skp_jabatan')
        ->sortable()
        ->searchable(),

      Column::make('Unor', 'skp_unor')
        ->sortable()
        ->searchable(),

      Column::make('Tahun SKP', 'year')
        ->sortable()
        ->searchable(),

      Column::make('Periode Awal', 'start_period_formatted', 'start_period')
        ->sortable(),

      Column::make('Periode Akhir', 'end_period_formatted', 'end_period')
        ->sortable(),

      Column::make('Perilaku Kerja', 'work_behavior')
        ->sortable()
        ->searchable(),

      Column::make('Hasil Kerja', 'work_result')
        ->sortable()
        ->searchable(),

      Column::make('Hasil Akhir', 'final_result')
        ->sortable()
        ->searchable(),

      Column::make('MENJABAT PLT/PLH/PJB', 'is_skp_plt_plh_pjb')
        ->sortable()
        ->searchable(),

      Column::make('Waktu Dinilai', 'rated_at_formatted', 'rated_at')
        ->sortable(),

      Column::action('Action')
    ];
  }

  public function filters(): array
  {
    return [
      Filter::datepicker('start_period'),
      Filter::datepicker('end_period'),
    ];
  }

  #[\Livewire\Attributes\On('edit')]
  public function edit($rowId): void
  {
    $this->js('alert(' . $rowId . ')');
  }

  public function actions(SkpReport $row): array
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
