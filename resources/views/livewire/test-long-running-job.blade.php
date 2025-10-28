<div>
 <button
  class="btn btn-primary"
  wire:click="triggerEvent"
 >
  Test Dispatch Event
 </button>
 <span x-text="$wire.text"></span>
 <span>
  {{ $text }}
 </span>
</div>
