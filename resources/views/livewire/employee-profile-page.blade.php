<div class="mx-auto grid max-w-screen-xl gap-4 px-4 py-4 sm:px-6">
 @can('Lihat Pegawai')
  <div class="card card-compact bg-white">
   <div class="card-body">
    <x-select-employees />
   </div>
  </div>
 @endcan
 <article class="card card-bordered card-compact bg-white">
  <div class="card-body">
   <livewire:employee-profile :nip="$this->employee?->nip" />
  </div>
 </article>
</div>
