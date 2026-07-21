<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agency_settings', function (Blueprint $table) {
            $table->id();
            $table->string('agency_name')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('system_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('default_dashboard')->default('admin.dashboard');
            $table->string('date_format')->default('M d, Y');
            $table->string('time_format')->default('H:i');
            $table->string('timezone')->default('Asia/Manila');
            $table->boolean('maintenance_mode')->default(false);
            $table->string('system_version')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('security_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('two_factor_enabled')->default(false);
            $table->integer('session_timeout')->default(30);
            $table->integer('max_login_attempts')->default(5);
            $table->integer('lockout_duration')->default(15);
            $table->boolean('force_logout_all')->default(false);
            $table->timestamp('last_password_change')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('appearance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('theme')->default('light');
            $table->string('language')->default('en');
            $table->string('font_size')->default('medium');
            $table->string('sidebar_behavior')->default('expanded');
            $table->boolean('high_contrast')->default(false);
            $table->boolean('reduce_motion')->default(false);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('backup_type')->default('manual'); // manual, scheduled
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('status')->default('completed'); // pending, completed, failed
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['backup_type', 'status', 'created_at']);
        });

        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // error, system_activity, configuration_change, audit
            $table->string('module')->nullable(); // settings, users, training, evaluation, etc.
            $table->text('description')->nullable();
            $table->string('severity')->default('info'); // info, warning, error, critical
            $table->json('metadata')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('performed_at')->nullable();
            $table->timestamps();

            $table->index(['event_type', 'severity', 'performed_at']);
            $table->index('module');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('backups');
        Schema::dropIfExists('appearance_settings');
        Schema::dropIfExists('security_settings');
        Schema::dropIfExists('system_preferences');
        Schema::dropIfExists('agency_settings');
    }
};
