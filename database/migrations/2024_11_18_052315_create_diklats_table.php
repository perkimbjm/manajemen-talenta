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
    Schema::create('diklats', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('code', 4);
      $table->char('nip', 18);
      $table
        ->foreign('nip')
        ->references('nip')
        ->on('employees')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->year('year');
      $table->string('type');
      $table->date('date')->nullable();
      $table->string('letter_number');
      $table->string('name');
      $table->text('description')->nullable();
      $table->unsignedTinyInteger('rank')->nullable();
      $table->unsignedTinyInteger('status')->default(1);

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('diklats');
  }
};
