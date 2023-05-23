<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Transformers\SubmissionTransformer;
use Flugg\Responder\Contracts\Responder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetSubmissionsController
{
    public function __invoke(Request $request, Responder $responder): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \Spatie\Permission\Models\Role|null $role */
        $role = $user->roles->first();

        $submissions = $role?->name === UserRole::Patient->value
            ? $user->patient?->submissions()->paginate() ?? []
            : [];
        return $responder->success($submissions, SubmissionTransformer::class)->respond(Response::HTTP_OK);
    }
}
