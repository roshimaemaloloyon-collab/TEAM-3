<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompetencyAssessmentFactory extends Factory
{
    protected $model = \App\Models\CompetencyAssessment::class;

    public function definition(): array
    {
        return [
            'driver_id' => User::factory(),
            'competency_id' => fake()->numberBetween(1, 10),
            'score' => fake()->randomFloat(1, 0.0, 100.0),
            'status' => fake()->randomElement(['pending', 'assessed', 'reviewed', 'archived']),
            'assessor_remarks' => fake()->optional()->sentence(),
            'recommendations' => fake()->optional()->sentence(),
            'metadata' => [
                'source' => fake()->randomElement(['manual', 'system', 'import']),
                'period' => fake()->randomElement(['Q1 2026', 'Q2 2026', 'Q3 2026', 'Q4 2025']),
            ],
            'assessed_by' => User::factory(),
            'assessed_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function passing(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => fake()->randomFloat(1, 75.0, 100.0),
            'status' => 'assessed',
        ]);
    }

    public function failing(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => fake()->randomFloat(1, 0.0, 59.99),
            'status' => 'assessed',
        ]);
    }
}