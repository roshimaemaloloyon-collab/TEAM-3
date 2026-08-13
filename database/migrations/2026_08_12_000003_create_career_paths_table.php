<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_paths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->string('current_title')->default('Junior Driver');
            $table->string('target_title')->default('Senior Driver');
            $table->decimal('readiness_percentage', 5, 2)->default(0.00);
            $table->string('status')->default('in_progress');
            $table->json('required_skills')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_paths');
    }
};
