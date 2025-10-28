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
    Schema::create('occupations', function (Blueprint $table) {
      $table->uuid('id')->primary();

      $table
        ->char('type_code', 1);

      $table
        ->foreign('type_code')
        ->on('occupation_types')
        ->references('code')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();

      $table
        ->char('echelon_code', 4)->nullable();

      $table->char('code', 5)->unique();
      $table->string('name');
      $table->string('nomenclature')->nullable();
      $table->string('group')->nullable();

      $table->unsignedSmallInteger('sequence')->default(1);
      $table->unsignedSmallInteger('grade')->nullable();
      $table->unsignedSmallInteger('level')->default(1);
      $table->text('description')->nullable();
      $table->json('tags')->default(json_encode([]));

      $table->foreignUuid('created_by')->nullable();
      $table->foreignUuid('updated_by')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('occupations');
  }
};
