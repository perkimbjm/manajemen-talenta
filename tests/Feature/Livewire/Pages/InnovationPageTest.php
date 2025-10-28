<?php

use App\Livewire\Pages\InnovationPage;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(InnovationPage::class)
        ->assertStatus(200);
});
