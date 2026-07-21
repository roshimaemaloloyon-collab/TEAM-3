<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'driver'])->default('driver')->after('email');
            $table->enum('status', ['active', 'inactive', 'suspended', 'archived'])->default('active')->after('role');
            $table->string('phone')->nullable()->after('status');
            $table->string('address')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'phone', 'address']);
        });
    }
};
