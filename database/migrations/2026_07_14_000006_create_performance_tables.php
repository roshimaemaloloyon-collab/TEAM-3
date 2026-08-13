<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('customer_rating', 3, 2)->nullable();
            $table->decimal('peer_evaluation_score', 3, 2)->nullable();
            $table->decimal('attendance_rate', 5, 2)->nullable();
            $table->decimal('trip_completion_rate', 5, 2)->nullable();
            $table->decimal('cancellation_rate', 5, 2)->nullable();
            $table->decimal('safety_score', 3, 2)->nullable();
            $table->integer('complaints_count')->default(0);
            $table->integer('commendations_count')->default(0);
            $table->decimal('overall_score', 3, 2)->nullable();
            $table->string('performance_status')->default('average'); // excellent, good, average, needs_improvement
            $table->integer('ranking')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('recorded_at')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['driver_id', 'recorded_at']);
            $table->index('performance_status');
        });

        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->string('kpi_name');
            $table->text('description')->nullable();
            $table->decimal('target_value', 10, 2);
            $table->decimal('actual_value', 10, 2)->nullable();
            $table->decimal('achievement_percentage', 5, 2)->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, achieved, missed
            $table->string('kpi_category')->nullable(); // safety, attendance, customer_service, efficiency
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['driver_id', 'kpi_category', 'period_start']);
        });

        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->string('review_type'); // monthly, quarterly, annual
            $table->string('period'); // e.g., "January 2026", "Q1 2026", "2026"
            $table->date('review_date');
            $table->decimal('performance_score', 3, 2)->nullable();
            $table->text('admin_feedback')->nullable();
            $table->text('recommendations')->nullable();
            $table->string('status')->default('pending'); // pending, completed, archived
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['driver_id', 'review_type', 'period']);
            $table->index('status');
        });

        Schema::create('performance_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('overall_score', 5, 2)->nullable();
            $table->decimal('kpi_score', 5, 2)->nullable();
            $table->integer('ranking')->nullable();
            $table->string('performance_status')->nullable();
            $table->string('record_type'); // snapshot, review, kpi_update, ranking_change
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('recorded_at')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['driver_id', 'recorded_at']);
            $table->index('record_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_history');
        Schema::dropIfExists('performance_reviews');
        Schema::dropIfExists('kpis');
        Schema::dropIfExists('performances');
    }
};
