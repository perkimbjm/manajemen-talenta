<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('recaps', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->year('year');
      $table->foreignId('affair_id')->constrained('affairs')->cascadeOnDelete();
      $table->unsignedSmallInteger('zone_id')->nullable()->default(3);
      $table->char('sector_code', 4);
      $table->foreign('sector_code')->on('sectors')->references('code');
      $table->char('unit_code', 22);
      $table->foreign('unit_code')->on('units')->references('code');
      $table->char('program_code', 7);
      $table->foreign(['zone_id', 'program_code'])->on('programs')->references(['zone_id', 'code']);
      $table->char('activity_code', 12);
      $table->foreign('activity_code')->on('activities')->references('code');
      $table->char('sub_activity_code', 17);
      $table->foreign('sub_activity_code')->on('sub_activities')->references('code');
      $table->char('fund_code', 17)->nullable();
      $table->foreign('fund_code')->on('funds')->references('code');
      $table->char('expense_code', 17)->nullable();
      $table->foreign('expense_code')->on('expenses')->references('code');
      $table->double('budget')->nullable();
      $table->unsignedInteger('sequence')->default(1);
      $table->timestamps();

      $table->unique(
        [
          'year',
          'sector_code',
          'unit_code',
          'fund_code',
          'expense_code',
          'sub_activity_code'
        ],
        'code'
      );
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('recaps');
  }
};
