<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use App\Models\Task;

/**
 * @OA\Tag(
 *     name="Tasks",
 *     description="Operaciones relacionadas con las tareas"
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Obtener todas las tareas del usuario autenticado",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tareas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        $tasks = Task::where('user_id', $userId)
            ->orderBy('due_date', 'asc')
            ->get();

        return TaskResource::collection($tasks);
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Crear una nueva tarea",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarea creada",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function store(StoreTaskRequest $request)
    {
        $userId = Auth::id();

        $data = $request->validated();
        $data['user_id'] = $userId;

        $task = Task::create($data);
        return response(new TaskResource($task), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Obtener una tarea específica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la tarea",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la tarea",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No tienes permiso para ver esta tarea"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tienes permiso para ver esta tarea.'], 403);
        }

        return new TaskResource($task);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Actualizar una tarea específica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la tarea",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No tienes permiso para actualizar esta tarea"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tienes permiso para actualizar esta tarea.'], 403);
        }

        $data = $request->validated();
        $task->update($data);
        return new TaskResource($task);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Eliminar una tarea específica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la tarea",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Tarea eliminada"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No tienes permiso para eliminar esta tarea"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tienes permiso para eliminar esta tarea.'], 403);
        }

        $task->delete();
        return response('', 204);
    }
}