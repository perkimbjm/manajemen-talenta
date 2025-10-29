<x-slot name="header">
 <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
  Pengaturan Role &amp; Permission
 </h2>
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
  <li>Role &amp; Permission</li>
  </ul>
 </div>
</x-slot>

<div class="px-4 py-6 sm:px-8">
 <div class="space-y-6">
  <div
   class="card rounded-md bg-base-100 px-4 py-4 shadow-lg"
   x-data="{
       removeRole(roleId) {
           $dispatch('confirm-modal', {
               content: 'Apakah anda yakin akan menghapus role ini?',
               confirmAction: () => {
                   $wire.removeRole(roleId)
               }
           })
       }
   }"
  >
   <div class="mb-4 flex items-center justify-between">
    <div>
     <h3 class="text-lg font-semibold text-base-content">Daftar Role</h3>
     <p class="text-sm text-base-content/70">Kelola role dan hak akses pengguna.</p>
    </div>
    <button
     class="btn btn-primary"
     x-on:click="() => {
       $dispatch('show-modal', {
         component: 'role-form',
         title: 'Tambah Role',
       })
      }"
    >
     Tambah Role
    </button>
   </div>

   <livewire:role-table />
  </div>

  <div
   class="card rounded-md bg-base-100 px-4 py-4 shadow-lg"
   x-data="{
       removePermission(permissionId) {
           $dispatch('confirm-modal', {
               content: 'Apakah anda yakin akan menghapus permission ini?',
               confirmAction: () => {
                   $wire.removePermission(permissionId)
               }
           })
       }
   }"
  >
   <div class="mb-4 flex items-center justify-between">
    <div>
     <h3 class="text-lg font-semibold text-base-content">Daftar Permission</h3>
     <p class="text-sm text-base-content/70">Atur permission dan route yang dapat diakses.</p>
    </div>
    <button
     class="btn btn-secondary"
     x-on:click="() => {
       $dispatch('show-modal', {
         component: 'permission-form',
         title: 'Tambah Permission',
       })
      }"
    >
     Tambah Permission
    </button>
   </div>

   <livewire:permission-table />
  </div>
 </div>
</div>
