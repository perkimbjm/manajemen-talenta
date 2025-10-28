<x-slot
 name="subheader"
 :sticky="false"
 class="flex-col gap-0 bg-transparent px-0 sm:px-0 lg:px-0"
>
 <div class="mx-auto w-full max-w-7xl bg-white px-4 sm:px-6 lg:px-8">
  <x-breadcrumbs :paths="['Standar Kompetensi Jabatan']" />
 </div>
 <x-skj-menu-tabs />
</x-slot>

<div
 class="mx-auto max-w-screen-xl px-4 py-4 sm:px-6"
 x-data="{
     occupationId: null,
 }"
 x-on:upload-document="(ev) => {
  this.occupationId = ev.detail.rowId
  const dashboard = $uppy.getPlugin('Dashboard')
  dashboard.openModal()
 }"
 x-uppy="{
  restrictions: {
    allowedFileTypes: ['application/pdf'],
  }
 }"
 x-uppy:tus="{
  removeFingerprintOnSuccess: true,
 }"
 x-uppy:dashboard="{
  inline: false,
  note: 'cuma menerima berkas PDF',
  target: document.body,
 }"
 x-uppy-on:complete="async (result) => {
  if(!this.occupationId) {
    throw new Error('occupationId is null')
  }

  $toastify({
    type: 'warning',
    message: 'Sedang mengupload berkas..',
  })

  if(result.successful.length > 0) {
    console.log(`🚀 ~ x-uppy-on:complete ~ result.successful:`, result.successful)
    $toastify({
      type: 'success',
      message: `Berhasil Mengupload Berkas (${result.successful.length})`,
    })

    $toastify({
      type: 'warning',
      message: `Sedang mengupdate skj...`,
    })

    $wire.uploadSKJ(this.occupationId, result.successful.map(file => {
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
    this.occupationId = null
  }
 }"
>
 <article class="card card-bordered card-compact bg-white">
  <div class="card-body">
   <livewire:skj-table />
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
