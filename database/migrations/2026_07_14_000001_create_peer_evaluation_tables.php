<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('weight')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('peer_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('evaluated_driver_id')->constrained('users')->cascadeOnDelete();
            $table->date('evaluation_date');
            $table->boolean('is_anonymous')->default(false);
            $table->json('category_scores')->nullable();
            $table->decimal('overall_score', 4, 2)->nullable();
            $table->text('comments')->nullable();
            $table->text('suggestions')->nullable();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])->default('draft');
            $table->text('admin_remarks')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['evaluator_id', 'evaluated_driver_id', 'evaluation_date']);
            $table->index('status');
        });

        Schema::create('evaluation_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peer_evaluation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewed_by')->constrained('users')->cascadeOnDelete();
            $table->enum('action', ['approved', 'rejected', 'returned'])->default('approved');
            $table->text('remarks')->nullable();
            $table->timestamp('reviewed_at');
            $table->timestamps();
        });

        Schema::create('evaluation_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->text('positive_feedback')->nullable();
            $table->text('improvement_areas')->nullable();
            $table->text('common_strengths')->nullable();
            $table->text('common_weaknesses')->nullable();
            $table->text('recommendations')->nullable();
            $table->decimal('average_peer_rating', 4, 2)->nullable();
            $table->integer('total_evaluations')->default(0);
            $table->integer('positive_count')->default(0);
            $table->integer('improvement_count')->default(0);
            $table->date('feedback_period_start')->nullable();
            $table->date('feedback_period_end')->nullable();
            $table->timestamps();

            $table->index(['driver_id', 'feedback_period_start', 'feedback_period_end']);
        });

        Schema::create('evaluation_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type'); // individual, department, monthly, quarterly
            $table->string('title');
            $table->text('parameters')->nullable();
            $table->json('report_data')->nullable();
            $table->string('export_format')->nullable(); // pdf, excel, print
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('evaluation_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peer_evaluation_id')->constrained()->cascadeOnDelete();
            $table->enum('action', ['created', 'updated', 'submitted', 'approved', 'rejected', 'archived', 'restored'])->default('created');
            $table->text('changes')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('performed_at');
            $table->timestamps();

            $table->index(['peer_evaluation_id', 'performed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_history');
        Schema::dropIfExists('evaluation_reports');
        Schema::dropIfExists('evaluation_feedback');
        Schema::dropIfExists('evaluation_reviews');
        Schema::dropIfExists('peer_evaluations');
        Schema::dropIfExists('evaluation_categories');
    }
};
