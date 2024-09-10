<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\group;
use App\Models\Privilege;
use Illuminate\Database\Seeder;
use User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Privilege::factory()->count(5)->create();

        // Create some groups
        group::factory()->count(3)->create();

        // Create some users
        User::factory()->count(10)->create();
    }
}
