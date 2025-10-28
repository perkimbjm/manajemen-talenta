<?php

namespace App\Providers;

use function Illuminate\Filesystem\join_paths;
use function Laravel\Prompts\info;

use App\Http\Middleware\TusResponse;
use TusPhp\Tus\Server as TusServer;
use TusPhp\Config as TusConfig;

use function Laravel\Prompts\warning;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use TusPhp\Events\TusEvent;

class TusServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    $this->app->singleton('tus-server', function ($app) {
      TusConfig::set(base_path('config/tus-server.php'));
      $server = new TusServer('redis');

      $base_dir = 'app';
      $default_dir = 'temp';

      $server
        ->setUploadDir(storage_path(join_paths($base_dir, $default_dir))) // uploads dir.
        ->setApiPath('/tus'); // tus server endpoint.

      $server->middleware()->add(TusResponse::class);

      return $server;
    });
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
