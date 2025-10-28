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
    Schema::create('occupation_types', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->enum('group', ['Struktural', 'Staff']);
      $table->char('code', 1)->unique();
      $table->string('name')->unique();
      $table->string('acronym')->nullable();
      $table->tinyInteger('level')->default(0);
      $table->json('theme')->nullable();
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('occupation_types');
  }
};
