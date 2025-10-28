<li
 class="group relative flex cursor-pointer items-center py-3 pl-5 pr-2 hover:bg-base-200 focus:bg-base-200 focus:outline-none"
 x-data="{
     item: getItem(data),
     isActive: () => selecteds.some(selected => getItem(selected).value == getItem(data).value),
 }"
 x-bind:class="{
     'bg-base-300': isActive(),
 }"
 tabindex="0"
 x-on:click="toggleItem(item)"
>
 {{ $slot }}
</li>
