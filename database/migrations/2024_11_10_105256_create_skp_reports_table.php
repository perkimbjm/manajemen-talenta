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
    Schema::create('skp_reports', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->unsignedInteger('type_id');
      $table->char('nip', 18);
      $table->foreign('nip')
        ->on('employees')
        ->references('nip')
        ->onDelete('cascade')
        ->onUpdate('cascade');
      $table->year('year');
      $table->date('start_period');
      $table->date('end_period');
      $table->string('work_behavior');
      $table->string('work_result');
      $table->string('final_result');
      $table->string('skp_unor_id');
      $table->string('skp_unor');
      $table->string('skp_unor_induk');
      $table->string('skp_jabatan');
      $table->string('skp_jenis_jabatan');
      $table->boolean('is_skp_plt_plh_pjb');
      $table->string('golru');
      $table->timestamp('rated_at');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('skp_reports');
  }
};
