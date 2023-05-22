<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can not create a submission it is not logged in', function () {
    $response = $this->postJson('api/submissions', []);
    $response->assertUnauthorized();
});

it('can not create a submission it is not a patient', function () {
    $doctor = UserFactory::new()->doctor()->create();
    $this->actingAs($doctor);
    $response = $this->postJson('api/submissions', []);
    $response->assertForbidden();
});

it('can not create a submission if the title or the symptoms are missing', function ($body) {
    $patient = UserFactory::new()->patient()->create();
    $this->actingAs($patient);
    $response = $this->postJson('api/submissions', $body);
    $response->assertUnprocessable();
})->with([
    'No title' => [[
        'symptoms' => 'Some random symptoms',
    ]],
    'No symptoms' => [[
        'title' => 'Submission title',
    ]],
]);

it('can create a submission', function () {
    $patient = UserFactory::new()->patient()->create();
    Sanctum::actingAs($patient);

    $body = [
        'title' => 'Submission title',
        'symptoms' => 'Some random symptoms',
    ];

    $response = $this->postJson('api/submissions', $body);
    $response->assertCreated();
    $this->assertDatabaseHas('submissions', $body);
});
