<?php

namespace App\Console\Commands;

use App\Models\Assessment;
use App\Models\TalentPoolBox;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\progress;

class FillBox extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'box:fill';

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
      $assessments = Assessment::all();
      $boxes = TalentPoolBox::all();

      progress(
        label: 'Filling Talent Pool Boxes',
        steps: $boxes,
        callback: function ($box) use ($assessments) {
          $current_assessments = $assessments->filter(fn($assessment) => $assessment->get_performance_value > $box->min_performance_value && $assessment->get_potential_value > $box->min_potential_value);

          Assessment::whereIn('id', $current_assessments->pluck('id'))->update([
            'box_id' => $box->id,
          ]);
        }
      );
    });
  }
}
