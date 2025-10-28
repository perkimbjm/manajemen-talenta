<x-slot name="header">
 <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
  Pengaturan User
 </h2>
 <div class="ml-auto">
  <button
   class="btn btn-primary"
   x-on:click="() => {
    $dispatch('show-modal', {
      component: 'user-form',
      title: 'Tambah User',
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
   <li>Pengaturan</li>
   <li>User</li>
  </ul>
 </div>
</x-slot>

<div class="px-4 py-6 sm:px-8">
 <div
  class="card rounded-md bg-base-100 px-2 py-4 shadow-lg"
  x-data="{
      removeUser(userId) {
          $dispatch('confirm-modal', {
            content: 'Apakah anda yakin akan menghapus user ini?',
            confirmAction: () => {
              $wire.removeUser(userId)
            }
          })
      }
  }"
 >
  <livewire:user-table />
 </div>

</div>
