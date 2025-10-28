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
    Schema::create('talent_pool_boxes', function (Blueprint $table) {
      $table->id();
      $table->string('label');
      $table->unsignedSmallInteger('order');
      $table->char('hsl', 11);
      $table->char('color', 16);
      $table->float('min_potential_value')->unsigned();
      $table->float('max_potential_value')->unsigned();
      $table->float('min_performance_value')->unsigned();
      $table->float('max_performance_value')->unsigned();
      $table->text('description');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('talent_pool_boxes');
  }
};
