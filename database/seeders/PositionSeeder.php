<?php

namespace Database\Seeders;

use App\Models\Stage;
use App\Models\Mapping;

use App\Models\Position;
use App\Models\Occupation;

use Illuminate\Database\Seeder;
use function Laravel\Prompts\spin;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\warning;
use function Laravel\Prompts\progress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->loadJpt();
    $this->loadAdministrator();
    $this->loadPengawas();
    $this->loadPelaksana();
    $this->loadFungsional();
  }

  public function loadJpt()
  {
    $dataset = $this->fetchData('?facets[type]=Pimpinan%20Tinggi%20Pratama');
    if (!$dataset) return;

    $stages = Stage::all();
    $occupations = Occupation::with('type')->get();
    $mappings = Mapping::where('group', 'Stage Sipejabat V2')->get();

    $this->upsertPositions($dataset, $stages, $occupations, $mappings);
  }

  public function loadAdministrator()
  {
    $dataset = $this->fetchData('?facets[type]=Administrator');
    if (!$dataset) return;

    $stages = Stage::all();
    $occupations = Occupation::with('type')->get();
    $mappings = Mapping::where('group', 'Stage Sipejabat V2')->get();

    $this->upsertPositions($dataset, $stages, $occupations, $mappings);
  }

  public function loadPengawas()
  {
    $dataset = $this->fetchData('?facets[type]=Pengawas');
    if (!$dataset) return;

    $stages = Stage::all();
    $occupations = Occupation::with('type')->get();
    $mappings = Mapping::where('group', 'Stage Sipejabat V2')->get();

    $this->upsertPositions($dataset, $stages, $occupations, $mappings);
  }

  public function loadPelaksana()
  {
    $dataset = $this->fetchData('?facets[type]=Pelaksana');
    if (!$dataset) return;

    $stages = Stage::all();
    $occupations = Occupation::with('type')->get();
    $mappings = Mapping::where('group', 'Stage Sipejabat V2')->get();

    $this->upsertPositions($dataset, $stages, $occupations, $mappings);
  }

  public function loadFungsional()
  {
    $dataset = $this->fetchData('?facets[type]=Fungsional');
    if (!$dataset) return;

    $stages = Stage::all();
    $occupations = Occupation::with('type')->get();
    $mappings = Mapping::where('group', 'Stage Sipejabat V2')->get();

    $this->upsertPositions($dataset, $stages, $occupations, $mappings);
  }

  public function upsertPositions($dataset, $stages, $occopations, $mappings)
  {
    progress(
      label: 'Upserting positions',
      steps: $dataset,
      callback: function ($data) use ($stages, $occopations, $mappings) {
        $this->upsertPosition($data, $stages, $occopations, $mappings);
      }
    );
  }

  public function upsertPosition($data, $stages, $occupations, $mappings)
  {
    $mapping = $mappings->where('prev_id', $data['stage_code'])->first();
    $occupation = $occupations->where('code', $data['occupation_code'])->first();

    if (!$occupation) {
      dd($data);
    }

    $stage = $stages->where('code', $mapping?->current_id)->first();
    if ($data['stage_code'] && !$stage) {
      dd($data);
    }

    try {
      $position = Position::updateOrCreate([
        'id' => $data['id'],
      ], [
        'code' => $data['code'],
        'name' => $data['name'],
        'grade' => $data['grade'],
        'root_code' => $data['root_code'],
        'unit_code' => $data['unit_code'],
        'sequence' => $data['sequence'],
        'parent_id' => $data['parent_id'],
        'is_structural' => $data['is_structural'],
        'is_head' => $data['is_head'],
        'type' => $data['type'],
        'occupation_type_code' => $occupation->type_code,
        'occupation_code' => $occupation->code,
        'level' => $stage?->level,
      ]);

      return $position;
    } catch (\Throwable $th) {
      // dd($th->getMessage());
      return $th;
    }
  }

  public function fetchData($params_url = '')
  {
    $response = null;
    $api_url = 'http://localhost:8001/api/positions' . $params_url;

    spin(
      message: 'Fetching positions...',
      callback: function () use (&$response, $api_url) {
        $response = Http::withOptions([
          'verify' => false,
        ])
          ->get($api_url)
          ->json();
      }
    );

    if (@$response['status'] != 'success') {
      warning('Failed to fetch positions: ' . @$response['message']);
    }

    return @$response['data'];
  }
}
