<form wire:submit.prevent="submit" class="space-y-4">
 <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
  <div>
   <h3 class="text-lg font-semibold">Route untuk Permission {{ $permission->name }}</h3>
   <p class="text-sm text-base-content/70">
    Pilih route yang dapat diakses oleh permission ini.
    <span class="block text-xs text-base-content/60 sm:text-sm">Gunakan tombol Select All atau Deselect All untuk mempercepat pengaturan.</span>
   </p>
  </div>
  <div class="flex flex-wrap items-center gap-2">
   <button type="button" class="btn btn-sm" wire:click="selectAll">Select All</button>
   <button type="button" class="btn btn-sm btn-outline" wire:click="deselectAll">Deselect All</button>
  </div>
 </div>

<div class="form-control">
 <div class="label">
  <span class="label-text">Cari Route</span>
 </div>
 <input
   type="text"
   class="input input-bordered"
   placeholder="Cari berdasarkan nama route"
   wire:model.debounce.300ms="search"
 />
</div>

 <div class="rounded-lg border border-base-300 bg-base-200/40 p-3 text-xs text-base-content/70">
  <p class="font-semibold text-base-content">Daftar Route</p>
  <p>Pilih route yang relevan. Setiap pilihan dilengkapi deskripsi fungsi untuk membantu memastikan akses yang tepat.</p>
 </div>

<div class="max-h-72 overflow-y-auto rounded-lg border border-base-300 p-3 space-y-3">
 @forelse ($this->filteredRoutes as $route)
   <label class="flex items-start gap-3 rounded-lg p-2 transition hover:bg-base-200">
    <input
     type="checkbox"
     class="checkbox checkbox-primary"
     value="{{ $route }}"
     wire:model="selectedRoutes"
    />
    <span>
     <span class="font-mono text-xs font-semibold text-base-content md:text-sm">{{ $route }}</span>
     <span class="mt-1 block text-xs text-base-content/70 md:text-sm">{{ $this->descriptionFor($route) }}</span>
    </span>
   </label>
 @empty
  <p class="text-sm text-base-content/60">Route tidak ditemukan.</p>
 @endforelse
</div>

 <div class="flex justify-end">
  <button type="submit" class="btn btn-primary">Simpan</button>
 </div>
</form>
