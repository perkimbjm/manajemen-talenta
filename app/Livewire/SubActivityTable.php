<?php

namespace App\Livewire;

use App\Models\SubActivity;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class SubActivityTable extends PowerGridComponent
{
  use WithExport;

  public string $sortField = 'code';

  public string $sortDirection = 'asc';

  public function setUp(): array
  {
    $this->showCheckBox();

    return [
      Exportable::make('export')
        ->striped()
        ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
      Header::make()->showSearchInput(),
      Footer::make()
        ->showPerPage()
        ->showRecordCount(),
    ];
  }

  public function datasource(): Builder
  {
    return SubActivity::query();
  }

  public function relationSearch(): array
  {
    return [];
  }

  public function fields(): PowerGridFields
  {
    return PowerGrid::fields()
      ->add('id')
      ->add('sector_code')
      ->add('activity_code')
      ->add('code')
      ->add('sequence')
      ->add('name')
      ->add('description')
      ->add('outcome')
      ->add('indicator')
      ->add('type')
      ->add('piece')
      ->add('tags')
      ->add('implementer')
      ->add('spm')
      ->add('created_at');
  }

  public function columns(): array
  {
    return [
      Column::make('Code', 'code')
        ->sortable()
        ->contentClasses('font-mono')
        ->searchable(),

      Column::make('Name', 'name')
        ->sortable()
        ->contentClasses('w-96 block')
        ->searchable(),

      Column::make('Description', 'description')
        ->sortable()
        ->contentClasses('line-clamp-4')
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

  public function actions(SubActivity $row): array
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
