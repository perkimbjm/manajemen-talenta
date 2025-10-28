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
    Schema::create('feedbacks', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->char('nip', 18)->unique();
      $table
        ->foreign('nip')
        ->references('nip')
        ->on('employees')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->unsignedSmallInteger('superior_raters')->default(0);
      $table->unsignedSmallInteger('peer_raters')->default(0);
      $table->unsignedSmallInteger('subordinate_raters')->default(0);
      $table->decimal('superior_value', 5, 2)->default(0);
      $table->decimal('peer_value', 5, 2)->default(0);
      $table->decimal('subordinate_value', 5, 2)->default(0);
      $table->decimal('specific_value', 5, 2)->default(0); // Nilai agregat umpan balik 360
      $table->unsignedSmallInteger('status')->default(0); // 0: Draft, 1: Ditolak, 2: Terkonfirmasi
      $table->text('notes')->nullable();
      $table->unsignedSmallInteger('year');
      $table->timestamps();

      $table->unique(['nip', 'year']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('feedbacks');
  }
};
