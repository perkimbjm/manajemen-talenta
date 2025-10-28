<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      RoleSeeder::class,
      UserSeeder::class,
      UnitSeeder::class,
      SectorSeeder::class,
      EchelonSeeder::class,
      RankSeeder::class,
      OccupationSeeder::class,
      PositionSeeder::class,
      EducationLevelSeeder::class,
      AssessmentElementSeeder::class,
      StageSeeder::class,
      EmployeeSeeder::class,
      UnitSectorsSeeder::class,
      AttendancePercentageSeeder::class,
      FeedbackSeeder::class,
      SkpReportSeeder::class,
      TalentPoolBoxSeeder::class,
    ]);
  }
}
