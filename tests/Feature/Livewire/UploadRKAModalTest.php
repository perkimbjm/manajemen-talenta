<?php

use App\Livewire\UploadRKAModal;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(UploadRKAModal::class)
        ->assertStatus(200);
});
