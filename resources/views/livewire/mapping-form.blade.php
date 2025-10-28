<form wire:submit.prevent="submit">
 <label class="form-control">
  <div class="label"><span class="label-text">Prev ID</span></div>
  <input
   type="text"
   wire:model="prev_id"
   class="input input-bordered"
  />
  <div class="label py-0">
   <span class="label-text min-h-[1em] text-error">
    @error('prev_id')
     {{ $message }}
    @enderror
   </span>
  </div>
 </label>

 <label class="form-control">
  <div class="label"><span class="label-text">Prev Name</span></div>
  <input
   type="text"
   wire:model="prev_name"
   class="input input-bordered"
  />
  <div class="label py-0">
   <span class="label-text min-h-[1em] text-error">
    @error('prev_name')
     {{ $message }}
    @enderror
   </span>
  </div>
 </label>

 <label class="form-control">
  <div class="label"><span class="label-text">Current ID</span></div>
  <input
   type="text"
   wire:model="current_id"
   class="input input-bordered"
  />
  <div class="label py-0">
   <span class="label-text min-h-[1em] text-error">
    @error('current_id')
     {{ $message }}
    @enderror
   </span>
  </div>
 </label>

 <label class="form-control">
  <div class="label"><span class="label-text">Current Name</span></div>
  <input
   type="text"
   wire:model="current_name"
   class="input input-bordered"
  />
  <div class="label py-0">
   <span class="label-text min-h-[1em] text-error">
    @error('current_name')
     {{ $message }}
    @enderror
   </span>
  </div>
 </label>

 <div class="flex justify-end gap-4">
  <button class="btn btn-primary">
   Simpan
  </button>
 </div>
</form>
