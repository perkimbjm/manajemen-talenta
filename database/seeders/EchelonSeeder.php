<?php

namespace Database\Seeders;

use App\Models\Echelon;
use Illuminate\Database\Seeder;

use function Laravel\Prompts\progress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EchelonSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->upsertEchelons();
  }

  private function upsertEchelons()
  {
    $dataset = $this->getDataset();

    progress(
      label: 'Upserting Echelons',
      steps: $dataset,
      callback: function ($echelon) {
        Echelon::updateOrCreate(
          [
            'id' => $echelon['id'],
          ],
          $echelon
        );
      }
    );
  }

  private function getDataset()
  {
    return [
      [
        'id' => 11,
        'name' => 'I.a',
        'group' => 'I',
        'section' => 'a',
        'order' => 1,
        'min_rank' => 45,
      ],
      [
        'id' => 12,
        'name' => 'I.b',
        'group' => 'I',
        'section' => 'b',
        'order' => 1,
        'min_rank' => 44,
      ],
      [
        'id' => 21,
        'name' => 'II.a',
        'group' => 'II',
        'section' => 'a',
        'order' => 2,
        'min_rank' => 43,
      ],
      [
        'id' => 22,
        'name' => 'II.b',
        'group' => 'II',
        'section' => 'b',
        'order' => 2,
        'min_rank' => 42,
      ],
      [
        'id' => 31,
        'name' => 'III.a',
        'group' => 'III',
        'section' => 'a',
        'order' => 3,
        'min_rank' => 41,
      ],
      [
        'id' => 32,
        'name' => 'III.b',
        'group' => 'III',
        'section' => 'b',
        'order' => 3,
        'min_rank' => 34,
      ],
      [
        'id' => 41,
        'name' => 'IV.a',
        'group' => 'IV',
        'section' => 'a',
        'order' => 4,
        'min_rank' => 33,
      ],
      [
        'id' => 42,
        'name' => 'IV.b',
        'group' => 'IV',
        'section' => 'b',
        'order' => 4,
        'min_rank' => 32,
      ],
      [
        'id' => 50,
        'name' => 'V',
        'group' => 'V',
        'section' => null,
        'order' => 5,
        'min_rank' => 31,
      ],
    ];
  }
}
