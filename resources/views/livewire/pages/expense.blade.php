<x-slot name="header">
 <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
  Rekening Belanja
 </h2>
 <div class="ml-auto">
  <button
   class="btn btn-primary"
   x-on:click="() => {
        Livewire.dispatch('openModal', {
          component: 'modal.create-expense',
          modalAttributes: {
           title: 'Tambah Rekening Belanja'
          },
        })
      }"
  >
   Tambah
  </button>
 </div>
</x-slot>

<x-slot name="subheader">
 <div class="breadcrumbs text-sm">
  <ul>
   <li>
    <a
     href="{{ route('dashboard') }}"
     x-vision
    >
     <i class="i-mdi-home h-4 w-4 text-base-content"></i>
    </a>
   </li>
   <li>Master</li>
   <li>Rekening Belanja</li>
  </ul>
 </div>
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
      <th class="whitespace-nowrap px-4 py-2 text-left font-medium">Kode</th>
      <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Nama Rekening</th>
      <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Created At</th>
     </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
     @forelse ($expenses as $expense)
      <tr class="odd:bg-gray-50">
       <td class="whitespace-nowrap px-4 py-2 font-mono text-gray-900">{{ $expense->code }}</td>
       <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $expense->name }}</td>
       <td class="whitespace-nowrap px-4 py-2 text-gray-700">
        <output class="text-sm text-gray-500">
         {{ $expense->created_at }}
        </output>
       </td>
      </tr>
     @empty
      <tr class="odd:bg-gray-50">
       <td
        class="whitespace-nowrap px-4 py-4 text-center font-medium text-gray-400"
        colspan="3"
       >Belum Ada Data</td>
      </tr>
     @endforelse
    </tbody>
   </table>
  </div>

  <div class="mt-4 px-4">
   {{ $expenses->onEachSide(1)->links() }}
  </div>
 </div>

</div>
