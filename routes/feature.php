<?php

use App\Livewire\Pages\SkjPage;
use App\Livewire\ProfileAsnPage;
use App\Livewire\TalentPoolPage;
use App\Livewire\Pages\ManjaPage;
use App\Livewire\TrackRecordPage;
use App\Livewire\Pages\SkjStaffPage;
use App\Livewire\AssessmentOtherPage;
use Illuminate\Support\Facades\Route;
use App\Livewire\AssessmentCenterPage;
use App\Livewire\Pages\InnovationPage;
use App\Livewire\Pages\SkjJabatanPage;
use App\Livewire\Pages\AsnPotensialPage;
use App\Livewire\Pages\OrganizationPage;
use App\Livewire\Pages\SkjOccupationPage;
use App\Livewire\Pages\SupportingTaskPage;

Route::prefix('features')->name('features.')->middleware(['auth'])->group(function () {
  Route::get('skj', SkjPage::class)->name('skj');
  Route::get('skj/occupations', SkjOccupationPage::class)->name('skj.occupations');
  Route::get('skj/{occupation_type:code}/staff', SkjStaffPage::class)->name('skj.staff');
  Route::get('skj/{occupation_type:code}/struktural', SkjJabatanPage::class)->name('skj.struktural');
  Route::get('manja', ManjaPage::class)->name('manja');
  Route::get('kinerja/inovasi', InnovationPage::class)->name('innovations');
  Route::get('kinerja/organisasi', OrganizationPage::class)->name('organizations');
  Route::get('kinerja/tugas-pendukung', SupportingTaskPage::class)->name('supportingTasks');
  Route::get('asn-potensial', AsnPotensialPage::class)->name('asn-potensial');
  Route::get('asn-potensial/track-record', TrackRecordPage::class)->name('asn-potensial.track-record');
  Route::get('asn-potensial/other', AssessmentOtherPage::class)->name('asn-potensial.other');
  Route::get('talent-pool', TalentPoolPage::class)->name('talent-pool');
  Route::get('assessment-center', AssessmentCenterPage::class)->name('assessment-center');
  Route::get('profil-talenta-asn', ProfileAsnPage::class)->name('profil-talenta-asn');
});
