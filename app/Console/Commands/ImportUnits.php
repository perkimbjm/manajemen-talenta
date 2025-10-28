<?php

namespace App\Console\Commands;

use App\Imports\UnitsImport;
use App\Models\Unit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\progress;

class ImportUnits extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:units';

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
    $this->output->title('Starting import');
    $file = public_path('imports/rekap-per-rekening-belanja.xlsx');
    (new UnitsImport)->withOutput($this->output)->import($file);
    $this->output->success('Import successful');
    $this->output->info('Updating unit acronym');

    $url = 'https://sipejabat-v2.banjarmasinkota.go.id/api/units?is_root=1';

    $response = Http::get($url)->json();

    $data = $response['data'];
    progress(
      label: 'Updating unit acronym',
      steps: $data,
      callback: function ($unit) {
        Unit::where('code', $unit['code'])->update(['acronym' => $unit['acronym']]);
      },
    );

    $this->output->success('Updating unit acronym successful');
  }
}
