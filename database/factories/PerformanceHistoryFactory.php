<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerformanceHistoryFactory extends Factory
{
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();

        return [
            'driver_id' => $user?->id,
            'overall_score' => fake()->randomFloat(2, 3.0, 5.0),
            'kpi_score' => fake()->randomFloat(2, 70.0, 98.0),
            'ranking' => fake()->numberBetween(1, 50),
            'performance_status' => fake()->randomElement(['excellent', 'good', 'average', 'needs_improvement']),
            'record_type' => fake()->randomElement(['snapshot', 'review', 'kpi_update', 'ranking_change']),
            'notes' => fake()->optional(0.7)->sentence(),
            'metadata' => [
                'source' => fake()->randomElement(['manual', 'system', 'import']),
                'period' => fake()->randomElement(['Q1 2026', 'Q2 2026', 'Q3 2026', 'Q4 2025']),
            ],
            'recorded_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'recorded_by' => User::inRandomOrder()->first()?->id,
        ];
    }
}
