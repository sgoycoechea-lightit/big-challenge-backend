<?php

namespace Tests\Feature;

use Database\Factories\SubmissionFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can not get submissions if it is not logged in', function () {
    $response = $this->getJson('api/submissions');
    $response->assertUnauthorized();
});

it('can return a patients submissions', function () {
    $numberOfSubmissions = 3;
    $patient1 = UserFactory::new()->patient()->withInformation()->create();
    $patient2 = UserFactory::new()->patient()->withInformation()->create();
    $doctor1 = UserFactory::new()->doctor()->create();

    Sanctum::actingAs($patient1);

    SubmissionFactory::new()
        ->count($numberOfSubmissions)
        ->create(['patient_id' => $patient1->id]);

    SubmissionFactory::new()
        ->count($numberOfSubmissions)
        ->create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
        ]);

    SubmissionFactory::new()
        ->count(1)
        ->create([
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor1->id,
        ]);

    $response = $this->getJson('api/submissions');
    $response->assertSuccessful()->assertJsonCount($numberOfSubmissions * 2, 'data');
});
