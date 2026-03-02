<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'is_completed' => ['sometimes', 'in:0,1,true,false'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'q' => ['sometimes', 'string', 'max:100'],
        ]);

        $perPage = (int) ($validated['per_page'] ?? 10);

        $query = $request->user()->tasks()->latest();

        // filter: is_completed
        if (array_key_exists('is_completed', $validated)) {
            $value = $validated['is_completed'];
            $bool = in_array($value, [1, '1', true, 'true'], true);
            $query->where('is_completed', $bool);
        }

        // search: q (title/description)
        if (!empty($validated['q'])) {
            $q = $validated['q'];
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $tasks = $query->paginate($perPage);

        return response()->json([
            'data' => TaskResource::collection($tasks->items()),
            'meta' => [
                'current_page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'last_page' => $tasks->lastPage(),
            ],
        ]);
    }

    public function store(StoreTaskRequest $request, TaskService $service)
    {
        $task = $service->createForUser(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'data' => new TaskResource($task),
        ], 201);
    }

    public function update(UpdateTaskRequest $request, int $id, TaskService $service)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $task = $service->update($task, $request->validated());

        return response()->json([
            'data' => new TaskResource($task),
        ]);
    }

    public function destroy(Request $request, int $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }
}
