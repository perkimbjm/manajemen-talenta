<?php

namespace App\Console\Commands;

use App\Imports\SectorsImport;
use Illuminate\Console\Command;

class ImportSectors extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:sectors';

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
    $this->output->title('Starting import from rekap-per-rekening-belanja.xlsx');
    $file1 = public_path('imports/rekap-per-rekening-belanja.xlsx');
    (new SectorsImport)->withOutput($this->output)->import($file1);

    $this->output->title('Starting import from master-program-kegiatan-sub-kegiatan.xlsx');
    $file2 = public_path('imports/master-program-kegiatan-sub-kegiatan.xlsx');
    (new SectorsImport)->withOutput($this->output)->import($file2);

    $this->output->success('Import successful');
  }
}
