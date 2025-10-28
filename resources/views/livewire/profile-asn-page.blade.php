<x-slot
 name="subheader"
 :sticky="true"
>
 <x-breadcrumbs :paths="['Profil Talenta ASN']" />
</x-slot>

<div
 class="mx-auto max-w-screen-xl px-4 py-4 sm:px-6"
 x-on:page-refresh.window="$wire.$refresh()"
>
 @can('Lihat Pegawai')
  <div class="card card-compact mb-4 bg-white">
   <div class="card-body">
    <x-select-employees />
   </div>
  </div>
 @endcan

 <article class="card card-compact bg-white @container">
  <div class="card-body">
   <div class="grid gap-4 @2xl:grid-cols-2">
    <x-employee-profile :employee="$this->employee" />
    <div>
     <header class="mb-2 flex w-full gap-4 border-b px-4 pb-2 text-left text-xl font-semibold">
      <h3>Penilaian</h3>
      @if (!!$this->employee)
       <div
        class="tooltip tooltip-left ml-auto"
        data-tip="Sinkronisasi Profil"
       >
        <button
         class="btn btn-primary btn-sm h-auto min-h-0 p-1"
         x-on:click="() => $wire.syncAll()"
        >
         <span class="i-mdi-sync h-4 w-4"></span>
        </button>
       </div>
      @endif
     </header>
     <table class="table">
      <tbody>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top text-base font-bold">Nilai Akhir Kinerja</th>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top text-base font-bold">{{ $this->employee?->assessment?->performance_value }}</td>
        <td class="px-1 py-2 align-top text-base font-bold">
         <div class="flex justify-end gap-4">
          <span class="mr-auto">
           {{ $this->employee?->assessment?->performance_label }}
          </span>
         </div>
        </td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">
         <div class="flex items-center gap-2">
          <span class="i-mdi-circle h-2 w-2 text-gray-700"></span>
          <span>
           Nilai Kinerja Spesifik
          </span>
         </div>
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->assessment?->specific }}</td>
        <td class="px-1 py-2 align-top font-semibold">
         <div class="flex justify-end gap-4">
          <span class="mr-auto">
           {{ $this->employee?->assessment?->specific_label }}
          </span>
         </div>
        </td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">
         <div class="flex items-center gap-2">
          <span class="i-mdi-circle h-2 w-2 text-gray-700"></span>
          <span>
           Nilai Kinerja Generik
          </span>
         </div>
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td
         class="px-1 py-2 align-top font-semibold"
         colspan="2"
        >
         {{ $this->employee?->assessment?->generic_value }}
        </td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top text-base font-bold">Nilai Akhir Potensial</td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top text-base font-bold">
         {{ @$this->employee->assessment->get_potential_value }}
        </td>
        <td class="px-1 py-2 align-top text-base font-bold">
         {{ @$this->employee->assessment->potential_label }}
        </td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">
         <div class="flex items-center gap-2">
          <span class="i-mdi-circle h-2 w-2 text-gray-700"></span>
          <span>
           Nilai Asesmen Potensi
          </span>
         </div>
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ @$this->employee->assessment->potential_percentage }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">
         <div class="flex items-center gap-2">
          <span class="i-mdi-circle h-2 w-2 text-gray-700"></span>
          <span>
           Nilai Asesmen Kompetensi
          </span>
         </div>
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ @$this->employee->assessment->competency_percentage }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">
         <div class="flex items-center gap-2">
          <span class="i-mdi-circle h-2 w-2 text-gray-700"></span>
          <span>
           Nilai Rekam Jejak
          </span>
         </div>
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ @$this->employee->assessment->track_record_value }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">
         <div class="flex items-center gap-2">
          <span class="i-mdi-circle h-2 w-2 text-gray-700"></span>
          <span>
           Nilai Pertimbangan Lain
          </span>
         </div>
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ @$this->employee->assessment->other }}</td>
       </tr>

       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top text-base font-bold">
         Kotak Talenta
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td
         class="px-1 py-2 align-top text-base font-bold"
         colspan="2"
        >
         {{ @$this->employee->assessment->box_id }}
        </td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top text-base font-bold">
         Gap Kompetensi
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td
         class="px-1 py-2 align-top text-base font-bold"
         colspan="2"
        >
         @php
          $gap = $this->employee?->assessment?->competencies?->sum('gap');
         @endphp
         @if ($gap > 0)
          +{{ $gap }}
         @else
          {{ $gap }}
         @endif
        </td>
       </tr>

       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top text-base font-bold">
         Gap Kinerja
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td
         class="px-1 py-2 align-top text-base font-bold"
         colspan="2"
        >
         @if ($this->employee?->assessment?->specific >= 35)
          Tidak Ada Gap
         @else
          Masih Ada Gap
         @endif
        </td>
       </tr>
      </tbody>
     </table>
    </div>
   </div>
  </div>
 </article>

 <template id="OccupationStandardPreviewTemplate">
  <div
   class="@container"
   max-width="screen-lg"
  >
   <div class="skeleton mb-8 h-7"></div>
   <div class="grid grid-cols-1 gap-6 @xl:grid-cols-2 @2xl:grid-cols-3">
    <template x-for="item in [1, 2, 3]">
     <div class="group skeleton relative flex gap-2 overflow-hidden rounded-md bg-white p-2">
      <div class="skeleton flex h-full w-20 shrink-0 items-center justify-center rounded bg-rose-500/50">
       <span class="i-mdi-file-pdf h-full w-full text-white"></span>
      </div>
      <div class="flex h-full w-full flex-col gap-1">
       <div class="skeleton h-10"></div>
       <div class="skeleton mb-4 h-4">
       </div>
       <div class="skeleton mt-auto block h-10 rounded sm:hidden">
       </div>
      </div>
     </div>
    </template>
   </div>
  </div>
 </template>
</div>
