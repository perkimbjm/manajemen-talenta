<form wire:submit.prevent="submit">
 <label class="form-control">
  <div class="label">
   <span class="label-text">Nama Role</span>
  </div>
  <input
   type="text"
   placeholder="Nama Role"
   class="input input-bordered w-full"
   wire:model="name"
   required
  />
  <div class="label">
   <span class="label-text text-error">
    @error('name')
     {{ $message }}
    @enderror
   </span>
  </div>
 </label>

 <div class="mt-4 flex justify-end gap-4">
  <button class="btn btn-primary">Simpan</button>
 </div>
</form>
