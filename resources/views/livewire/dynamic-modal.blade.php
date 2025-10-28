<div
 x-data="{
     show: $wire.entangle('show'),
     components: $wire.entangle('components')
 }"
 role="dynamic-modal"
>
 <div
  x-on:show-modal.window="() => {
    $nextTick(() => {
      $wire.showModal({
        id: $event.detail.id || $slug($event.detail.component),
        name: $event.detail.component,
        arguments: $event.detail.arguments,
      })
    });
  }"
  x-on:remove-modal.window="(ev) => {
    let componentId = ev.detail.componentId

    if(!componentId) {
      const component = $wire.components.at(-1)
      componentId = component?.id
    }

    $wire.closeModal(componentId)
  }"
 >
  <!-- Modal content (rendered dynamically by Livewire) -->
 </div>
 <!-- Modal Display Controlled by Livewire -->
 @foreach ($components as $component)
  <div
   wire:key="{{ $component['id'] }}"
   wire:ignore
   x-init="() => {
       const container = document.getElementById('modal-box-{{ $component['id'] }}')
       container.dispatchEvent(new CustomEvent('modal-rendered', {
           detail: {
               id: '{{ $component['id'] }}',
               component: '{{ $component['name'] }}',
           },
           bubbles: true
       }))
   }"
  >
   @teleport("#modal-box-{$component['id']}")
    @livewire($component['name'], @$component['arguments'] ?? [], key($component['id']))
   @endteleport
  </div>
 @endforeach
</div>
