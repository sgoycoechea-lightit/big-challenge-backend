<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Flugg\Responder\Contracts\Responder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class SignUpController
{
    public function __invoke(SignUpRequest $request, Responder $responder): JsonResponse
    {
        $request['password'] = Hash::make($request['password']);
        $user = User::create($request->only(['name', 'email', 'password']));
        $user->assignRole($request['role']);
        return $responder->success($user, UserTransformer::class)->respond(Response::HTTP_CREATED);
    }
}
