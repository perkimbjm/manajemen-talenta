<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendancePercentageImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use function Laravel\Prompts\spin;

class AttendancePercentageSeeder extends Seeder
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
      callback: fn() => Excel::import(new AttendancePercentageImport, public_path('imports/absensi2024.xlsx')),
      message: 'Loading file ...'
    );
  }
}
