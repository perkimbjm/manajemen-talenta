<?php

namespace App\Livewire;

use App\Models\Award;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Reactive;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class AwardTable extends PowerGridComponent
{
  public string $tableName = 'award-table';

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
    return Award::query()->where('nip', $this->nip);
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
      ->add('type')
      ->add('name')
      ->add('description')
      ->add('status_label_template', function ($row) {
        return [
          'statusTemplate' => [
            'id' => $row->id,
            'status' => $row->status,
            'status_label' => $row->status_label,
          ]
        ];
      })
      ->add('created_at');
  }

  public function columns(): array
  {
    return [
      Column::make('Jenis', 'type')
        ->sortable()
        ->searchable(),

      Column::make('Nama Penghargaan', 'name')
        ->sortable()
        ->searchable(),

      Column::make('Keterangan', 'description')
        ->searchable(),

      Column::make('Status', 'status_label_template', 'status')
        ->sortable()
        ->template(),

      Column::action('Action')
    ];
  }

  public function filters(): array
  {
    return [];
  }

  #[\Livewire\Attributes\On('verify-row')]
  public function verifyRow($rowId): void
  {
    $this->dispatch(
      event: 'show-modal',
      component: 'verify-award-form',
      title: 'Verifikasi Berkas Penghargaan',
      arguments: [
        'award' => $rowId,
      ]
    );
  }

  public function changeStatus(Award $award, int $status, ?string $description = null)
  {
    try {
      $award->update(compact('status', 'description'));

      $this->dispatch('page-refresh');
      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Status Penghargaan berhasil diubah',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Status Penghargaan gagal diubah',
        'message' => $th->getMessage(),
      ]);
    }
  }

  public function actions(Award $row): array
  {
    return [
      Button::add('edit')
        ->slot('Edit')
        ->id()
        ->class('btn btn-sm btn-info')
        ->dispatch('show-modal', [
          'component' => 'award-form',
          'title' => 'Edit Penghargaan',
          'arguments' => [
            'nip' => $this->nip,
            'award' => $row->id,
          ],
        ])
    ];
  }

  public function rowTemplates(): array
  {
    return [
      'statusTemplate' => (
        view('components.change-status-button')->render()
      ),
    ];
  }

  public function actionRules($row): array
  {
    return [
      // Hide button edit for ID 1
      Rule::button('edit')
        ->hide()
        ->when(fn($row) => $row->status !== 0)
    ];
  }
}
