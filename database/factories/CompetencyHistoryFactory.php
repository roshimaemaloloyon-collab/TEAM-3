<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompetencyHistoryFactory extends Factory
{
    protected $model = \App\Models\CompetencyHistory::class;

    public function definition(): array
    {
        return [
            'driver_id' => User::factory(),
            'competency_id' => fake()->numberBetween(1, 10),
            'score' => fake()->randomFloat(1, 40.0, 100.0),
            'record_type' => fake()->randomElement(['assessment', 'plan_update', 'coaching', 'review']),
            'notes' => fake()->optional()->sentence(),
            'metadata' => [
                'source' => fake()->randomElement(['manual', 'system', 'import']),
                'period' => fake()->randomElement(['Q1 2026', 'Q2 2026', 'Q3 2026', 'Q4 2025']),
            ],
            'recorded_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'recorded_by' => User::factory(),
        ];
    }
}
