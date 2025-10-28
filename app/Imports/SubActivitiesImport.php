<?php

namespace App\Imports;

use App\Models\SubActivity;
use App\Models\Program;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class SubActivitiesImport implements
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
    $sector_code = substr($row['kode_program'], 0, 4);
    if ($sector_code === 'X.XX') {
      $sector_code = null;
    }

    if (str($sector_code)->startsWith('9.')) {
      return null;
    }

    return new SubActivity([
      'sector_code' => $sector_code,
      'activity_code' => $row['kode_kegiatan'],
      'code' => $row['kode_sub_kegiatan'],
      'name' => $row['nama_sub_kegiatan'],
      'outcome' => $row['kinerja'],
      'indicator' => $row['indikator'],
      'piece' => $row['satuan'],
      'description' => $row['definisi_operasional'],
      'implementer' => $row['pelaksana'],
      'spm' => $row['spm'],
      'type' => $row['jenis'],
      'tags' => $row['tag'] ?? '[]',
    ]);
  }

  public function batchSize(): int
  {
    return 1;
  }

  public function uniqueBy()
  {
    return 'code';
  }
}
