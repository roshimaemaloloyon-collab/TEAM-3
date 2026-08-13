<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get driver connection type (e.g. pgsql, mysql, sqlite)
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            // Enable Row Level Security (RLS) on all public tables in PostgreSQL / Supabase
            $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");

            foreach ($tables as $table) {
                $tableName = $table->tablename;
                // Skip migration tracking table if needed, or enable for all
                DB::statement("ALTER TABLE public.\"{$tableName}\" ENABLE ROW LEVEL SECURITY;");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");

            foreach ($tables as $table) {
                $tableName = $table->tablename;
                DB::statement("ALTER TABLE public.\"{$tableName}\" DISABLE ROW LEVEL SECURITY;");
            }
        }
    }
};
