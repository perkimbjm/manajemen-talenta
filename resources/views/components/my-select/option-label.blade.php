<div class="flex flex-col">
 <span x-tash="item.label">{item.label}</span>
 <template x-if="!!item.description">
  <span
   class="text-gray-500"
   x-tash="item.description"
  >
   {item.description}
  </span>
 </template>
</div>
