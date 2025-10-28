<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div
 x-on:open-modal.window.camel="$dispatch('render-template')"
 x-data="{
     repeat: 2
 }"
>
 <template x-render:render-template="true">
  <dialog
   class="modal modal-bottom sm:modal-middle"
   x-init="() => {
       $nextTick(() => $el.showModal());
   }"
   x-on:close="async () => {
       await $once('transitionend', 300)
       $el.remove()
   }"
  >
   <div class="modal-box">
    <p class="skeleton mb-4 h-8 w-1/2"></p>
    <x-form-skeleton />

    <form method="dialog">
     <button class="btn btn-circle btn-ghost btn-sm absolute right-2 top-2">✕</button>
    </form>
   </div>
  </dialog>
 </template>
</div>
