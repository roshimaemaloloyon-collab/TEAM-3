<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompetencyFactory extends Factory
{
    protected $model = \App\Models\Competency::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Safe Driving',
                'Customer Service',
                'Communication Skills',
                'Navigation Proficiency',
                'Professionalism',
                'Time Management',
                'Vehicle Care',
                'Route Planning',
                'Emergency Response',
                'Documentation',
            ]),
            'slug' => fake()->slug(),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement(['safety', 'customer_service', 'technical', 'behavioral']),
            'target_score' => fake()->numberBetween(75, 95),
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }
}
