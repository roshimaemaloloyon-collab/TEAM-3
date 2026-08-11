<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = \App\Models\Notification::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'message' => fake()->paragraph(),
            'type' => fake()->randomElement(['training', 'performance', 'evaluation', 'announcement', 'system']),
            'category' => fake()->randomElement(['training', 'performance', 'evaluation', 'announcement', 'system']),
            'priority' => fake()->randomElement(['low', 'normal', 'high', 'urgent']),
            'status' => fake()->randomElement(['sent', 'delivered', 'read', 'archived']),
            'channel' => fake()->randomElement(['email', 'sms', 'in_app', 'push']),
            'read_at' => fake()->optional()->dateTime(),
            'archived_at' => fake()->optional()->dateTime(),
            'expires_at' => fake()->optional()->dateTime('+30 days'),
        ];
    }

    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => null,
            'status' => 'delivered',
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
        ]);
    }
}