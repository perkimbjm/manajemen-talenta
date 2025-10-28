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
    Schema::create('units', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('code', 22)->unique();
      $table->unsignedSmallInteger('sequence')->default(1);
      $table->string('type');
      $table->string('name');
      $table->string('group')->nullable();
      $table->string('acronym')->nullable();
      $table->unsignedSmallInteger('level')->default(1);
      $table->boolean('is_root')->default(1);
      $table->unsignedSmallInteger('order')->nullable();
      $table->text('description')->nullable();
      $table->json('tags')->nullable();
      $table->timestamps();
    });

    Schema::table('units', function (Blueprint $table) {
      $table->char('parent_code', 22)->nullable();
      $table->char('root_code', 22)->nullable();
      $table
        ->foreign('root_code')
        ->on('units')
        ->references('code')
        ->nullOnDelete();
      $table
        ->foreign('parent_code')
        ->on('units')
        ->references('code')
        ->nullOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('units', function (Blueprint $table) {
      $table->dropConstrainedForeignId('root_code');
      $table->dropConstrainedForeignId('parent_code');
    });
    Schema::dropIfExists('units');
  }
};
