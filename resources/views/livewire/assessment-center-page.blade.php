<x-slot
 name="subheader"
 :sticky="true"
>
 <x-breadcrumbs :paths="['Asesmen Center']" />
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
 <article class="card card-compact mb-4 bg-white @container">
  <div class="card-body">
   <div class="grid gap-4 @2xl:grid-cols-2">
    <livewire:employee-profile :nip="$this->nip" />
    <div>
     <header class="mb-2 flex w-full gap-4 border-b px-4 pb-2 text-left text-xl font-semibold">
      <h3>Penilaian</h3>
      @if ($this->employee)
       <div
        class="tooltip tooltip-left ml-auto"
        data-tip="Sinkronisasi Data"
       >
        <button
         class="btn btn-primary btn-sm px-2"
         x-on:click="() => $wire.updateAssessment()"
        >
         <span class="i-mdi-sync h-4 w-4"></span>
        </button>
       </div>
      @endif
     </header>
     <table class="table self-start">
      <tbody>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">Penilaian Potensi</th>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->assessment?->potential }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">Penilaian Kompetensi</th>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->assessment?->competency }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">Job Person Match</th>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->assessment?->jpm }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">
         <div class="flex items-center gap-2">
          <span class="">a.</span>
          <span>
           Manajerial
          </span>
         </div>
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->assessment?->manajerial }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">
         <div class="flex items-center gap-2">
          <span class="">b.</span>
          <span>
           Sosialkultural
          </span>
         </div>
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->assessment?->sosialkultural }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">
         <div class="flex items-center gap-2">
          <span class="">c.</span>
          <span>
           Teknis
          </span>
         </div>
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->assessment?->teknis }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">Kesesuaian dengan Jabatan</th>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->assessment?->compatibility }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">Rekomendasi Pengembangan</th>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->assessment?->recommendation }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">Gap Kompetensi</th>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">
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
        <td class="w-56 py-2 pr-1 text-left align-top">
         <div class="flex items-center gap-2">
          <span class="">-</span>
          <span>
           Rekomendasi
          </span>
         </div>
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">
         {{ $this->employee?->assessment?->competencies?->first()?->recommendation }}
        </td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">
         <div class="flex items-center gap-2">
          <span class="">-</span>
          <span>
           Kesenjangan
          </span>
         </div>
        </td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">
         {{ $this->employee?->assessment?->competencies?->first()?->description }}
        </td>
       </tr>
      </tbody>
     </table>
    </div>
   </div>
  </div>
 </article>

 @if (@$this->employee?->assessment?->competencies)
  <article class="card card-compact bg-white">
   <div class="card-body">
    <table class="table-zebra table">
     <thead>
      <tr>
       <th>Kode</th>
       <th>Jenis Kompetensi</th>
       <th>Nilai Capaian</th>
       <th>JPM</th>
       <th>SKJ</th>
       <th>Gap</th>
      </tr>
     </thead>
     <tbody>
      @foreach ($this->employee->assessment->competencies as $competency)
       <tr>
        <td>{{ $competency->code }}</td>
        <td>{{ $competency->label }}</td>
        <td>{{ $competency->value }}</td>
        <td>{{ $competency->skj }}</td>
        <td>{{ $competency->skj }}</td>
        <td>{{ $competency->gap }}</td>
       </tr>
      @endforeach
     </tbody>
    </table>
   </div>
  </article>
 @endif

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
