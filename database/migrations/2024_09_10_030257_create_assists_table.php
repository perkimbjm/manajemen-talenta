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
    Schema::create('assists', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table
        ->char('unit_code', 22);
      $table
        ->foreign('unit_code')
        ->on('units')
        ->references('code');
      $table->char('sub_activity_code', 17);
      $table->foreign('sub_activity_code')->on('sub_activities')->references('code');
      $table->string('title');

      $table->string('filename');
      $table->text('filepath');
      $table->text('description')->nullable();

      $table->foreignUuid('created_by');
      $table->foreignUuid('updated_by');

      $table->unique(['unit_code', 'sub_activity_code'], 'unique_code');
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('assists');
  }
};
