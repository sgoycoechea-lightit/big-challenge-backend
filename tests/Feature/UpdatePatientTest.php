<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can not update a patient if it is not logged in', function () {
    $response = $this->putJson('api/update', []);
    $response->assertUnauthorized();
});

it('can not update a user if it is not a patient', function () {
    $doctor = UserFactory::new()->doctor()->create();
    $this->actingAs($doctor);
    $response = $this->putJson('api/update', []);
    $response->assertForbidden();
});

it('can not update a user if phone_number, weight or height is missing', function ($body) {
    $patient = UserFactory::new()->patient()->create();
    $this->actingAs($patient);
    $response = $this->putJson('api/update', $body);
    $response->assertUnprocessable();
})->with([
    'No phone_number' => [[
        'height' => '111',
        'weight' => '99',
    ]],
    'No height' => [[
        'phone_number' => '987654321',
        'weight' => '99',
    ]],
    'No weight' => [[
        'phone_number' => '111111111',
        'height' => '100',
    ]],
]);

it('can update a patients info', function ($body) {
    $patient = UserFactory::new()->patient()->create();
    Sanctum::actingAs($patient);

    $response = $this->putJson('api/update', $body);
    $response->assertOk();
    $this->assertDatabaseHas('users', $body);
})->with([
    'With other info' => [[
        'phone_number' => '123456789',
        'height' => '100.22',
        'weight' => '99',
        'other_information' => 'Some random information.',
    ]],
    'Without other info' => [[
        'phone_number' => '123456789',
        'height' => '100.22',
        'weight' => '99',
    ]],
]);
