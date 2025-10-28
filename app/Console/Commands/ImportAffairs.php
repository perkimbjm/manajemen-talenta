<?php

namespace App\Console\Commands;

use App\Imports\AffairsImport;
use Illuminate\Console\Command;

class ImportAffairs extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:affairs';

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
    (new AffairsImport)->withOutput($this->output)->import($file);
    $this->output->success('Import successful');
  }
}
