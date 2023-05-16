<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function doctor()
    {
        return $this->afterCreating(function (User $user) {
            Role::findOrCreate(UserRole::DOCTOR->value);
            $user->assignRole(UserRole::DOCTOR->value);
        });
    }

    public function patient()
    {
        return $this->afterCreating(function (User $user) {
            $patientRole = Role::findOrCreate(UserRole::PATIENT->value);
            Permission::findOrCreate('update personal information');
            $patientRole->givePermissionTo('update personal information');
            $user->assignRole(UserRole::PATIENT->value);
        });
    }

    public function withInformation()
    {
        return $this->afterCreating(function (User $user) {
            $patientInformation = [
                'phone_number' => '123456789',
                'height' => 85,
                'weight' => 80,
                'other_information' => 'Some other information'
            ];
            $user->update($patientInformation);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
