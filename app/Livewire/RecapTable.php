<?php

namespace App\Livewire;

use App\Models\Recap;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class RecapTable extends PowerGridComponent
{
  use WithExport;

  public function setUp(): array
  {
    $this->showCheckBox();

    return [
      Exportable::make('export')
        ->striped()
        ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
      Header::make()->showSearchInput(),
      Footer::make()
        ->showPerPage(perPage: 20)
        ->showRecordCount(),
    ];
  }

  public function datasource(): Builder
  {
    return Recap::query()
      ->join('affairs', function ($affairs) {
        $affairs->on('recaps.affair_id', '=', 'affairs.id');
      })
      ->join('sectors', function ($sectors) {
        $sectors->on('recaps.sector_code', '=', 'sectors.code');
      })
      ->join('units', function ($units) {
        $units->on('recaps.unit_code', '=', 'units.code');
      })
      ->join('programs', function ($programs) {
        $programs
          ->on('recaps.program_code', '=', 'programs.code')
          ->on('recaps.zone_id', '=', 'programs.zone_id');
      })
      ->join('activities', function ($activities) {
        $activities->on('recaps.activity_code', '=', 'activities.code');
      })
      ->join('sub_activities', function ($sub_activities) {
        $sub_activities->on('recaps.sub_activity_code', '=', 'sub_activities.code');
      })
      ->join('funds', function ($funds) {
        $funds->on('recaps.fund_code', '=', 'funds.code');
      })
      ->join('expenses', function ($expenses) {
        $expenses->on('recaps.expense_code', '=', 'expenses.code');
      })
      ->select([
        'recaps.*',
        'affairs.name as affair_name',
        'sectors.name as sector_name',
        'units.name as unit_name',
        'programs.name as program_name',
        'activities.name as activity_name',
        'sub_activities.name as sub_activity_name',
        'funds.name as fund_name',
        'expenses.name as expense_name',
      ]);
  }

  public function relationSearch(): array
  {
    return [];
  }

  public function fields(): PowerGridFields
  {
    return PowerGrid::fields()
      ->add('id')
      ->add('year')
      ->add('affair', function ($row) {
        return "<div class='flex gap-2 pt-1'><p class='font-mono text-sm leading-[18px]'>{$row->affair_id}</p><p class='w-80 whitespace-normal'>{$row->affair_name}</p></div>";
      })
      ->add('affair_name')
      ->add('sector_code')
      ->add('sector_name')
      ->add('unit_code')
      ->add('unit_name')
      ->add('program_code')
      ->add('program_name')
      ->add('activity_code')
      ->add('activity_name')
      ->add('sub_activity_code')
      ->add('sub_activity_name')
      ->add('fund_code')
      ->add('fund_name')
      ->add('expense_code')
      ->add('expense_name')
      ->add('budget')
      ->add('sequence')
      ->add('created_at')
      ->add('index', function ($row, $index) {
        return $index + 1 + (($this->paginators['page'] - 1) * $row->getPerPage());
      });
  }

  public function columns(): array
  {
    return [
      Column::make('NO', 'index'),
      Column::make('TAHUN', 'year')
        ->sortable()
        ->searchable(),

      Column::make('URUSAN', 'affair', 'affairs.id')
        ->sortable(),

      Column::make('BIDANG URUSAN', 'sector_name', 'sectors.name')
        ->sortable()
        ->searchable(),

      Column::make('SKPD', 'unit_name', 'units.name')
        ->sortable()
        ->searchable(),

      Column::make('Program', 'program_name', 'programs.name')
        ->sortable()
        ->searchable(),

      Column::make('Kegiatan', 'activity_name',   'activities.name')
        ->sortable()
        ->searchable(),

      Column::make('Sub Kegiatan', 'sub_activity_name', 'sub_activities.name')
        ->sortable()
        ->searchable(),

      Column::make('Sumber Dana', 'fund_name', 'funds.name')
        ->sortable()
        ->searchable(),

      Column::make('Rekening', 'expense_name', 'expenses.name')
        ->sortable()
        ->searchable(),

      Column::make('PAGU', 'budget')
        ->sortable()
        ->searchable(),

      Column::make('Created at', 'created_at_formatted', 'created_at')
        ->sortable(),

      Column::make('Created at', 'created_at')
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
    $this->js('alert(`' . $rowId . '`)');
  }

  public function actions(Recap $row): array
  {
    return [
      Button::add('edit')
        ->slot('Edit')
        ->id()
        ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
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
