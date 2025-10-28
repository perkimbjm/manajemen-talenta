<x-slot
 name="subheader"
 :sticky="false"
 class="flex-col gap-0 bg-transparent px-0 sm:px-0 lg:px-0"
>
 <div class="mx-auto w-full max-w-7xl bg-white px-4 sm:px-6 lg:px-8">
  <x-breadcrumbs :paths="['Manajemen Kinerja ASN']" />
 </div>
 <x-manja-menu-tabs />
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
   <div class="overflow-x-auto">
    <div class="mb-2 flex gap-4 border-b">
     <h3 class="px-4 pb-2 text-left text-xl font-semibold">Kreatifitas / Inovasi</h3>
     <div class="ml-auto">
      {{-- <button
       class="btn btn-warning btn-sm"
       x-on:click="()=> {
        $wire.updateAssessmentInnovation()
       }"
      >
       Update Rekap Inovasi
      </button> --}}
      <button
       class="btn btn-primary btn-sm"
       x-on:click="()=> {
        $dispatch('show-modal', {
          component: 'innovation-form',
          title: 'Tambah Kreatifitas / Inovasi',
          arguments: {
            nip: '{{ $this->employee?->nip }}',
          }
        })
       }"
      >
       Tambah Inovasi
      </button>
     </div>
    </div>
    <table class="table">
     <thead>
      <tr>
       <th>
        <label>
         <input
          type="checkbox"
          class="checkbox checkbox-sm checked:checkbox-primary"
         />
        </label>
       </th>
       <th>Nama Inovasi</th>
       <th>Lingkup</th>
       <th>Nilai</th>
       <th>Keterangan</th>
       <th>Berkas</th>
       <th>Status</th>
      </tr>
     </thead>
     <tbody>
      @foreach ($innovations as $innovation)
       <tr
        x-id="['row']"
        wire:key="{{ $innovation->id }}"
       >
        <th>
         <label>
          <input
           type="checkbox"
           class="checkbox checkbox-sm checked:checkbox-primary"
          />
         </label>
        </th>
        <td>
         {{ $innovation->name }}
        </td>
        <td>{{ $innovation->scope }}</td>
        <td>{{ $innovation->value }}</td>
        <td>
         {{ $innovation->description }}
        </td>
        <td>
         <div class="grid gap-2">
          @if ($user->hasExactRoles('Pegawai'))
           @foreach (['Piagam/Sertifikat', 'SK Inovasi', 'Surat Keterangan Atasan'] as $type)
            <button
             class="btn btn-sm w-48 justify-start text-left"
             x-on:click="$dispatch('show-modal', {
          component: 'innovation-document-form',
          title: 'Berkas Inovasi [{{ $type }}',
          arguments: {
            innovation: '{{ $innovation->id }}',
            type: '{{ $type }}',
          }
        })"
            >
             <span>
              {{ $type }}
             </span>
             @if ($innovation->getMedia($type)->count() > 0)
              <span class="i-mdi-check-circle-outline ml-auto h-4 w-4 text-success"></span>
             @endif
            </button>
           @endforeach
          @else
           @php
            $documents = $innovation->getMedia('*');
           @endphp
           @foreach ($documents as $document)
            <a
             href="/files/local/{{ $document?->id }}/{{ $document?->file_name }}"
             class="btn btn-sm w-48 justify-start text-left"
             target="_blank"
            >
             <span>
              {{ $document->collection_name }}
             </span>
             <span class="i-mdi-check-circle-outline ml-auto h-4 w-4 text-success"></span>
            </a>
           @endforeach
          @endif
         </div>
        </td>
        <td>
         @can('Ubah Status Penilaian')
          <button
           class="btn no-animation btn-sm"
           x-bind:id="$id('row', 'status-label')"
           x-on:click="$dom($id('row', 'status-menu')).dispatchEvent(new CustomEvent('show-popover'))"
          >
           {{ $innovation->status_label }}
          </button>
          <div
           x-data="Popover({})"
           x-bind:id="$id('row', 'status-menu')"
           x-show="open"
           x-transition
           x-cloak
           x-trap.noreturn="open"
           x-on:click.outside="closePopover()"
           x-anchored="{
            when: open,
            anchor: $dom($id('row', 'status-label')),
            offset: 8,
            padding: 8,
            placement: 'bottom-start',
          }"
           class="popover rounded-md border bg-white p-2 shadow"
          >
           <div class="grid gap-2">
            <button
             @disabled($innovation->status >= 1)
             class="btn btn-ghost btn-sm justify-start text-left"
             x-on:click="$dispatch('confirm-modal', {
              content: 'Apakah anda yakin akan menolak inovasi ini?',
              prompt: true,
              promptPlaceholder: 'Alasan Penolakan',
              disableConfirm: (promptInput) => promptInput === '',
              confirmAction: (promptInput) => $wire.changeStatus('{{ $innovation->id }}', 1, promptInput)
             })"
            >Ditolak</button>
            <button
             @disabled($innovation->status >= 2)
             class="btn btn-ghost btn-sm justify-start text-left"
             x-on:click="$dispatch('show-modal', {
              component: 'innovation-verify-form',
              title: 'Apakah anda yakin akan memverifikasi data ini?',
              arguments: {
                innovation: '{{ $innovation->id }}',
              }
             })"
            >Diverifikasi</button>
           </div>
          </div>
         @else
          {{ $innovation->status_label }}
         @endcan
        </td>
       </tr>
      @endforeach
     </tbody>
    </table>
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
