@php
 $tabs = [
     [
         'title' => 'Jenis Jabatan',
         'link' => route('features.skj'),
     ],
     [
         'title' => 'JPT',
         'link' => route('features.skj.struktural', [
             'occupation_type' => '3',
         ]),
     ],
     [
         'title' => 'Administrator',
         'link' => route('features.skj.struktural', [
             'occupation_type' => '4',
         ]),
     ],
     [
         'title' => 'Pengawas',
         'link' => route('features.skj.struktural', [
             'occupation_type' => '5',
         ]),
     ],
     [
         'title' => 'Pelaksana',
         'link' => route('features.skj.staff', [
             'occupation_type' => '6',
         ]),
     ],
     [
         'title' => 'Fungsional',
         'link' => route('features.skj.staff', [
             'occupation_type' => '9',
         ]),
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
