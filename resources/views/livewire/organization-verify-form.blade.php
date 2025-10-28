<form wire:submit.prevent="verify">
 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Nama Tim/Pokja</span>
  </div>
  <input
   type="text"
   value="{{ $organization->name }}"
   class="input input-bordered w-full"
   readonly
   placeholder="Nama Tim/pokja"
  />
 </label>
 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Sebagai?</span>
  </div>
  <input
   type="text"
   value="{{ $organization->as }}"
   class="input input-bordered w-full"
   readonly
  />
 </label>
 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Lingkup</span>
  </div>
  <select
   class="select select-bordered w-full"
   wire:model="scope"
   readonly
  >
   <option
    value=""
    selected
    disabled
   >Pilih Lingkup Tim/Pokja</option>
   @foreach ($scopes as $scope)
    <option value="{{ $scope['name'] }}">
     {{ $scope['name'] }}
    </option>
   @endforeach
  </select>
 </label>

 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Keterangan</span>
  </div>
  <textarea
   class="textarea textarea-bordered w-full"
   placeholder="Keterangan tambahan"
   wire:model="description"
  ></textarea>
 </label>

 <div class="mt-4 flex justify-end">
  <button
   type="submit"
   class="btn btn-primary"
  >Verifikasi</button>
 </div>
</form>
