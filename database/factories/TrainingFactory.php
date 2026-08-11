<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingFactory extends Factory
{
    protected $model = \App\Models\Training::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'category' => fake()->randomElement(['Safety', 'Technical', 'Customer Service', 'Compliance', 'Leadership']),
            'description' => fake()->paragraph(),
            'instructor' => fake()->name(),
            'venue' => fake()->company(),
            'capacity' => fake()->numberBetween(10, 50),
            'start_datetime' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'end_datetime' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'status' => fake()->randomElement(['upcoming', 'ongoing', 'completed', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'upcoming',
        ]);
    }

    public function ongoing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ongoing',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }
}