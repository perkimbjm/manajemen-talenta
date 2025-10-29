<form wire:submit.prevent="submit">
 <div class="grid gap-4">
  <label class="form-control">
   <div class="label">
    <span class="label-text">Nama Permission</span>
   </div>
   <input
    type="text"
    class="input input-bordered w-full"
    placeholder="Nama Permission"
    wire:model="name"
    required
   />
   <div class="label">
    <span class="label-text-alt text-error">
     @error('name')
      {{ $message }}
     @enderror
    </span>
   </div>
  </label>

  <label class="form-control">
   <div class="label">
    <span class="label-text">Guard Name</span>
   </div>
   <input
    type="text"
    class="input input-bordered w-full"
    placeholder="Guard Name"
    wire:model="guard_name"
    required
   />
   <div class="label">
    <span class="label-text-alt text-error">
     @error('guard_name')
      {{ $message }}
     @enderror
    </span>
   </div>
  </label>
 </div>

 <div class="mt-6 flex justify-end gap-4">
  <button type="submit" class="btn btn-primary">Simpan</button>
 </div>
</form>
