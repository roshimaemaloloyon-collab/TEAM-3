<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('type'); // training, performance, system, announcement, alert, reminder
            $table->string('category'); // training, performance, system, announcement
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->string('status')->default('unread'); // unread, read, archived, deleted
            $table->string('channel')->default('in-app'); // in-app, email, sms
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'created_at']);
            $table->index('type');
            $table->index('category');
        });

        Schema::create('notification_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['sent', 'delivered', 'read', 'failed', 'archived'])->default('sent');
            $table->string('recipient')->nullable();
            $table->string('channel')->nullable(); // in-app, email, sms
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['notification_id', 'status', 'sent_at']);
        });

        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category'); // training, performance, system, announcement
            $table->string('type'); // email, in_app, sms
            $table->boolean('enabled')->default(true);
            $table->string('frequency')->default('immediate'); // immediate, daily, weekly
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'category', 'type']);
            $table->index('enabled');
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // created, sent, delivered, read, failed, retried, archived
            $table->string('recipient')->nullable();
            $table->string('channel')->nullable(); // in-app, email, sms
            $table->string('status')->default('success'); // success, failed, pending
            $table->text('error_message')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('performed_at')->nullable();
            $table->timestamps();

            $table->index(['notification_id', 'status', 'performed_at']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notification_settings');
        Schema::dropIfExists('notification_history');
        Schema::dropIfExists('notifications');
    }
};
