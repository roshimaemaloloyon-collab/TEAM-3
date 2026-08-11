<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerformanceFactory extends Factory
{
    protected $model = \App\Models\Performance::class;

    public function definition(): array
    {
        return [
            'driver_id' => User::factory(),
            'customer_rating' => fake()->randomFloat(2, 1.0, 5.0),
            'peer_evaluation_score' => fake()->randomFloat(2, 1.0, 5.0),
            'attendance_rate' => fake()->randomFloat(2, 50.0, 100.0),
            'trip_completion_rate' => fake()->randomFloat(2, 50.0, 100.0),
            'cancellation_rate' => fake()->randomFloat(2, 0.0, 20.0),
            'safety_score' => fake()->randomFloat(2, 1.0, 5.0),
            'complaints_count' => fake()->numberBetween(0, 10),
            'commendations_count' => fake()->numberBetween(0, 20),
            'overall_score' => fake()->randomFloat(2, 1.0, 5.0),
            'performance_status' => fake()->randomElement(['excellent', 'good', 'average', 'needs_improvement']),
            'ranking' => fake()->numberBetween(1, 100),
            'recorded_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'recorded_by' => User::factory(),
        ];
    }

    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'performance_status' => 'excellent',
            'overall_score' => fake()->randomFloat(2, 90.0, 100.0),
        ]);
    }

    public function needsImprovement(): static
    {
        return $this->state(fn (array $attributes) => [
            'performance_status' => 'needs_improvement',
            'overall_score' => fake()->randomFloat(2, 1.0, 59.99),
        ]);
    }
}
