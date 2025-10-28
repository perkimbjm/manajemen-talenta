<?php

use App\Livewire\Pages\UserPage;
use Livewire\Livewire;

test('renders successfully', function () {
    Livewire::test(UserPage::class)
        ->assertStatus(200);
});
