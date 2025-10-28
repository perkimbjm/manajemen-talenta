<div
 class="mx-auto grid max-w-screen-xl gap-4 px-4 py-4 sm:px-6"
 x-data="{
     innovationId: null,
 }"
 x-on:upload-innovation="(ev) => {
this.innovationId = ev.detail.rowId
const dashboard = $uppy.getPlugin('Dashboard')
dashboard.openModal()
}"
 x-uppy="{
restrictions: {
allowedFileTypes: [
  'application/pdf',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
  ],
}
}"
 x-uppy:tus="{
removeFingerprintOnSuccess: true,
}"
 x-uppy:dashboard="{
inline: false,
note: 'Silahkan upload berkas yang diperlukan',
target: document.body,
}"
 x-uppy-on:complete="async (result) => {
if(!this.innovationId) {
 throw new Error('Inovasi belum dipilih')
}

$toastify({
  type: 'warning',
  message: 'Sedang mengupload berkas..',
})

if(result.successful.length > 0) {
  $toastify({
    type: 'warning',
    message: `Sedang mengupdate berkas...`,
  })

  $wire.uploadInnovation(this.innovationId, result.successful.map(file => {
    let filePath = file.uploadURL.split('/').at(-1)
    filePath = `upload/${filePath}.${file.extension}`

    return ({
      description: file.name,
      file_name: file.name,
      file_disk: 'local',
      file_path: filePath,
      file_type: file.type,
    })
  }))
  
  $uppy.getPlugin('Dashboard').closeModal()
  this.innovationId = null
}
}"
>
 @can('Lihat Pegawai')
  <div class="card card-compact bg-white">
   <div class="card-body">
    <x-select-employees />
   </div>
  </div>
 @endcan
 <article class="card card-compact bg-white @container">
  <div class="card-body">
   <header class="mb-2 flex gap-4 border-b">
    <h3 class="px-4 pb-2 text-left text-xl font-semibold">Kreatifitas / Inovasi</h3>
    <div class="ml-auto">
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
   </header>
   <livewire:innovation-table
    :nip="@$this->employee?->nip"
    lazy
   />
  </div>
 </article>

 <article class="card card-compact bg-white @container">
  <div class="card-body">
   <header class="mb-2 flex gap-4 border-b">
    <h3 class="px-4 pb-2 text-left text-xl font-semibold">Tim / Pokja</h3>
    <div class="ml-auto">
     <button
      class="btn btn-primary btn-sm"
      x-on:click="()=> {
          $dispatch('show-modal', {
            component: 'organization-form',
            title: 'Tambah Tim/Pokja',
            arguments: {
              nip: '{{ $this->employee?->nip }}',
            }
          })
        }"
     >
      Tambah Tim/Pokja
     </button>
    </div>
   </header>
   <livewire:organization-table
    :nip="@$this->employee?->nip"
    lazy
   />
  </div>
 </article>

 <article class="card card-compact bg-white @container">
  <div class="card-body">
   <header class="mb-2 flex gap-4 border-b">
    <h3 class="px-4 pb-2 text-left text-xl font-semibold">Tugas Tambahan</h3>
    <div class="ml-auto">
     <button
      class="btn btn-primary btn-sm"
      x-on:click="()=> {
          $dispatch('show-modal', {
            component: 'organization-form',
            title: 'Tambah Tugas Tambahan',
            arguments: {
              nip: '{{ $this->employee?->nip }}',
            }
          })
        }"
     >
      Tambah Tugas
     </button>
    </div>
   </header>
   <livewire:supporting-task-table
    :nip="@$this->employee?->nip"
    lazy
   />
  </div>
 </article>

 <article class="card card-compact bg-white @container">
  <div class="card-body">
   <header class="mb-2 flex gap-4 border-b">
    <h3 class="px-4 pb-2 text-left text-xl font-semibold">Penghargaan</h3>
    <div class="ml-auto">
     <button
      class="btn btn-primary btn-sm"
      x-on:click="()=> {
          $dispatch('show-modal', {
            component: 'organization-form',
            title: 'Tambah Penghargaan',
            arguments: {
              nip: '{{ $this->employee?->nip }}',
            }
          })
        }"
     >
      Tambah Penghargaan
     </button>
    </div>
   </header>
   <livewire:award-table
    :nip="@$this->employee?->nip"
    lazy
   />
  </div>
 </article>

 <article class="card card-compact bg-white @container">
  <div class="card-body">
   <header class="mb-2 flex gap-4 border-b">
    <h3 class="px-4 pb-2 text-left text-xl font-semibold">Pengalaman Organisasi</h3>
    <div class="ml-auto">
     <button
      class="btn btn-primary btn-sm"
      x-on:click="()=> {
          $dispatch('show-modal', {
            component: 'experience-form',
            title: 'Tambah Pengalaman Organisasi',
            arguments: {
              nip: '{{ $this->employee?->nip }}',
            }
          })
        }"
     >
      Tambah Pengalaman Organisasi
     </button>
    </div>
   </header>
   <livewire:experience-table
    :nip="@$this->employee?->nip"
    lazy
   />
  </div>
 </article>

 <article class="card card-compact bg-white @container">
  <div class="card-body">
   <header class="mb-2 flex gap-4 border-b">
    <h3 class="px-4 pb-2 text-left text-xl font-semibold">Sertifikat</h3>
    <div class="ml-auto">
     <button
      class="btn btn-primary btn-sm"
      x-on:click="()=> {
          $dispatch('show-modal', {
            component: 'sertificate-form',
            title: 'Tambah Sertifikat',
            arguments: {
              nip: '{{ $this->employee?->nip }}',
            }
          })
        }"
     >
      Tambah Sertifikat
     </button>
    </div>
   </header>
   <livewire:sertificate-table
    :nip="@$this->employee?->nip"
    lazy
   />
  </div>
 </article>


 <template id="DocumentPreviewTemplate">
  <div
   class="@container"
   max-width="screen-lg"
  >
   <div class="skeleton mb-8 h-7"></div>
   <div class="grid grid-cols-1 gap-6 @xl:grid-cols-2 @2xl:grid-cols-3">
    <template x-for="item in [1, 2, 3]">
     <div class="group skeleton relative flex gap-2 overflow-hidden rounded-md border bg-white p-2">
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
</div>
