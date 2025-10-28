<?php

use App\Livewire\Pages\AssistPage;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(AssistPage::class)
        ->assertStatus(200);
});
