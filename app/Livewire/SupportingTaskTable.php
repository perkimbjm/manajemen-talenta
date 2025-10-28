<?php

namespace App\Livewire;

use App\Models\SupportingTask;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Reactive;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SupportingTaskTable extends PowerGridComponent
{
  public string $tableName = 'supporting-task-table';

  #[Reactive]
  public ?string $nip = null;

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
    $query =  SupportingTask::query();

    $query->where('nip', $this->nip);

    return $query;
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
      ->add('scope')
      ->add('name')
      ->add('description')
      ->add('status')
      ->add('status_template', function ($row) {
        return [
          'statusTemplate' => [
            'id' => $row->id,
            'status' => $row->status,
            'status_label' => $row->status_label,
            'verifyEvent' => 'verify-supportingTask',
          ]
        ];
      })
      ->add('year')
      ->add('created_at');
  }

  public function columns(): array
  {
    $columns = [

      Column::make('Nama Organisasi', 'name')
        ->sortable()
        ->searchable(),

      Column::make('Ruang Lingkup', 'scope')
        ->sortable()
        ->searchable(),

      Column::make('Keterangan', 'description')
        ->searchable(),

      Column::make('Status', 'status_template', 'status')
        ->sortable()
        ->template(),

      Column::action('Action')
    ];

    return $columns;
  }

  public function filters(): array
  {
    return [];
  }

  #[\Livewire\Attributes\On('verify-supportingTask')]
  public function verifyRow($rowId): void
  {
    $this->dispatch(
      event: 'show-modal',
      component: 'verify-supportingTask-form',
      title: 'Verifikasi Berkas Sertifikat',
      arguments: [
        'supportingTask' => $rowId,
      ]
    );
  }

  public function changeStatus(SupportingTask $supportingTask, int $status, ?string $description = null)
  {
    try {
      $supportingTask->update(compact('status', 'description'));

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Status Sertifikat berhasil diubah',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Status Sertifikat gagal diubah',
        'message' => $th->getMessage(),
      ]);
    }
  }


  public function rowTemplates(): array
  {
    $user = request()->user();
    if ($user->hasRole('Super Admin')) {
      $template = view('components.change-status-button')->render();
    } else {
      $template = <<<'HTML'
        <span class="text-gray-900" x-text="templateContent.statusTemplate.status_label"></span>
      HTML;
    }

    return [
      'statusTemplate' => (
        $template
      ),
    ];
  }

  public function actions(SupportingTask $row): array
  {
    return [
      Button::add('edit')
        ->slot('Edit')
        ->id()
        ->class('btn btn-sm btn-info')
        ->dispatch('edit', ['rowId' => $row->id])
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
