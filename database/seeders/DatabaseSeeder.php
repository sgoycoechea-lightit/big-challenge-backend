<?php

namespace Database\Seeders;

use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        UserFactory::new()->create([
             'name' => 'Santiago',
             'email' => 'test@example.com',
        ]);

        $this->call(RoleSeeder::class);
    }
}
