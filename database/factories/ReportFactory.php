<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Monthly Performance Report',
                'Quarterly KPI Report',
                'Driver Ranking Report',
                'Safety Performance Report',
                'Attendance Performance Report',
            ]) . ' - ' . fake()->monthName() . ' ' . fake()->year(),
            'category' => 'performance',
            'report_type' => fake()->randomElement(['individual', 'ranking', 'kpi', 'safety', 'attendance']),
            'parameters' => [
                'branch' => fake()->randomElement(['North Branch', 'South Branch', 'East Branch', 'West Branch', 'Central Branch']),
                'vehicle_type' => fake()->randomElement(['Sedan', 'SUV', 'Van', 'Motorcycle']),
                'period' => fake()->randomElement(['Q1 2026', 'Q2 2026', 'Q3 2026', 'Q4 2025']),
            ],
            'report_data' => [
                'total_drivers' => fake()->numberBetween(5, 50),
                'average_score' => fake()->randomFloat(2, 3.5, 5.0),
                'top_performer' => fake()->name(),
                'summary' => fake()->sentence(),
            ],
            'export_format' => fake()->randomElement(['pdf', 'excel', null]),
            'status' => fake()->randomElement(['generated', 'completed', 'pending', 'failed']),
            'generated_by' => User::inRandomOrder()->first()?->id,
            'generated_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
