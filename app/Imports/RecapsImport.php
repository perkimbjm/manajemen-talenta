<?php

namespace App\Imports;

use App\Models\Recap;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class RecapsImport implements
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
    $program_code = $row['kode_program'];
    $activity_code = $row['kode_kegiatan'];
    $sub_activity_code = $row['kode_sub_kegiatan'];
    if ($row['nama_program'] === 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH KABUPATEN/KOTA') {
      $prefix_code = 'X.XX';
      $program_code = str($program_code)->substr(4)->prepend($prefix_code);
      $activity_code = str($activity_code)->substr(4)->prepend($prefix_code);
      $sub_activity_code = str($sub_activity_code)->substr(4)->prepend($prefix_code);
    }

    return new Recap([
      'year' => $row['tahun'],
      'affair_id' => $row['kode_urusan'],
      'zone_id' => 3,
      'sector_code' => $row['kode_bidang_urusan'],
      'unit_code' => $row['kode_skpd'],
      'program_code' => $program_code,
      'activity_code' => $activity_code,
      'sub_activity_code' => $sub_activity_code,
      'fund_code' => $row['kode_sumber_dana'],
      'expense_code' => $row['kode_rekening'],
      'budget' => $row['pagu'],
      'sequence' => $row['no'],
    ]);
  }

  public function batchSize(): int
  {
    return 1;
  }

  public function uniqueBy()
  {
    return [
      'year',
      'sector_code',
      'unit_code',
      'fund_code',
      'expense_code',
      'sub_activity_code'
    ];
  }
}
