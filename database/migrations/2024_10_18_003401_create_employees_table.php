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
    Schema::create('employees', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->foreignUuid('user_id')->nullable();
      $table->char('nip', 18)->unique();
      $table->string('name');
      $table->string('front_title')->nullable();
      $table->string('back_title')->nullable();
      $table->unsignedSmallInteger('rank_code')->nullable();
      $table->unsignedSmallInteger('echelon_code')->nullable();
      $table->string('rank')->nullable();
      $table->string('order')->nullable();
      $table->string('echelon')->nullable();
      $table->char('unit_code', 22)->nullable();
      $table->foreign('unit_code')->on('units')->references('code');
      $table->string('work_unit')->nullable();
      $table->string('position_type');
      $table->string('position_name');
      $table->unsignedInteger('education_level')->nullable();
      $table->string('education_name')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('employees');
  }
};
