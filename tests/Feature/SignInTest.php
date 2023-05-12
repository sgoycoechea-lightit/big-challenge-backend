<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = UserFactory::new()->create([
        'password' => Hash::make('password'),
    ]);
});

it('returns an unauthorized status code when the password is invalid', function () {
    $userData = [
        'email' => $this->user->email,
        'password' => 'invalid-password',
        'device_name' => 'mobile',
    ];
    $response = $this->postJson('api/login', $userData);
    $response->assertUnauthorized();
});

it('can log a user in', function () {
    $userData = [
        'email' => $this->user->email,
        'password' => 'password',
        'device_name' => 'mobile',
    ];
    $response = $this->postJson('api/login', $userData);
    $response->assertOk();
});

it('returns an unprocessable content status code when the data is invalid', function (array $body) {
    $this->postJson('api/login', $body)->assertUnprocessable();
})->with([
    'Empty body' => [[
        'email' => '',
        'password' => '',
        'device_name' => '',
    ]],
    'Missing email' => [[
        'password' => 'password',
        'device_name' => 'mobile',
    ]],
    'Invalid email' => [[
        'email' => 'invalid-email',
        'password' => 'password',
        'device_name' => 'mobile',
    ]],
    'Missing password' => [[
        'email' => "test@example.com",
        'device_name' => 'mobile',
    ]],
    'Missing device name' => [[
        'email' => 'test@example.com',
        'password' => 'password',
    ]],
]);
