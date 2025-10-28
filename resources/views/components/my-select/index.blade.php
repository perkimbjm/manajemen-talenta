<x-my-select.container {{ $attributes }}>
 <x-my-select.trigger />
 <x-my-select.trigger-indicator />

 <x-my-select.menu>
  <template x-if="dataset.length === 0">
   <x-my-select.empty />
  </template>
  <template
   x-for="data in filterDataset(dataset)"
   :key="getItem(data).value"
  >
   <x-my-select.option>
    <x-my-select.option-label />
    <x-my-select.option-indicator />
   </x-my-select.option>
  </template>
  <template x-if="total">
   <x-my-select.total-indicator />
  </template>
 </x-my-select.menu>
</x-my-select.container>
