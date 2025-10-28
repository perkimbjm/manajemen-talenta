<div
  class="absolute right-3 top-0.5 flex h-10 flex-shrink-0 items-center justify-center text-current transition-opacity duration-150"
  x-bind:class="{
      'opacity-60 group-hover:text-error': isActive(),
      'opacity-0 group-hover:opacity-40': !isActive(),
      '[ul:hover_&]:opacity-40 [ul:hover_&]:text-error': isActive() && !multiselect
  }"
 >
  <span
   class="!size-5 i-mdi-check"
   x-bind:class="{
       'group-hover:i-mdi-close': isActive(),
       '[ul:hover_.group:hover_&]:i-mdi-check': !isActive(),
       '[ul:hover_&]:i-mdi-close': isActive() &&
           !multiselect
   }"
  ></span>
 </div>