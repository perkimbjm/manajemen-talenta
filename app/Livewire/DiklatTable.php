<?php

namespace App\Livewire;

use App\Models\Diklat;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Reactive;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class DiklatTable extends PowerGridComponent
{
  public string $tableName = 'diklat-table';

  #[Reactive]
  public ?string $nip = null;

  public function setUp(): array
  {
    $this->showCheckBox();

    return [
      PowerGrid::header(),
      PowerGrid::footer()
        ->showPerPage()
        ->showRecordCount(),
    ];
  }

  public function datasource(): Builder
  {
    return Diklat::query()
      ->where('nip', $this->nip);
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
      ->add('code')
      ->add('type')
      ->add('rank')
      ->add('year')
      ->add('date_formatted', fn(Diklat $model) => Carbon::parse($model->date)->format('d/m/Y'))
      ->add('name')
      ->add('letter_number')
      ->add('description')
      ->add('status', fn($row) => $row->status_label)
      ->add('created_at');
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

      Column::make('Tingkat', 'rank')
        ->sortable()
        ->searchable(),

      Column::make('Tahun', 'year')
        ->sortable()
        ->searchable(),

      Column::make('Nama Diklat', 'name')
        ->sortable()
        ->searchable(),

      Column::make('Nomor', 'letter_number')
        ->sortable()
        ->searchable(),

      Column::make('Status', 'status')
        ->sortable()
        ->searchable(),

      Column::action('Action')
    ];
  }

  public function filters(): array
  {
    return [
      Filter::datepicker('date'),
    ];
  }

  #[\Livewire\Attributes\On('edit')]
  public function edit($rowId): void
  {
    $this->js('alert(' . $rowId . ')');
  }

  public function actions(Diklat $row): array
  {
    return [
      Button::add('edit')
        ->slot('Edit')
        ->id()
        ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
        ->dispatch('edit', ['rowId' => $row->id])
    ];
  }

  #[\Livewire\Attributes\On('syncDiklat')]
  public function syncDiklatFromSIMASN(): void
  {
    try {
      $employee = Employee::where('nip', $this->nip)->first();
      $employee->syncDiklatFromSimASN();

      $this->dispatch('page-refresh');
      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Berhasil menyingkronkan data',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Gagal menyingkronkan data',
        'description' => $th->getMessage(),
      ]);
    }
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
