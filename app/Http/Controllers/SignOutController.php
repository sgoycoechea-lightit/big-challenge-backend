<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Flugg\Responder\Contracts\Responder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SignOutController
{
    public function __invoke(Request $request, Responder $responder): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        $user->tokens()->where('id', $token->id)->delete();
        return $responder->success(['message' => 'Logged out'])->respond(Response::HTTP_OK);
    }
}
