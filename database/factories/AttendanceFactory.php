<?php

namespace Database\Factories;

use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = \App\Models\Attendance::class;

    public function definition(): array
    {
        return [
            'driver_id' => User::factory(),
            'training_id' => Training::factory(),
            'status' => fake()->randomElement(['present', 'late', 'absent', 'excused']),
            'check_in_time' => fake()->optional()->dateTime('Y-m-d H:i:s'),
            'check_out_time' => fake()->optional()->dateTime('Y-m-d H:i:s'),
            'remarks' => fake()->optional()->sentence(),
            'recorded_by' => User::factory(),
        ];
    }
}
