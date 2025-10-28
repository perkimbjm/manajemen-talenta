<x-slot name="header">
 <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
  {{ __('Counter') }}
 </h2>
</x-slot>

<div class="py-12">
 <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
  <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
   <div class="p-6 text-gray-900 dark:text-gray-100">
    <div
     id="counter"
     class="sticky flex gap-4 px-4 py-3 bg-white border rounded top-10 dark:border-gray-500 dark:bg-gray-700 dark:text-white"
     x-on:count-saved="console.log($event)"
    >

     <button x-on:click="$wire.count++">+</button>
     <button x-on:click="$wire.count--">-</button>
     <h1 x-text="$wire.count">{{ $count }}</h1>
     <button x-on:click="$wire.$refresh()">Refresh</button>
     <button wire:click='save()'>
      Save
     </button>

     <div class="flex flex-wrap">
      <button
       x-on:click="Livewire.dispatch('openModal', {component: 'edit-user', modalAttributes: {
        title: 'Edit User'
       }})"
       class="btn btn-primary"
      >Edit Users 1</button>
     </div>

    </div>
   </div>
  </div>
 </div>
</div>
