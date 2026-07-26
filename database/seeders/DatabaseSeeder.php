<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Admin Users
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@gscrisk.com'],
            [
                'name' => 'Global Admin Manager',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Risk Analyst User',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]
        );

        // Run other seeders
        $this->call([
            CountrySeeder::class,
            CountryMetricSeeder::class,
            PortSeeder::class,
            LexiconWordSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
