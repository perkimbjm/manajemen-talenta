<?php

namespace App\Jobs;

use App\Events\TestNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestHorizonJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public function handle()
  {
    Log::info('TestHorizonJob executed!');

    TestNotification::dispatch();
  }


  public function tags(): array
  {
    return ['test'];
  }
}
