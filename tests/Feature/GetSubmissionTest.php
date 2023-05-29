<?php

namespace Tests\Feature;

use Database\Factories\SubmissionFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = UserFactory::new()->patient()->withInformation()->create();
    $this->submission = SubmissionFactory::new()->create(['patient_id' => $this->user->patient->id]);
});

it('can not get a submission if it is not logged in', function () {
    $response = $this->getJson("api/submissions/{$this->submission->id}");
    $response->assertUnauthorized();
});

it('can not return a submission if the patient is not its owner', function () {
    $patient2 = UserFactory::new()->patient()->withInformation()->create();
    Sanctum::actingAs($patient2);
    $response = $this->getJson("api/submissions/{$this->submission->id}");
    $response->assertForbidden();
});

it('can return a patients submission', function () {
    Sanctum::actingAs($this->user);
    $response = $this->getJson("api/submissions/{$this->submission->id}");
    $response->assertSuccessful();
});
