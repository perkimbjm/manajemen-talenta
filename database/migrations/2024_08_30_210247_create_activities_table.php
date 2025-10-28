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
    Schema::create('activities', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('code', 12)->unique();
      $table->unsignedSmallInteger('zone_id')->nullable()->default(3);
      $table->char('sector_code', 4)->nullable();
      $table->foreign('sector_code')->on('sectors')->references('code');
      $table->char('program_code', 7);
      $table->foreign(['zone_id', 'program_code'])->on('programs')->references(['zone_id', 'code']);
      $table->unsignedInteger('sequence')->default(1);
      $table->text('name');
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('activities');
  }
};
