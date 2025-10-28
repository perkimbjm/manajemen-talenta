<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('stages', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('occupation_type_code', 1);
      $table->unsignedTinyInteger('level');

      $table->char('code', 2)->unique();

      $table->string('group')->nullable();
      $table->string('name');
      $table->text('description')->nullable();

      $table->unique(['occupation_type_code', 'level']);

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
    Schema::dropIfExists('stages');
  }
};
