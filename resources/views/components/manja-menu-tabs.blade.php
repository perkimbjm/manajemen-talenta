@php
 $tabs = [
     [
         'title' => 'Hasil Penilaian',
         'link' => route('features.manja'),
     ],
     [
         'title' => 'Kreatifitas/Inovasi',
         'link' => route('features.innovations'),
     ],
     [
         'title' => 'Partisipasi TIM/Pokja',
         'link' => route('features.organizations'),
     ],
     [
         'title' => 'Tugas Pendukung',
         'link' => route('features.supportingTasks'),
     ],
 ];
@endphp

<div class="mx-auto w-full max-w-7xl overflow-x-auto bg-white px-4 pt-2 sm:px-6 lg:px-8">
 <div
  role="tablist"
  class="tabs tabs-lifted [&>.tab]:[--tab-bg:var(--fallback-b2,oklch(var(--b2)/1))]"
 >
  @foreach ($tabs as $tab)
   <a
    role="tab"
    x-data="{
        url: new URL(`{{ $tab['link'] }}`),
    }"
    x-bind:href="`${url}?${$store.location.params}`"
    wire:navigate
    @class(['tab', 'tab-active' => $tab['link'] == request()->url()])
   >
    <div class="line-clamp-1">
     {{ $tab['title'] }}
    </div>
   </a>
  @endforeach
 </div>
</div>
