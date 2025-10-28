<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\EducationLevel;
use Illuminate\Database\Seeder;

use function Laravel\Prompts\progress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EducationLevelSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->upsertEducations();
  }

  private function upsertEducations()
  {
    $dataset = $this->getDataset();

    progress(
      label: 'Upserting Education Levels',
      steps: $dataset,
      callback: function ($data) {
        EducationLevel::updateOrCreate(
          ['id' => $data['id']],
          ['name' => $data['name'], 'standard_value' => $data['standard_value']]
        );
      }
    );
  }

  private function getDataset()
  {
    $dataset = [
      [
        'id' => 1,
        'name' => 'SD/Sederajat',
        'standard_value' => 5,
      ],
      [
        'id' => 2,
        'name' => 'SLTP/Sederajat',
        'standard_value' => 10,
      ],
      [
        'id' => 3,
        'name' => 'SLTA/Sederajat',
        'standard_value' => 20,
      ],
      [
        'id' => 4,
        'name' => 'Diploma 1',
        'standard_value' => 30,
      ],
      [
        'id' => 5,
        'name' => 'Diploma 2',
        'standard_value' => 30,
      ],
      [
        'id' => 6,
        'name' => 'Diploma 3',
        'standard_value' => 45,
      ],
      [
        'id' => 7,
        'name' => 'Diploma 4',
        'standard_value' => 60,
      ],
      [
        'id' => 8,
        'name' => 'Strata 1',
        'standard_value' => 60,
      ],
      [
        'id' => 9,
        'name' => 'Strata 2',
        'standard_value' => 80,
      ],
      [
        'id' => 10,
        'name' => 'Strata 3',
        'standard_value' => 100,
      ],
    ];

    return $dataset;
  }
}
