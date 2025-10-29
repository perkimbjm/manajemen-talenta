<x-slot
  name="subheader"
  :sticky="false"
  class="flex-col gap-0 px-0 bg-transparent sm:px-0 lg:px-0"
>
  <div class="px-4 mx-auto w-full max-w-7xl bg-white sm:px-6 lg:px-8">
    <x-breadcrumbs :paths="['Standar Kompetensi Jabatan', 'Kamus Kompetensi Jabatan']" />
  </div>
  <x-skj-menu-tabs />
</x-slot>

<div class="px-4 py-4 mx-auto max-w-screen-xl sm:px-6">
  <article class="bg-white card card-bordered card-compact">
    <div class="card-body">
      <div class="flex flex-col gap-4">
        <!-- Search and Filter Section -->
        <div class="flex flex-col gap-4 justify-between items-start sm:flex-row sm:items-center">
          <div class="flex flex-col gap-4 items-start sm:flex-row sm:items-center">
            <div class="form-control">
              <label class="label">
                <span class="label-text">Jenis Jabatan</span>
              </label>
              <select wire:model.live="selectedType" class="select select-bordered select-sm">
                <option value="fungsional">Fungsional</option>
                <option value="pelaksana">Pelaksana</option>
              </select>
            </div>
            <div class="form-control">
              <label class="label">
                <span class="label-text">Cari Jabatan</span>
              </label>
              <input
                type="text"
                wire:model.live.debounce.500ms="search"
                placeholder="Ketik nama jabatan..."
                class="w-full input input-bordered input-sm sm:w-64"
              />
            </div>
          </div>
          <div class="text-sm text-gray-500">
            @if($loading)
              <span class="loading loading-spinner loading-sm"></span> Memuat data...
            @else
              Menampilkan {{ count($competencyData) }} hasil
            @endif
          </div>
        </div>

        <!-- Results Section -->
        @if($loading)
          <div class="flex justify-center py-8">
            <div class="loading loading-spinner loading-lg"></div>
          </div>
        @elseif(empty($competencyData))
          <div class="py-8 text-center">
            <div class="text-gray-500">
              <i class="mb-2 text-4xl i-mdi-file-search"></i>
              <p>Tidak ada data kompetensi ditemukan</p>
              <p class="text-sm">Coba ubah kata kunci pencarian atau jenis jabatan</p>
            </div>
          </div>
        @else
          <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            @foreach($competencyData as $item)
              <div class="card card-bordered bg-base-100">
                <div class="p-4 card-body">
                  <h3 class="mb-3 text-lg card-title">{{ $item['nama'] ?? 'N/A' }}</h3>

                  <div class="space-y-3">
                    <!-- Faktor 1: Pengetahuan yang dibutuhkan -->
                    @if(isset($item['lv_faktor1']))
                      <div class="pl-3 border-l-4 border-blue-500">
                        <div class="text-sm font-medium text-blue-700">Pengetahuan yang dibutuhkan</div>
                        <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor1'] }}</div>
                      </div>
                    @endif

                    <!-- Faktor 2: Pengawasan penyelia -->
                    @if(isset($item['lv_faktor2']))
                      <div class="pl-3 border-l-4 border-green-500">
                        <div class="text-sm font-medium text-green-700">Pengawasan penyelia</div>
                        <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor2'] }}</div>
                      </div>
                    @endif

                    <!-- Faktor 3: Pedoman -->
                    @if(isset($item['lv_faktor3']))
                      <div class="pl-3 border-l-4 border-yellow-500">
                        <div class="text-sm font-medium text-yellow-700">Pedoman</div>
                        <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor3'] }}</div>
                      </div>
                    @endif

                    <!-- Faktor 4 -->
                    @if(isset($item['lv_faktor4']))
                      <div class="pl-3 border-l-4 border-purple-500">
                        <div class="text-sm font-medium text-purple-700">Kompleksitas</div>
                        <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor4'] }}</div>
                      </div>
                    @endif

                    <!-- Faktor 5 -->
                    @if(isset($item['lv_faktor5']))
                      <div class="pl-3 border-l-4 border-indigo-500">
                        <div class="text-sm font-medium text-indigo-700">Ruang lingkup dan dampak</div>
                        <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor5'] }}</div>
                      </div>
                    @endif

                    <!-- Faktor 6 -->
                    @if(isset($item['lv_faktor6']))
                      <div class="pl-3 border-l-4 border-pink-500">
                        <div class="text-sm font-medium text-pink-700">Sifat hubungan</div>
                        <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor6'] }}</div>
                      </div>
                    @endif

                    <!-- Faktor 7 -->
                    @if(isset($item['lv_faktor7']))
                      <div class="pl-3 border-l-4 border-red-500">
                        <div class="text-sm font-medium text-red-700">Tujuan hubungan</div>
                        <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor7'] }}</div>
                      </div>
                    @endif

                    <!-- Faktor 8 -->
                    @if(isset($item['lv_faktor8']))
                      <div class="pl-3 border-l-4 border-teal-500">
                        <div class="text-sm font-medium text-teal-700">Tuntutan fisik</div>
                        <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor8'] }}</div>
                      </div>
                    @endif

                    <!-- Faktor 9 -->
                    @if(isset($item['lv_faktor9']))
                      <div class="pl-3 border-l-4 border-orange-500">
                        <div class="text-sm font-medium text-orange-700">Lingkungan pekerja</div>
                        <div class="mt-1 text-sm text-gray-600">{{ $item['lv_faktor9'] }}</div>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </article>
</div>
