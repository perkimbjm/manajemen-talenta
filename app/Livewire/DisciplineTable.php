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

final class DisciplineTable extends PowerGridComponent
{
  public string $tableName = 'discipline-table';

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
    return AttendancePercentage::query()->where('nip', $this->nip);
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
      ->add('created_at');
  }

  public function columns(): array
  {
    return [

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
    return [];
  }
}
