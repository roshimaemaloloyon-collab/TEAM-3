<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@tripwise.app',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::factory()->create([
            'name' => 'Juan Dela Cruz',
            'email' => 'driver@tripwise.app',
            'password' => bcrypt('password'),
            'role' => 'driver',
            'status' => 'active',
        ]);
    }
}
