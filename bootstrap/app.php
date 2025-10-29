<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    channels: __DIR__ . '/../routes/channels.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $proxies = explode(',', env('APP_TRUST_PROXIES', '*'));
    $middleware->trustProxies(
      at: $proxies,
      headers: Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB
    );

    $middleware->alias([
      'route.permission' => \App\Http\Middleware\EnsureRouteHasPermission::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();
