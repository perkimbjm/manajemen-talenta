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
    Schema::create('unit_sectors', function (Blueprint $table) {
      $table->char('unit_code', 22);
      $table->char('sector_code', 4);
      $table
        ->foreign('unit_code')
        ->on('units')
        ->references('code')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table
        ->foreign('sector_code')
        ->on('sectors')
        ->references('code')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();

      $table->unsignedSmallInteger('order')->default(1);

      $table->primary(['unit_code', 'sector_code']);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('unit_sectors');
  }
};
