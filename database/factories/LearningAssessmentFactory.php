<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\LearningModule;
use Illuminate\Database\Eloquent\Factories\Factory;

class LearningAssessmentFactory extends Factory
{
    protected $model = \App\Models\LearningAssessment::class;

    public function definition(): array
    {
        return [
            'driver_id' => User::factory(),
            'learning_module_id' => LearningModule::factory(),
            'score' => fake()->numberBetween(50, 100),
            'passing_score' => fake()->randomElement([70, 75, 80]),
            'attempt' => fake()->numberBetween(1, 3),
            'max_attempts' => 3,
            'status' => fake()->randomElement(['passed', 'failed', 'pending', 'in_progress']),
            'score_breakdown' => [
                'correct' => fake()->numberBetween(5, 20),
                'incorrect' => fake()->numberBetween(0, 5),
                'skipped' => fake()->numberBetween(0, 2),
            ],
            'feedback' => fake()->optional()->sentence(),
            'completed_at' => fake()->optional()->dateTime('Y-m-d H:i:s'),
            'graded_by' => User::factory(),
        ];
    }
}
