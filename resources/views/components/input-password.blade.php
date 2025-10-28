<div
 class="relative"
 x-data="{
     type: 'password',
     toggleVisibility() {
         this.type = this.type === 'password' ? 'text' : 'password';
     }
 }"
>
 @if ($slot->isNotEmpty())
  {{ $slot }}
 @else
  <input
   type="password"
   x-bind:type="type"
   {{ $attributes->twMerge('input input-bordered') }}
  />
 @endif
 <div class="absolute inset-y-0 right-0 top-0 flex items-center px-1">
  <button
   class="btn btn-ghost btn-sm text-gray-500 hover:text-base-content"
   type="button"
   tabindex="-1"
   x-on:click="toggleVisibility"
  >
   <span
    class="h-4 w-4"x-bind:class="{
        'i-mdi-eye': type !== 'password',
        'i-mdi-eye-off': type === 'password'
    }"
    x-cloak
   ></span>
  </button>
 </div>
</div>
