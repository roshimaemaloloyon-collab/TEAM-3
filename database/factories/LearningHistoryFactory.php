<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\LearningModule;
use Illuminate\Database\Eloquent\Factories\Factory;

class LearningHistoryFactory extends Factory
{
    protected $model = \App\Models\LearningHistory::class;

    public function definition(): array
    {
        return [
            'driver_id' => User::factory(),
            'learning_module_id' => LearningModule::factory(),
            'record_type' => fake()->randomElement(['assignment', 'completion', 'assessment', 'certificate']),
            'title' => fake()->optional()->sentence(),
            'description' => fake()->optional()->sentence(),
            'metadata' => [
                'source' => fake()->randomElement(['manual', 'system', 'import']),
                'period' => fake()->randomElement(['Q1 2026', 'Q2 2026', 'Q3 2026', 'Q4 2025']),
            ],
            'recorded_at' => fake()->dateTime('Y-m-d H:i:s'),
            'recorded_by' => User::factory(),
        ];
    }
}
