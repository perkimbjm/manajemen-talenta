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
    Schema::create('assessments', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('nip', 18);
      $table->string('register_number')->nullable();
      $table->year('year');
      $table->foreign('nip')
        ->on('employees')
        ->references('nip')
        ->onDelete('cascade')
        ->onUpdate('cascade');
      $table->foreignId('box_id')
        ->nullable()
        ->constrained('talent_pool_boxes')
        ->cascadeOnDelete();
      $table->float('potential_value')->unsigned()->default(0);
      $table->float('performance_value')->unsigned()->default(0);
      $table->float('specific')->unsigned()->default(0);
      $table->float('innovation')->unsigned()->default(0);
      $table->float('organizational')->unsigned()->default(0);
      $table->float('performance_preference')->unsigned()->default(0);
      $table->float('extra')->unsigned()->default(0);
      $table->float('potential')->unsigned()->default(0);
      $table->float('upper_competency')->unsigned()->default(0);
      $table->float('competency')->unsigned()->default(0);
      $table->float('track_record')->unsigned()->default(0);
      $table->float('other')->unsigned()->default(0);
      $table->float('potential_preference')->unsigned()->default(0);
      $table->float('manajerial')->unsigned()->default(0);
      $table->float('sosialkultural')->unsigned()->default(0);
      $table->float('teknis')->unsigned()->default(0);
      $table->float('jpm')->unsigned()->default(0);
      $table->unsignedSmallInteger('status')->default(0);
      $table->string('compatibility')->nullable();
      $table->string('recommendation')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('assessments');
  }
};
