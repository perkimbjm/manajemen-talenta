<form wire:submit.prevent="submit">
 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Jenis Pengalaman</span>
  </div>
  <select
   class="select select-bordered"
   wire:model="type"
  >
   <option
    value=""
    disabled
    selected
   >Pilih jenis</option>
   @foreach ($types as $type)
    <option value="{{ $type['name'] }}">
     {{ $type['name'] }}
    </option>
   @endforeach
  </select>
  <div class="label">
   @error('type')
    <span class="label-text-alt">{{ $message }}</span>
   @enderror
  </div>
 </label>
 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Nama Organisasi</span>
  </div>
  <input
   type="text"
   placeholder="Nama organisasi"
   class="input input-bordered w-full"
   wire:model="name"
   required
  />
  <div class="label">
   @error('name')
    <span class="label-text-alt">{{ $message }}</span>
   @enderror
  </div>
 </label>

 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Keterangan</span>
  </div>
  <textarea
   placeholder="Keterangan tambahan"
   class="textarea textarea-bordered w-full"
   wire:model="description"
  ></textarea>
  <div class="label">
   @error('description')
    <span class="label-text-alt">{{ $message }}</span>
   @enderror
  </div>
 </label>

 <div class="mt-4 flex justify-end">
  <button class="btn btn-primary">
   Simpan
  </button>
 </div>
</form>
