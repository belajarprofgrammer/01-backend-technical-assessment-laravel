<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Fluent;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ])) {
            $user = $request->user();

            $accessToken = $user->createToken(
                'access-token', ['*'], now()->addWeek(),
            )->plainTextToken;

            $token = new Fluent([
                'access_token' => $accessToken,
                'expired_at' => now()->addWeek()->timestamp,
            ]);

            return (new LoginResource($token))
                ->additional([
                    'timestamp' => now()->toISOString(),
                    'code' => Response::HTTP_OK,
                    'status' => Response::$statusTexts[Response::HTTP_OK],
                    'message' => 'Data retrieved successfully',
                ])
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
}
