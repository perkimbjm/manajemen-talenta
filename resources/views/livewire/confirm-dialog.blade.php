<dialog
 role="confirm-dialog"
 class="modal"
 x-data="{
     promptInput: '',
     confirmAction: () => {},
     initData(data) {
         $data.disableConfirm = data.disableConfirm ?? function() {
             return !!$data.prompt && $data.promptInput !== $data.prompt
         }
         $data.content = data.content ?? 'Apakah anda yakin akan melanjutkan aksi ini?'
         $data.confirmText = data.confirmText ?? 'Yakin'
         $data.hint = data.hint ?? ''
         $data.promptInput = data.promptInput ?? ''
         $data.prompt = data.prompt ?? ''
         $data.promptPlaceholder = data.promptPlaceholder ?? ''
         $data.confirmAction = data.confirmAction ?? function() {}
     },
     init() {
         this.initData({})
     }
 }"
 x-bind:id="$id('confirm-modal')"
 x-on:confirm-modal.window="(ev) => {
    initData(ev.detail)
    $el.showModal()
    $el.setAttribute('active', true)
 }"
 x-on:close="$el.removeAttribute('active')"
>
 <div class="min-h-16 modal-box w-full max-w-screen-sm pr-4 scrollbar-stable">
  <div class="flex items-start gap-4">
   <div class="flex flex-shrink-0 items-center justify-center rounded-full border border-warning bg-base-200 p-1">
    <span class="i-mdi-warning-circle h-6 w-6 text-warning"></span>
   </div>
   <div class="mt-1 w-full">
    <p
     x-text="content"
     class="text-lg font-medium"
    ></p>
    <template x-if="!!hint">
     <p
      class="text-sm text-base-content"
      x-text="hint"
     ></p>
    </template>
    <template x-if="!!prompt">
     <input
      class="input input-bordered mt-4"
      x-bind:placeholder="promptPlaceholder ? promptPlaceholder : `Ketik ${$quote('[{prompt}]', {prompt})} untuk melanjutkan`"
      x-model="promptInput"
     />
    </template>
   </div>
  </div>
  <div class="modal-action">
   <button
    class="btn btn-neutral btn-sm text-white"
    x-on:click="$el.closest('dialog').close()"
   >
    Batal
   </button>
   <button
    class="btn btn-warning btn-sm"
    x-on:click="() => {
     $el.closest('dialog').close()
     confirmAction(promptInput)
    }"
    x-text="confirmText"
    x-bind:disabled="() => disableConfirm(promptInput)"
   >
    Yakin
   </button>
  </div>
 </div>
</dialog>
