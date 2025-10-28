<?php

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Seeder;

use function Laravel\Prompts\info;
use function Laravel\Prompts\progress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StageSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->loadAll();
  }

  public function loadAll()
  {
    info('Upserting stages');

    $dataset = $this->getDataset();
    progress(
      label: 'Upserting Stages',
      steps: $dataset,
      callback: function ($data) {
        $this->upsertStage($data);
      }
    );
  }

  public function upsertStage(mixed $data)
  {
    return Stage::updateOrCreate([
      'code' => $data['code'],
    ], $data);
  }


  public function getDataset()
  {
    return [
      [
        'code' => 61,
        'occupation_type_code' => 6,
        'group' => 'Jabatan Pelaksana',
        'name' => 'Pelaksana Penyelia',
        'level' => 4,
        'description' => 'Memiliki peran pengawasan atau supervisi teknis terhadap pelaksana lainnya.',
      ],
      [
        'code' => 62,
        'occupation_type_code' => 6,
        'group' => 'Jabatan Pelaksana',
        'name' => 'Pelaksana Lanjutan',
        'level' => 3,
        'description' => 'Untuk tugas administratif dengan tanggung jawab lebih besar.',
      ],
      [
        'code' => 63,
        'occupation_type_code' => 6,
        'group' => 'Jabatan Pelaksana',
        'name' => 'Pelaksana',
        'level' => 2,
        'description' => 'Melaksanakan tugas administratif tingkat dasar.',
      ],
      [
        'code' => 64,
        'occupation_type_code' => 6,
        'group' => 'Jabatan Pelaksana',
        'name' => 'Pelaksana Pemula',
        'level' => 1,
        'description' => 'Biasanya untuk tugas yang sederhana dan bersifat teknis.',
      ],
      [
        'code' => 91,
        'occupation_type_code' => 9,
        'group' => 'Fungsional Keahlian',
        'name' => 'Ahli Utama',
        'level' => 8,
        'description' => 'Pembina Utama Muda (IV/c) s.d. Pembina Utama (IV/e)',
      ],
      [
        'code' => 92,
        'occupation_type_code' => 9,
        'group' => 'Fungsional Keahlian',
        'name' => 'Ahli Madya',
        'level' => 7,
        'description' => 'Pembina (IV/a) s.d. Pembina Tingkat I (IV/b)',
      ],
      [
        'code' => 93,
        'occupation_type_code' => 9,
        'group' => 'Fungsional Keahlian',
        'name' => 'Ahli Muda',
        'level' => 6,
        'description' => 'Penata (III/c) s.d. Penata Tingkat I (III/d)',
      ],
      [
        'code' => 94,
        'occupation_type_code' => 9,
        'group' => 'Fungsional Keahlian',
        'name' => 'Ahli Pertama',
        'level' => 5,
        'description' => 'Penata Muda (III/a) s.d. Penata Muda Tingkat I (III/b)',
      ],
      [
        'code' => 95,
        'occupation_type_code' => 9,
        'group' => 'Fungsional Keterampilan',
        'name' => 'Penyelia',
        'level' => 4,
        'description' => 'Penata Muda (III/a) s.d. Penata Muda Tingkat I (III/b)',
      ],
      [
        'code' => 96,
        'occupation_type_code' => 9,
        'group' => 'Fungsional Keterampilan',
        'name' => 'Mahir',
        'level' => 3,
        'description' => 'Pengatur Muda (II/a) s.d. Pengatur Tingkat I (II/b)',
      ],
      [
        'code' => 97,
        'occupation_type_code' => 9,
        'group' => 'Fungsional Keterampilan',
        'name' => 'Terampil',
        'level' => 2,
        'description' => 'Juru (I/c) s.d. Juru Tingkat I (I/d)',
      ],
      [
        'code' => 98,
        'occupation_type_code' => 9,
        'group' => 'Fungsional Keterampilan',
        'name' => 'Pemula',
        'level' => 1,
        'description' => 'Juru Muda (I/a) s.d. Juru Muda Tingkat I (I/b)',
      ],
    ];
  }
}
