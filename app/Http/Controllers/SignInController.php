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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class SignInController
{
    public function __invoke(SignInRequest $request, Responder $responder): JsonResponse
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();
        $credentials = Arr::only($validated, ['email', 'password']);

        if (! $user || ! Auth::attempt($credentials)) {
            throw new IncorrectCredentialsException();
        }

        $token = $user->createToken($validated['device_name'])->plainTextToken;

        return $responder->success($user, UserTransformer::class)
            ->meta(['token' => $token])
            ->respond(Response::HTTP_OK);
    }
}
