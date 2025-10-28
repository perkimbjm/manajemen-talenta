<x-slot
 name="subheader"
 :sticky="false"
>
 <x-breadcrumbs :paths="['Persentase Kehadiran Pegawai']" />
</x-slot>

<div class="mx-auto max-w-screen-xl px-4 py-4 sm:px-6">
 <article class="card-compact card card-bordered bg-white">
  <div
   class="card-body"
   x-on:delete-attendance="(ev) => {
    $dispatch('confirm-modal', {
      content: 'Apakah anda yakin akan menghapus data kehadiran ini?',
      confirmAction: () => $wire.deleteAttendance(ev.detail.id)
    })
   }"
  >
   <livewire:attendance-table />
  </div>
 </article>

 <template id="FormTemplate">
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
