<button
 type="button"
 x-bind:id="$id('select', 'trigger')"
 x-on:click="() => {
    showMenu()
 }"
 x-bind:class="{
     'h-auto min-h-10': multiselect
 }"
 {{ $attributes->twMerge('input w-full items-center text-left pr-8') }}
>
 <span
  x-show="!selecteds.length"
  x-text="placeholder"
  class="opacity-50"
 >
  Pilih Data
 </span>
 <template x-if="!multiselect && selecteds.length > 0">
  <div
   x-text="getItem(selecteds[0]).label"
   class="line-clamp-1"
  >
   Terpilih
  </div>
 </template>
 <template x-if="multiselect && selecteds.length > 0">
  <div class="flex flex-wrap gap-1">
   <template
    x-for="selected in selecteds"
    :key="getItem(selected).value"
   >
    <span
     x-data="{
         item: getItem(selected)
     }"
     class="badge badge-ghost border border-base-300"
     title="Hapus item"
     x-on:click.stop="deselectItem(item)"
    >
     <span
      class="max-w-36 truncate"
      x-text="item.label"
     ></span>
    </span>
   </template>
  </div>
 </template>
</button>
