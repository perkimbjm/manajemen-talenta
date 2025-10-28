<x-slot name="header">
 <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
  Data Kegiatan
 </h2>
 <div class="ml-auto">
  <button
   class="btn btn-primary"
   x-on:click="() => {
       Livewire.dispatch('openModal', {
         component: 'modal.create-activity',
         modalAttributes: {
          title: 'Tambah Kegiatan'
         },
       })
     }"
  >
   Tambah
  </button>
 </div>
</x-slot>

<x-slot name="subheader">
 <x-breadcrumbs :paths="[
     'Data Master',
     [
         'title' => 'Program',
         'link' => route('master.programs'),
     ],
     'Kegiatan',
 ]" />
</x-slot>

<div class="px-4 py-6 sm:px-8">
 <!--
  Heads up! 👋

  This component comes with some `rtl` classes. Please remove them if they are not needed in your project.
-->

 <div class="card rounded-md bg-base-100 px-2 py-4 shadow-lg">
  <div class="overflow-x-auto">
   <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
    <thead class="ltr:text-left rtl:text-right">
     <tr>
      <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Kode</th>
      <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Nama Kegiatan</th>
      <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Program</th>
      <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Bidang Urusan</th>
     </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
     @forelse ($activities as $activity)
      <tr class="odd:bg-gray-50">
       <td class="whitespace-nowrap px-4 py-2 align-top font-mono text-gray-900">
        {{ $activity->code }}
       </td>
       <td class="px-4 py-2 align-top text-gray-700">{{ $activity->name }}</td>
       <td class="px-4 py-2 align-top text-gray-700">
        <div class="flex max-w-sm items-start gap-2 text-xs">
         <p class="shrink-0 font-mono">
          {{ $activity->program->code }}
         </p>
         <p class="">
          {{ $activity->program->name }}
         </p>
        </div>
       </td>
       <td class="px-4 py-2 align-top text-gray-700">
        <div class="flex max-w-sm items-start gap-2 text-xs">
         <p class="shrink-0 font-mono">
          {{ @$activity->sector->code ?? 'X.XX' }}
         </p>
         <p class="">
          {{ @$activity->sector->name ?? 'URUSAN X BIDANG XX' }}
         </p>
        </div>
       </td>
      </tr>
     @empty
      <tr class="odd:bg-gray-50">
       <td
        class="whitespace-nowrap px-4 py-4 text-center font-medium text-gray-400"
        colspan="4"
       >Belum Ada Data</td>
      </tr>
     @endforelse
    </tbody>
   </table>
  </div>

  <div class="mt-4 px-4">
   {{ $activities->onEachSide(1)->links() }}
  </div>
 </div>

</div>
