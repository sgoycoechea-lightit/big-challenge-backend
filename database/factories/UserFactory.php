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

    public function doctor(): static
    {
        return $this->afterCreating(function (User $user) {
            Role::findOrCreate(UserRole::Doctor->value);
            $user->assignRole(UserRole::Doctor->value);
        });
    }

    public function patient(): static
    {
        return $this->afterCreating(function (User $user) {
            $patientRole = Role::findOrCreate(UserRole::Patient->value);
            $user->assignRole(UserRole::Patient->value);

            Permission::findOrCreate('update personal information');
            $patientRole->givePermissionTo('update personal information');

            Permission::findOrCreate('create submissions');
            $patientRole->givePermissionTo('create submissions');
        });
    }

    public function withInformation(): static
    {
        return $this->afterCreating(function (User $user) {
            PatientFactory::new()->create([
                'user_id' => $user->id,
            ]);
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
