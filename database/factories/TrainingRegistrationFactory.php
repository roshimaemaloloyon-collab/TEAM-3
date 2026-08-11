<?php

namespace Database\Factories;

use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingRegistrationFactory extends Factory
{
    protected $model = \App\Models\TrainingRegistration::class;

    public function definition(): array
    {
        return [
            'driver_id' => User::factory(),
            'training_id' => Training::factory(),
            'registration_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'waitlisted']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
