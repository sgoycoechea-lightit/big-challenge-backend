<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Transformers\UserTransformer;
use Flugg\Responder\Contracts\Responder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetUserController
{
    public function __invoke(Request $request, Responder $responder): JsonResponse
    {
        return $responder->success($request->user(), UserTransformer::class)
            ->respond(Response::HTTP_OK);
    }
}
