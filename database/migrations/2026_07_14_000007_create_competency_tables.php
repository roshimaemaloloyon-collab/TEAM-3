<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // safety, customer_service, technical, behavioral
            $table->integer('target_score')->default(80);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('competency_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->foreignId('competency_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('status')->default('pending'); // pending, assessed, reviewed, archived
            $table->text('assessor_remarks')->nullable();
            $table->text('recommendations')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('assessed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assessed_at')->nullable();
            $table->timestamps();

            $table->index(['driver_id', 'competency_id', 'assessed_at']);
            $table->index('status');
        });

        Schema::create('competency_development_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->string('plan_name');
            $table->text('description')->nullable();
            $table->json('assigned_competencies')->nullable();
            $table->json('assigned_trainings')->nullable();
            $table->json('assigned_learning_modules')->nullable();
            $table->json('coaching_sessions')->nullable();
            $table->text('development_objectives')->nullable();
            $table->integer('completion_percentage')->default(0);
            $table->date('target_completion_date')->nullable();
            $table->text('hr_remarks')->nullable();
            $table->string('status')->default('active'); // active, completed, on_hold, cancelled
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['driver_id', 'status', 'target_completion_date']);
        });

        Schema::create('competency_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('competency_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('record_type'); // assessment, plan_update, coaching, review
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('recorded_at')->nullable();
            $table->timestamps();

            $table->index(['driver_id', 'competency_id', 'recorded_at']);
            $table->index('record_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competency_history');
        Schema::dropIfExists('competency_development_plans');
        Schema::dropIfExists('competency_assessments');
        Schema::dropIfExists('competencies');
    }
};
