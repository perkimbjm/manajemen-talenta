<div
 class="grid gap-6"
 x-data="{
     items: () => Array.from({ length: $data.repeat || $el.dataset.repeat || 3 }, (_, i) => i)
 }"
 {{ $attributes }}
>
 <template x-for="i in items">
  <div class="relative grid gap-1 text-sm">
   <p class="h-5 skeleton"></p>
   <div class="px-3 py-2 skeleton">
    <div class="h-5 overflow-hidden">
     {{ $slot }}
    </div>
   </div>
  </div>
 </template>

 <div class="flex justify-end gap-4">
  <div class="w-full px-3 py-2 max-w-24 skeleton">
   <div class="h-5"></div>
  </div>
 </div>
</div>
