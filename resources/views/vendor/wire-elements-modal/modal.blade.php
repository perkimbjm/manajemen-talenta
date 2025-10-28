<div x-data="WireElementModal()">
 @isset($jsPath)
  <script>
   {!! file_get_contents($jsPath) !!}
  </script>
 @endisset
 @isset($cssPath)
  <style>
   {!! file_get_contents($cssPath) !!}
  </style>
 @endisset
 @php
  $i = 0;
 @endphp
 <div
  class="fixed inset-0 z-40 flex cursor-wait items-center justify-center bg-black/10 transition-colors"
  x-cloak
  x-show="false"
  x-transition:enter.duration.200ms
  x-transition:leave.duration.150ms
 >
  <span
   class="loading-primary loading loading-spinner loading-lg shrink-0"
   x-show="loading"
   x-transition.delay.150ms
  ></span>
 </div>

 <dialog
  class="modal modal-bottom sm:modal-middle"
  x-bind:class="{
      'backdrop:transition-opacity backdrop:!opacity-0': closing,
  }"
  x-modal
  x-bind:open="$data.show"
 >
  <div
   class="min-h-60 modal-box relative w-full pt-0 sm:max-w-md md:max-w-xl lg:max-w-2xl"
   x-bind:class="[
       closing ? 'transition-all duration-200 opacity-75 !translate-y-10 sm:!scale-90 sm:!translate-y-0' : '',
       modalWidth,
   ]"
  >
   <div
    class="sticky top-0 z-10 -mx-6 flex w-[calc(100%+3rem)] items-center px-6 py-3"
    x-bind:class="{
        'border-b bg-base-100 mb-2': !!title
    }"
   >
    <h3
     class="font-semibold"
     x-cloak
     x-text="title"
     x-show="!!title"
     x-transition
    ></h3>
    <button
     class="btn btn-circle btn-ghost btn-sm absolute right-3 top-2 flex aspect-square h-8 min-h-0 w-8 items-center bg-white/75 p-0"
     x-bind:disabled="loading"
     x-on:click="closeModal()"
    >
     <span class="i-mdi-close h-4 w-4 shrink-0"></span>
    </button>
   </div>
   @forelse($components as $id => $component)
    <div
     id="modal-component-{{ $id }}"
     x-ref="{{ $id }}"
     wire:key="{{ $id }}"
     x-init="() => {
         $nextTick(() => {
             $dispatch('modal-rendered', {
                 id: '{{ $id }}'
             })
         });
     }"
     x-cloak
     x-show="activeComponent == '{{ $id }}'"
    >
     @livewire($component['name'], $component['arguments'], key($id))
    </div>
   @empty
   @endforelse
   <div
    class="absolute inset-0 z-10 overflow-hidden bg-base-100 p-6"
    x-cloak
    x-show="showSkeleton"
    x-transition:enter.opacity.duration.150ms
    x-transition:leave.opacity.duration.250ms
    x-data="{
        repeat: 3,
        showSkeleton: false,
        initRepeat() {
            this.repeat = Math.round($el.parentElement.offsetHeight / 100)
        }
    }"
    x-init="initRepeat()"
    x-effect="async () => {
        if(loading || activeComponent) {
          initRepeat()
        }

        if(loading) {
          const wait = await $once('modal-rendered', document, 150)
          if(wait instanceof Error) {
            showSkeleton = loading
          }
          return
        } 

        $nextTick(async () => {
          if(activeComponent) {
            await $once('transitionend', $dom(`modal-component-${activeComponent}`), 300)
          }
          initRepeat()
          await $timeout(150)
          showSkeleton = false
        });
    }"
    x-cloak
   >
    <x-form-skeleton />
   </div>
  </div>
 </dialog>
</div>
