@props(['home' => route('dashboard'), 'paths' => []])

<div class="breadcrumbs text-sm">
 <ul>
  @if ($home)
   <li>
    <a
     href="{{ $home }}"
     x-vision
    >
     <i class="i-mdi-home h-4 w-4 text-base-content"></i>
    </a>
   </li>
  @endif
  @foreach ($paths as $path)
   <li>
    @if (is_array($path) && @$path['link'])
     <a
      href="{{ $path['link'] }}"
      x-vision
     >
      {{ $path['title'] }}
     </a>
    @else
     {{ is_array($path) ? @$path['title'] : $path }}
    @endif
   </li>
  @endforeach
 </ul>
</div>
