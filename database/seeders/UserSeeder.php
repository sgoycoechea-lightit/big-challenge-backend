<?php

namespace Database\Seeders;

use Database\Factories\PatientFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserFactory::new()->patient()->withInformation()->create([
            'name' => 'Complete Patient',
            'email' => 'patient@test.com',
        ]);

        UserFactory::new()->patient()->create([
            'name' => 'Incomplete Patient',
            'email' => 'incomplete@test.com',
        ]);

        UserFactory::new()->doctor()->create([
            'name' => 'Doctor',
            'email' => 'doctor@test.com',
        ]);
    }
}
