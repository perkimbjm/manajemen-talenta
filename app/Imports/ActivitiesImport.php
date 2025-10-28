<?php

namespace App\Imports;

use App\Models\Activity;
use App\Models\Program;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ActivitiesImport implements
  ToModel,
  WithHeadingRow,
  WithBatchInserts,
  WithUpserts,
  WithProgressBar
{
  use Importable;

  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function model(array $row)
  {
    $zones = Program::zones();
    $zone = $zones->firstOrFail('label', $row['jenis_pemda']);
    $sector_code = substr($row['kode_program'], 0, 4);
    if ($sector_code === 'X.XX') {
      $sector_code = null;
    }

    if (str($sector_code)->startsWith('9.')) {
      return null;
    }

    return new Activity([
      'zone_id'  => $zone->id,
      'sector_code' => $sector_code,
      'program_code'     => $row['kode_program'],
      'code'     => $row['kode_kegiatan'],
      'name'    => $row['nama_kegiatan'],
    ]);
  }

  public function batchSize(): int
  {
    return 1000;
  }

  public function uniqueBy()
  {
    return ['zone_id', 'code'];
  }
}
