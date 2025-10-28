@props(['sticky' => true])

<div @class(['border-b border-base-200', 'sticky top-0 z-30' => $sticky])>
 <div {{ $attributes->twMerge('min-h-8 mx-auto flex gap-4 max-w-7xl items-center px-4 sm:px-6 lg:px-8 bg-base-100') }}>
  {{ $slot }}
 </div>
</div>
