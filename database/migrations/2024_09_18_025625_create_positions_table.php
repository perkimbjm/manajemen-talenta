<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('positions', function (Blueprint $table) {
      $table->uuid('id')->primary();

      $table->char('occupation_type_code', 1)->nullable();
      $table->foreign('occupation_type_code')
        ->on('occupation_types')
        ->references('code')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();

      $table->char('occupation_code', 5)->nullable();
      $table->foreign('occupation_code')
        ->on('occupations')
        ->references('code')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();

      $table->char('root_code', 22)->nullable();
      $table->char('unit_code', 22)->nullable();

      $table
        ->foreign('root_code')
        ->references('code')
        ->on('units')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();

      $table
        ->foreign('unit_code')
        ->references('code')
        ->on('units')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();

      $table->char('code', 32);

      $table->unsignedSmallInteger('sequence')->default(1);

      $table->string('type');
      $table->string('name')->nullable();
      $table->unsignedTinyInteger('grade')->nullable();
      $table->unsignedTinyInteger('level')->nullable();
      $table->text('description')->nullable();
      $table->json('tags')->default(json_encode([]));
      $table->boolean('is_structural')->default(0);
      $table->boolean('is_head')->default(0);
      $table->unsignedTinyInteger('status')->default(1);

      $table->foreignUuid('created_by')->nullable();
      $table->foreignUuid('updated_by')->nullable();
      $table->timestamps();
    });

    Schema::table('positions', function (Blueprint $table) {
      $table
        ->foreignUuid('parent_id')
        ->nullable()
        ->constrained('positions')
        ->nullOnDelete()
        ->cascadeOnUpdate();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('positions', function (Blueprint $table) {
      $table->dropConstrainedForeignId('parent_id');
    });
    Schema::dropIfExists('positions');
  }
};
