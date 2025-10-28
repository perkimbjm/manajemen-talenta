<form
 class="grid"
 wire:submit.prevent="submit"
>
 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Data hasil Penilaian Spesifik/Predikat Kinerja ASN (55%) yang berasal dari tarikan data hasil
    penilaian kinerja melalui aplikasi e-kinerja BKN</span>
  </div>
  <select
   class="select select-bordered"
   wire:model="value"
  >
   <option
    value="0"
    disabled
    selected
   >Pilih Evaluasi</option>
   @foreach ($evaluations as $evaluation)
    <option value="{{ $evaluation['value'] }}">
     {{ $evaluation['name'] }}
    </option>
   @endforeach
  </select>
  <div class="label">
   @error('evaluation')
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
