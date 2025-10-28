<form
 class="grid gap-4"
 x-on:submit.prevent="() => {
  $toastify({
    type: 'info',
    message: 'Menyimpan berkas...',
  })
  $uppy.upload()
 }"
 max-width="screen-md"
 x-data="{
     files: [],
 }"
 x-uppy="{
  restrictions: {
    allowedFileTypes: ['application/pdf'],
    maxNumberOfFiles: 1,
    minNumberOfFiles: 0,
  }
 }"
 x-uppy:tus="{
  removeFingerprintOnSuccess: true,
 }"
 x-uppy-on:file-added="(file) => {
  files.push(file)
}"
 x-uppy-on:file-removed="(file) => {
  files = files.filter((f) => f.id !== file.id)
}"
 x-uppy-on:complete="(result) => {
  const file = result.successful[0]

  if(!file) {
    $toastify({
      type: 'danger',
      message: 'Gagal menyimpan berkas!',
    })
    return
  }

  $wire.saveDocument(file)
}"
>
 <label class="form-control w-full">
  <div class="label">
   <span class="label-text">Nama Organisasi</span>
  </div>
  <input
   type="text"
   placeholder="Nama organisasi"
   class="input input-bordered w-full"
   readonly
   value="{{ $organization->name }}"
  />
 </label>

 <div class="form-control w-full">
  <div class="label">
   <span class="label-text">Berkas {{ $type }}</span>
  </div>
  <div
   class="flex items-center gap-4 rounded border px-3 py-2"
   x-show="!!$wire.organization_document_id"
   x-transition
  >
   <a
    href="/files/local/{{ $organization_document?->id }}/{{ $organization_document?->file_name }}"
    class="link text-primary"
    target="_blank"
   >
    {{ $organization_document?->name }}
   </a>

   <button
    class="btn btn-warning btn-sm ml-auto"
    type="button"
    x-on:click="() => {
        $dispatch('confirm-modal', {
          content: 'Apakah anda yakin akan menghapus berkas ini?',
          confirmAction: () => $wire.organization_document_id = null,
        })
       }"
   >
    <span class="i-mdi-trash h-5 w-5"></span>
   </button>
  </div>
  <div
   class="h-64"
   x-cloak
   x-show="!$wire.organization_document_id"
   x-transition
   x-uppy:dashboard="{
      inline: false,
      height: 256,
      inline: true,
      target: $el,
      width: '100%',
      hideUploadButton: true,
      note: 'cuma menerima berkas PDF',
     }"
  >
  </div>
 </div>

 <div class="mt-4 flex justify-end">
  <button
   class="btn btn-primary"
   x-bind:disabled="files.length === 0"
  >
   Simpan
  </button>
 </div>
</form>
