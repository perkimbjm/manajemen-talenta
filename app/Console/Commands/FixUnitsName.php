<?php

namespace App\Console\Commands;

use App\Models\Unit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\progress;

class FixUnitsName extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fix:units-name';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Fixing units name';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $units = Unit::all();

    progress(
      label: 'Fixing units name',
      steps: $units,
      callback: function ($unit) {
        $unit->update([
          'name' => str($unit->name)->squish(),
        ]);
      }
    );
  }
}
