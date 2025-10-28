<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Events\TestNotification;

class TestLongRunningJob extends Component
{
  public string $text = 'text';

  public function testNotification()
  {
    $this->js("console.log('test notification success')");
    $this->dispatch('notifications', [
      'type' => 'success',
      'message' => 'test notification success',
    ]);
    $this->text = 'Changed Text';
  }

  public function getListeners()
  {
    return [
      // Private Channel
      "echo-private:test,TestNotification" => 'testNotification',
    ];
  }

  public function triggerEvent()
  {
    event(new TestNotification());
  }

  public function render()
  {
    return view('livewire.test-long-running-job');
  }
}
