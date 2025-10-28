<?php

namespace App\Livewire;

use App\Models\Experience;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Reactive;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ExperienceTable extends PowerGridComponent
{
  public string $tableName = 'experience-table';

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
    return Experience::query()->where('nip', $this->nip);
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
            'verifyEvent' => 'verify-experience',
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

      Column::make('Nama Pengalaman', 'name')
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
  public function actions(Experience $row): array
  {
    return [
      Button::add('edit')
        ->slot('Edit')
        ->id()
        ->class('btn btn-sm btn-info')
        ->dispatch('show-modal', [
          'component' => 'experience-form',
          'title' => 'Edit Pengalaman Organisasi',
          'arguments' => [
            'nip' => $this->nip,
            'experience' => $row->id,
          ],
        ])
    ];
  }

  public function changeStatus(Experience $experience, int $status, ?string $description = null)
  {
    try {
      $experience->update(compact('status', 'description'));

      $this->dispatch('notifications', [
        'type' => 'success',
        'message' => 'Status Pengalaman Organisasi berhasil diubah',
      ]);
    } catch (\Throwable $th) {
      $this->dispatch('notifications', [
        'type' => 'danger',
        'message' => 'Status Pengalaman Organisasi gagal diubah',
        'message' => $th->getMessage(),
      ]);
    }
  }

  #[\Livewire\Attributes\On('verify-experience')]
  public function verifyRow($rowId): void
  {
    $this->dispatch(
      event: 'show-modal',
      component: 'verify-experience-form',
      title: 'Verifikasi Berkas Pengalaman',
      arguments: [
        'experience' => $rowId,
      ]
    );
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
