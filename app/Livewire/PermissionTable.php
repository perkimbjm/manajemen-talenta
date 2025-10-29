<?php

namespace App\Livewire;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class PermissionTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'PermissionTable';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Permission::query();
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
            ->add('guard_name')
            ->add('routes_count', fn (Permission $permission) => count($permission->routes ?? []))
            ->add('created_at')
            ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Nama Permission', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Guard', 'guard_name')
                ->sortable()
                ->searchable(),

            Column::make('Jumlah Route', 'routes_count'),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::make('Updated at', 'updated_at')
                ->sortable()
                ->searchable(),

            Column::action('Action'),
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('delete-permission')]
    public function removePermission($rowId): void
    {
        $this->js("removePermission('$rowId')");
    }

    public function actions(Permission $row): array
    {
        return [
            Button::add('manage-routes')
                ->slot(
                    <<<'HTML'
                    <span class="i-mdi-map-marker-path w-4 h-4 text-white"></span>
                    HTML
                )
                ->id()
                ->class('btn btn-sm btn-accent py-1 px-2')
                ->dispatch('show-modal', [
                    'component' => 'permission-route-form',
                    'title' => 'Atur Route Permission',
                    'arguments' => [
                        'permission' => $row->id,
                    ]
                ]),
            Button::add('edit')
                ->slot(
                    <<<'HTML'
                    <span class="i-mdi-edit w-4 h-4 text-white"></span>
                    HTML
                )
                ->id()
                ->class('btn btn-sm btn-info py-1 px-2')
                ->dispatch('show-modal', [
                    'component' => 'permission-form',
                    'title' => 'Edit Permission',
                    'arguments' => [
                        'permission' => $row->id,
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
                ->dispatch('delete-permission', ['rowId' => $row->id]),
        ];
    }
}
