<form
 class="flex flex-col"
 method="POST"
 wire:submit="save"
>
 @csrf
 @method('POST')
 <label class="form-control">
  <div class="label">
   <span class="label-text">Nama</span>
  </div>
  <input
   type="text"
   class="input"
   placeholder="Nama urusan"
   name="name"
   wire:model='name'
  />
  <div class="label">
   @error('name')
    <span class="label-text-alt text-rose-500">{{ $message }}</span>
   @enderror
  </div>
 </label>
 <label class="form-control">
  <div class="label">
   <span class="label-text">Keterangan</span>
  </div>
  <textarea
   class="textarea textarea-bordered"
   placeholder="Keterangan tambahan"
   name="description"
   wire:model='description'
  ></textarea>
  <div class="label">
   @error('description')
    <span class="label-text-alt text-rose-500">{{ $message }}</span>
   @enderror
  </div>
 </label>
 <div class="flex justify-end gap-1 mt-4">
  <button
   type="submit"
   class="btn btn-primary"
  >Simpan</button>
 </div>
</form>
