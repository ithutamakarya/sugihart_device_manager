<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sdm';

    public function up(): void
    {
        $schema = Schema::connection('sdm');
        if (! $schema->hasTable('users')) {
            $schema->create('users', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->integer('is_active')->default(1);
                $table->rememberToken();
                $table->timestamps();
            });
        }
        if (! $schema->hasTable('vendor')) {
            $schema->create('vendor', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('token');
                $table->integer('is_active')->default(1);
                $table->timestamps();
            });
        }
        if (! $schema->hasTable('device')) {
            $schema->create('device', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('url_device')->nullable();
                $table->string('type');
                $table->integer('vendor_id');
                $table->string('ip_access')->nullable();
                $table->integer('is_active')->default(1);
                $table->string('token')->nullable();
                $table->timestamps();
            });
        }
        if (! $schema->hasTable('log_activity')) {
            $schema->create('log_activity', function (Blueprint $table): void {
                $table->id();
                $table->string('user_device')->nullable();
                $table->string('activity');
                $table->string('ip_address');
                $table->timestamps();
            });
        }
        if (! $schema->hasTable('password_reset_tokens')) {
            $schema->create('password_reset_tokens', function (Blueprint $table): void {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Tables may predate this package. Roll back a deployment by restoring a backup.
        throw new LogicException('Imported device-manager tables cannot be dropped by rollback. Restore a database backup explicitly.');
    }
};
