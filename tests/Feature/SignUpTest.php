<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => UserRole::PATIENT]);
    Role::create(['name' => UserRole::DOCTOR]);
});

it('can sign up a user', function ($body) {
    $response = $this->postJson('api/signup', $body);
    $response->assertCreated();
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
})->with([
    'Patient' => [[
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'name' => 'John Doe',
        'role' => UserRole::PATIENT,
    ]],
    'Doctor' => [[
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'name' => 'John Doe',
        'role' => UserRole::DOCTOR,
    ]],
]);

it('returns an unprocessable content status code when the data is invalid', function (array $body) {
    $this->postJson('api/signup', $body)->assertUnprocessable();
    $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    $this->assertDatabaseMissing('users', ['name' => 'John Doe']);
})->with([
    'Empty body' => [[ ]],
    'Missing email' => [[
        'name' => 'John Doe',
        'role' => UserRole::PATIENT,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]],
    'Invalid email' => [[
        'email' => 'invalid-email',
        'name' => 'John Doe',
        'role' => UserRole::PATIENT,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]],
    'Missing name' => [[
        'email' => 'test@example.com',
        'role' => UserRole::PATIENT,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]],
    'Missing role' => [[
        'email' => 'test@example.com',
        'name' => 'John Doe',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]],
    'Missing password' => [[
        'email' => 'test@example.com',
        'name' => 'John Doe',
        'role' => UserRole::PATIENT,
        'password_confirmation' => 'password',
    ]],
    'Missing password confirmation' => [[
        'email' => 'test@example.com',
        'name' => 'John Doe',
        'role' => UserRole::PATIENT,
        'password' => 'password',
    ]],
    'Wrong password confirmation' => [[
        'email' => 'test@example.com',
        'name' => 'John Doe',
        'role' => UserRole::PATIENT,
        'password' => 'password',
        'password_confirmation' => 'password2',
    ]],
    'Invalid role' => [[
        'email' => 'test@example.com',
        'name' => 'John Doe',
        'role' => 'invalid-role',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]],
]);

it('can not sign up a user with an email that is taken', function () {
    $body = [
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'name' => 'John Doe',
        'role' => UserRole::PATIENT,
    ];
    $response = $this->postJson('api/signup', $body);
    $response->assertCreated();

    $response = $this->postJson('api/signup', $body);
    $response->assertUnprocessable();
});
