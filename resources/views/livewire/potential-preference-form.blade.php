<form wire:submit.prevent="submit">
 <label class="form-control">
  <div class="label">
   <span class="label-text">Nilai Preferensi Potensial</span>
  </div>
 </label>
 <input
  type="number"
  wire:model="value"
  class="input"
  min="0"
  max="10"
 />

 <div class="mt-4 flex justify-end gap-4">
  <button
   type="submit"
   class="btn btn-primary"
  >Simpan</button>
 </div>
</form>
