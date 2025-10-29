<?php

use App\Livewire\Pages\RolePage;
use App\Livewire\Pages\UserPage;
use Illuminate\Support\Facades\Route;

Route::prefix('settings')->name('settings.')->middleware(['auth', 'route.permission'])->group(function () {
  Route::get('/users', UserPage::class)->name('users');
  Route::get('/roles', RolePage::class)->name('roles');
});
