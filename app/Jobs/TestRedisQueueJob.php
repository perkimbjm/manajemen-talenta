<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestRedisQueueJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public function __construct()
  {
    // You can pass any parameters you need for the job here.
  }

  public function handle()
  {
    Log::info('TestRedisQueueJob executed!');
  }

  public function tags(): array
  {
    return ['test'];
  }
}
