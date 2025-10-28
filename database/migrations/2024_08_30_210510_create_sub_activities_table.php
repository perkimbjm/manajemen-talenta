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
    Schema::create('sub_activities', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('code', 17)->unique();
      $table->char('sector_code', 4)->nullable();
      $table->foreign('sector_code')->on('sectors')->references('code');
      $table->char('activity_code', 12);
      $table->foreign('activity_code')->on('activities')->references('code');
      $table->unsignedInteger('sequence')->default(1);
      $table->text('name');
      $table->string('type')->nullable();
      $table->text('outcome')->nullable();
      $table->text('indicator')->nullable();
      $table->text('description')->nullable();
      $table->text('implementer')->nullable();
      $table->string('spm')->nullable();
      $table->string('piece')->nullable();
      $table->text('tags')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('sub_activities');
  }
};
