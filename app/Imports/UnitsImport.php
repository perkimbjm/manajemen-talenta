<?php

namespace App\Imports;

use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class UnitsImport implements
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
    $name = trim($row['nama_skpd']);
    $description = '';
    $type = str($name)->before(' ');
    if (str_contains($name, 'Kota Banjarmasin')) {
      $name = str_replace('Kota Banjarmasin', '', $name);
      $description = 'Kota Banjarmasin';
    }

    return new Unit([
      'sector_code' => $row['kode_bidang_urusan'],
      'code'     => $row['kode_skpd'],
      'name'    => $name,
      'type' => $type,
      'description' => $description
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
