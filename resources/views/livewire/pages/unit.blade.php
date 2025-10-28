<x-slot name="header">
 <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
  Unit Organisasi
 </h2>
 <div class="ml-auto">
  <button
   class="btn btn-primary"
   x-on:click="() => {
       Livewire.dispatch('openModal', {
         component: 'modal.create-unit',
         modalAttributes: {
          title: 'Tambah Unit'
         },
       })
     }"
  >
   Tambah
  </button>
 </div>
</x-slot>

<x-slot name="subheader">
 <x-breadcrumbs :paths="[
     'Data Master',
     [
         'title' => 'Bidang Urusan',
         'link' => route('master.sectors'),
     ],
     'Unit Organisasi',
 ]" />
</x-slot>

<div class="px-4 py-6 sm:px-8">
 <!--
  Heads up! 👋

  This component comes with some `rtl` classes. Please remove them if they are not needed in your project.
-->

 <div class="card rounded-md bg-base-100 px-2 py-4 shadow-lg">
  <livewire:unit-table />
 </div>

</div>
