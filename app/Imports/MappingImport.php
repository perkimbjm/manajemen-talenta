<?php

namespace App\Imports;

use App\Models\Mapping;
use App\Models\Program;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class MappingImport implements
  ToModel,
  WithHeadingRow,
  WithBatchInserts,
  WithUpserts,
  WithProgressBar,
  SkipsEmptyRows,
  SkipsOnFailure,
  SkipsOnError
{
  use Importable;

  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function model(array $row)
  {
    return new Mapping($row);
  }

  public function batchSize(): int
  {
    return 100;
  }

  public function uniqueBy()
  {
    return ['group', 'prev_id', 'current_id'];
  }

  public function onFailure(Failure ...$failures)
  {
    // Handle the failures how you'd like.
  }

  public function onError(\Throwable $e)
  {
    // Handle the exception how you'd like.
  }
}
