<?php

use App\Livewire\Modal\CreateSector;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(CreateSector::class)
        ->assertStatus(200);
});
