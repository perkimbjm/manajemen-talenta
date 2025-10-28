@props(['background' => 'white'])
<img
 src="{{ asset('images/brand-white-outline.png') }}"
 alt="logo"
 {{ $attributes->twMerge('w-[120px] h-[40px] object-contain') }}
/>
