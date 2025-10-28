<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\info;

class PostBuildRun extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'post-build:run';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command to run after build is done';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    info("Nothing to run yet.");
  }
}
