<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingFactory extends Factory
{
    protected $model = \App\Models\Training::class;

    public function definition(): array
    {
        $englishTrainingTitles = [
            'Defensive Driving & Road Hazard Protocol',
            'Passenger Relations & Professional Ethics',
            'Vehicle Preventive Maintenance Workshop',
            'Emergency Incident Response & First Aid',
            'GPS Route Navigation & Fleet Logistics',
            'Compliance & Traffic Rules Refresher',
            'TNVS Service Quality & Customer Care',
            'Advanced Heavy Weather Driving Techniques',
        ];

        return [
            'title' => fake()->randomElement($englishTrainingTitles),
            'category' => fake()->randomElement(['Safety', 'Technical', 'Customer Service', 'Compliance', 'Leadership']),
            'description' => 'Designed to close critical skill variances in driving & safety protocols for TNVS driver fleet.',
            'instructor' => fake()->name(),
            'venue' => 'TripWise Fleet Training Center',
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