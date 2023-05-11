<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SignInRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SignInController extends Controller
{
    public function __invoke(SignInRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();
        $credentials = Arr::only($validated, ['email', 'password']);

        if (! $user || ! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($validated['device_name'])->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->only(['id', 'name', 'email']),
        ], Response::HTTP_CREATED);
    }
}
