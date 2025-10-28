<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;

use function Laravel\Prompts\progress;

class RandomizeAssessments extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'randomize:assessments';

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
    $year = 2024;
    $employees = Employee::with('assessment')->get();
    progress(
      label: 'Randomizing assessments',
      steps: $employees,
      callback: function ($employee) use ($year) {
        // Generate a random float between $min and $max with 2 decimal precision
        $innovation = round(mt_rand(0 * 100, 15 * 100) / 100, 2);
        $organizational = round(mt_rand(0 * 100, 15 * 100) / 100, 2);
        $extra = round(mt_rand(0 * 100, 15 * 100) / 100, 2);
        $potential = round(mt_rand(0 * 100, 20 * 100) / 100, 2);
        $competency = round(mt_rand(0 * 100, 30 * 100) / 100, 2);
        $track_record = round(mt_rand(0 * 100, 35 * 100) / 100, 2);
        $other = round(mt_rand(0 * 100, 15 * 100) / 100, 2);

        $employee->assessment()->updateOrCreate([
          'year' => $year,
        ], compact([
          'innovation',
          'organizational',
          'extra',
          'potential',
          'competency',
          'track_record',
          'other'
        ]));
      }
    );
  }
}
