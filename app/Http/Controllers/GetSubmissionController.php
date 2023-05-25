<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GetSubmissionRequest;
use App\Models\Submission;
use App\Transformers\SubmissionTransformer;
use Flugg\Responder\Contracts\Responder;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Response;

class GetSubmissionController
{
    public function __invoke(GetSubmissionRequest $request, Submission $submission, Responder $responder): JsonResponse
    {
        return $responder->success($submission, SubmissionTransformer::class)->respond(Response::HTTP_OK);
    }
}
