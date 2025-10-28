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
    Schema::create('competency_standards', function (Blueprint $table) {
      // Use a UUID for the primary key
      $table->uuid('id')->primary();

      // Use CHAR(27) for the polymorphic standardable_id
      $table->char('standardable_id', 36);

      // Polymorphic type field (Position or Occupation)
      $table->string('standardable_type');

      // Competency and level fields
      $table->text('description');
      $table->string('file_disk')->nullable();
      $table->text('file_path');
      $table->text('file_type');
      $table->string('level')->nullable();

      // Timestamps for created_at and updated_at
      $table->timestamps();

      // Add indexes for polymorphic relation lookup
      $table->index(['standardable_id', 'standardable_type']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('competency_standards');
  }
};
