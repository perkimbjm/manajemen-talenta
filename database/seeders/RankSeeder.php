<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Seeder;

use function Laravel\Prompts\progress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RankSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->upsertRanks();
  }

  private function upsertRanks()
  {
    $dataset = $this->getDataset();

    progress(
      label: 'Upserting Ranks',
      steps: $dataset,
      callback: function ($rank) {
        Rank::updateOrCreate(
          [
            'id' => $rank['id'],
          ],
          $rank
        );
      }
    );
  }

  private function getDataset()
  {
    return [
      [
        'id' => 11,
        'name' => 'Juru Muda',
        'group' => 'I',
        'section' => 'a',
      ],
      [
        'id' => 12,
        'name' => 'Juru Muda Tingkat I',
        'group' => 'I',
        'section' => 'b',
      ],
      [
        'id' => 13,
        'name' => 'Juru',
        'group' => 'I',
        'section' => 'c',
      ],
      [
        'id' => 14,
        'name' => 'Juru Tingkat I',
        'group' => 'I',
        'section' => 'd',
      ],
      [
        'id' => 21,
        'name' => 'Pengatur Muda',
        'group' => 'II',
        'section' => 'a',
      ],
      [
        'id' => 22,
        'name' => 'Pengatur Muda Tingkat I',
        'group' => 'II',
        'section' => 'b',
      ],
      [
        'id' => 23,
        'name' => 'Pengatur',
        'group' => 'II',
        'section' => 'c',
      ],
      [
        'id' => 24,
        'name' => 'Pengatur Tingkat I',
        'group' => 'II',
        'section' => 'd',
      ],
      [
        'id' => 31,
        'name' => 'Penata Muda',
        'group' => 'III',
        'section' => 'a',
      ],
      [
        'id' => 32,
        'name' => 'Penata Muda Tingkat I',
        'group' => 'III',
        'section' => 'b',
      ],
      [
        'id' => 33,
        'name' => 'Penata',
        'group' => 'III',
        'section' => 'c',
      ],
      [
        'id' => 34,
        'name' => 'Penata Tingkat I',
        'group' => 'III',
        'section' => 'd',
      ],
      [
        'id' => 41,
        'name' => 'Pembina',
        'group' => 'IV',
        'section' => 'a',
      ],
      [
        'id' => 42,
        'name' => 'Pembina Tingkat I',
        'group' => 'IV',
        'section' => 'b',
      ],
      [
        'id' => 43,
        'name' => 'Pembina Utama Muda',
        'group' => 'IV',
        'section' => 'c',
      ],
      [
        'id' => 44,
        'name' => 'Pembina Utama Madya',
        'group' => 'IV',
        'section' => 'd',
      ],
      [
        'id' => 45,
        'name' => 'Pembina Utama',
        'group' => 'IV',
        'section' => 'e',
      ],
    ];
  }
}
