<?php

use App\Livewire\Pages\SectorPage;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(SectorPage::class)
        ->assertStatus(200);
});
