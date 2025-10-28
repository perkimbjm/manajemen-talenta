<?php

namespace Database\Seeders;

use App\Models\Occupation;
use App\Models\OccupationType;
use Illuminate\Database\Seeder;
use function Laravel\Prompts\spin;

use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\progress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OccupationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->seedingOccupationTypes();
    $this->seedingOccupations();
  }

  private function seedingOccupationTypes(): void
  {
    $response = null;
    spin(message: 'Fetching occupations...', callback: function () use (&$response) {
      $response = Http::get('https://sipejabat-v2.banjarmasinkota.go.id/api/occupation-types');
    });

    if (@$response['status'] !== 'success') {
      throw new \Exception("Occupation Types api get error", 1);
    }

    $data = $response['data'];
    $total = count($data);

    $this->command->getOutput()->info("Occupation types fetched successfully {$total} records");

    $this->command->getOutput()->info('Preparing dataset...');

    spin(
      message: "Importing Occupation types...",
      callback: function () use ($data) {
        OccupationType::upsert(collect($data)->select(['id', 'code', 'name', 'acronym', 'group', 'level'])->toArray(), ['code']);
      }
    );

    $this->command->getOutput()->success("Occupation types imported successfully");
  }

  private function seedingOccupations(): void
  {
    $response = null;
    spin(message: 'Fetching occupations...', callback: function () use (&$response) {
      $response = Http::get('https://sipejabat-v2.banjarmasinkota.go.id/api/occupations');
    });

    if (@$response['status'] !== 'success') {
      throw new \Exception("Occupations api get error", 1);
    }

    $total = count($response['data']);

    $this->command->getOutput()->info("Occupations fetched successfully {$total} records");

    $this->command->getOutput()->info('Preparing dataset...');

    $chunkSize = 500;

    $chunks = collect($response['data'])->chunk($chunkSize);

    progress(
      label: "Importing Occupations in chunks ({$chunkSize})...",
      steps: $chunks,
      callback: function ($chunk) {
        $dataset = $chunk->select([
          'id',
          'code',
          'name',
          'type_code',
          'echelon_code',
          'nomenclature',
          'group',
          'sequence',
          'grade',
          'level',
          'description',
        ])->toArray();
        Occupation::upsert($dataset, ['code']);
      }
    );

    $this->command->getOutput()->success("Occupations imported successfully");
  }
}
