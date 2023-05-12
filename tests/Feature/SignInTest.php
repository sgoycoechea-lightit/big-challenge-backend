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

it('returns an unauthorized status code when the user is invalid', function () {
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
    $response->assertCreated();
});
