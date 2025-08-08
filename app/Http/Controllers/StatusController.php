<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StatusController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected TaskService $taskService,
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(StatusRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();

        $task = $this->taskService->setStatus($id, $data['status']);

        return (new TaskResource($task))
            ->additional([
                'timestamp' => now()->toISOString(),
                'code' => Response::HTTP_OK,
                'status' => Response::$statusTexts[Response::HTTP_OK],
                'message' => 'Data updated successfully',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
