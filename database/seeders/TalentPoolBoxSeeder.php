<?php

namespace Database\Seeders;

use App\Models\TalentPoolBox;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Laravel\Prompts\progress;

class TalentPoolBoxSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $dataset = $this->getBoxs();
    progress(
      label: 'Creating Talent Pool Boxes',
      steps: $dataset,
      callback: function ($data) {
        TalentPoolBox::updateOrCreate([
          "id" => $data["label"],
        ], [
          'label' => $data['label'],
          'order' => $data['label'],
          'hsl' => $data['hsl'],
          'color' => "hsl({$data['hsl']})",
          'min_potential_value' => $data['xMin'],
          'max_potential_value' => $data['xMax'],
          'min_performance_value' => $data['yMin'],
          'max_performance_value' => $data['yMax'],
          'description' => $data['description'],
        ]);
      }
    );
  }

  public static function getBoxs()
  {
    // X => Potensial
    // Y => Kinerja
    return collect([
      [
        'hsl' => '0,100%,50%',
        'label' => 1,
        'description' => 'Kinerja di bawah ekspektasi dan potensial rendah',
        'xMin' => 20,
        'xMax' => 46,
        'yMin' => 20,
        'yMax' => 46,
      ],
      [
        'hsl' => '11,90%,80%',
        'label' => 2,
        'description' => 'Kinerja sesuai ekspektasi dan potensial rendah',
        'xMin' => 20,
        'xMax' => 46,
        'yMin' => 46,
        'yMax' => 72,
      ],
      [
        'hsl' => '29,88%,77%',
        'label' => 3,
        'description' => 'Kinerja di bawah ekspektasi dan potensial menengah',
        'xMin' => 46,
        'xMax' => 72,
        'yMin' => 20,
        'yMax' => 46,
      ],
      [
        'hsl' => '60,100%,50%',
        'label' => 4,
        'description' => 'Kinerja di atas ekspektasi dan potensial rendah',
        'xMin' => 20,
        'xMax' => 46,
        'yMin' => 72,
        'yMax' => 100,
      ],
      [
        'hsl' => '45,100%,50%',
        'label' => 5,
        'description' => 'Kinerja sesuai ekspektasi dan potensial menengah',
        'xMin' => 46,
        'xMax' => 72,
        'yMin' => 46,
        'yMax' => 72,
      ],
      [
        'hsl' => '184,53%,71%',
        'label' => 6,
        'description' => 'Kinerja di bawah ekspektasi dan potensial tinggi',
        'xMin' => 72,
        'xMax' => 100,
        'yMin' => 20,
        'yMax' => 46,
      ],
      [
        'hsl' => '137,53%,57%',
        'label' => 7,
        'description' => 'Kinerja di atas ekspektasi dan potensial menengah',
        'xMin' => 46,
        'xMax' => 72,
        'yMin' => 72,
        'yMax' => 100,
      ],
      [
        'hsl' => '136,53%,43%',
        'label' => 8,
        'description' => 'Kinerja sesuai ekspektasi dan potensial tinggi',
        'xMin' => 72,
        'xMax' => 100,
        'yMin' => 46,
        'yMax' => 72,
      ],
      [
        'hsl' => '136,53%,33%',
        'label' => 9,
        'description' => 'Kinerja di atas ekspektasi dan potensial tinggi',
        'xMin' => 72,
        'xMax' => 100,
        'yMin' => 72,
        'yMax' => 100,
      ],
    ]);
  }
}
