<x-slot
 name="subheader"
 :sticky="false"
 class="flex-col gap-0 bg-transparent px-0 sm:px-0 lg:px-0"
>
 <div class="mx-auto w-full max-w-7xl bg-white px-4 sm:px-6 lg:px-8">
  <x-breadcrumbs :paths="['ASN Potensial']" />
 </div>
 <x-potensial-menu-tabs />
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
    <livewire:employee-profile :nip="$this->nip" />
    <div>
     <header class="mb-2 flex w-full gap-4 border-b pb-2 pl-4 text-left text-xl font-semibold">
      <h3>Penilaian</h3>
      @if ($this->employee)
       <div
        class="tooltip tooltip-left ml-auto"
        data-tip="Sinkronisasi Data"
       >
        <button
         class="btn btn-primary btn-sm h-auto min-h-0 p-1"
         x-on:click="() => $wire.syncAssessment()"
        >
         <span class="i-mdi-sync h-4 w-4"></span>
        </button>
       </div>
      @endif
     </header>
     <table class="table">
      <tbody>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top text-base font-bold">Nilai Akhir Potensial</td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top text-base font-semibold">
         {{ @$this->employee->assessment->get_potential_value }}
        </td>
        <td class="px-1 py-2 align-top text-base font-semibold">
         {{ @$this->employee->assessment->potential_label }}
        </td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">Nilai Asesmen Potensi</td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ @$this->employee->assessment->potential_percentage }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">Nilai Asesmen Kompetensi</td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ @$this->employee->assessment->competency_percentage }}</td>
       </tr>
       @php
        $track_record_evaluation = @$this->employee?->getTrackRecordEvaluation();
       @endphp
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">Nilai Rekam Jejak</td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ @$track_record_evaluation['percentage'] }}</td>
       </tr>
       @php
        $other_evaluation = @$this->employee?->getOtherConsiderationEvaluation();
       @endphp
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">Nilai Pertimbangan Lain</td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ @$other_evaluation['percentage'] }}</td>
       </tr>
       <tr class="hover">
        <td class="w-56 py-2 pr-1 text-left align-top">Preferensi Potensial</td>
        <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
        <td class="px-1 py-2 align-top font-semibold">{{ @$this->employee->assessment->potential_preference }}</td>
        <td class="px-1 py-2 align-top font-semibold">
         <div class="flex gap-4">
          <div
           class="tooltip tooltip-left ml-auto"
           data-tip="Ubah Preferensi Potensial"
          >
           @if (@$this->employee)
            @can('Ubah Nilai Preferensi')
             <button
              class="btn btn-primary btn-sm h-auto min-h-0 p-1"
              x-on:click="() => {
            $dispatch('show-modal', {
              component: 'potential-preference-form',
              title: 'Nilai Preferensi Potensial',
              arguments: {
                employee: '{{ $this->employee->id }}',
              }
            })
            }"
             >
              <span class="i-mdi-edit h-4 w-4"></span>
             </button>
            @endcan
           @endif
          </div>
         </div>
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
