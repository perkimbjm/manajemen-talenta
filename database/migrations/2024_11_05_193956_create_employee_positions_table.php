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
    Schema::create('employee_positions', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('nip', 18);
      $table->foreign('nip')
        ->on('employees')
        ->references('nip')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      $table->string('decree_number');
      $table->string('type');
      $table->string('name');
      $table->string('echelon')->nullable();
      $table->date('tmt_date');
      $table->date('decree_date');
      $table->date('inauguration_date');
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('employee_positions');
  }
};
