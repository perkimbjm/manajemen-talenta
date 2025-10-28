<div>
 <header class="mb-2 flex w-full gap-4 border-b px-4 pb-2 text-left text-xl font-semibold">
  <h3>Profil</h3>
  @if (!!$this->employee)
   <div
    class="tooltip tooltip-left ml-auto"
    data-tip="Sinkronisasi Profil"
   >
    <button
     class="btn btn-primary btn-sm h-auto min-h-0 p-1"
     x-on:click="() => $wire.syncProfile()"
    >
     <span class="i-mdi-sync h-4 w-4"></span>
    </button>
   </div>
  @endif
 </header>

 @props(['employee'])

 <table class="table self-start">
  <tbody>
   <tr class="hover">
    <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">NIP</th>
    <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
    <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->nip }}</td>
   </tr>
   <tr class="hover">
    <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Nama Pegawai</th>
    <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
    <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->full_name }}</td>
   </tr>
   <tr class="hover">
    <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Golongan</th>
    <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
    <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->order }}</td>
   </tr>
   <tr class="hover">
    <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Pangkat</th>
    <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
    <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->rank }}</td>
   </tr>
   <tr class="hover">
    <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Jenis Jabatan</th>
    <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
    <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->position_type }}</td>
   </tr>
   <tr class="hover">
    <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Jabatan</th>
    <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
    <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->position_name }}</td>
   </tr>
   <tr class="hover">
    <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Eselon</th>
    <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
    <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->echelon }}</td>
   </tr>
   <tr class="hover">
    <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">SKPD</th>
    <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
    <td class="px-1 py-2 align-top font-semibold uppercase">{{ $this->employee?->unit?->name }}</td>
   </tr>
   <tr class="hover">
    <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Unit Kerja</th>
    <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
    <td class="px-1 py-2 align-top font-semibold uppercase">{{ $this->employee?->work_unit }}</td>
   </tr>
   <tr class="hover">
    <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Pendidikan</th>
    <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
    <td class="px-1 py-2 align-top font-semibold">{{ $this->employee?->education_name }}</td>
   </tr>
  </tbody>
 </table>

</div>
