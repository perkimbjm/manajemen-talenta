<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\SkpReport;
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

class SkpImport2 implements ToCollection, WithHeadingRow, SkipsOnFailure,  SkipsOnError
{
  /**
   * @param Collection $collection
   */
  public function collection(Collection $rows)
  {
    $list_errors = collect([]);

    progress(
      label: 'Importing SKP',
      steps: $rows,
      callback: function ($data) use ($list_errors) {
        if (!$data['hasil_akhir']) return;
        try {
          SkpReport::updateOrCreate([
            'nip' => $data['nip'],
            'year' => $data['tahun_skp'],
          ], [
            'final_result' => $data['hasil_akhir'],
            'type_id' => $data['jenis'],
            'start_period' => $data['periode_awal_skp'],
            'end_period' => $data['periode_akhir_skp'],
            'work_behavior' => $data['perilaku_kerja'],
            'work_result' => $data['hasil_kerja'],
            'final_result' => $data['hasil_akhir'],
            'skp_unor_id' => $data['skp_unor_id'],
            'skp_unor' => $data['skp_unor'],
            'skp_unor_induk' => $data['skp_unor_induk'],
            'skp_jabatan' => $data['skp_jabatan'],
            'skp_jenis_jabatan' => $data['skp_jenis_jabatan'],
            'is_skp_plt_plh_pjb' => $data['is_skp_plt_plh_pjb'],
            'golru' => $data['golru'],
            'rated_at' => $data['waktu_dinilai'],
          ]);
        } catch (\Illuminate\Database\QueryException $e) {
          $list_errors->push($e->getMessage());
          return;
        }
      }
    );

    warning("list_Errors x{$list_errors->count()}");
    if ($list_errors->count() > 0) {
      dump($list_errors[0]);
    }
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
