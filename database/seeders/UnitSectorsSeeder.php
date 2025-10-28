<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use function Laravel\Prompts\info;

use function Laravel\Prompts\spin;
use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\warning;
use function Laravel\Prompts\progress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSectorsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $units = Unit::all();
    foreach ($units as $unit) {
      $this->importUnitSectorsFromSipejabat($unit);
    }
  }

  private function importUnitSectorsFromSipejabat(Unit $unit)
  {
    $response = null;
    spin(message: 'Fetching units...', callback: function () use (&$response, $unit) {
      $api_url = "https://sipejabat-v2.banjarmasinkota.go.id/api/units/{$unit->code}/fields";
      $response = Http::get($api_url)->json();
    });

    if ($response['status'] !== 'success') {
      warning($response['message']);
      return;
    }

    info("Fields unit {$unit->name}");

    $fields = collect($response['data']);

    $unit->sectors()->sync($fields->mapWithKeys(fn($field) => [$field['code'] => [
      'order' => $field['pivot']['order']
    ]])->toArray());
  }
}
