<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return (new ProfileResource($user))
            ->additional([
                'timestamp' => now()->toISOString(),
                'code' => Response::HTTP_OK,
                'status' => Response::$statusTexts[Response::HTTP_OK],
                'message' => 'Data retrieved successfully by User',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return (new ProfileResource($user))
            ->additional([
                'timestamp' => now()->toISOString(),
                'code' => Response::HTTP_OK,
                'status' => Response::$statusTexts[Response::HTTP_OK],
                'message' => 'Data updated successfully',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): Response
    {
        $user = $request->user();
        $user->delete();

        return response()->noContent();
    }
}
