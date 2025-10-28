<div
 class="relative"
 x-data="{
     loading: true
 }"
>
 <iframe
  src="{{ $url }}"
  frameborder="0"
  class="peer h-[calc(100vh-8rem)] w-full rounded"
  x-on:load="console.log('loaded'); loading = false"
 ></iframe>
 <div
  class="skeleton absolute inset-3 rounded text-center"
  x-show="loading"
  x-transition.opacity
 >
 </div>
 <div
  class="absolute bottom-2 right-2 flex"
  x-show="!loading"
  x-transition
 >
  <a
   href="{{ $url }}"
   class="btn btn-accent btn-sm text-white"
   target="_blank"
   referrerpolicy="no-referrer"
  >Full View</a>
 </div>
</div>
