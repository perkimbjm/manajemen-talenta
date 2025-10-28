<form wire:submit.prevent="submit">
 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Jenis Diklat</span>
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
    <option value="{{ $type }}">
     {{ $type }}
    </option>
   @endforeach
  </select>
  <div class="label">
   @error('type')
    <span class="label-text-alt">{{ $message }}</span>
   @enderror
  </div>
 </label>

 <template x-if="$wire.type === 'Pim'">
  <label class="form-control w-full">
   <div class="label">
    <span class="label-text">Tingkat Diklat Pim</span>
   </div>
   <input
    type="number"
    placeholder="Tingkat diklat"
    class="input input-bordered w-full"
    wire:model="rank"
    required
   />
   <div class="label">
    @error('rank')
     <span class="label-text-alt">{{ $message }}</span>
    @enderror
   </div>
  </label>
 </template>

 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Tahun Diklat</span>
  </div>
  <input
   type="number"
   placeholder="Tahun diklat"
   class="input input-bordered w-full"
   wire:model="year"
   required
  />
  <div class="label">
   @error('year')
    <span class="label-text-alt">{{ $message }}</span>
   @enderror
  </div>
 </label>

 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Nomor Diklat</span>
  </div>
  <input
   type="text"
   placeholder="Nomor diklat"
   class="input input-bordered w-full"
   wire:model="letter_number"
   required
  />
  <div class="label">
   @error('letter_number')
    <span class="label-text-alt">{{ $message }}</span>
   @enderror
  </div>
 </label>

 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Nama Diklat</span>
  </div>
  <input
   type="text"
   placeholder="Nama diklat"
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
   <span class="label-text">Status Diklat</span>
  </div>
  <select
   class="select select-bordered"
   wire:model="status"
  >
   <option
    value=""
    disabled
    selected
   >Pilih status</option>
   @foreach ($statuses as $status_id => $status_label)
    <option value="{{ $status_id }}">
     {{ $status_label }}
    </option>
   @endforeach
  </select>
  <div class="label">
   @error('status')
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
