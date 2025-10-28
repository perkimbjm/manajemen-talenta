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
    Schema::create('users', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('name');
      $table->string('email')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->text('password');
      $table->text('avatar')->nullable();
      $table->rememberToken();
      $table->timestamps();
    });

    Schema::create('accounts', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table
        ->foreignUuid('user_id')
        ->constrained(table: 'users')
        ->cascadeOnDelete();
      $table->char('provider', 50);
      $table->char('type', 32);
      $table->char('code', 32);
      $table->string('label')->nullable();
      $table->string('label_short')->nullable();
      $table->string('name')->nullable();
      $table->text('access_token')->nullable();
      $table->text('refresh_token')->nullable();
      $table->timestamp('expires_at')->nullable();
      $table->timestamp('verified_at')->nullable();
      $table->string('token_type')->nullable();
      $table->json('scope')->nullable();
      $table->text('id_token')->nullable();
      $table->string('session_state')->nullable();

      $table->foreignUuid('created_by')->nullable();
      $table->foreignUuid('updated_by')->nullable();
      $table->timestamps();

      $table->unique(['user_id', 'provider', 'type', 'code'], 'unique_id');
    });

    Schema::create('profiles', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table
        ->foreignUuid('user_id')
        ->constrained(table: 'users')
        ->cascadeOnDelete();
      $table
        ->foreignUuid('account_id')
        ->nullable()
        ->constrained(table: 'accounts')
        ->cascadeOnDelete();
      $table->string('name');
      $table->text('avatar')->nullable();
      $table->text('data')->nullable();
      $table->timestamps();
    });

    Schema::create('password_reset_tokens', function (Blueprint $table) {
      $table->string('provider_account_id')->primary();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });

    Schema::create('sessions', function (Blueprint $table) {
      $table->string('id')->primary();
      $table->foreignUuid('user_id')->nullable()->index();
      $table->string('ip_address', 45)->nullable();
      $table->text('user_agent')->nullable();
      $table->longText('payload');
      $table->integer('last_activity')->index();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('profiles');
    Schema::dropIfExists('accounts');
    Schema::dropIfExists('sessions');
    Schema::dropIfExists('password_reset_tokens');
    Schema::dropIfExists('users');
  }
};
