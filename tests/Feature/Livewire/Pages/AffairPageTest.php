<?php

use App\Livewire\Pages\AffairPage;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(AffairPage::class)
        ->assertStatus(200);
});
