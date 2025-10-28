@props(['link', 'icon', 'title', 'description' => null])

<a
 href="{{ $link }}"
 class="flex gap-4 rounded-md border bg-white p-4 transition hover:skeleton hover:bg-opacity-50 hover:shadow-xl active:animate-pulse hover:active:scale-95 hover:active:shadow-none sm:p-6"
 x-vision
>
 <img
  src="{{ $icon }}"
  alt="SKJ"
  loading="lazy"
  class="h-12 w-12 object-contain"
 />
 <div>
  <h3 class="mb-2 text-xl font-semibold uppercase">
   {{ $title }}
  </h3>
  <p>{{ $description }}</p>
 </div>
</a>
