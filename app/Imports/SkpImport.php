<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Assessment;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\PerformanceManagement;
use function Laravel\Prompts\warning;
use function Laravel\Prompts\progress;
use Maatwebsite\Excel\Validators\Failure;

use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SkpImport implements ToCollection, WithHeadingRow, SkipsOnFailure,  SkipsOnError
{
  /**
   * @param Collection $collection
   */
  public function collection(Collection $rows)
  {
    // $dataset = $rows->whereIn('nip', $employees->pluck('nip'));
    $evaluations = Assessment::getSpecificLabels();

    $year = 2024;

    $error_nips = collect([]);

    progress(
      label: 'Importing SKP',
      steps: $rows,
      callback: function ($data) use ($year, $evaluations, $error_nips) {
        $evaluation = $evaluations
          ->filter(fn($eval) => strtolower($eval['name']) === strtolower($data['hasil_akhir']))->first();
        if (!$evaluation) {
          return;
        }
        try {
          Assessment::updateOrCreate([
            'nip' => $data['nip'],
            'year' => $year,
          ], [
            'specific' => $evaluation['value'],
          ]);
        } catch (\Illuminate\Database\QueryException $e) {
          $error_nips->push($data['nip']);
          return;
        }
      }
    );

    warning("Skipped NIP x{$error_nips->count()}");
  }

  public function headingRow(): int
  {
    return 8;
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
