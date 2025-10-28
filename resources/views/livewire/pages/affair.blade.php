<x-slot name="header">
 <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
  Urusan
 </h2>
 <div class="ml-auto">
  <button
   class="btn btn-primary"
   x-on:click="() => {
        Livewire.dispatch('openModal', {
          component: 'modal.create-affair',
          modalAttributes: {
           title: 'Tambah Urusan'
          },
        })
      }"
  >
   Tambah
  </button>
 </div>
</x-slot>

<x-slot name="subheader">
 <div class="breadcrumbs text-sm">
  <ul>
   <li>
    <a
     href="{{ route('dashboard') }}"
     x-vision
    >
     <i class="i-mdi-home h-4 w-4 text-base-content"></i>
    </a>
   </li>
   <li>Master</li>
   <li>Urusan</li>
  </ul>
 </div>
</x-slot>

<div class="px-4 py-6 sm:px-8">
 <!--
   Heads up! 👋
 
   This component comes with some `rtl` classes. Please remove them if they are not needed in your project.
 -->

 <div class="card rounded-md bg-base-100 px-2 py-4 shadow-lg">
  <livewire:affair-table />
 </div>

</div>
