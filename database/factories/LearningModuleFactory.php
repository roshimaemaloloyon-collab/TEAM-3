<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LearningModuleFactory extends Factory
{
    protected $model = \App\Models\LearningModule::class;

    public function definition(): array
    {
        return [
            'title' => fake()->randomElement([
                'Road Safety Basics',
                'Defensive Driving',
                'Customer Service Excellence',
                'Company Policies',
                'Traffic Rules',
                'Emergency Response',
                'Vehicle Maintenance',
                'Navigation Skills',
            ]),
            'slug' => fake()->slug(),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement([
                'road_safety',
                'defensive_driving',
                'customer_service',
                'company_policies',
                'traffic_rules',
                'emergency_response',
                'vehicle_maintenance',
            ]),
            'type' => fake()->randomElement(['course', 'video', 'pdf', 'quiz']),
            'duration_minutes' => fake()->numberBetween(30, 480),
            'difficulty' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'status' => fake()->randomElement(['active', 'inactive', 'archived']),
            'metadata' => [
                'source' => fake()->randomElement(['manual', 'system', 'import']),
            ],
            'created_by' => User::factory(),
        ];
    }
}
