<?php

namespace Database\Factories;

use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingEvaluationFactory extends Factory
{
    protected $model = \App\Models\TrainingEvaluation::class;

    public function definition(): array
    {
        return [
            'driver_id' => User::factory(),
            'training_id' => Training::factory(),
            'overall_rating' => fake()->numberBetween(1, 5),
            'knowledge_assessment' => fake()->numberBetween(1, 5),
            'instructor_feedback' => fake()->numberBetween(1, 5),
            'training_effectiveness' => fake()->numberBetween(1, 5),
            'driver_feedback' => fake()->optional()->paragraph(),
            'recommendations' => fake()->optional()->sentence(),
            'remarks' => fake()->optional()->sentence(),
            'evaluated_by' => User::factory(),
        ];
    }
}
