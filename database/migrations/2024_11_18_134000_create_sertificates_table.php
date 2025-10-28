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
    Schema::create('sertificates', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('nip', 18);
      $table
        ->foreign('nip')
        ->references('nip')
        ->on('employees')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->string('type');
      $table->string('name');
      $table->float('value')->unsigned();
      $table->text('description')->nullable();
      $table->unsignedTinyInteger('status')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('sertificates');
  }
};
