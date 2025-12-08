<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Usage:
     *   php artisan db:seed                    - Seeds admin user only
     *   php artisan db:seed --class=ContentSeeder - Seeds sample content only
     *   php artisan migrate:fresh --seed      - Fresh database with admin user
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'role' => UserRole::Admin,
                'password' => bcrypt('password'),
            ]
        );

        // Create editor user
        User::firstOrCreate(
            ['email' => 'editor@editor.com'],
            [
                'name' => 'Editor',
                'role' => UserRole::Editor,
                'password' => bcrypt('password'),
            ]
        );

        // To seed with sample content, run: php artisan db:seed --class=ContentSeeder
    }
}
