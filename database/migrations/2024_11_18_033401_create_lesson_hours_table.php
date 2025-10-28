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
    Schema::create('lesson_hours', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->year('year');
      $table->char('nip', 18);
      $table->foreign('nip')
        ->on('employees')
        ->references('nip')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->float('total_hours')->unsigned()->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('lesson_hours');
  }
};
