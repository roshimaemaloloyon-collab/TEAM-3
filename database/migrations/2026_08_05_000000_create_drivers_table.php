<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('driver_id')->unique(); // e.g. #DRV-2026-0001
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('photo')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->default('Male');
            $table->string('civil_status')->default('Single');
            $table->text('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('emergency_contact_person')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->date('date_hired')->nullable();
            $table->string('branch')->default('North Branch');
            $table->string('vehicle_assignment')->nullable();
            $table->string('vehicle_type')->default('Sedan');
            $table->string('route_assignment')->default('North Route');
            $table->enum('status', ['active', 'inactive', 'review', 'suspended', 'archived'])->default('active');
            $table->decimal('performance_score', 3, 1)->default(4.5);
            $table->integer('trips_count')->default(0);
            $table->integer('complaints_count')->default(0);
            $table->string('username')->nullable();
            $table->string('role')->default('Driver');
            $table->string('license_number')->nullable();
            $table->date('license_expiration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
