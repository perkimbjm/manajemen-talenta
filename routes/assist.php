<?php

use App\Livewire\Pages\AssistPage;
use App\Livewire\Pages\RecapPage;
use Illuminate\Support\Facades\Route;

Route::prefix('assist')
  ->name('assist.')
  ->middleware(['auth'])
  ->group(function () {
    Route::get('rka', AssistPage::class)->name('rka');
    Route::get('recaps', RecapPage::class)->name('recaps');
  });
