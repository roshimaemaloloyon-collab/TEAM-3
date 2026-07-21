<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // driver, evaluation, analytics, visualization, export, history
            $table->string('report_type'); // performance, competency, training, learning, attendance, safety, ranking, peer_evaluation, recognition, feedback, promotion, succession, kpi, etc.
            $table->text('parameters')->nullable(); // JSON filters/parameters
            $table->json('report_data')->nullable();
            $table->string('export_format')->nullable(); // pdf, excel, print
            $table->string('status')->default('generated'); // generated, exporting, failed, archived
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->index(['category', 'report_type']);
            $table->index('status');
            $table->index('generated_at');
        });

        Schema::create('report_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->string('export_format'); // pdf, excel, print
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('status')->default('completed'); // pending, completed, failed
            $table->foreignId('exported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('exported_at')->nullable();
            $table->timestamps();

            $table->index(['report_id', 'exported_at']);
        });

        Schema::create('report_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->enum('action', ['generated', 'downloaded', 'exported', 'archived', 'restored', 'deleted'])->default('generated');
            $table->text('notes')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('performed_at')->nullable();
            $table->timestamps();

            $table->index(['report_id', 'performed_at']);
        });

        Schema::create('analytics_data', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name');
            $table->string('category'); // performance, competency, learning, training, recognition, evaluation
            $table->json('metric_value')->nullable();
            $table->date('recorded_date');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['category', 'recorded_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_data');
        Schema::dropIfExists('report_history');
        Schema::dropIfExists('report_exports');
        Schema::dropIfExists('reports');
    }
};
