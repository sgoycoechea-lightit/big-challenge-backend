<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SubmissionStatus;
use App\Http\Requests\StoreSubmissionRequest;
use App\Transformers\SubmissionTransformer;
use Flugg\Responder\Contracts\Responder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class StoreSubmissionController
{
    public function __invoke(StoreSubmissionRequest $request, Responder $responder): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $data = $request->validated();
        $data['status'] = SubmissionStatus::Pending->value;
        $submission = $user->submissions()->create($data);
        return $responder->success($submission, SubmissionTransformer::class)->respond(Response::HTTP_CREATED);
    }
}
