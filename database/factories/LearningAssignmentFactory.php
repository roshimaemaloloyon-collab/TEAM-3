<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\LearningModule;
use Illuminate\Database\Eloquent\Factories\Factory;

class LearningAssignmentFactory extends Factory
{
    protected $model = \App\Models\LearningAssignment::class;

    public function definition(): array
    {
        return [
            'driver_id' => User::factory(),
            'learning_module_id' => LearningModule::factory(),
            'status' => fake()->randomElement(['assigned', 'in_progress', 'completed', 'overdue']),
            'progress_percentage' => fake()->numberBetween(0, 100),
            'assigned_date' => fake()->date('Y-m-d'),
            'due_date' => fake()->date('Y-m-d', '+3 months'),
            'completed_date' => fake()->optional()->date('Y-m-d'),
            'notes' => fake()->optional()->sentence(),
            'metadata' => [
                'source' => fake()->randomElement(['manual', 'system', 'import']),
            ],
            'assigned_by' => User::factory(),
        ];
    }
}
