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
    Schema::create('organizations', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('nip', 18);
      $table
        ->foreign('nip')
        ->references('nip')
        ->on('employees')
        ->onDelete('cascade')
        ->onUpdate('cascade');
      $table->string('scope');
      $table->unsignedMediumInteger('value');
      $table->string('name');
      $table->enum('as', ['Ketua', 'Anggota'])->default('Anggota');
      $table->unsignedSmallInteger('status')->default(0);
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
    Schema::dropIfExists('organizations');
  }
};
