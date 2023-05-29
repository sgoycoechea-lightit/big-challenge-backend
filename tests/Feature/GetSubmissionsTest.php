<?php

namespace Tests\Feature;

use App\Enums\SubmissionStatus;
use Database\Factories\SubmissionFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Database\Eloquent\Factories\Sequence;

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
    $numberOfSubmissionsPerType = 3;
    $user = UserFactory::new()->patient()->withInformation()->create();
    Sanctum::actingAs($user);

    SubmissionFactory::new()
        ->count($numberOfSubmissionsPerType * 3)
        ->sequence(
            ['status' => SubmissionStatus::Pending->value],
            ['status' => SubmissionStatus::InProgress->value],
            ['status' => SubmissionStatus::Done->value],
        )
        ->create([
            'patient_id' => $user->patient->id,
        ]);

    $response = $this->getJson("api/submissions?status={$status}");
    $response->assertSuccessful()->assertJsonCount($numberOfSubmissionsPerType, 'data');
})->with([
    SubmissionStatus::Pending->value,
    SubmissionStatus::InProgress->value,
    SubmissionStatus::Done->value,
]);
