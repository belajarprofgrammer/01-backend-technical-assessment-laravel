<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected TaskService $taskService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);

        $tasks = $this->taskService->findAll($perPage);

        return (new TaskCollection($tasks))
            ->additional([
                'timestamp' => now()->toISOString(),
                'code' => Response::HTTP_OK,
                'status' => Response::$statusTexts[Response::HTTP_OK],
                'message' => 'Data retrieved successfully',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $data = $request->validated();

        $task = $this->taskService->create($data);

        return (new TaskResource($task))
            ->additional([
                'timestamp' => now()->toISOString(),
                'code' => Response::HTTP_CREATED,
                'status' => Response::$statusTexts[Response::HTTP_CREATED],
                'message' => 'Data created successfully',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $task = $this->taskService->findById($id);

        return (new TaskResource($task))
            ->additional([
                'timestamp' => now()->toISOString(),
                'code' => Response::HTTP_OK,
                'status' => Response::$statusTexts[Response::HTTP_OK],
                'message' => 'Data retrieved successfully by ID',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();

        $task = $this->taskService->update($id, $data);

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        $this->taskService->delete($id);

        return response()->noContent();
    }
}
