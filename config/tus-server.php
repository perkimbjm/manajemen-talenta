<?php

return [

  /**
   * Redis connection parameters.
   */
  'redis' => [
    'host' => env('REDIS_HOST', '127.0.0.1'),
    'port' => env('REDIS_PORT', 6379),
    'database' => env('REDIS_DB', 0),
    'password' => env('REDIS_PASSWORD'),
  ],

  /**
   * File cache configs.
   */
  'file' => [
    'dir' => storage_path('app/tus.cache'),
    'name' => 'tus_php.server.cache',
  ],
];
