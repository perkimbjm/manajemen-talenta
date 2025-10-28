<?php

namespace App\Imports;

use App\Models\Affair;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class AffairsImport implements
  ToModel,
  WithStartRow,
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
    return new Affair([
      'id'     => $row[2],
      'name'    => $row[3],
    ]);
  }

  public function startRow(): int
  {
    return 2;
  }

  public function batchSize(): int
  {
    return 1000;
  }

  public function uniqueBy()
  {
    return 'id';
  }
}
