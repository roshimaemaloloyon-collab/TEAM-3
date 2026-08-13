<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competency_gaps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->json('strengths')->nullable();
            $table->json('weaknesses')->nullable();
            $table->json('skill_gaps')->nullable();
            $table->decimal('overall_gap_score', 5, 2)->default(0.00);
            $table->timestamp('assessed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competency_gaps');
    }
};
