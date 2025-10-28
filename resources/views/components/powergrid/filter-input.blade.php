<div class="mt-1">
 @json($attributes->getAttributes())
 <input
  type="text"
  class="input h-9"
  {{ $attributes->get('inputAttributes') }}
  {{ $attributes->only(['placeholder']) }}
 />
</div>
