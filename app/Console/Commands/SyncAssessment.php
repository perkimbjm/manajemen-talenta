<?php

namespace App\Console\Commands;

use App\Models\SkpReport;
use App\Models\Assessment;
use App\Models\TalentPoolBox;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\progress;

class SyncAssessment extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'sync:assessment {nip?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    DB::transaction(function () {
      $nip = $this->argument('nip');
      if ($nip) {
        $this->updateSkpValue([$nip]);
      } else {
        $this->updateSkpValue();
      }

      $boxs = TalentPoolBox::orderByDesc('order')->get();

      $query = Assessment::query();
      if ($nip) {
        $query->where('nip', $nip)->get();
      }
      $assessments = $query->get();

      progress(
        label: 'Syncing Assessment',
        steps: $assessments,
        callback: fn($assessment) => $this->syncAssessment($assessment, $boxs)
      );
    });
  }

  public function updateSkpValue($nips = null)
  {
    $evaluations = Assessment::getSpecificLabels();

    $year = 2024;

    $error_nips = collect([]);

    $query = SkpReport::query();
    if (is_array($nips)) {
      $query->whereIn('nip', $nips);
    }

    $dataset = $query->get();

    progress(
      label: 'Updating Assessment SKP',
      steps: $dataset,
      callback: function ($data) use ($year, $evaluations, $error_nips) {
        $evaluation = $evaluations
          ->filter(fn($eval) => strtolower($eval['name']) === strtolower($data['final_result']))->first();

        if (!$evaluation) {
          return;
        }

        try {
          Assessment::updateOrCreate([
            'nip' => $data['nip'],
            'year' => $year,
          ], [
            'specific' => $evaluation['value'],
          ]);
        } catch (\Illuminate\Database\QueryException $e) {
          $error_nips->push($data['nip']);
          return;
        }
      }
    );
  }

  public function syncAssessment(Assessment $assessment, Collection $boxs)
  {
    $box = $boxs->filter(fn($box) => $assessment->get_performance_value > $box->min_performance_value && $assessment->get_potential_value > $box->min_potential_value)->first();

    $assessment->update([
      'performance_value' => $assessment->get_performance_value,
      'potential_value' => $assessment->get_potential_value,
      'box_id' => $box?->id,
    ]);
  }
}
