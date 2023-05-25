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

it('can filter patient submissions by status', function () {
    $numberOfPendingSubmissions = 1;
    $numberOfInProgressSubmissions = 3;
    $numberOfDoneSubmissions = 5;

    $user = UserFactory::new()->patient()->withInformation()->create();
    Sanctum::actingAs($user);

    SubmissionFactory::new()
        ->count($numberOfPendingSubmissions)
        ->create([
            'patient_id' => $user->patient->id,
            'status' => SubmissionStatus::Pending->value
        ]);

    SubmissionFactory::new()
        ->count($numberOfInProgressSubmissions)
        ->create([
            'patient_id' => $user->patient->id,
            'status' => SubmissionStatus::InProgress->value
        ]);

    SubmissionFactory::new()
        ->count($numberOfDoneSubmissions)
        ->create([
            'patient_id' => $user->patient->id,
            'status' => SubmissionStatus::Done->value
        ]);

    $response = $this->getJson("api/submissions?status=PENDING");
    $response->assertSuccessful()->assertJsonCount($numberOfPendingSubmissions, 'data');

    $response = $this->getJson("api/submissions?status=IN_PROGRESS");
    $response->assertSuccessful()->assertJsonCount($numberOfInProgressSubmissions, 'data');

    $response = $this->getJson("api/submissions?status=DONE");
    $response->assertSuccessful()->assertJsonCount($numberOfDoneSubmissions, 'data');
});
