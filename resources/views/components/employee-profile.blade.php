@props(['employee'])

<table class="table self-start">
 <caption class="mb-2 border-b px-4 pb-2 text-left text-xl font-semibold">Profil</caption>
 <tbody>
  <tr class="hover">
   <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">NIP</th>
   <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
   <td class="px-1 py-2 align-top font-semibold">{{ $employee?->nip }}</td>
  </tr>
  <tr class="hover">
   <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Nama Pegawai</th>
   <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
   <td class="px-1 py-2 align-top font-semibold">{{ $employee?->full_name }}</td>
  </tr>
  <tr class="hover">
   <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Golongan</th>
   <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
   <td class="px-1 py-2 align-top font-semibold">{{ $employee?->order }}</td>
  </tr>
  <tr class="hover">
   <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Pangkat</th>
   <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
   <td class="px-1 py-2 align-top font-semibold">{{ $employee?->rank }}</td>
  </tr>
  <tr class="hover">
   <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Jenis Jabatan</th>
   <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
   <td class="px-1 py-2 align-top font-semibold">{{ $employee?->position_type }}</td>
  </tr>
  <tr class="hover">
   <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Jabatan</th>
   <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
   <td class="px-1 py-2 align-top font-semibold">{{ $employee?->position_name }}</td>
  </tr>
  <tr class="hover">
   <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Eselon</th>
   <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
   <td class="px-1 py-2 align-top font-semibold">{{ $employee?->echelon }}</td>
  </tr>
  <tr class="hover">
   <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">SKPD</th>
   <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
   <td class="px-1 py-2 align-top font-semibold uppercase">{{ $employee?->unit?->name }}</td>
  </tr>
  <tr class="hover">
   <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Unit Kerja</th>
   <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
   <td class="px-1 py-2 align-top font-semibold uppercase">{{ $employee?->work_unit }}</td>
  </tr>
  <tr class="hover">
   <td class="w-40 py-2 pr-1 text-left align-top @2xl:w-32">Pendidikan</th>
   <td class="w-5 px-0 py-2 text-center align-top leading-[1.25]">:</td>
   <td class="px-1 py-2 align-top font-semibold">{{ $employee?->education_name }}</td>
  </tr>
 </tbody>
</table>
