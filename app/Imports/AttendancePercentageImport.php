<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\SkpReport;
use App\Models\Assessment;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\AttendancePercentage;
use App\Models\PerformanceManagement;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;

use function Laravel\Prompts\warning;

use function Laravel\Prompts\progress;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendancePercentageImport implements ToCollection, WithHeadingRow, SkipsOnFailure, SkipsOnError
{
  use Importable, SkipsFailures;
  /**
   * @param Collection $collection
   */
  public function collection(Collection $rows)
  {
    $errors = collect([]);

    progress(
      label: 'Importing Attendances',
      steps: $rows,
      callback: function ($data) use ($errors) {
        try {
          $nip = str_replace("'", "", $data['nip']);
          AttendancePercentage::updateOrCreate([
            'nip' => $nip,
            'year' => 2024,
          ], [
            'januari' => @$data['januari'],
            'februari' => @$data['februari'],
            'maret' => @$data['maret'],
            'april' => @$data['april'],
            'mei' => @$data['mei'],
            'juni' => @$data['juni'],
            'juli' => @$data['juli'],
            'agustus' => @$data['agustus'],
            'september' => @$data['september'],
            'oktober' => @$data['oktober'],
            'november' => @$data['november'],
            'desember' => @$data['desember'],
          ]);
        } catch (\Throwable $th) {
          $errors->push($th->getMessage());
          return;
        }
      }
    );

    warning("Errors x{$errors->count()}");
    if ($errors->count() > 0) {
      dump($errors[0]);
    }
  }

  public function headingRow(): int
  {
    return 1;
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
