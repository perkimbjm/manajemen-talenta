<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  Route::post('files/move', [FileController::class, 'move'])->name('files.move');
  Route::post('files/copy', [FileController::class, 'copy'])->name('files.copy');

  Route::get('files/download/{disk}/{path}', [FileController::class, 'download'])
    ->where('path', '.*')->name('files.download');

  Route::get('files/{disk}/{path}', [FileController::class, 'show'])
    ->where('path', '.*')->name('files.show');

  Route::get('/upload/{file_id}', [FileController::class, 'showById'])->middleware('auth');
});
