@props(['pins' => [], 'pinsLength' => 4, 'xModel' => null])

@php
 if (!$xModel && empty($pins) && $pinsLength) {
     $pins = array_fill(0, $pinsLength, '');
 } else {
     $pinsLength = count($pins);
 }

 $gapCount = $pinsLength - 1;
@endphp

<div
 x-id="['pin-input']"
 x-data="{
     pins: @json(!!$xModel) ? {{ $xModel ?? '[]' }} : @js($pins),
     focusNextInput(index, value) {
         console.log(index, this.pins.length)
         if (index < this.pins.length - 1 && value !== '') {
             document.getElementById($id('pin-input', (index + 1))).focus();
         }
     },
     focusPrevInput(index, value) {
         if (value === '' && index > 0) {
             document.getElementById($id('pin-input', (index - 1))).focus();
         }
     },
     submitPin() {
         alert('Submitted PIN: ' + this.pins.join(''));
     }
 }"
 class="flex min-h-[2.5em] gap-x-3"
 @style(["min-width: calc(2.5em * {$pinsLength} + 0.75rem * {$gapCount})"])
>
 <template
  x-for="(pin, index) in pins"
  :key="index"
 >
  <input
   x-model="pins[index]"
   type="text"
   maxlength="1"
   class="input h-[2.5em] w-[2.5em] text-center text-[1em] !ring-offset-0 focus:border-primary focus:ring-1 focus:ring-primary disabled:pointer-events-none disabled:opacity-50"
   x-on:input="focusNextInput(index, $el.value)"
   x-on:keydown.backspace="focusPrevInput(index, $el.value)"
   x-bind:id="$id('pin-input', index)"
  />
 </template>
</div>
