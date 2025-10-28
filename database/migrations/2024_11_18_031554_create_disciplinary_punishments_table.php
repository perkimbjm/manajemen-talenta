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
    Schema::create('disciplinary_punishments', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('nip', 18);
      $table->foreign('nip')
        ->on('employees')
        ->references('nip')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->date('register_date');
      $table->date('end_date');
      $table->text('description')->nullable();
      $table->year('year');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('disciplinary_punishments');
  }
};
