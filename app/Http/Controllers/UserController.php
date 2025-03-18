<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Operaciones relacionadas con los usuarios"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Obtener todos los usuarios",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return UserResource::collection(
            User::query()->orderBy('id','desc')->get()
        );
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Crear un nuevo usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return response(new UserResource($user), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Obtener un usuario específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del usuario",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Actualizar un usuario específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user->update($data);
        return new UserResource($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Eliminar un usuario específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Usuario eliminado"
     *     )
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response('', 204);
    }
}