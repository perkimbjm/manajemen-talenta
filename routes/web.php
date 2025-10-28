<?php

use App\Livewire\Counter;
use App\Livewire\Pages\Landing;
use App\Livewire\Pages\UserPage;
use Illuminate\Support\Facades\Route;

Route::get('/', Landing::class)->name('landing');

Route::view('dashboard', 'dashboard')
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

Route::view('profile', 'profile')
  ->middleware(['auth'])
  ->name('profile');

require __DIR__ . '/test.php';
require __DIR__ . '/feature.php';
require __DIR__ . '/tus.php';
require __DIR__ . '/file.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/assist.php';
require __DIR__ . '/setting.php';
require __DIR__ . '/master.php';
require __DIR__ . '/unit.php';
require __DIR__ . '/employee.php';
