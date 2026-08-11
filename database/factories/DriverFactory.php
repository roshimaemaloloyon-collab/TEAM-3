<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    protected $model = \App\Models\Driver::class;

    public function definition(): array
    {
        return [
            'driver_id' => '#DRV-2026-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT),
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->optional()->firstName(),
            'last_name' => fake()->lastName(),
            'photo' => null,
            'birth_date' => fake()->date('Y-m-d', '-30 years', '-18 years'),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'civil_status' => fake()->randomElement(['Single', 'Married', 'Widowed']),
            'address' => fake()->address(),
            'contact_number' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'emergency_contact_person' => fake()->name(),
            'emergency_contact_number' => fake()->phoneNumber(),
            'date_hired' => fake()->date('Y-m-d', '-2 years', 'now'),
            'branch' => fake()->randomElement(['North Branch', 'South Branch', 'East Branch', 'West Branch', 'Central Branch']),
            'vehicle_assignment' => fake()->randomElement(['Toyota Fortuner', 'Honda Civic', 'Mitsubishi Montero', 'Hyundai Tucson', 'Nissan Terra', 'Yamaha NMAX', 'Toyota Hiace']),
            'vehicle_type' => fake()->randomElement(['SUV', 'Sedan', 'Van', 'Motorcycle']),
            'route_assignment' => fake()->randomElement(['North Route', 'South Route', 'East Route', 'West Route', 'Central Route']),
            'status' => fake()->randomElement(['active', 'inactive', 'review', 'suspended']),
            'performance_score' => fake()->randomFloat(1, 2.0, 5.0),
            'trips_count' => fake()->numberBetween(0, 5000),
            'complaints_count' => fake()->numberBetween(0, 20),
            'username' => fake()->unique()->userName(),
            'role' => 'Driver',
            'license_number' => fake()->regexify('[A-Z]{2}[0-9]{6}'),
            'license_expiration' => fake()->date('Y-m-d', '+1 year', '+5 years'),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    public function underReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'review',
        ]);
    }
}