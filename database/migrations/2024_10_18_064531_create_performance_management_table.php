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
    Schema::create('performance_management', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('nip', 18);
      $table->foreign('nip')
        ->on('employees')
        ->references('nip')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->unsignedSmallInteger('specific_value')->default(0); // Nilai dari E-Kinerja
      $table->unsignedSmallInteger('creativity')->default(0);
      $table->unsignedSmallInteger('organizational')->default(0);
      $table->unsignedSmallInteger('extra')->default(0);
      $table->unsignedSmallInteger('status')->default(0);
      $table->year('year');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('performance_management');
  }
};
