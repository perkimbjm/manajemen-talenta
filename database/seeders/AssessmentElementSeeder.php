<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssessmentElementSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void {}

  private function getDataset()
  {
    return [
      [
        'name' => 'PENGALAMAN ORGANISASI',
        'weight' => 0.05,
        'instruments' => [
          [
            'name' => 'Menjadi Ketua organisasi dalam lingkup nasional',
            'value' => 100,
          ],
          [
            'name' => 'Menjadi pengurus (selain Ketua) organisasi dalam lingkup nasional',
            'value' => 80,
          ],
          [
            'name' => 'Menjadi Ketua organisasi dalam lingkup wilayah Propinsi',
            'value' => 70,
          ],
          [
            'name' => 'Menjadi pengurus (selain Ketua) organisasi dalam lingkup wilayah Propinsi',
            'value' => 60,
          ],
          [
            'name' => 'Menjadi Ketua organisasi dalam lingkup wilayah Kota/Kabupaten',
            'value' => 50,
          ],
          [
            'name' => 'Menjadi pengurus (selain Ketua) organisasi dalam lingkup wilayah Kota/Kabupaten',
            'value' => 40,
          ],
        ]
      ],
      [
        'name' => 'DISIPLIN',
        'weight' => 0.05,
      ],
      [
        'name' => 'MEMILIKI SERTIFIKAT PENGADAAN BARANG DAN JASA ATAU SERTIFIKAT KEAHLIAN LAINNYA',
        'weight' => 0.05,
      ],
    ];
  }
}
