<?php

namespace Database\Seeders;

use App\Models\Affair;
use App\Models\Sector;

use Illuminate\Database\Seeder;
use function Laravel\Prompts\spin;

use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\warning;

use function Laravel\Prompts\progress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SectorSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->importSectorsFromSipejabat();
  }

  private function importSectorsFromSipejabat()
  {
    $response = null;
    spin(
      callback: function () use (&$response) {
        $api_url = 'https://sipejabat-v2.banjarmasinkota.go.id/api/fields';
        $response = Http::get($api_url)->json();
      }
    );

    if ($response['status'] !== 'success') {
      warning($response['message']);
      return;
    }

    $total = count($response['data']);
    info("Fetched fields from sipejabat {$total}");

    progress(
      label: 'Importing affairs...',
      steps: $response['data'],
      callback: function ($field) {
        $affair_id = str($field['code'])->before('.')->toInteger();
        Affair::updateOrCreate(
          [
            'id' => $affair_id,
          ],
          [
            'name' => $field['description']
          ]
        );

        Sector::updateOrCreate(
          [
            'affair_id' => $affair_id,
            'code' => $field['code'],
          ],
          [
            'name' => $field['name']
          ]
        );
      }
    );
  }
}
