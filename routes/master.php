<?php

use App\Livewire\AttendancePage;
use App\Livewire\EmployeePage;
use App\Livewire\Pages\FundPage;
use App\Livewire\Pages\UnitPage;
use App\Livewire\Pages\AffairPage;
use App\Livewire\Pages\SectorPage;
use App\Livewire\Pages\ExpensePage;
use App\Livewire\Pages\ProgramPage;
use App\Livewire\Pages\ActivityPage;
use Illuminate\Support\Facades\Route;
use App\Livewire\SkpPage;

Route::prefix('master')->name('master.')->middleware(['auth'])->group(function () {
  Route::get('sectors', SectorPage::class)->name('sectors');
  Route::get('affairs', AffairPage::class)->name('affairs');
  Route::get('units', UnitPage::class)->name('units');
  Route::get('employees', EmployeePage::class)->name('employees');
  Route::get('attendances', AttendancePage::class)->name('attendances');
  Route::get('skp', SkpPage::class)->name('skp');
});
