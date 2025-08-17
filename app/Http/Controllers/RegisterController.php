<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\RegisterResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return (new RegisterResource($user))
            ->additional([
                'timestamp' => now()->toISOString(),
                'code' => Response::HTTP_CREATED,
                'status' => Response::$statusTexts[Response::HTTP_CREATED],
                'message' => 'Data created successfully',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
