<div
 x-id="['select']"
 x-bind:id="$id('select', 'container')"
 tabindex="1"
 {{ $attributes->twMerge('relative text-sm flex')->merge([
     'x-modelable' => 'values',
 ]) }}
>
 {{ $slot }}
</div>
