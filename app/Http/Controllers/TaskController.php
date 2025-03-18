<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        $userId = Auth::id();

        $tasks = Task::where('user_id', $userId)
            ->orderBy('due_date', 'asc')
            ->get();

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $userId = Auth::id();

        $data = $request->validated();
        $data['user_id'] = $userId;

        $task = Task::create($data);
        return response(new TaskResource($task), 201);
    }


    public function show(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tienes permiso para ver esta tarea.'], 403);
        }

        return new TaskResource($task);
    }


    public function update(UpdateTaskRequest $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tienes permiso para actualizar esta tarea.'], 403);
        }

        $data = $request->validated();
        $task->update($data);
        return new TaskResource($task);
    }


    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tienes permiso para eliminar esta tarea.'], 403);
        }

        $task->delete();
        return response('', 204);
    }
}