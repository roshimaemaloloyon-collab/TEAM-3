<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_modules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // road_safety, defensive_driving, customer_service, company_policies, traffic_rules, emergency_response, vehicle_maintenance
            $table->string('type')->default('course'); // course, video, pdf, quiz
            $table->integer('duration_minutes')->nullable();
            $table->string('difficulty')->default('beginner'); // beginner, intermediate, advanced
            $table->string('status')->default('active'); // active, inactive, archived
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('learning_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('learning_module_id')->constrained()->cascadeOnDelete();
            $table->date('assigned_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->string('status')->default('assigned'); // assigned, in_progress, completed, overdue, expired
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['driver_id', 'learning_module_id', 'status']);
            $table->index('due_date');
        });

        Schema::create('learning_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('learning_module_id')->constrained()->cascadeOnDelete();
            $table->integer('score')->nullable();
            $table->integer('passing_score')->default(70);
            $table->integer('attempt')->default(1);
            $table->integer('max_attempts')->default(3);
            $table->string('status')->default('pending'); // pending, passed, failed, in_progress
            $table->json('score_breakdown')->nullable(); // correct, incorrect, skipped
            $table->text('feedback')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['driver_id', 'learning_module_id', 'status']);
            $table->index('completed_at');
        });

        Schema::create('learning_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('learning_module_id')->constrained()->cascadeOnDelete();
            $table->string('record_type'); // assignment, completion, assessment, certificate
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('recorded_at')->nullable();
            $table->timestamps();

            $table->index(['driver_id', 'learning_module_id', 'recorded_at']);
            $table->index('record_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_history');
        Schema::dropIfExists('learning_assessments');
        Schema::dropIfExists('learning_assignments');
        Schema::dropIfExists('learning_modules');
    }
};
