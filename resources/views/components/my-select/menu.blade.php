<div
 x-bind:id="$id('select', 'menu')"
 x-data="Popover({})"
 x-transition.duration.300ms
 x-cloak
 x-show="open"
 x-trap.noreturn.noautofocus="open"
 x-on:click.outside="closePopover()"
 x-anchored="{
    anchor: $dom($id('select', 'trigger')),
    boundary: $dom($id('select', 'container')),
    offset: 8,
    when: open,
    placement: 'bottom',
 }"
 class="popover min-h-64 w-full"
>
 <div class="grid overflow-hidden rounded-md border bg-white shadow-lg">
  <template x-if="searchVisible">
   <div
    class="sticky top-0 z-10 bg-white px-2 py-2"
    role="searchbox"
   >
    <x-my-select.search-input />
   </div>
  </template>
  <ul
   class="relative flex max-h-[calc(70vh-2.5rem)] flex-col overflow-y-auto sm:max-h-[calc(50vh-2.5rem)] [[role=searchbox]+&]:max-h-[calc(70vh-6rem)] sm:[[role=searchbox]+&]:max-h-[calc(50vh-6rem)]"
   x-bind:class="{
       'h-[calc(70vh-2.5rem)] sm:h-[calc(50vh-2.5rem)]': total >= 10,
   }"
  >
   {{ $slot }}
  </ul>
 </div>
</div>
