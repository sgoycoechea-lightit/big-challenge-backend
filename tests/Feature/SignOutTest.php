<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can log a user out', function () {
    Sanctum::actingAs(UserFactory::new()->create());
    $response = $this->postJson('api/logout');
    $response->assertStatus(Response::HTTP_OK);
});
