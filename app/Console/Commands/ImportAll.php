<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportAll extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:all';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Run All Import Commands';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->call('import:affairs');
    $this->call('import:funds');
    $this->call('import:expenses');
    $this->call('import:sectors');
    $this->call('import:units');
    $this->call('import:programs');
    $this->call('import:activities');
    $this->call('import:sub-activities');
    $this->call('import:recaps');
  }
}
