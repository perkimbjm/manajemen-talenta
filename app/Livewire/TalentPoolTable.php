<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Assessment;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

#[Lazy()]
final class TalentPoolTable extends PowerGridComponent
{
  public string $tableName = 'talent-pool-table';
  public string $sortField = 'box_id';
  public string $sortDirection = 'desc';

  public ?array $nips = null;

  #[On('filter_nips')]
  public function filterNIPS(?array $nips = null): void
  {
    $this->resetPage('page');
    $this->nips = $nips;

    if (is_array($nips)) {
      $this->dispatch('notifications', [
        'type' => 'info',
        'message' => 'Terpilih ' . count($nips) . ' Pegawai',
      ]);
    } else {
      $this->dispatch('notifications', [
        'type' => 'info',
        'message' => 'Talent pool direset',
      ]);
    }
  }

  public function datasource()
  {
    $query = Assessment::query()
      ->select('assessments.*', 'employees.name')
      ->join('employees', function ($assessments) {
        $assessments->on('assessments.nip', '=', 'employees.nip');
      })
      ->whereNotNull('box_id');

    if (is_array($this->nips)) {
      $query->whereIn('assessments.nip', $this->nips);
    }

    return $query;
  }

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

  public function fields(): PowerGridFields
  {
    return PowerGrid::fields()
      ->add('id')
      ->add('box_id')
      ->add('name')
      ->add('potential_value')
      ->add('potential_label', fn($assessment) => $assessment->potential_label)
      ->add('performance_value')
      ->add('performance_label', fn($assessment) => $assessment->performance_label)
      ->add('nip', fn($row) => ([
        'anchorNIP' => [
          'nip' => $row->nip
        ]
      ]));
  }

  public function rowTemplates(): array
  {
    return [
      'anchorNIP' => (
        <<<'HTML'
          <a x-data="{
            nip: templateContent.anchorNIP.nip
          }" x-bind:href="`/features/profil-talenta-asn?nip=${nip}&is_redirected=true`" x-text="nip" class="link text-primary underline" wire:navigate>
          </a>
        HTML
      ),
    ];
  }

  public function columns(): array
  {
    return [
      Column::make('NIP', 'nip')
        ->searchable()
        ->sortable()
        ->template(),

      Column::make('Nama', 'name')
        ->searchable()
        ->sortable(),

      Column::make('Kotak Talenta', 'box_id')
        ->searchable()
        ->sortable(),

      Column::make('Nilai Kinerja', 'performance_value')->sortable(),

      Column::make('KK', 'performance_label'),

      Column::make('Nilai Potensial', 'potential_value')->sortable(),

      Column::make('KP', 'potential_label'),

      // Column::action('Action')
    ];
  }
}
