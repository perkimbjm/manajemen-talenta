<div>
 <div
  class="pointer-events-none absolute inset-y-0 right-2 flex max-h-40 flex-shrink-0 items-center text-current opacity-50"
 >
  <span class="i-mdi-chevron-up-down h-5 w-5"></span>
 </div>
 <template x-if="selecteds.length">
  <button
   class="absolute inset-y-0 right-6 flex max-h-40 flex-shrink-0 cursor-pointer items-center px-1 text-current text-gray-500 opacity-50 hover:bg-base-300 hover:text-rose-500"
   title="Reset"
   x-on:click.stop="() => {
    resetSelecteds()
  }"
  >
   <span class="i-mdi-close h-4 w-4"></span>
  </button>
 </template>
</div>
