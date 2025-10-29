<form wire:submit.prevent="submit" class="space-y-4">
 <div>
  <h3 class="text-lg font-semibold">Route untuk Permission {{ $permission->name }}</h3>
  <p class="text-sm text-base-content/70">Pilih route yang dapat diakses oleh permission ini.</p>
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

 <div class="max-h-72 overflow-y-auto rounded-lg border border-base-300 p-3 space-y-2">
  @forelse ($this->filteredRoutes as $route)
   <label class="flex items-center gap-3">
    <input
     type="checkbox"
     class="checkbox checkbox-primary"
     value="{{ $route }}"
     wire:model="selectedRoutes"
    />
    <span class="font-mono text-xs md:text-sm">{{ $route }}</span>
   </label>
  @empty
   <p class="text-sm text-base-content/60">Route tidak ditemukan.</p>
  @endforelse
 </div>

 <div class="flex justify-end">
  <button type="submit" class="btn btn-primary">Simpan</button>
 </div>
</form>
