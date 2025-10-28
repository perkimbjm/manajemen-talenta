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
    Schema::create('attendance_percentages', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('nip', 18);
      $table->foreign('nip')
        ->on('employees')
        ->references('nip')
        ->onDelete('cascade')
        ->onUpdate('cascade');
      $table->year('year');
      $table->float('januari')->unsigned()->nullable();
      $table->float('februari')->unsigned()->nullable();
      $table->float('maret')->unsigned()->nullable();
      $table->float('april')->unsigned()->nullable();
      $table->float('mei')->unsigned()->nullable();
      $table->float('juni')->unsigned()->nullable();
      $table->float('juli')->unsigned()->nullable();
      $table->float('agustus')->unsigned()->nullable();
      $table->float('september')->unsigned()->nullable();
      $table->float('oktober')->unsigned()->nullable();
      $table->float('november')->unsigned()->nullable();
      $table->float('desember')->unsigned()->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('attendance_percentages');
  }
};
