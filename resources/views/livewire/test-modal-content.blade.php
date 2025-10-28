<div max-width="screen-lg">
 <div
  class="relative grid gap-2"
  x-data="{
      _selecteds: []
  }"
 >
  <input
   class="input h-10"
   wire:model="code"
  />
  <x-select-async
   placeholder="Pilih SKPD"
   :async-data="[
       'api' => route('units.data'),
   ]"
   option-label="name"
   option-value="code"
   option-description="code"
  />

  <x-my-select
   x-model="_selecteds"
   x-data="Select({
       placeholder: 'Pilih SKPD',
       getItem(data) {
           return {
               value: data.code,
               label: data.name,
               description: data.code,
           }
       },
       asyncData() {
           return $fetch('{{ route('units.json') }}', {
               query: {
                   search: this.search
               }
           })
       },
   })"
  />
 </div>

 <button
  class="btn btn-sm"
  x-on:click="() => {
    $dispatch('show-modal', {
      id: 'nestedModal',
      component: 'test-modal-content2',
      arguments: {
        code: 4321,
      },
      template: $refs.modalContentTemplate2,
    })
  }"
 >Open Nested Modal</button>

 <button
  class="btn btn-secondary"
  x-on:click="$toastify({ type: 'info', message: 'Test Toast', duration: -1 })"
 >Test Toast</button>

 <template x-ref="modalContentTemplate2">
  <div max-width="sm">
   loading...
  </div>
 </template>
</div>
