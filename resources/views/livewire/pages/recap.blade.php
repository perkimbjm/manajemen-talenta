<x-slot
 name="header"
 :sticky="false"
>
 <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
  Rekap per-Rekening Belanja
 </h2>
 <div class="ml-auto">
  <button
   class="btn btn-primary"
   x-on:click="() => {
        Livewire.dispatch('openModal', {
          component: 'modal.create-recap',
          modalAttributes: {
           title: 'Tambah Rekap'
          },
        })
      }"
  >
   Tambah
  </button>
 </div>
</x-slot>

<x-slot
 name="subheader"
 :sticky="false"
>
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
   <li>Asistensi</li>
   <li>Rekap per-Rekening Belanja</li>
  </ul>
 </div>
</x-slot>

<div
 class="px-4 py-6 sm:px-8"
>
 <!--
   Heads up! 👋
 
   This component comes with some `rtl` classes. Please remove them if they are not needed in your project.
 -->

 <div class="card rounded-md bg-base-100 px-2 py-4 shadow-lg">
  <livewire:recap-table />
 </div>

</div>
