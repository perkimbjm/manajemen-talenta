<?php

namespace App\Imports;

use App\Models\Fund;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class FundsImport implements
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
    if ($row['kode_sumber_dana'] == null) {
      return null;
    }

    return new Fund([
      'code'     => $row['kode_sumber_dana'],
      'name'    => $row['nama_sumber_dana'],
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
