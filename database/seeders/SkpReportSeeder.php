<?php

namespace Database\Seeders;

use App\Imports\SkpImport2;
use Illuminate\Database\Seeder;

use function Laravel\Prompts\spin;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SkpReportSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->importFromExcel();
  }

  public function importFromExcel()
  {
    info('Starting import file...');
    spin(
      callback: fn() => Excel::import(new SkpImport2, public_path('imports/laporan-skp-2023-terbaru.xlsx')),
      message: 'Loading file ...'
    );
  }
}
