<?php

namespace App\Livewire;

use App\Models\Sertificate;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Reactive;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SertificateTable extends PowerGridComponent
{
  public string $tableName = 'sertificate-table';

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
    return Sertificate::query()->where('nip', $this->nip);
  }

  public function relationSearch(): array
  {
    return [];
  }

  public function fields(): PowerGridFields
  {
    return PowerGrid::fields()
      ->add('id')
      ->add('type')
      ->add('name')
      ->add('status')
      ->add('status_template', function ($row) {
        return [
          'statusTemplate' => [
            'id' => $row->id,
            'status' => $row->status,
            'status_label' => $row->status_label,
            'verifyEvent' => 'verify-sertificate',
          ]
        ];
      })
      ->add('value')
      ->add('description')
      ->add('created_at');
  }

  public function columns(): array
  {
    return [
      Column::make('Jenis', 'type')
        ->sortable()
        ->searchable(),

      Column::make('Nama Sertifikat', 'name')
        ->sortable()
        ->searchable(),

      Column::make('Nilai', 'value')
        ->sortable()
        ->searchable(),

      Column::make('Status', 'status_template', 'status')
        ->sortable()
        ->template(),

      Column::make('Keterangan', 'description')
        ->sortable()
        ->searchable(),

      Column::action('Action')
    ];
  }

  public function filters(): array
  {
    return [];
  }

  public function actions(Sertificate $row): array
  {
    return [
      Button::add('edit')
        ->slot('Edit')
        ->id()
        ->class('btn btn-sm btn-info')
        ->dispatch('show-modal', [
          'component' => 'sertificate-form',
          'title' => 'Edit Sertifikat',
          'arguments' => [
            'nip' => $this->nip,
            'sertificate' => $row->id,
          ],
        ])
    ];
  }

  #[\Livewire\Attributes\On('verify-sertificate')]
  public function verifyRow($rowId): void
  {
    $this->dispatch(
      event: 'show-modal',
      component: 'verify-sertificate-form',
      title: 'Verifikasi Berkas Sertifikat',
      arguments: [
        'sertificate' => $rowId,
      ]
    );
  }

  public function changeStatus(Sertificate $sertificate, int $status, ?string $description = null)
  {
    try {
      $sertificate->update(compact('status', 'description'));

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
