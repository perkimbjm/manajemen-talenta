<x-slot
 name="subheader"
 :sticky="false"
 class="flex-col gap-0 bg-transparent px-0 sm:px-0 lg:px-0"
>
 <div class="mx-auto w-full max-w-7xl bg-white px-4 sm:px-6 lg:px-8">
  <x-breadcrumbs :paths="['ASN Potensial', 'Pertimbangan Lain']" />
 </div>
 <x-potensial-menu-tabs />
</x-slot>

<div
 class="mx-auto max-w-screen-xl px-4 py-4 sm:px-6"
 x-on:page-refresh.window="$wire.$refresh()"
>
 @can('Lihat Pegawai')
  <div class="card-compact card mb-4 bg-white">
   <div class="card-body">
    <x-select-employees />
   </div>
  </div>
 @endcan
 <article class="card-compact card bg-white @container">
  <div class="card-body">
   @php
    $total = 0;
   @endphp
   <header class="mb-2 flex w-full gap-4 border-b pb-2 pl-4 text-left text-xl font-semibold">
    <h3>PERTIMBANGAN LAIN</h3>
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
    <thead>
     <tr>
      <th>Unsur Penilaian</th>
      <th>Bobot</th>
      <th class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</th>
      <th>Instrumen Penilaian</th>
      <th class="text-center">Standar Nilai</th>
      <th class="text-center">Nilai Talenta</th>
     </tr>
    </thead>
    <tbody>
     @php
      $experience_evaluation = $this->employee?->getOrgExperienceEvaluation();
      $total += @$experience_evaluation['value'];
     @endphp
     <tr class="hover">
      <td class="w-56 py-2 pr-1 text-left align-top">Pengalaman Organisasi</td>
      <td class="w-10 px-0 py-2 text-center align-top leading-[1.25]">5%</td>
      <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
      <td class="py-2 align-top font-semibold">
       {{ @$experience_evaluation['name'] }}
      </td>
      <td class="py-2 text-center align-top font-semibold">
       {{ @$experience_evaluation['value'] }}
      </td>
      <td class="py-2 text-center align-top font-semibold">
       {{ round(@$experience_evaluation['value'] * 0.05, 2) }}
      </td>
     </tr>
     @php
      $discipline_evaluation = $this->employee?->getDisciplineEvaluation();
      $total += @$discipline_evaluation['value'];
     @endphp
     <tr class="hover">
      <td class="w-56 py-2 pr-1 text-left align-top">Disiplin</td>
      <td class="w-10 px-0 py-2 text-center align-top leading-[1.25]">5%</td>
      <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
      <td class="py-2 align-top font-semibold">
       {{ @$discipline_evaluation['description'] }}
      </td>
      <td class="py-2 text-center align-top font-semibold">
       {{ @$discipline_evaluation['value'] }}
      </td>
      <td class="py-2 text-center align-top font-semibold">
       {{ round(@$discipline_evaluation['value'] * 0.05, 2) }}
      </td>
     </tr>
     @php
      $sertificate_evaluation = $this->employee?->getSertificateEvaluation();
      $total += @$sertificate_evaluation['value'] ?? 0;
     @endphp
     <tr class="hover">
      <td class="w-56 py-2 pr-1 text-left align-top">
       Memiliki Sertifikat Pengadaan Barang Dan Jasa (PBJ) Atau Sertifikat Keahlian Lainnya
      </td>
      <td class="w-10 px-0 py-2 text-center align-top leading-[1.25]">5%</td>
      <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
      <td class="py-2 align-top font-semibold">
       {{ @$sertificate_evaluation['name'] }}
      </td>
      <td class="py-2 text-center align-top font-semibold">
       {{ @$sertificate_evaluation['value'] }}
      </td>
      <td class="py-2 text-center align-top font-semibold">
       {{ round(@$sertificate_evaluation['value'] * 0.05, 2) }}
      </td>
     </tr>
    </tbody>
    <tbody>
     @php
      $total_average = $total / 3;
     @endphp
     <tr class="hover">
      <th class="w-56 py-2 pr-1 text-left align-top">REKAP PENILAIAN</th>
      <th class="w-10 px-0 py-2 text-center align-top leading-[1.25]">15%</th>
      <th class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</th>
      <th class="py-2 align-top font-semibold">
       Total Nilai {{ $total }} / 7 Unsur Penilaian
      </th>
      <th class="py-2 text-center align-top font-semibold">
       {{ round($total_average, 2) }}
      </th>
      <th class="py-2 text-center align-top font-semibold">
       {{ round($total_average * 0.15, 2) }}
      </th>
     </tr>
    </tbody>
   </table>
  </div>
 </article>

 <section class="card-compact card mt-4 bg-white">
  <div class="card-body">
   <div class="card-title">
    <h3>Pengalaman Organisasi</h3>
    <div class="ml-auto">
     <div
      class="tooltip tooltip-left"
      data-tip="Tambah Experience"
     >
      <button
       @disabled(!$this->nip)
       type="button"
       class="btn btn-primary btn-sm"
       x-on:click="$dispatch('show-modal', {
         component: 'experience-form',
         title: 'Tambah Pengalaman Organisasi',
         arguments: {
           nip: '{{ $this->nip }}',
         }
       })"
      >
       <span class="i-mdi-plus h-4 w-4"></span>
      </button>
     </div>
    </div>
   </div>
   <livewire:experience-table :nip="@$this->employee?->nip" />
  </div>
 </section>

 <section class="card-compact card mt-4 bg-white">
  <div class="card-body">
   <div class="card-title">
    <h3>Disiplin / Kehadiran</h3>
   </div>
   <livewire:discipline-table :nip="@$this->employee?->nip" />
  </div>
 </section>

 <section class="card-compact card mt-4 bg-white">
  <div class="card-body">
   <div class="card-title">
    <h3>Sertifikat</h3>
    <div class="ml-auto">
     <div
      class="tooltip tooltip-left"
      data-tip="Tambah Sertifikat"
     >
      <button
       @disabled(!$this->nip)
       type="button"
       class="btn btn-primary btn-sm"
       x-on:click="$dispatch('show-modal', {
        component: 'sertificate-form',
        title: 'Tambah Sertifikat',
        arguments: {
          nip: '{{ $this->nip }}',
        }
      })"
      >
       <span class="i-mdi-plus h-4 w-4"></span>
      </button>
     </div>
    </div>
   </div>
   <livewire:sertificate-table :nip="@$this->employee?->nip" />
  </div>
 </section>

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
