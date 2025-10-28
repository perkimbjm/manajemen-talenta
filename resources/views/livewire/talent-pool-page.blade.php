@assets()
 <!-- Load Chart.js -->
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
@endassets

<article
 class="flex flex-col"
 x-data="{
     selectedBox: null,
     infoToast: null,
 }"
 x-init="() => {
     infoToast = $toastify({
         type: 'info',
         message: 'Sedang memuat data talent pool...',
         duration: 60000,
     })
 }"
 x-on:talent-pool-chart-loaded="() => {
  infoToast.hideToast()
  $toastify({
      type: 'success',
      message: 'Berhasil menampilkan Talent pool chart',
      duration: 3000
  })
 }"
>
 <template x-if="!!selectedBox">
  <div class="fixed right-4 top-4 z-[99]">
   <button
    class="btn btn-sm"
    x-bind:style="{
        backgroundColor: selectedBox.color,
    }"
    x-on:click="() => {
      $dispatch('filter_nips', {
        nips: null
       })
       selectedBox = null
    }"
   >Reset</button>
  </div>
 </template>
 <section class="flex gap-4 px-4 py-4">
  <div class="aspect-[11/10] h-full w-full rounded-md border bg-white p-2 sm:max-h-[100vh] sm:max-w-[100vh]">
   <livewire:talent-pool-chart lazy />
  </div>
  <div class="drawer-end bottom-4 right-4 z-40 max-xl:drawer xl:drawer-open max-xl:fixed">
   <input
    id="talent-pool-legends-checkbox"
    type="checkbox"
    class="drawer-toggle"
   />
   <div class="drawer-content absolute bottom-0 right-0 z-40 xl:hidden">
    <!-- Page content here -->
    <label
     for="talent-pool-legends-checkbox"
     class="btn btn-primary drawer-button btn-sm"
    >
     <span class="[.drawer-toggle:checked+.drawer-content_&]:hidden">Lihat Keterangan</span>
     <span class="hidden [.drawer-toggle:checked+.drawer-content_&]:inline">Tutup</span>
    </label>
   </div>
   <div class="max-xl:drawer-side">
    <label
     for="talent-pool-legends-checkbox"
     aria-label="close sidebar"
     class="drawer-overlay"
    ></label>
    <div class="grid max-w-sm rounded-md border bg-white px-4 pb-16 pt-14 max-xl:h-full xl:pt-10">
     <table class="">
      <tbody>
       @foreach ($boxs->reverse() as $box)
        <tr x-data="{
            box: @js($box),
        }">
         <td
          class="h-5 w-10"
          @style(["background-color: {$box['color']}"])
         >
          <div class="flex items-center justify-center">
           {{ $box['label'] }}
          </div>
         </td>
         <td
          class="py-[12.5px] pl-3"
          x-bind:style="{
              backgroundColor: box.label == selectedBox?.label ? selectedBox?.color : undefined
          }"
         >
          <p class="leading-4">
           {{ $box['description'] }}
          </p>
         </td>
        </tr>
       @endforeach
      </tbody>
     </table>
    </div>
   </div>
  </div>
 </section>

 <section class="px-4 pb-4">
  <div
   class="card card-compact relative rounded-md bg-white"
   x-bind:style="{
       backgroundColor: selectedBox?.color
   }"
  >
   <div class="absolute inset-x-0 h-2 rounded-t-md"></div>
   <template x-if="!!selectedBox">
    <div
     class="absolute left-4 top-5 text-xl font-semibold"
     x-text="`Kotak ${selectedBox.label}`"
    ></div>
   </template>
   <div class="card-body [&_table]:!bg-white">
    <livewire:talent-pool-table />
   </div>
  </div>
 </section>
</article>
