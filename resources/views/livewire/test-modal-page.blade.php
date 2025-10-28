<div>
 <button
  x-on:click="() => {
    try {
      $dispatch('show-modal', {   
        component: 'test-modal-content',
        template: $refs.modalContentTemplate,
      })
    } catch (e) {
      console.error(e)
    }
  }"
  class="btn btn-primary"
 >
  Show Modal with TestModalContent
 </button>

 <template x-ref="modalContentTemplate">
  <div max-width="screen-lg">
   <div class="relative grid gap-2">
    <input
     class="input skeleton h-10"
     disabled
    />
    <div class="skeleton h-10 rounded border"></div>
    <div class="skeleton h-10 rounded border"></div>
   </div>

   <button
    disabled
    class="btn skeleton btn-sm"
   >Open Nested Modal</button>
  </div>
 </template>
</div>
