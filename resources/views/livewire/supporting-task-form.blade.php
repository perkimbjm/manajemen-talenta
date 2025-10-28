<form
 class="grid"
 wire:submit.prevent="submit"
>
 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Nama Tugas Pendukung</span>
  </div>
  <input
   type="text"
   placeholder="Nama tugas pendukung"
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
   <span class="label-text">Lingkup</span>
  </div>
  <select
   class="select select-bordered"
   wire:model="scope"
  >
   <option
    value=""
    disabled
    selected
   >Pilih ruang lingkup</option>
   @foreach ($scopes as $scope)
    <option value="{{ $scope['name'] }}">
     {{ $scope['name'] }}
    </option>
   @endforeach
  </select>
  <div class="label">
   @error('scope')
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
