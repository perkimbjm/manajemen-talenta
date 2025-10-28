<?php

use App\Livewire\EmployeeProfilePage;
use App\Livewire\EmployeeDocumentsPage;
use Illuminate\Support\Facades\Route;

Route::prefix('employee')->name('employees.')->middleware(['auth'])->group(function () {
  Route::get('profile', EmployeeProfilePage::class)->name('profile');
  Route::get('documents', EmployeeDocumentsPage::class)->name('documents');
});
