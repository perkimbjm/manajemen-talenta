<?php

namespace App\Imports;

use App\Models\Sector;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class SectorsImport implements
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
    if (@$row['bidang_urusan']) {
      $code = substr($row['bidang_urusan'], 0, 4);
      if ($code === 'X.XX') return null;

      $name = str($row['bidang_urusan'])->after($code)->trim();
      $affair_id = substr($code, 0, 1);

      if ($affair_id == 9) return null;
    } else {
      $affair_id = $row['kode_urusan'];
      $code = $row['kode_bidang_urusan'];
      $name = $row['nama_bidang_urusan'];
    }

    return new Sector([
      'affair_id' => $affair_id,
      'code' => $code,
      'name' => $name,
    ]);
  }

  public function batchSize(): int
  {
    return 1000;
  }

  public function uniqueBy()
  {
    return 'code';
  }
}
