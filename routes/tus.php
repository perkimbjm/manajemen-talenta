<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\TusUploadConroller;
use function Illuminate\Filesystem\join_paths;

Route::post('/tus/complete', [TusUploadConroller::class, 'complete'])->name('tus.complete');

Route::any('/tus/{any?}', function (Request $request) {
  $server = app('tus-server');

  if ($request->method() === 'POST') {
    $base_dir = 'app';
    $default_dir = 'upload';

    $uploadDir = $request->header('Tus-Upload-Dir', $default_dir);
    $dirPath = storage_path(join_paths($base_dir, $uploadDir));

    if (!Storage::directoryExists($dirPath)) {
      Storage::makeDirectory($uploadDir, 0777, true);
    }

    $server->setUploadDir($dirPath);
  }

  return $server->serve();
})->where('any', '.*');
