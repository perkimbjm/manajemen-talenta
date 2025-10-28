<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

final class UserTable extends PowerGridComponent
{
  use WithExport;

  public string $tableName = 'UserTable';

  public function setUp(): array
  {
    $this->showCheckBox();

    return [
      PowerGrid::exportable('export')
        ->striped()
        ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
      Powergrid::header()->showSearchInput(),
      Powergrid::footer()
        ->showPerPage()
        ->showRecordCount(),
    ];
  }

  public function datasource(): Builder
  {
    return User::query();
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
      ->add('created_at')
      ->add('updated_at');
  }

  public function columns(): array
  {
    return [
      Column::make('Username', 'name')
        ->sortable()
        ->searchable(),

      Column::make('Created at', 'created_at')
        ->sortable()
        ->searchable(),

      Column::make('Updated at', 'updated_at')
        ->sortable()
        ->searchable(),

      Column::action('Action')
    ];
  }

  public function filters(): array
  {
    return [];
  }

  #[\Livewire\Attributes\On('delete')]
  public function removeUser($rowId): void
  {
    $this->js("removeUser('$rowId')");
  }

  public function actions(User $row): array
  {
    return [
      Button::add('edit')
        ->slot(
          <<<'HTML'
          <span class="i-mdi-edit w-4 h-4 text-white"></span>
          HTML
        )
        ->id()
        ->class('btn btn-sm btn-info py-1 px-2')
        ->dispatch('show-modal', [
          'component' => 'user-form',
          'title' => 'Edit User',
          'arguments' => [
            'user' => $row->id,
          ]
        ]),
      Button::add('delete')
        ->slot(
          <<<'HTML'
          <span class="i-mdi-delete w-4 h-4"></span>
          HTML
        )
        ->id()
        ->class('btn btn-sm btn-warning py-1 px-2')
        ->dispatch('delete', ['rowId' => $row->id])
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
