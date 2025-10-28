<?php

namespace App\Console\Commands;

use App\Imports\SkpImport;
use App\Imports\SkpImport2;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendancePercentageImport;
use App\Imports\MappingImport;

class ImportExcel extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:excel {type}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Run excel import';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    switch ($this->argument('type')) {
      case 'assessments':
        $this->importAssessmentsFromExcel();
        break;
      case 'skp':
        $this->importSkpFromExcel();
        break;
      case 'kehadiran':
        $this->importAttendancesFromExcel();
        break;
      case 'mappings':
        $this->importMappingsFromExcel();
        break;

      default:
        # code...
        break;
    }
  }

  public function importAssessmentsFromExcel()
  {
    info('Starting import skp...');
    spin(
      callback: fn() => Excel::import(new SkpImport, public_path('imports/laporan-skp-2023-terbaru.xlsx')),
      message: 'Loading file ...'
    );
  }

  public function importMappingsFromExcel()
  {
    info('Starting import mappings...');
    spin(
      callback: fn() => Excel::import(new MappingImport, public_path('imports/data-mappings.xlsx')),
      message: 'Loading file ...'
    );
  }

  public function importSkpFromExcel()
  {
    info('Starting import skp...');
    spin(
      callback: fn() => Excel::import(new SkpImport2, public_path('imports/laporan-skp-2023-terbaru.xlsx')),
      message: 'Loading file ...'
    );
  }

  public function importAttendancesFromExcel()
  {
    info('Starting import kehadiran...');
    spin(
      callback: fn() => Excel::import(new AttendancePercentageImport, public_path('imports/absensi2024.xlsx')),
      message: 'Loading file ...'
    );
  }
}
