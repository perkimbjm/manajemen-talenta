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
    Schema::create('sectors', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->foreignId('affair_id')
        ->constrained('affairs')
        ->cascadeOnDelete();
      $table->unsignedSmallInteger('sequnce')->default(1);
      $table->char('code', 4)->unique();
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
    Schema::dropIfExists('sectors');
  }
};
