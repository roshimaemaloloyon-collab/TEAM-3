<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeerEvaluationFactory extends Factory
{
    protected $model = \App\Models\PeerEvaluation::class;

    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();

        return [
            'evaluator_id' => fake()->randomElement($userIds),
            'evaluated_driver_id' => fake()->randomElement($userIds),
            'evaluation_date' => fake()->date('Y-m-d', '-1 month', 'now'),
            'is_anonymous' => fake()->boolean(70),
            'category_scores' => [
                'communication' => fake()->randomFloat(1, 1.0, 10.0),
                'teamwork' => fake()->randomFloat(1, 1.0, 10.0),
                'punctuality' => fake()->randomFloat(1, 1.0, 10.0),
                'professionalism' => fake()->randomFloat(1, 1.0, 10.0),
                'technical_skill' => fake()->randomFloat(1, 1.0, 10.0),
            ],
            'overall_score' => fake()->randomFloat(2, 1.0, 100.0),
            'comments' => fake()->optional()->paragraph(),
            'suggestions' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['draft', 'submitted', 'under_review', 'approved', 'rejected']),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'submitted',
        ]);
    }
}