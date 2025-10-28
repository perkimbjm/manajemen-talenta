<div role="dynamic-modal-render">
 <template x-ref="dynamicModal">
  <dialog
   role="modal"
   x-data="{
       loading: true,
       closing: false,
       removed: false,
   }"
   x-bind:id="`modal-${id}`"
   x-init="() => {
       $nextTick(() => {
           $el.showModal()
           $data.prevActiveModal = document.querySelector('dialog[open][active]')
           $data.prevActiveModal?.removeAttribute('active')
           $el.setAttribute('active', true)
       })
   }"
   x-on:close-modal="() => {
    $el.close()
    $data.prevActiveModal?.setAttribute('active', true)
   }"
   x-on:modal-removed.window="(ev) => {
    if(ev.detail.componentId !== id) return

    $data.removed = true
    $el.remove()
   }"
   x-on:close="async () => {
    $data.closing = true

    await $once('transitionend', 300)

    if(!$data.removed) {
      $dispatch('remove-modal', {
        componentId: id
      })
    }
   }"
   class="modal modal-bottom duration-300 sm:modal-middle"
   wire:ignore.self
  >
   <div
    class="min-h-16 modal-box pr-4 transition-all duration-300 scrollbar-stable"
    x-bind:style="{
        maxHeight: loading ? tempHeight : 'calc(100vh - 5em)',
    }"
    x-bind:id="`modal-box-${id}`"
    x-on:modal-rendered="loading = false"
    max-width="content"
   >
    <form
     method="dialog"
     class="sticky top-0 z-20"
    >
     <button
      class="btn btn-circle btn-ghost btn-sm absolute -right-4 -top-4 z-10 p-1 text-base-content/75 hover:text-base-content"
      x-on:click="$dispatch('close-modal', {
       componentId: id
     })"
      x-bind:disabled="loading"
     >
      <span class="i-mdi-close h-5 w-5">✕</span>
     </button>
    </form>
    <template x-if="!!title">
     <header
      class="sticky inset-x-0 -top-3 z-10 mb-4 border-b bg-base-100 pb-2 before:absolute before:inset-x-0 before:-top-4 before:h-4 before:bg-white"
     >
      <span x-text="title"></span>
     </header>
    </template>
    <div x-show="loading">
     <template x-template-outlet="template"></template>
    </div>
   </div>
  </dialog>
 </template>
 <div
  x-on:show-modal.window="(ev) => {
    let template = ev.detail.template
    let tempHeight = ev.detail.tempHeight
    
    if(!!template && typeof template === 'string') {
      template = document.querySelector(template)
    } 

    if(!tempHeight && template?.getAttribute('max-height')) {
      tempHeight = template.getAttribute('max-height')
    } else if(!tempHeight) {
      tempHeight = '200px'
    }

    const data = {
      id: ev.detail.id || $slug(ev.detail.component),
      title: ev.detail.title,
      component: ev.detail.component,
      tempHeight: tempHeight,
      template: template,
    }

    $appendTemplate($refs.dynamicModal, document.body, data)
  }"
 ></div>
</div>
