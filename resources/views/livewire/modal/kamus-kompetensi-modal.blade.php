<div>
@if($showModal)
<div
  class="flex fixed inset-0 z-50 justify-center items-center bg-black bg-opacity-50"
  x-show="$wire.showModal"
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
>
  <div class="w-full max-w-4xl max-h-[90vh] overflow-hidden bg-white rounded-lg shadow-xl">
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">Kamus Kompetensi Jabatan</h3>
        <p class="text-sm text-gray-600">{{ $name }}</p>
      </div>
      <button
        type="button"
        class="text-gray-400 hover:text-gray-600"
        wire:click="closeModal"
      >
        <i class="w-6 h-6 i-mdi-close"></i>
      </button>
    </div>

    <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
    @if($loading)
      <div class="flex justify-center py-8">
        <div class="loading loading-spinner loading-lg"></div>
        <span class="ml-2">Memuat data kompetensi...</span>
      </div>
    @elseif(empty($competencyData))
      <div class="py-8 text-center">
        <div class="text-gray-500">
          <i class="mb-2 text-4xl i-mdi-book-open-page-variant"></i>
          <p>Tidak ada data kompetensi ditemukan untuk jabatan ini</p>
          <p class="text-sm">Coba jabatan dengan nama yang berbeda</p>
        </div>
      </div>
    @else
      <div class="overflow-y-auto space-y-4 max-h-96">
        @foreach($competencyData as $item)
          <div class="card card-bordered bg-base-100">
            <div class="p-4 card-body">
              <h4 class="mb-3 text-base card-title">{{ $item['nama'] ?? 'N/A' }}</h4>

              <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <!-- Faktor 1: Pengetahuan yang dibutuhkan -->
                @if(isset($item['lv_faktor1']))
                  <div class="pl-3 border-l-4 border-blue-500">
                    <div class="text-xs font-medium tracking-wide text-blue-700 uppercase">Pengetahuan yang dibutuhkan</div>
                    <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor1'] }}</div>
                  </div>
                @endif

                <!-- Faktor 2: Pengawasan penyelia -->
                @if(isset($item['lv_faktor2']))
                  <div class="pl-3 border-l-4 border-green-500">
                    <div class="text-xs font-medium tracking-wide text-green-700 uppercase">Pengawasan penyelia</div>
                    <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor2'] }}</div>
                  </div>
                @endif

                <!-- Faktor 3: Pedoman -->
                @if(isset($item['lv_faktor3']))
                  <div class="pl-3 border-l-4 border-yellow-500">
                    <div class="text-xs font-medium tracking-wide text-yellow-700 uppercase">Pedoman</div>
                    <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor3'] }}</div>
                  </div>
                @endif

                <!-- Faktor 4: Kompleksitas -->
                @if(isset($item['lv_faktor4']))
                  <div class="pl-3 border-l-4 border-purple-500">
                    <div class="text-xs font-medium tracking-wide text-purple-700 uppercase">Kompleksitas</div>
                    <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor4'] }}</div>
                  </div>
                @endif

                <!-- Faktor 5: Ruang lingkup dan dampak -->
                @if(isset($item['lv_faktor5']))
                  <div class="pl-3 border-l-4 border-indigo-500">
                    <div class="text-xs font-medium tracking-wide text-indigo-700 uppercase">Ruang lingkup dan dampak</div>
                    <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor5'] }}</div>
                  </div>
                @endif

                <!-- Faktor 6: Sifat hubungan -->
                @if(isset($item['lv_faktor6']))
                  <div class="pl-3 border-l-4 border-pink-500">
                    <div class="text-xs font-medium tracking-wide text-pink-700 uppercase">Sifat hubungan</div>
                    <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor6'] }}</div>
                  </div>
                @endif

                <!-- Faktor 7: Tujuan hubungan -->
                @if(isset($item['lv_faktor7']))
                  <div class="pl-3 border-l-4 border-red-500">
                    <div class="text-xs font-medium tracking-wide text-red-700 uppercase">Tujuan hubungan</div>
                    <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor7'] }}</div>
                  </div>
                @endif

                <!-- Faktor 8: Tuntutan fisik -->
                @if(isset($item['lv_faktor8']))
                  <div class="pl-3 border-l-4 border-teal-500">
                    <div class="text-xs font-medium tracking-wide text-teal-700 uppercase">Tuntutan fisik</div>
                    <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor8'] }}</div>
                  </div>
                @endif

                <!-- Faktor 9: Lingkungan pekerja -->
                @if(isset($item['lv_faktor9']))
                  <div class="pl-3 border-l-4 border-orange-500">
                    <div class="text-xs font-medium tracking-wide text-orange-700 uppercase">Lingkungan pekerja</div>
                    <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor9'] }}</div>
                  </div>
                @endif
              </div>

              <!-- Additional info -->
              @if(isset($item['kelas']) && isset($item['nilai']))
                <div class="pt-3 mt-3 border-t border-gray-200">
                  <div class="flex justify-between text-sm">
                    <span class="font-medium">Kelas: {{ $item['kelas'] }}</span>
                    <span class="font-medium">Nilai: {{ $item['nilai'] }}</span>
                  </div>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @endif
    </div>

    <div class="flex justify-end p-6 border-t border-gray-200">
      <button
        type="button"
        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        wire:click="closeModal"
      >
        Tutup
      </button>
    </div>
  </div>
</div>
@endif
