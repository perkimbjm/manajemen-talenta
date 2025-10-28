<form
 class="grid"
 wire:submit.prevent="submit"
>
 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Nama Tim/Pokja</span>
  </div>
  <input
   type="text"
   placeholder="Nama Tim/Pokja"
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

 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Sebagai?</span>
  </div>
  <select
   class="select select-bordered"
   wire:model="position"
  >
   <option
    value=""
    disabled
    selected
   >Pilih posisi dalam organisasi</option>
   @foreach ($positions as $position)
    <option value="{{ $position['name'] }}">
     {{ $position['name'] }}
    </option>
   @endforeach
  </select>
  <div class="label">
   @error('position')
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
