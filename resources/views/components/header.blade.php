@props(['sticky' => true])

<header
 @class([
     'border-b border-base-200 bg-base-100',
     'scroll-animation sticky top-0 z-40 ' => $sticky,
 ])
 x-bind:style="{
     '--animation-name': 'to-shadow',
     '--animation-range-start': 0,
     '--animation-range-end': '104px',
 }"
>
 <div {{ $attributes->twMerge('min-h-14 mx-auto flex gap-4 max-w-7xl items-center px-4 py-2 sm:px-6 lg:px-8') }}>
  {{ $slot }}
 </div>
</header>
