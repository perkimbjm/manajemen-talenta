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
    Schema::create('competencies', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('register_number');
      $table->char('code', 2);
      $table->char('nip', 18);
      $table->string('label');
      $table->float('value')->unsigned()->default(0);
      $table->float('skj')->unsigned()->default(0);
      $table->float('gap')->default(0);
      $table->float('manajerial')->default(0);
      $table->float('kultural')->default(0);
      $table->text('ket_manajerial')->nullable();
      $table->text('ket_kultural')->nullable();
      $table->text('recommendation')->nullable();
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('competencies');
  }
};
