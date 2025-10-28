<div
 popover="manual"
 x-data="Popover({})"
 x-show="open"
 x-transition.duration.300ms
 x-trap.noreturn="open"
 x-on:click.outside="closePopover()"
 x-bind:open="open"
 {{ $attributes->twMerge('popover') }}
>
 {{ $slot }}
</div>
