<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = Task::query()
            ->paginate($request->query('per_page', 15))
            ->withQueryString();

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
        $task = new Task;
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->due_date = $request->input('due_date');
        $task->is_recurring = $request->input('is_recurring');
        $task->recurring_interval = $request->input('recurring_interval');
        $task->save();

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
    public function show(Task $task): JsonResponse
    {
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
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->due_date = $request->input('due_date');
        $task->is_recurring = $request->input('is_recurring');
        $task->recurring_interval = $request->input('recurring_interval');
        $task->save();

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
    public function destroy(Task $task): Response
    {
        $task->delete();

        return response()->noContent();
    }
}
