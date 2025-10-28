<?php

use App\Livewire\MappingPage;
use App\Livewire\TestComponents;
use App\Livewire\TestLongRunningJob;
use App\Livewire\TestModalPage;
use Illuminate\Support\Facades\Route;

Route::prefix('test')
  ->name('test.')
  ->group(function () {
    Route::middleware(['auth'])->group(function () {
      Route::get('modal', TestModalPage::class)->name('modal');
      Route::get('components', TestComponents::class)->name('components');

      Route::get('long-running-job', TestLongRunningJob::class)->name('long-running-job');

      Route::get('mappings', MappingPage::class)->name('mappings');
    });
  });
