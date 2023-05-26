<?php

namespace Tests\Feature;

use App\Enums\SubmissionStatus;
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
        ->create(['patient_id' => $patient1->patient->id]);

    SubmissionFactory::new()
        ->count($numberOfSubmissions)
        ->create([
            'patient_id' => $patient1->patient->id,
            'doctor_id' => $doctor1->id,
        ]);

    SubmissionFactory::new()
        ->create([
            'patient_id' => $patient2->patient->id,
            'doctor_id' => $doctor1->id,
        ]);

    $response = $this->getJson('api/submissions');
    $response->assertSuccessful()->assertJsonCount($numberOfSubmissions * 2, 'data');
});

it('can filter patient submissions by status', function ($status) {
    $numberOfSubmissions = [
        SubmissionStatus::Pending->value => 1,
        SubmissionStatus::InProgress->value => 3,
        SubmissionStatus::Done->value => 5,
    ];

    $user = UserFactory::new()->patient()->withInformation()->create();
    Sanctum::actingAs($user);

    SubmissionFactory::new()
        ->count($numberOfSubmissions[SubmissionStatus::Pending->value])
        ->create([
            'patient_id' => $user->patient->id,
            'status' => SubmissionStatus::Pending->value
        ]);

    SubmissionFactory::new()
        ->count($numberOfSubmissions[SubmissionStatus::InProgress->value])
        ->create([
            'patient_id' => $user->patient->id,
            'status' => SubmissionStatus::InProgress->value
        ]);

    SubmissionFactory::new()
        ->count($numberOfSubmissions[SubmissionStatus::Done->value])
        ->create([
            'patient_id' => $user->patient->id,
            'status' => SubmissionStatus::Done->value
        ]);

    $response = $this->getJson("api/submissions?status={$status}");
    $response->assertSuccessful()->assertJsonCount($numberOfSubmissions[$status], 'data');
})->with([
    SubmissionStatus::Pending->value,
    SubmissionStatus::InProgress->value,
    SubmissionStatus::Done->value,
]);
