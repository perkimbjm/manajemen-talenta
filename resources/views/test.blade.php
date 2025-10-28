@forelse($components as $id => $component)
 <div
  x-show.immediate="activeComponent == '{{ $id }}'"
  x-ref="{{ $id }}"
  wire:key="{{ $id }}"
 >
  @livewire($component['name'], $component['arguments'], key($id))
 </div>
@empty
@endforelse
