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
    Schema::create('programs', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->unsignedSmallInteger('zone_id')->nullable()->default(3);
      $table->char('code', 7);
      $table->unique(['zone_id', 'code']);
      $table->char('sector_code', 4)->nullable();
      $table->foreign('sector_code')->on('sectors')->references('code');
      $table->unsignedInteger('sequence')->default(1);
      $table->string('name');
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('programs');
  }
};
