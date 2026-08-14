<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('instructor');
            $table->string('venue')->nullable();
            $table->integer('capacity')->default(0);
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'start_datetime']);
        });

        Schema::create('training_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('training_id')->constrained()->cascadeOnDelete();
            $table->dateTime('registration_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'waitlisted'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['driver_id', 'training_id']);
            $table->index(['training_id', 'status']);
        });

        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('training_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['present', 'late', 'absent', 'excused'])->default('absent');
            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['driver_id', 'training_id']);
            $table->index(['training_id', 'status']);
        });

        Schema::create('training_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('training_id')->constrained()->cascadeOnDelete();
            $table->integer('overall_rating')->nullable();
            $table->integer('knowledge_assessment')->nullable();
            $table->integer('instructor_feedback')->nullable();
            $table->integer('training_effectiveness')->nullable();
            $table->text('driver_feedback')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->default('completed');
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['training_id', 'driver_id']);
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('training_id')->constrained()->cascadeOnDelete();
            $table->string('certificate_number')->unique();
            $table->dateTime('issue_date');
            $table->enum('status', ['issued', 'revoked', 'expired'])->default('issued');
            $table->text('notes')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['driver_id', 'training_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('training_evaluations');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('training_registrations');
        Schema::dropIfExists('trainings');
    }
};
