<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePatientRequest;
use App\Transformers\UserTransformer;
use Flugg\Responder\Contracts\Responder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UpdatePatientController
{
    public function __invoke(UpdatePatientRequest $request, Responder $responder): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->update($request->validated());
        return $responder->success($user, UserTransformer::class)->respond(Response::HTTP_OK);
    }
}
