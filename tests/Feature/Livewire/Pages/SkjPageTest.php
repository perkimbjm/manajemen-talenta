<?php

use App\Livewire\SKJPage;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(SKJPage::class)
        ->assertStatus(200);
});
