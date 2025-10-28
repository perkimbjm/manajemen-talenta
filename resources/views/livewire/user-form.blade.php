<form
 x-on:submit.prevent="() => {
  console.log($wire.password, rePassword)
  if($wire.password !== rePassword) {
    $toastify({
      type: 'warning',
      message: 'Password konfirmasi tidak cocok',
    })
    rePassword = ''
    $dom('ConfirmPassword').focus()
    return
  }

  $wire.submit()
 }"
 x-data="{
     rePassword: '',
 }"
>
 <label class="form-control">
  <div class="label">
   <span class="label-text">Role</span>
  </div>
  <select
   wire:model="role"
   class="select select-bordered w-full"
  >
   <option
    value=""
    disabled
    selected
   >
    Pilih role
   </option>
   @foreach ($roles as $role)
    <option value="{{ $role['name'] }}">
     {{ $role['name'] }}
    </option>
   @endforeach
  </select>
  <div class="label">
   <span class="label-text text-error">
    @error('role')
     {{ $message }}
    @enderror
   </span>
  </div>
 </label>

 <label class="form-control">
  <div class="label">
   <span class="label-text">Username</span>
  </div>
  <input
   type="text"
   placeholder="Username (unik)"
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
 <label class="form-control">
  <div class="label">
   <span class="label-text">Password</span>
  </div>
  <x-input-password
   placeholder="Password..."
   wire:model="password"
  />
  <div class="label">
   <span class="label-text text-error">
    @error('password')
     {{ $message }}
    @enderror
   </span>
  </div>
 </label>
 <label class="form-control">
  <div class="label">
   <span class="label-text">Konfirmasi Password</span>
  </div>
  <x-input-password
   id="ConfirmPassword"
   placeholder="Ulangi password anda"
   x-model="rePassword"
  />
 </label>

 <div class="mt-4 flex justify-end gap-4">
  <button class="btn btn-primary">Simpan</button>
 </div>
</form>
