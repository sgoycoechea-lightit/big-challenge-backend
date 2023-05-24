<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GetSubmissionsRequest;
use App\Transformers\SubmissionTransformer;
use Flugg\Responder\Contracts\Responder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GetSubmissionsController
{
    public function __invoke(GetSubmissionsRequest $request, Responder $responder): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $submissions = $user->patient?->submissions()->paginate() ?? [];
        return $responder->success($submissions, SubmissionTransformer::class)->respond(Response::HTTP_OK);
    }
}
