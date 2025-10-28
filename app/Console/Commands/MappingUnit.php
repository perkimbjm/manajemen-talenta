<?php

namespace App\Console\Commands;

use App\Models\Mapping;
use App\Models\Unit;
use Illuminate\Console\Command;

class MappingUnit extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'mapping:units';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $dataset = $this->getDataset();
    $units = Unit::where('is_root', 1)->where('level', 1)->get();

    $mappings = [];

    // dd($units->where('name', 'Dinas Pekerjaan Umum dan Penataan Ruang'));

    $old_dataset = $this->getOldDataset();
    foreach ($old_dataset as $kd => $nm_skpd) {
      Unit::updateOrCreate([
        'code' => $kd,
      ], [
        'type' => 'Undecided',
        'name' => $nm_skpd,
      ]);

      Mapping::updateOrCreate([
        'group' => 'SKPD SIM-ASN',
        'prev_id' => $kd,
      ], [
        'group' => 'SKPD SIM-ASN',
        'prev_name' => $nm_skpd,
        'current_id' => $kd,
        'current_name' => $nm_skpd,
      ]);
    }

    foreach ($dataset as $kd => $nm_skpd) {
      $unit = $units->filter(fn($unit) => str($unit->name)->squish()->lower() == str($nm_skpd)->squish()->lower())->first();
      if (!$unit) {
        dd([$kd => $nm_skpd]);
      }

      $mappings[$kd] = [
        'group' => 'SKPD SIM-ASN',
        'prev_id' => $kd,
        'prev_name' => $nm_skpd,
        'current_id' => $unit->code,
        'current_name' => $unit->name,
      ];
      Mapping::updateOrCreate([
        'group' => 'SKPD SIM-ASN',
        'prev_id' => $kd,
        'current_id' => $unit->code,
      ], $mappings[$kd]);
    }

    // foreach ($units as $unit) {
    //   $data = collect($mappings)->where('current_id', $unit->code)->first();
    //   if (!$data) {
    //     if (@$old_dataset[$unit->code]) {
    //       continue;
    //     }
    //     dd($unit->code, $unit->toArray());
    //   }

    //   Mapping::updateOrCreate([
    //     'group' => $data['group'],
    //     'prev_id' => $data['prev_id'],
    //   ], $data);
    // }
  }

  private function getOldDataset()
  {
    $dataset = [
      "IDK0004" => "Satuan Polisi Pamong Praja dan Pemadam Kebakaran",
      "IDK0025" => "Badan Keuangan Daerah",
    ];

    return $dataset;
  }

  private function getDataset()
  {
    $dataset = [
      "IDK0001" => "Sekretariat Daerah",
      "IDK0002" => "Sekretariat Dewan Perwakilan Rakyat Daerah",
      "IDK0003" => "Inspektorat",
      "IDK0006" => "Dinas Pendidikan",
      "IDK0007" => "Dinas Kesehatan",
      "IDK0008" => "Dinas Pekerjaan Umum Dan Penataan Ruang",
      "IDK0009" => "Dinas Perumahan Rakyat Dan Kawasan Permukiman",
      "IDK0010" => "Dinas Sosial",
      "IDK0011" => "Dinas Pemberdayaan Perempuan Dan Perlindungan Anak",
      "IDK0012" => "Dinas Lingkungan Hidup",
      "IDK0013" => "Dinas Ketahanan Pangan, Pertanian Dan Perikanan",
      "IDK0014" => "Dinas Kependudukan dan Pencatatan Sipil",
      "IDK0015" => "Dinas Pengendalian Penduduk, Keluarga Berencana Dan Pemberdayaan Masyarakat",
      "IDK0016" => "Dinas Perhubungan",
      "IDK0017" => "Dinas Komunikasi, Informatika Dan Statistik",
      "IDK0018" => "Dinas Koperasi, Usaha Mikro Dan Tenaga Kerja",
      "IDK0019" => "Dinas Penanaman Modal Dan Pelayanan Terpadu Satu Pintu",
      "IDK0022" => "Dinas Perpustakaan Dan Kearsipan",
      "IDK0023" => "Dinas Perdagangan Dan Perindustrian",
      "IDK0024" => "Badan Kesatuan Bangsa dan Politik",
      "IDK0026" => "Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan",
      "IDK0027" => "Badan Kepegawaian Daerah, Pendidikan dan Pelatihan",
      "IDK0028" => "Badan Penanggulangan Bencana Daerah",
      "IDK0029" => "Kecamatan Banjarmasin Selatan",
      "IDK0030" => "Kecamatan Banjarmasin Tengah",
      "IDK0031" => "Kecamatan Banjarmasin Timur",
      "IDK0032" => "Kecamatan Banjarmasin Utara",
      "IDK0033" => "Kecamatan Banjarmasin Barat",
      "IDK0034" => "Badan Pengelolaan Keuangan, Pendapatan dan Aset Daerah",
      "IDK0035" => "Dinas Pemadam Kebakaran dan Penyelamatan",
      "IDK0036" => "Dinas Kebudayaan, Kepemudaan, Olahraga Dan Pariwisata",
      "IDK0020" => "Dinas Kebudayaan, Kepemudaan, Olahraga Dan Pariwisata",
      "IDK0037" => "Satuan Polisi Pamong Praja",
    ];

    return $dataset;
  }
}
