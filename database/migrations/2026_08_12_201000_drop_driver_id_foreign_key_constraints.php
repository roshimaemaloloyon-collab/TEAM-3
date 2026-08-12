<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE competency_assessments DROP CONSTRAINT IF EXISTS competency_assessments_driver_id_foreign;");
        } catch (\Throwable $e) {
            // Ignore if constraint does not exist
        }

        try {
            DB::statement("ALTER TABLE competency_development_plans DROP CONSTRAINT IF EXISTS competency_development_plans_driver_id_foreign;");
        } catch (\Throwable $e) {
            // Ignore if constraint does not exist
        }
    }

    public function down(): void
    {
    }
};
