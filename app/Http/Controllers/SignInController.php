<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\IncorrectCredentialsException;
use App\Http\Requests\SignInRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Flugg\Responder\Contracts\Responder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SignInController
{
    public function __invoke(SignInRequest $request, Responder $responder): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Auth::attempt($credentials)) {
            throw new IncorrectCredentialsException();
        }

        $token = $user->createToken($request['device_name'])->plainTextToken;

        return $responder->success($user, UserTransformer::class)
            ->meta(['token' => $token])
            ->respond(Response::HTTP_OK);
    }
}
