<?php

use App\Livewire\InnovationForm;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(InnovationForm::class)
        ->assertStatus(200);
});
