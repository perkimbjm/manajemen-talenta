<form wire:submit.prevent="submit" class="flex flex-col gap-4">
 <div>
  <h3 class="text-lg font-semibold">Hak Akses untuk {{ $role->name }}</h3>
  <p class="text-sm text-base-content/70">Pilih permission yang diperbolehkan untuk role ini.</p>
 </div>

 <div class="flex gap-2">
  <button type="button" class="btn btn-sm" wire:click="selectAll">Pilih Semua</button>
  <button type="button" class="btn btn-sm" wire:click="resetSelection">Kosongkan</button>
 </div>

 <div class="max-h-64 overflow-y-auto rounded-lg border border-base-300 p-3 space-y-2">
  @forelse ($permissions as $permission)
   <label class="flex items-center gap-3">
    <input
     type="checkbox"
     class="checkbox checkbox-primary"
     value="{{ $permission->id }}"
     wire:model="selectedPermissions"
    />
    <span>{{ $permission->name }}</span>
   </label>
  @empty
   <p class="text-sm text-base-content/60">Belum ada permission yang tersedia.</p>
  @endforelse
 </div>

 <div class="mt-4 flex justify-end gap-3">
  <button type="submit" class="btn btn-primary">Simpan</button>
 </div>
</form>
