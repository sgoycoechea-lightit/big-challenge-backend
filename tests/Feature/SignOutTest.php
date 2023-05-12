<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can log a user out', function () {
    Sanctum::actingAs(UserFactory::new()->create());
    $response = $this->postJson('api/logout');
    $response->assertOk();
});

it('can not logout if it is not logged in', function () {
    $response = $this->postJson('api/logout');
    $response->assertUnauthorized();
});
