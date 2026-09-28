<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('code', 30)->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id')->constrained()->restrictOnDelete();
            $table->boolean('is_active')->default(true)->after('password');
        });

        Schema::create('request_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('code', 30)->unique();
            $table->unsignedSmallInteger('level')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('request_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('code', 30)->unique();
            $table->boolean('is_final')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 150);
            $table->string('resource_type', 100);
            $table->string('location', 200);
            $table->string('status', 30)->default('available');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('code', 25)->unique();
            $table->foreignId('requester_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('request_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('priority_id')->constrained()->restrictOnDelete();
            $table->foreignId('status_id')->constrained('request_statuses')->restrictOnDelete();
            $table->string('title', 150);
            $table->text('description');
            $table->string('location', 200);
            $table->timestampTz('submitted_at')->nullable();
            $table->timestampTz('assigned_at')->nullable();
            $table->timestampTz('started_at')->nullable();
            $table->timestampTz('resolved_at')->nullable();
            $table->timestampTz('closed_at')->nullable();
            $table->timestampTz('cancelled_at')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->index(['requester_id', 'created_at']);
            $table->index(['assigned_to_user_id', 'status_id']);
            $table->index(['status_id', 'priority_id', 'created_at']);
            $table->index(['request_type_id', 'created_at']);
        });

        Schema::create('request_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by_user_id')->constrained('users')->restrictOnDelete();
            $table->string('original_name');
            $table->string('file_path', 500);
            $table->string('disk', 50)->default('public');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size_bytes');
            $table->timestampsTz();
        });

        Schema::create('request_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_user_id')->constrained('users')->restrictOnDelete();
            $table->text('content');
            $table->timestampsTz();
            $table->index(['service_request_id', 'created_at']);
        });

        Schema::create('request_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50);
            $table->jsonb('old_values')->nullable();
            $table->jsonb('new_values')->nullable();
            $table->text('note')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->index(['service_request_id', 'created_at']);
        });

        Schema::create('request_resources', function (Blueprint $table) {
            $table->foreignId('service_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resource_id')->constrained()->restrictOnDelete();
            $table->timestampTz('created_at')->useCurrent();
            $table->primary(['service_request_id', 'resource_id']);
            $table->index('resource_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_resources');
        Schema::dropIfExists('request_history');
        Schema::dropIfExists('request_comments');
        Schema::dropIfExists('request_attachments');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('request_statuses');
        Schema::dropIfExists('priorities');
        Schema::dropIfExists('request_types');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn('is_active');
        });

        Schema::dropIfExists('roles');
    }
};
